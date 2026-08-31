<?php

namespace App\Filament\Resources\Study\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getStudyInfoSection(),
                self::getStudyDescriptionSection(),
            ]);
    }


    private static function getStudyInfoSection(): Section
    {
        return Section::make('Información Académica')
            ->description('Institución y Fechas')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('entity')
                    ->label('Institución')
                    ->required()
                    ->rules(['min:2', 'max:120'])
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Fecha de Inicio')
                    ->native(false)
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Fecha de Fin')
                    ->native(false)
                    ->afterOrEqual('start_date')
                    ->nullable(),
            ]);
    }

    private static function getStudyDescriptionSection(): Section
    {
        return Section::make('Descripción del Estudio')
            ->description('Título y descripción en ambos idiomas')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('title_es')
                    ->label('Título (ES)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                TextInput::make('title_en')
                    ->label('Título (EN)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                Textarea::make('description_es')
                    ->label('Descripción (ES)'),
                Textarea::make('description_en')
                    ->label('Descripción (EN)'),
            ]);
    }
}
