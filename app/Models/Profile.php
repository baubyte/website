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
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $avatar
 * @property string|null $email_contact
 * @property string|null $description_es
 * @property string|null $description_en
 * @property string $specialty_es
 * @property string|null $specialty_en
 * @property string|null $language_es
 * @property string|null $language_en
 * @property string|null $github_url
 * @property string|null $linkedin_url
 * @property string|null $instagram_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereDescriptionEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereEmailContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereGithubUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLanguageEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLanguageEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLinkedinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSpecialtyEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSpecialtyEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutTrashed()
 * @mixin \Eloquent
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
