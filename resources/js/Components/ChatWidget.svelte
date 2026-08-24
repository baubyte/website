<script>
    import { onMount } from 'svelte';
    import Icon from '@iconify/svelte';
    import { iconSend } from '../lib/icons.js';
    import { t } from '../lib/i18n.js';
    import { route } from '../lib/route.js';

    /**
     * Chat widget that replaces the legacy `mailto:` contact link (see
     * `Contact.svelte`) per the spec's "chat widget replaces contact form"
     * requirement — there is no legacy form left to coexist with.
     *
     * Talks only to the Laravel proxy at `POST /api/chat`
     * (`App\Http\Controllers\ChatController`) — never directly to n8n, so
     * no webhook URL/secret ever reach the client. `locale` is the
     * same value shared globally by `HandleInertiaRequests::share()`,
     * forwarded down as a plain prop through `Home` -> `Contact` -> here
     * (same pattern `LocaleSwitcher`/`FrontLayout` already use), which
     * keeps this component trivially testable in isolation. `turnstileSiteKey`
     * is the same kind of global share, null until the owner configures a
     * real Cloudflare Turnstile site (see `ChatMessageRequest`) -- when
     * null, the widget below never renders and every message sends with no
     * `turnstile_token`, matching the backend's own skip-if-unconfigured.
     */
    let { locale = 'es', turnstileSiteKey = null } = $props();

    let messages = $state([]);
    let draft = $state('');
    let sending = $state(false);
    let conversationId = $state(null);
    let messagesEl;
    let turnstileEl = $state();
    let turnstileToken = $state(null);
    let turnstileWidgetId = null;

    /**
     * Cloudflare Turnstile tokens are single-use -- each successful
     * `siteverify` call server-side consumes it, per Cloudflare's own docs
     * -- so a widget left as-is after the first message would silently
     * fail every message after it. `turnstile.reset()` re-runs the
     * (usually invisible/managed) challenge and the `callback` below fires
     * again with a fresh token, ready for the next send.
     */
    onMount(() => {
        if (!turnstileSiteKey || typeof window === 'undefined') {
            return;
        }

        loadTurnstileScript()
            .then(() => {
                if (!turnstileEl || !window.turnstile) {
                    return;
                }

                turnstileWidgetId = window.turnstile.render(turnstileEl, {
                    sitekey: turnstileSiteKey,
                    size: 'compact',
                    language: locale,
                    callback: (token) => {
                        turnstileToken = token;
                    },
                    'expired-callback': () => {
                        turnstileToken = null;
                    },
                    'error-callback': () => {
                        turnstileToken = null;
                    },
                });
            })
            .catch(() => {
                // Turnstile script failed to load (offline, ad-blocker,
                // Cloudflare down) -- `turnstileToken` stays null, which
                // just keeps the send button disabled per the guard below
                // rather than crashing the widget.
            });
    });

    function loadTurnstileScript() {
        return new Promise((resolve, reject) => {
            if (window.turnstile) {
                resolve();
                return;
            }

            const existing = document.querySelector('script[data-turnstile]');

            if (existing) {
                existing.addEventListener('load', () => resolve());
                existing.addEventListener('error', () => reject(new Error('turnstile script failed')));
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
            script.async = true;
            script.defer = true;
            script.dataset.turnstile = 'true';
            script.addEventListener('load', () => resolve());
            script.addEventListener('error', () => reject(new Error('turnstile script failed')));
            document.head.appendChild(script);
        });
    }

    /**
     * Svelte 5 effect, not `afterUpdate`: re-runs whenever `messages`
     * changes (tracked via the `$state` read inside), scrolling the log to
     * the newest message the same way any chat product does.
     */
    $effect(() => {
        void messages.length;
        void sending;

        if (messagesEl) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    });

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
                    turnstile_token: turnstileSiteKey ? turnstileToken : undefined,
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

            if (turnstileSiteKey && turnstileWidgetId !== null && window.turnstile) {
                turnstileToken = null;
                window.turnstile.reset(turnstileWidgetId);
            }
        }
    }
</script>

<div class="rounded-box border border-base-content/15 bg-base-100 p-5 text-left shadow-sm">
    <div
        bind:this={messagesEl}
        class="mb-4 flex h-80 flex-col gap-3 overflow-y-auto scroll-smooth pr-1 [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-base-content/15 [&::-webkit-scrollbar-track]:bg-transparent"
        role="log"
        aria-live="polite"
        aria-label={t('chat.messages_label')}
    >
        {#if messages.length === 0 && !sending}
            <p class="m-auto max-w-[75%] text-center text-sm text-base-content/50">
                {t('chat.empty_hint')}
            </p>
        {/if}

        {#each messages as message (message.id)}
            <div class="flex {message.role === 'user' ? 'justify-end' : 'justify-start'}">
                <p
                    class="max-w-[85%] rounded-box px-4 py-2 text-sm {message.role === 'user'
                        ? 'bg-primary text-primary-content'
                        : 'bg-base-200 text-base-content'}"
                >
                    {message.text}
                </p>
            </div>
        {/each}

        {#if sending}
            <div class="flex justify-start">
                <div class="flex items-center gap-1 rounded-box bg-base-200 px-4 py-3">
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-base-content/50 [animation-delay:-0.3s]"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-base-content/50 [animation-delay:-0.15s]"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-base-content/50"></span>
                </div>
            </div>
        {/if}
    </div>

    {#if turnstileSiteKey}
        <div bind:this={turnstileEl} class="mb-2 flex justify-center"></div>
    {/if}

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

        <button
            type="submit"
            class="btn btn-primary gap-2"
            disabled={sending || !draft.trim() || (turnstileSiteKey && !turnstileToken)}
        >
            {#if sending}
                <span class="loading loading-spinner loading-sm" aria-hidden="true"></span>
            {:else}
                <Icon icon={iconSend} width="18" height="18" aria-hidden="true" />
            {/if}
            <span class="hidden sm:inline">{t('chat.send')}</span>
        </button>
    </form>
</div>
