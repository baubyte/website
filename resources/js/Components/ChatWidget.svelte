<script>
    import Icon from '@iconify/svelte';
    import { iconSend } from '../lib/icons.js';
    import { t } from '../lib/i18n.js';
    import { route } from '../lib/route.js';

    /**
     * PR12: chat widget that replaces the legacy `mailto:` contact link
     * (see `Contact.svelte`) per the spec's "chat widget replaces contact
     * form" requirement — there is no legacy form left to coexist with.
     *
     * Talks only to the Laravel proxy at `POST /api/chat`
     * (`App\Http\Controllers\ChatController`, PR11) — never directly to
     * n8n, so no webhook URL/secret ever reach the client. `locale` is the
     * same value shared globally by `HandleInertiaRequests::share()`,
     * forwarded down as a plain prop through `Home` -> `Contact` -> here
     * (same pattern `LocaleSwitcher`/`FrontLayout` already use), which
     * keeps this component trivially testable in isolation.
     */
    let { locale = 'es' } = $props();

    let messages = $state([]);
    let draft = $state('');
    let sending = $state(false);
    let conversationId = $state(null);

    /**
     * Laravel's default `web` CSRF middleware accepts the raw `XSRF-TOKEN`
     * cookie value copied verbatim into the `X-XSRF-TOKEN` header — it
     * decrypts it server-side, so no client-side decryption is needed.
     * `axios` (see `bootstrap.js`) does this automatically via its
     * `xsrfCookieName`/`xsrfHeaderName` defaults; plain `fetch` doesn't,
     * so this widget reads the cookie itself instead of pulling axios in
     * for a single request. Returns `null` outside a browser/cookie
     * context (e.g. the cookie hasn't been set yet).
     */
    function xsrfToken() {
        if (typeof document === 'undefined') {
            return null;
        }

        const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);

        return match ? decodeURIComponent(match[1]) : null;
    }

    function pushMessage(role, text) {
        messages = [...messages, { id: crypto.randomUUID(), role, text }];
    }

    /**
     * `conversation_id` is generated client-side on the first message of
     * a session and reused for every following message so the backend
     * (and n8n) can treat the whole exchange as one conversation, per the
     * `ChatController` contract (`conversation_id?: string uuid`).
     */
    async function sendMessage(event) {
        event.preventDefault();

        const message = draft.trim();

        if (!message || sending) {
            return;
        }

        if (!conversationId) {
            conversationId = crypto.randomUUID();
        }

        pushMessage('user', message);
        draft = '';
        sending = true;

        try {
            const response = await fetch(route('chat'), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': xsrfToken() ?? '',
                },
                body: JSON.stringify({
                    message,
                    conversation_id: conversationId,
                    locale,
                    page: typeof window !== 'undefined' ? window.location.pathname : undefined,
                }),
            });

            const body = await response.json().catch(() => null);

            if (response.ok && body?.reply) {
                conversationId = body.conversation_id ?? conversationId;
                pushMessage('assistant', body.reply);
                return;
            }

            // A 503 (`{ error: 'chat_unavailable', reply }`) already
            // carries a localized, user-facing `reply` from
            // `ChatController` — show it as-is instead of inventing
            // another error string. Only fall back to the local
            // `network_error` copy when the response itself is malformed.
            pushMessage('assistant', body?.reply ?? t('chat.network_error'));
        } catch {
            // `fetch()` only throws here on a real network-level failure
            // (offline, DNS, CORS, etc.) — the server never got to
            // respond, so there's no localized `reply` to reuse.
            pushMessage('assistant', t('chat.network_error'));
        } finally {
            sending = false;
        }
    }
</script>

<div class="rounded-box border border-base-content/15 bg-base-100 p-5 text-left shadow-sm">
    <div
        class="mb-4 flex max-h-80 flex-col gap-3 overflow-y-auto"
        role="log"
        aria-live="polite"
        aria-label={t('chat.messages_label')}
    >
        {#each messages as message (message.id)}
            <p
                class={message.role === 'user'
                    ? 'max-w-[85%] self-end rounded-box bg-primary px-4 py-2 text-sm text-primary-content'
                    : 'max-w-[85%] self-start rounded-box bg-base-200 px-4 py-2 text-sm text-base-content'}
            >
                {message.text}
            </p>
        {/each}
    </div>

    <form class="flex items-center gap-2" onsubmit={sendMessage}>
        <label for="chat-input" class="sr-only">{t('chat.input_label')}</label>
        <input
            id="chat-input"
            type="text"
            class="input input-bordered w-full"
            placeholder={t('chat.input_placeholder')}
            bind:value={draft}
            disabled={sending}
            autocomplete="off"
        />

        <button type="submit" class="btn btn-primary gap-2" disabled={sending || !draft.trim()}>
            {#if sending}
                <span class="loading loading-spinner loading-sm" aria-hidden="true"></span>
            {:else}
                <Icon icon={iconSend} width="18" height="18" aria-hidden="true" />
            {/if}
            <span class="hidden sm:inline">{t('chat.send')}</span>
        </button>
    </form>
</div>
