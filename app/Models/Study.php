<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $entity
 * @property string $title_es
 * @property string $title_en
 * @property string|null $description_es
 * @property string|null $description_en
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereDescriptionEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereTitleEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Study withoutTrashed()
 * @mixin \Eloquent
 */
class Study extends Model
{
    use ResolvesLocalizedFields, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'entity',
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'start_date',
        'end_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * @return list<string>
     */
    protected function localizedFields(): array
    {
        return ['title', 'description'];
    }
}
