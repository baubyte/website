<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Singleton-in-practice profile of the site owner. The application never
 * enforces a hard singleton constraint at the schema level; the UI/admin
 * layer (Filament, added in a later PR) is responsible for keeping this to
 * a single row.
 */
class Profile extends Model
{
    use ResolvesLocalizedFields, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'avatar',
        'email_contact',
        'description_es',
        'description_en',
        'specialty_es',
        'specialty_en',
        'language_es',
        'language_en',
        'github_url',
        'linkedin_url',
        'instagram_url',
    ];

    /**
     * `name`/`surname`/`avatar`/etc stay as-is; `description`/`specialty`/
     * `language` are resolved to a single language for the given locale
     * (see `ResolvesLocalizedFields::toLocalizedArray()`) so Inertia/Svelte
     * props never carry the raw `_es`/`_en` pair.
     *
     * @return list<string>
     */
    protected function localizedFields(): array
    {
        return ['description', 'specialty', 'language'];
    }
}
