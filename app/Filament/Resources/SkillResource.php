<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Models\Skill;
use App\Support\Icons\IconCatalog;
use Closure;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                Forms\Components\TextInput::make('percentage')
                    ->label('Percentage')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->label('Category')
                    ->helperText('How this skill groups on the public site. Free text — group them however makes sense, e.g. "Lenguajes", "Frameworks", "Bases de datos".')
                    ->datalist(fn () => Skill::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category')->all())
                    ->maxLength(60),
                Forms\Components\Select::make('icon')
                    ->label('Icon')
                    ->helperText('Search across ~1000 tech icons. Optional — skills left without one fall back to legacy name matching on the public site.')
                    ->searchable()
                    ->allowHtml()
                    ->getSearchResultsUsing(fn (string $search): array => self::iconOptions(IconCatalog::search($search)))
                    ->getOptionLabelUsing(fn (?string $state): ?string => $state === null ? null : self::renderIconOption($state, IconCatalog::labelFor($state) ?? $state))
                    ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        if ($value !== null && ! IconCatalog::has($value)) {
                            $fail('The selected icon is not valid.');
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentage')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->html()
                    ->formatStateUsing(fn (?string $state): string => $state === null
                        ? '—'
                        : self::renderIconOption($state, IconCatalog::labelFor($state) ?? $state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Skill::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category', 'category')->all()),
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
            '<span class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="20" height="20" class="shrink-0">%s</svg><span>%s</span></span>',
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
            'index' => Pages\ListSkills::route('/'),
            'create' => Pages\CreateSkill::route('/create'),
            'edit' => Pages\EditSkill::route('/{record}/edit'),
        ];
    }
}
