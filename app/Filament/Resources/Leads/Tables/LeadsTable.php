<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Filament\Resources\Leads\Schemas\LeadInfolist;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderableColumns()
            ->persistColumnSearchesInSession() // Persiste búsquedas por columna en sesión
            ->persistFiltersInSession()        // Persiste filtros aplicados
            ->persistSortInSession()
            ->columns([
                TextColumn::make('conversation_id')
                    ->label('ID de conversación')
                    ->limit(12)
                    ->copyable()
                    ->copyMessage('ID de conversación copiado correctamente')
                    ->copyableState(fn (string $state): string => "{$state}")
                    ->tooltip('Copiar ID de conversación')
                    ->grow(false)
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Mensaje')
                    ->wrap()
                    ->limit(50)
                    ->copyable()
                    ->copyMessage('Mensaje copiado correctamente')
                    ->copyableState(fn (string $state): string => "{$state}")
                    ->tooltip('Copiar mensaje')
                    ->searchable(),
                TextColumn::make('reply_status')
                    ->label('Estado de respuesta')
                    ->badge()
                    ->grow(false)
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'failed'  => 'danger',
                        'success' => 'success',
                        'pending' => 'warning',
                        default   => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('page')
                    ->label('Página')
                    ->grow(false)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('locale')
                    ->label('Idioma')
                    ->grow(false)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('client_hash')
                    ->label('Hash del cliente')
                    ->limit(12)
                    ->copyable()
                    ->copyMessage('Hash del cliente copiado correctamente')
                    ->copyableState(fn (string $state): string => "{$state}")
                    ->tooltip('Copiar hash del cliente')
                    ->grow(false)
                    ->toggleable()
                    ->searchable(),
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver()
                        ->modalWidth('5xl')
                        ->modalHeading('Detalle del Lead')
                        ->schema(fn(Schema $schema): Schema => LeadInfolist::configure($schema)),
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
