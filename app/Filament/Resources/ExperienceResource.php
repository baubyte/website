<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages\CreateExperience;
use App\Filament\Resources\ExperienceResource\Pages\EditExperience;
use App\Filament\Resources\ExperienceResource\Pages\ListExperiences;
use App\Models\Experience;
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
                self::getJobDetailsSection(),
            ]);
    }

    private static function getPersonalInfoSection(): Section
    {
        return Section::make('Información Laboral')
            ->description('Empresa y Fechas')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('company')
                    ->label('Empresa')
                    ->required()
                    ->maxLength(120)
                    ->minLength(2)
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Fecha de inicio')
                    ->native(false)
                    ->required()
                    ->date(),
                DatePicker::make('end_date')
                    ->label('Fecha de fin')
                    ->native(false)
                    ->date()
                    ->afterOrEqual('start_date')
                    ->nullable(),
            ]);
    }

    private static function getJobDetailsSection(): Section
    {
        return Section::make('Puesto')
            ->description('Información del puesto')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('specialty_es')
                    ->label('Especialidad (ES)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                TextInput::make('specialty_en')
                    ->label('Especialidad (EN)')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                Textarea::make('description_es')
                    ->label('Descripción (ES)')
                    ->nullable()
                    ->rules(['min:10', 'max:500'])
                    ->rows(6),
                Textarea::make('description_en')
                    ->label('Descripción (EN)')
                    ->nullable()
                    ->rules(['min:10', 'max:500'])
                    ->rows(6),
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
            'index' => ListExperiences::route('/'),
            'create' => CreateExperience::route('/create'),
            'edit' => EditExperience::route('/{record}/edit'),
        ];
    }
}
