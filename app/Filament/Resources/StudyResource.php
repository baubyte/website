<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyResource\Pages;
use App\Models\Study;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('entity')
                    ->required()
                    ->maxLength(120),
                Forms\Components\TextInput::make('title_es')
                    ->label('Title (ES)')
                    ->required()
                    ->maxLength(120),
                Forms\Components\TextInput::make('title_en')
                    ->label('Title (EN)')
                    ->required()
                    ->maxLength(120),
                Forms\Components\Textarea::make('description_es')
                    ->label('Description (ES)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->label('Description (EN)')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title_es')
                    ->label('Title (ES)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudies::route('/'),
            'create' => Pages\CreateStudy::route('/create'),
            'edit' => Pages\EditStudy::route('/{record}/edit'),
        ];
    }
}
