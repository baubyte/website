<?php

namespace App\Filament\Resources\Skill\Schemas;

use Filament\Forms\Components\TextInput;
use App\Support\Icons\IconCatalog;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getSkillInfoSection(),
                self::getSkillConfigSection(),
            ]);
    }


    private static function getSkillInfoSection(): Section
    {
        return Section::make('Información de la Habilidad')
            ->description('Datos y Nivel de la habilidad')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                TextInput::make('percentage')
                    ->label('Porcentaje')
                    ->suffix('%')
                    ->required()
                    ->rules(['numeric', 'integer', 'min:1', 'max:100']),
            ]);
    }

    private static function getSkillConfigSection(): Section
    {
        return Section::make('Configuración de la Habilidad')
            ->description('Categoría e Icono de la habilidad')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                Select::make('skill_category_id')
                    ->relationship('skillCategory', 'name_es')
                    ->label('Categoría')
                    ->helperText('Cómo se agrupa esta habilidad en el sitio público.')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name_es')
                            ->label('Nombre (Español)')
                            ->required()
                            ->maxLength(60),
                        TextInput::make('name_en')
                            ->label('Nombre (Inglés)')
                            ->maxLength(60),
                    ]),
                Select::make('icon')
                    ->label('Icono')
                    ->helperText('Busca entre ~1000 iconos de tecnología. Opcional: las habilidades sin icono quedan sin él y usan el sistema de coincidencia de nombres heredado en el sitio público.')
                    ->searchable()
                    ->allowHtml()
                    ->getSearchResultsUsing(fn(string $search): array => IconCatalog::options(IconCatalog::search($search)))
                    ->getOptionLabelUsing(fn(?string $state): ?string => $state === null ? null : IconCatalog::renderOption($state))
                    ->rule(fn(): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        if ($value !== null && ! IconCatalog::has($value)) {
                            $fail('El icono seleccionado no es válido.');
                        }
                    }),
            ]);
    }
}
