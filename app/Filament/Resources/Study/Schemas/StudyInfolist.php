<?php

namespace App\Filament\Resources\Study\Schemas;

use App\Models\Study;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class StudyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Académica')
                    ->description('Institución educativa y periodo cursado')
                    ->icon(Heroicon::AcademicCap)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('entity')
                            ->label('Institución')
                            ->icon(Heroicon::BuildingLibrary)
                            ->copyable()
                            ->copyMessage('Institución copiada'),

                        TextEntry::make('start_date')
                            ->label('Fecha de Inicio')
                            ->date('d/m/Y')
                            ->icon(Heroicon::Calendar),

                        TextEntry::make('end_date')
                            ->label('Fecha de Fin')
                            ->date('d/m/Y')
                            ->icon(Heroicon::CalendarDays)
                            ->placeholder('Presente / En curso'),
                    ]),

                Section::make('Titulación y Descripción')
                    ->description('Títulos obtenidos y detalles del programa')
                    ->icon(Heroicon::DocumentText)
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('title_es')
                            ->label('Título (Español)')
                            ->icon(Heroicon::Bookmark),

                        TextEntry::make('title_en')
                            ->label('Título (Inglés)')
                            ->icon(Heroicon::Bookmark),

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
                    ->description('Registro temporal del estudio')
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
                            ->visible(fn (?Study $record): bool => $record?->trashed() ?? false),
                    ]),
            ]);
    }
}
