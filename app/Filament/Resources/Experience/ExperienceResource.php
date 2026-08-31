<?php

namespace App\Filament\Resources\Experience;

use App\Filament\Resources\Experience\Pages\CreateExperience;
use App\Filament\Resources\Experience\Pages\EditExperience;
use App\Filament\Resources\Experience\Pages\ListExperiences;
use App\Filament\Resources\Experience\Schemas\ExperienceForm;
use App\Filament\Resources\Experience\Schemas\ExperienceInfolist;
use App\Filament\Resources\Experience\Tables\ExperienceTable;
use App\Models\Experience;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $modelLabel = 'experiencia';

    protected static ?string $pluralModelLabel = 'experiencias';

    protected static ?string $navigationLabel = 'Experiencia';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ExperienceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExperienceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExperienceTable::configure($table);
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
