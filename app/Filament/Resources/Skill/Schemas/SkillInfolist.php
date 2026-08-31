<?php

namespace App\Filament\Resources\Skill\Schemas;

use App\Models\Skill;
use App\Support\Icons\IconCatalog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SkillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalle de la Habilidad')
                    ->description('Información técnica y nivel de dominio')
                    ->icon(Heroicon::PuzzlePiece)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nombre')
                            ->icon(Heroicon::Tag)
                            ->copyable()
                            ->copyMessage('Nombre copiado'),

                        TextEntry::make('skillCategory.name_es')
                            ->label('Categoría')
                            ->badge()
                            ->color('info')
                            ->icon(Heroicon::Folder),

                        TextEntry::make('percentage')
                            ->label('Nivel de dominio')
                            ->suffix('%')
                            ->badge()
                            ->color(fn (int|string $state): string => match (true) {
                                (int) $state >= 80 => 'success',
                                (int) $state >= 50 => 'warning',
                                default => 'gray',
                            }),

                        TextEntry::make('icon')
                            ->label('Icono de tecnología')
                            ->html()
                            ->columnSpanFull()
                            ->formatStateUsing(fn (?string $state): string => $state === null
                                ? '—'
                                : IconCatalog::renderOption($state, size: 28)),
                    ]),

                Section::make('Auditoría')
                    ->description('Registro temporal de la habilidad')
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
                            ->visible(fn (?Skill $record): bool => $record?->trashed() ?? false),
                    ]),
            ]);
    }
}
