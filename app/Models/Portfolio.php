<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * NOTE: this model backs the `portfolios` table only. It intentionally has
 * no Filament Resource and no public route registered in this work unit —
 * the owner picks this back up in a later PR for a "Proyectos" section.
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
