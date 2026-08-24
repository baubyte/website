<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // PR11 (D8): `ChatController` proxies Laravel -> n8n; the browser never
    // talks to n8n directly, so this URL/secret must never reach the
    // client. Operational dependency the owner sets up before go-live --
    // until then both are empty and `ChatController` fails closed (503).
    'n8n' => [
        'chat_webhook' => env('N8N_CHAT_WEBHOOK_URL'),
        'secret' => env('N8N_CHAT_WEBHOOK_SECRET'),
    ],

    // Cost/abuse guard for the chat proxy: a site-wide hard cap on messages
    // forwarded to n8n per calendar day (see `SendChatMessage`). Has a safe
    // default so it works with zero setup -- unlike `n8n`/`turnstile`,
    // nothing external needs to be configured for this one to be active.
    'chat' => [
        'daily_limit' => (int) env('CHAT_DAILY_MESSAGE_LIMIT', 200),
    ],

    // Cloudflare Turnstile bot check on the chat widget's first message.
    // `site_key` is public (shipped to the browser); `secret_key` never is.
    // Operational dependency like `n8n` above -- until both are set,
    // `ChatMessageRequest` skips verification entirely so local dev keeps
    // working with zero setup.
    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

];
