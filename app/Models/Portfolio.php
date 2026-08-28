<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * NOTE: this model backs the `portfolios` table only. It intentionally has
 * no Filament Resource and no public route registered in this work unit —
 * the owner picks this back up in a later PR for a "Proyectos" section.
 *
 * @property int $id
 * @property string $company_name
 * @property string $image
 * @property string|null $website_url_es
 * @property string|null $website_url_en
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereWebsiteUrlEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereWebsiteUrlEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Portfolio extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_name',
        'image',
        'website_url_es',
        'website_url_en',
    ];
}
