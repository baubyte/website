<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
