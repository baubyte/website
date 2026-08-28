<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $company
 * @property string $specialty_es
 * @property string $specialty_en
 * @property string|null $description_es
 * @property string|null $description_en
 * @property Carbon $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereDescriptionEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereSpecialtyEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereSpecialtyEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Experience withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Experience extends Model
{
    use ResolvesLocalizedFields, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company',
        'specialty_es',
        'specialty_en',
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
        return ['specialty', 'description'];
    }
}
