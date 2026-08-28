<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * One row per chat message the site receives via `ChatController`,
 * whether or not the proxied n8n webhook answered successfully
 * (`reply_status` tracks that outcome). This replaces the old contact-form
 * lead capture.
 *
 * `client_hash` is an HMAC digest of the visitor's IP + User-Agent (see
 * `ChatController::clientHash()`) — the raw IP/User-Agent is never
 * persisted here or anywhere else.
 *
 * @property int $id
 * @property string $conversation_id
 * @property string $message
 * @property string $locale
 * @property string|null $page
 * @property string $client_hash
 * @property string $reply_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereClientHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereReplyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead withoutTrashed()
 *
 * @mixin \Eloquent
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
