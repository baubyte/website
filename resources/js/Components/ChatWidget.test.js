import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';
import { fireEvent, render, screen } from '@testing-library/svelte';
import ChatWidget from './ChatWidget.svelte';
import Contact from './Contact.svelte';

/**
 * PR12: `ChatWidget` talks to `POST /api/chat`
 * (`App\Http\Controllers\ChatController`, PR11) via plain `fetch` — these
 * tests mock `global.fetch` so no real network call is ever made. The
 * `chat` route already exists in the test build's `ziggy.js`, so `route()`
 * resolves for real; only the network layer is mocked.
 */
async function typeAndSend(message) {
    const input = screen.getByLabelText('Escribí tu mensaje');
    await fireEvent.input(input, { target: { value: message } });
    await fireEvent.click(screen.getByRole('button', { name: /enviar/i }));
}

describe('ChatWidget', () => {
    beforeEach(() => {
        vi.stubGlobal('fetch', vi.fn());
    });

    afterEach(() => {
        vi.unstubAllGlobals();
    });

    test('sending a message immediately appends it to the message list', async () => {
        global.fetch.mockResolvedValueOnce({
            ok: true,
            json: async () => ({ reply: 'Hola, ¿en qué puedo ayudarte?', conversation_id: 'abc-123' }),
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola, quiero hacerte una consulta');

        expect(screen.getByText('Hola, quiero hacerte una consulta')).toBeInTheDocument();
    });

    test('renders the real assistant reply from a successful response', async () => {
        global.fetch.mockResolvedValueOnce({
            ok: true,
            json: async () => ({ reply: 'Claro, contame más.', conversation_id: 'abc-123' }),
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola');

        expect(await screen.findByText('Claro, contame más.')).toBeInTheDocument();
    });

    test('reuses the same conversation_id on a second message instead of generating a new one', async () => {
        global.fetch
            .mockResolvedValueOnce({
                ok: true,
                json: async () => ({ reply: 'Primera respuesta', conversation_id: 'server-issued-id' }),
            })
            .mockResolvedValueOnce({
                ok: true,
                json: async () => ({ reply: 'Segunda respuesta', conversation_id: 'server-issued-id' }),
            });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Primer mensaje');
        await screen.findByText('Primera respuesta');

        await typeAndSend('Segundo mensaje');
        await screen.findByText('Segunda respuesta');

        const secondCallBody = JSON.parse(global.fetch.mock.calls[1][1].body);
        expect(secondCallBody.conversation_id).toBe('server-issued-id');
    });

    test('a 503 response shows its already-localized fallback reply as-is, without breaking the widget', async () => {
        global.fetch.mockResolvedValueOnce({
            ok: false,
            status: 503,
            json: async () => ({
                error: 'chat_unavailable',
                reply: 'El chat no está disponible en este momento. Probá de nuevo en unos minutos.',
            }),
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola');

        expect(
            await screen.findByText('El chat no está disponible en este momento. Probá de nuevo en unos minutos.'),
        ).toBeInTheDocument();

        // The widget must stay usable after a failed send — the input is
        // re-enabled and the user can try again.
        expect(screen.getByLabelText('Escribí tu mensaje')).not.toBeDisabled();
    });

    test('a real network failure (fetch rejects) falls back to the local error copy instead of throwing', async () => {
        global.fetch.mockRejectedValueOnce(new TypeError('Failed to fetch'));

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola');

        expect(
            await screen.findByText('No pudimos enviar tu mensaje. Revisá tu conexión e intentá de nuevo.'),
        ).toBeInTheDocument();
    });

    test('never sends an empty message and disables the send button until there is real input', async () => {
        render(ChatWidget, { props: { locale: 'es' } });

        expect(screen.getByRole('button', { name: /enviar/i })).toBeDisabled();
        expect(global.fetch).not.toHaveBeenCalled();
    });
});

describe('Contact', () => {
    test('replaces the legacy mailto contact form with the chat widget — no legacy form left', () => {
        render(Contact, { props: { locale: 'es' } });

        expect(screen.queryByRole('link', { name: /mailto/i })).not.toBeInTheDocument();
        expect(document.querySelector('a[href^="mailto:"]')).toBeNull();
        expect(document.querySelectorAll('form').length).toBe(1);
        expect(screen.getByLabelText('Escribí tu mensaje')).toBeInTheDocument();
    });
});
