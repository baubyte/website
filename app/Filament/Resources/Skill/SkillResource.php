<?php

namespace App\Filament\Resources\Skill;

use App\Filament\Resources\Skill\Pages\CreateSkill;
use App\Filament\Resources\Skill\Pages\EditSkill;
use App\Filament\Resources\Skill\Pages\ListSkills;
use App\Filament\Resources\Skill\Schemas\SkillForm;
use App\Filament\Resources\Skill\Schemas\SkillInfolist;
use App\Filament\Resources\Skill\Tables\SkillTable;
use App\Models\Skill;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $modelLabel = 'Habilidad';

    protected static ?string $pluralModelLabel = 'Habilidades';

    protected static ?string $navigationLabel = 'Habilidades';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::PuzzlePiece;

    public static function form(Schema $schema): Schema
    {
        return SkillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SkillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkillTable::configure($table);
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
            'index' => ListSkills::route('/'),
            'create' => CreateSkill::route('/create'),
            'edit' => EditSkill::route('/{record}/edit'),
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
