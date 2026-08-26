<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Actions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;


class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $modelLabel = 'experiencia';

    protected static ?string $pluralModelLabel = 'experiencias';

    protected static ?string $navigationLabel = 'Experiencia';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPersonalInfoSection(),
            ]);
    }

    private static function getPersonalInfoSection(): Section
    {
        return Section::make('Información Laboral')
            ->description('Datos de la experiencia')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('company')
                    ->label('Empresa')
                    ->required()
                    ->minLength(2)
                    ->maxLength(120)
                    ->columnSpanFull(),
                TextInput::make('specialty_es')
                    ->label('Especialidad (ES)')
                    ->required()
                    ->minLength(2)
                    ->maxLength(120),
                TextInput::make('specialty_en')
                    ->label('Especialidad (EN)')
                    ->required()
                    ->maxLength(120),
                Textarea::make('description_es')
                    ->label('Descripción (ES)')
                    ->nullable()
                    ->minLength(10)
                    ->maxLength(500)
                    ->rows(6)
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label('Descripción (EN)')
                    ->nullable()
                    ->minLength(10)
                    ->maxLength(120)
                    ->rows(6)
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Fecha de inicio')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Fecha de fin')
                    ->nullable()
                    ->minDate(fn (Get $get) => $get('start_date'))
                    ->validationMessages([
                        'after_or_equal' => 'La :attribute no puede ser menor a :date.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('specialty_es')
                    ->label('Especialidad (ES)')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Fecha de inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fecha de fin')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
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
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
