<?php

namespace App\Filament\Resources\Experience\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPersonalInfoSection(),
                self::getJobDetailsSection(),
            ]);
    }


    private static function getPersonalInfoSection(): Section
    {
        return Section::make('Información Laboral')
            ->description('Empresa y Fechas')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('company')
                    ->label('Empresa')
                    ->required()
                    ->maxLength(120)
                    ->minLength(2)
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Fecha de inicio')
                    ->native(false)
                    ->required()
                    ->date(),
                DatePicker::make('end_date')
                    ->label('Fecha de fin')
                    ->native(false)
                    ->date()
                    ->afterOrEqual('start_date')
                    ->nullable(),
            ]);
    }

    private static function getJobDetailsSection(): Section
    {
        return Section::make('Puesto')
            ->description('Información del puesto')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('specialty_es')
                    ->label('Especialidad (ES)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                TextInput::make('specialty_en')
                    ->label('Especialidad (EN)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                Textarea::make('description_es')
                    ->label('Descripción (ES)')
                    ->nullable()
                    ->rules(['min:10', 'max:500'])
                    ->rows(6),
                Textarea::make('description_en')
                    ->label('Descripción (EN)')
                    ->nullable()
                    ->rules(['min:10', 'max:500'])
                    ->rows(6),
            ]);
    }
}
