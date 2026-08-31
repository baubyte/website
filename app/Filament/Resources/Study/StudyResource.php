<?php

namespace App\Filament\Resources\Study;

use App\Filament\Resources\Study\Pages\CreateStudy;
use App\Filament\Resources\Study\Pages\EditStudy;
use App\Filament\Resources\Study\Pages\ListStudies;
use App\Filament\Resources\Study\Schemas\StudyForm;
use App\Filament\Resources\Study\Schemas\StudyInfolist;
use App\Filament\Resources\Study\Tables\StudyTable;
use App\Models\Study;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static ?string $modelLabel = 'Estudio';

    protected static ?string $pluralModelLabel = 'Estudios';

    protected static ?string $navigationLabel = 'Estudios';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    public static function form(Schema $schema): Schema
    {
        return StudyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudyTable::configure($table);
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
