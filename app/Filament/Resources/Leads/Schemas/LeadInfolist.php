<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mensaje Recibido')
                    ->description('Contenido y estado del mensaje enviado por el visitante')
                    ->icon(Heroicon::ChatBubbleLeftRight)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('message')
                            ->label('Mensaje')
                            ->columnSpanFull()
                            ->copyable()
                            ->copyMessage('Mensaje copiado correctamente')
                            ->extraAttributes(['class' => 'p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-base leading-relaxed']),

                        TextEntry::make('reply_status')
                            ->label('Estado de respuesta')
                            ->badge()
                            ->icon(fn (string $state): Heroicon => match (strtolower($state)) {
                                'success' => Heroicon::CheckCircle,
                                'failed'  => Heroicon::XCircle,
                                'pending' => Heroicon::Clock,
                                default   => Heroicon::QuestionMarkCircle,
                            })
                            ->color(fn (string $state): string => match (strtolower($state)) {
                                'failed'  => 'danger',
                                'success' => 'success',
                                'pending' => 'warning',
                                default   => 'gray',
                            }),

                        TextEntry::make('locale')
                            ->label('Idioma')
                            ->badge()
                            ->color('info')
                            ->icon(Heroicon::Language),

                        TextEntry::make('page')
                            ->label('Página de origen')
                            ->icon(Heroicon::GlobeAlt)
                            ->placeholder('Inicio / General'),
                    ]),

                Section::make('Trazabilidad y Cliente')
                    ->description('Identificadores de sesión y huella digital')
                    ->icon(Heroicon::Identification)
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('conversation_id')
                            ->label('ID de conversación')
                            ->icon(Heroicon::Hashtag)
                            ->copyable()
                            ->copyMessage('ID copiado correctamente'),

                        TextEntry::make('client_hash')
                            ->label('Hash del cliente (IP + User-Agent)')
                            ->icon(Heroicon::FingerPrint)
                            ->copyable()
                            ->copyMessage('Hash copiado correctamente'),
                    ]),

                Section::make('Auditoría')
                    ->description('Registro temporal del evento')
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
                            ->visible(fn (?Lead $record): bool => $record?->trashed() ?? false),
                    ]),
            ]);
    }
}
