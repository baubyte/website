<?php

namespace App\Filament\Resources\Skill\Tables;


use App\Support\Icons\IconCatalog;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SkillTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('skillCategory.name_es')
                    ->label('Categoría')
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('percentage')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('icon')
                    ->label('Icono')
                    ->html()
                    ->formatStateUsing(fn(?string $state): string => $state === null
                        ? '—'
                        : IconCatalog::renderOption($state))
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Eliminado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('skill_category_id')
                    ->relationship('skillCategory', 'name_es')
                    ->label('Categoría'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver()
                        ->modalWidth('5xl')
                        ->modalHeading('Detalle de la Habilidad'),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                ->icon(Heroicon::AdjustmentsHorizontal)
                ->tooltip('Acciones')
                ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
