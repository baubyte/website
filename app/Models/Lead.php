<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * One row per chat message the site receives via `ChatController`,
 * whether or not the proxied n8n webhook answered successfully
 * (`reply_status` tracks that outcome). This replaces the old contact-form
 * lead capture.
 *
 * `client_hash` is an HMAC digest of the visitor's IP + User-Agent (see
 * `ChatController::clientHash()`) — the raw IP/User-Agent is never
 * persisted here or anywhere else.
 */
class Lead extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'conversation_id',
        'message',
        'locale',
        'page',
        'client_hash',
        'reply_status',
    ];
}
