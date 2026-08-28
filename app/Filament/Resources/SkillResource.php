<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages\CreateSkill;
use App\Filament\Resources\SkillResource\Pages\EditSkill;
use App\Filament\Resources\SkillResource\Pages\ListSkills;
use App\Models\Skill;
use App\Support\Icons\IconCatalog;
use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $modelLabel = 'Habilidad';

    protected static ?string $pluralModelLabel = 'Habilidades';

    protected static ?string $navigationLabel = 'Habilidades';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getSkillInfoSection(),
                self::getSkillConfigSection(),
            ]);
    }

    protected static function getSkillInfoSection(): Section
    {
        return Section::make('Información de la Habilidad')
            ->description('Datos y Nivel de la habilidad')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->rules(['min:2', 'max:120']),
                TextInput::make('percentage')
                    ->label('Porcentaje')
                    ->suffix('%')
                    ->required()
                    ->rules(['numeric', 'integer', 'min:1', 'max:100']),
            ]);
    }

    protected static function getSkillConfigSection(): Section
    {
        return Section::make('Configuración de la Habilidad')
            ->description('Categoría e Icono de la habilidad')
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                Select::make('skill_category_id')
                    ->relationship('skillCategory', 'name_es')
                    ->label('Categoría')
                    ->helperText('Cómo se agrupa esta habilidad en el sitio público.')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name_es')
                            ->label('Nombre (Español)')
                            ->required()
                            ->maxLength(60),
                        TextInput::make('name_en')
                            ->label('Nombre (Inglés)')
                            ->maxLength(60),
                    ]),
                Select::make('icon')
                    ->label('Icono')
                    ->helperText('Busca entre ~1000 iconos de tecnología. Opcional: las habilidades sin icono quedan sin él y usan el sistema de coincidencia de nombres heredado en el sitio público.')
                    ->searchable()
                    ->allowHtml()
                    ->getSearchResultsUsing(fn (string $search): array => self::iconOptions(IconCatalog::search($search)))
                    ->getOptionLabelUsing(fn (?string $state): ?string => $state === null ? null : self::renderIconOption($state, IconCatalog::labelFor($state) ?? $state))
                    ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        if ($value !== null && ! IconCatalog::has($value)) {
                            $fail('El icono seleccionado no es válido.');
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('skillCategory.name_es')
                    ->label('Categoría')
                    ->badge()
                    ->sortable(),
                TextColumn::make('percentage')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('icon')
                    ->label('Icono')
                    ->html()
                    ->formatStateUsing(fn (?string $state): string => $state === null
                        ? '—'
                        : self::renderIconOption($state, IconCatalog::labelFor($state) ?? $state)),
            ])
            ->filters([
                SelectFilter::make('skill_category_id')
                    ->relationship('skillCategory', 'name_es')
                    ->label('Categoría'),
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

    /**
     * @param  list<array{id: string, label: string}>  $entries
     * @return array<string, string>
     */
    private static function iconOptions(array $entries): array
    {
        $options = [];

        foreach ($entries as $entry) {
            $options[$entry['id']] = self::renderIconOption($entry['id'], $entry['label']);
        }

        return $options;
    }

    // SECURITY: allowHtml() renders this raw — SVG must come only from IconCatalog::resolve(), never raw input.
    private static function renderIconOption(string $id, string $label): string
    {
        $icon = IconCatalog::resolve($id);

        if ($icon === null) {
            return e($label);
        }

        return sprintf(
            '<span class="inline-flex items-center gap-2" style="display: inline-flex; align-items: center; gap: 0.5rem; vertical-align: middle;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="20" height="20" class="shrink-0" style="width: 20px; height: 20px; flex-shrink: 0; display: inline-block; vertical-align: middle;">%s</svg><span>%s</span></span>',
            $icon['width'],
            $icon['height'],
            $icon['body'],
            e($label),
        );
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
}
