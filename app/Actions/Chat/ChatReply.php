<?php

namespace App\Actions\Chat;

/**
 * Result of `SendChatMessage`. `successful` decides the HTTP status the
 * controller returns (200 vs 503) -- `reply` is always a user-facing,
 * already-localized string either way, so the controller never needs to
 * pick between an app string and an upstream one.
 */
final readonly class ChatReply
{
    public function __construct(
        public bool $successful,
        public string $reply,
        public string $conversationId,
    ) {}
}
