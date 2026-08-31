<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('conversation_id')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('locale')
                    ->required(),
                TextInput::make('page'),
                TextInput::make('client_hash')
                    ->required(),
                TextInput::make('reply_status')
                    ->required(),
            ]);
    }
}
