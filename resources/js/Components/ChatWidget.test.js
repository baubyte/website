import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';
import { fireEvent, render, screen } from '@testing-library/svelte';
import axios from 'axios';
import ChatWidget from './ChatWidget.svelte';
import Contact from './Contact.svelte';

vi.mock('axios');

/**
 * `ChatWidget` talks to `POST /api/chat`
 * (`App\Http\Controllers\ChatController`) via `axios.post` — these
 * tests mock `axios.post` so no real network call is ever made. The
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
        vi.clearAllMocks();
    });

    test('sending a message immediately appends it to the message list', async () => {
        axios.post.mockResolvedValueOnce({
            data: { reply: 'Hola, ¿en qué puedo ayudarte?', conversation_id: 'abc-123' },
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola, quiero hacerte una consulta');

        expect(screen.getByText('Hola, quiero hacerte una consulta')).toBeInTheDocument();
    });

    test('pressing Enter without Shift sends the message', async () => {
        axios.post.mockResolvedValueOnce({
            data: { reply: 'Recibido por Enter', conversation_id: 'abc-123' },
        });

        render(ChatWidget, { props: { locale: 'es' } });

        const textarea = screen.getByLabelText('Escribí tu mensaje');
        await fireEvent.input(textarea, { target: { value: 'Mensaje con enter' } });
        await fireEvent.keyDown(textarea, { key: 'Enter', shiftKey: false });

        expect(await screen.findByText('Recibido por Enter')).toBeInTheDocument();
    });

    test('renders the real assistant reply from a successful response with markdown formatting', async () => {
        axios.post.mockResolvedValueOnce({
            data: { reply: 'Hola, soy **Ada** de `BAUBYTE`.', conversation_id: 'abc-123' },
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola');

        expect(await screen.findByText('Ada')).toBeInTheDocument();
        expect(screen.getByText('Ada').tagName).toBe('STRONG');
        expect(screen.getByText('BAUBYTE').tagName).toBe('CODE');
    });

    test('reuses the same conversation_id on a second message instead of generating a new one', async () => {
        axios.post
            .mockResolvedValueOnce({
                data: { reply: 'Primera respuesta', conversation_id: 'server-issued-id' },
            })
            .mockResolvedValueOnce({
                data: { reply: 'Segunda respuesta', conversation_id: 'server-issued-id' },
            });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Primer mensaje');
        await screen.findByText('Primera respuesta');

        await typeAndSend('Segundo mensaje');
        await screen.findByText('Segunda respuesta');

        const secondCallPayload = axios.post.mock.calls[1][1];
        expect(secondCallPayload.conversation_id).toBe('server-issued-id');
    });

    test('a 503 response shows its already-localized fallback reply as-is, without breaking the widget', async () => {
        axios.post.mockRejectedValueOnce({
            response: {
                status: 503,
                data: {
                    error: 'chat_unavailable',
                    reply: 'El chat no está disponible en este momento. Probá de nuevo en unos minutos.',
                },
            },
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

    test('a 422 validation error response displays the validation errors from backend', async () => {
        axios.post.mockRejectedValueOnce({
            response: {
                status: 422,
                data: {
                    error: 'validation_error',
                    reply: 'El campo mensaje no debe ser mayor que 800 caracteres.',
                },
            },
        });

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Mensaje largo');

        expect(
            await screen.findByText('El campo mensaje no debe ser mayor que 800 caracteres.'),
        ).toBeInTheDocument();

        expect(screen.getByLabelText('Escribí tu mensaje')).not.toBeDisabled();
    });

    test('a real network failure (axios rejects) falls back to the local error copy instead of throwing', async () => {
        axios.post.mockRejectedValueOnce(new Error('Network Error'));

        render(ChatWidget, { props: { locale: 'es' } });

        await typeAndSend('Hola');

        expect(
            await screen.findByText('No pudimos enviar tu mensaje. Revisá tu conexión e intentá de nuevo.'),
        ).toBeInTheDocument();
    });

    test('never sends an empty message and disables the send button until there is real input', async () => {
        render(ChatWidget, { props: { locale: 'es' } });

        expect(screen.getByRole('button', { name: /enviar/i })).toBeDisabled();
        expect(axios.post).not.toHaveBeenCalled();
    });

    test('shows an inviting empty-state hint, never a fake message pretending to be the assistant', () => {
        render(ChatWidget, { props: { locale: 'es' } });

        expect(screen.getByText('Contanos en qué proyecto estás pensando.')).toBeInTheDocument();
        expect(screen.getByRole('log')).not.toHaveTextContent('¡Hola!');
    });
});

describe('ChatWidget with Turnstile configured', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    afterEach(() => {
        delete window.turnstile;
    });

    test('the send button stays disabled until Turnstile issues a token, even with real input', async () => {
        window.turnstile = { render: vi.fn(() => 'widget-1'), reset: vi.fn() };

        render(ChatWidget, { props: { locale: 'es', turnstileSiteKey: 'test-site-key' } });
        await Promise.resolve();

        const input = screen.getByLabelText('Escribí tu mensaje');
        await fireEvent.input(input, { target: { value: 'Hola' } });

        expect(screen.getByRole('button', { name: /enviar/i })).toBeDisabled();
    });

    test('once Turnstile calls back with a token, sending includes it in the request body', async () => {
        window.turnstile = {
            render: vi.fn((el, options) => {
                options.callback('real-token-from-cloudflare');

                return 'widget-1';
            }),
            reset: vi.fn(),
        };
        axios.post.mockResolvedValueOnce({
            data: { reply: 'Hola!', conversation_id: 'abc-123' },
        });

        render(ChatWidget, { props: { locale: 'es', turnstileSiteKey: 'test-site-key' } });
        await Promise.resolve();

        await typeAndSend('Hola');

        const payload = axios.post.mock.calls[0][1];
        expect(payload.turnstile_token).toBe('real-token-from-cloudflare');
    });

    test('without a site key, no Turnstile token is required and none is sent', async () => {
        axios.post.mockResolvedValueOnce({
            data: { reply: 'Hola!', conversation_id: 'abc-123' },
        });

        render(ChatWidget, { props: { locale: 'es', turnstileSiteKey: null } });

        await typeAndSend('Hola');

        const payload = axios.post.mock.calls[0][1];
        expect(payload.turnstile_token).toBeUndefined();
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
