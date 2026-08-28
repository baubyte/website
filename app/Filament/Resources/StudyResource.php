<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyResource\Pages\CreateStudy;
use App\Filament\Resources\StudyResource\Pages\EditStudy;
use App\Filament\Resources\StudyResource\Pages\ListStudies;
use App\Models\Study;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static ?string $modelLabel = 'Estudio';

    protected static ?string $pluralModelLabel = 'Estudios';

    protected static ?string $navigationLabel = 'Estudios';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entity')
                    ->label('Institución')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title_es')
                    ->label('Titulo (ES)')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fecha de Fin')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListStudies::route('/'),
            'create' => CreateStudy::route('/create'),
            'edit' => EditStudy::route('/{record}/edit'),
        ];
    }
}
