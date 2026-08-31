<?php

namespace App\Filament\Resources\Experience\Schemas;

use App\Models\Experience;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ExperienceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Laboral')
                    ->description('Detalles de la empresa y periodo de trabajo')
                    ->icon(Heroicon::Briefcase)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('company')
                            ->label('Empresa')
                            ->icon(Heroicon::BuildingOffice2)
                            ->copyable()
                            ->copyMessage('Nombre de empresa copiado'),

                        TextEntry::make('start_date')
                            ->label('Fecha de Inicio')
                            ->date('d/m/Y')
                            ->icon(Heroicon::Calendar),

                        TextEntry::make('end_date')
                            ->label('Fecha de Fin')
                            ->date('d/m/Y')
                            ->icon(Heroicon::CalendarDays)
                            ->placeholder('Presente / Actualidad'),
                    ]),

                Section::make('Puesto y Responsabilidades')
                    ->description('Especialidad y funciones realizadas')
                    ->icon(Heroicon::Identification)
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('specialty_es')
                            ->label('Especialidad (ES)')
                            ->icon(Heroicon::Tag),

                        TextEntry::make('specialty_en')
                            ->label('Especialidad (EN)')
                            ->icon(Heroicon::Tag),

                        TextEntry::make('description_es')
                            ->label('Descripción (Español)')
                            ->columnSpanFull()
                            ->placeholder('Sin descripción')
                            ->extraAttributes(['class' => 'p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-base leading-relaxed']),

                        TextEntry::make('description_en')
                            ->label('Descripción (Inglés)')
                            ->columnSpanFull()
                            ->placeholder('Sin descripción')
                            ->extraAttributes(['class' => 'p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-base leading-relaxed']),
                    ]),

                Section::make('Auditoría')
                    ->description('Registro temporal del registro')
                    ->icon(Heroicon::Clock)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de creación')
                            ->dateTime('d/m/Y H:i:s')
                            ->icon(Heroicon::Calendar)
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i:s')
                            ->icon(Heroicon::ArrowPath)
                            ->placeholder('-'),

                        TextEntry::make('deleted_at')
                            ->label('Eliminado el')
                            ->dateTime('d/m/Y H:i:s')
                            ->color('danger')
                            ->icon(Heroicon::Trash)
                            ->visible(fn (?Experience $record): bool => $record?->trashed() ?? false),
                    ]),
            ]);
    }
}
