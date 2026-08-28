<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    use ResolvesLocalizedFields;

    protected $fillable = [
        'name_es',
        'name_en',
    ];

    protected function localizedFields(): array
    {
        return ['name'];
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
}
