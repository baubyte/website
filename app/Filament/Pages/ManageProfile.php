<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use App\Services\MaintenanceToggler;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * `profiles` is a singleton-in-practice table (see `Profile` model
 * docblock): a single owner row, no listing, no create/delete UI. This is a
 * custom `Filament\Pages\Page` (not a `Resource`) with its own form that
 * loads and saves that one row directly, instead of a full CRUD Resource.
 */
class ManageProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.manage-profile';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $title = 'Profile';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->profile()->toArray());
    }

    public function profile(): Profile
    {
        return Profile::query()->firstOrNew();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                TextInput::make('surname')
                    ->required()
                    ->maxLength(120),
                TextInput::make('avatar')
                    ->helperText('Filename/path of the profile avatar image.')
                    ->required()
                    ->maxLength(64),
                TextInput::make('email_contact')
                    ->label('Contact email')
                    ->email()
                    ->maxLength(100),
                Textarea::make('description_es')
                    ->label('Description (ES)')
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label('Description (EN)')
                    ->columnSpanFull(),
                TextInput::make('specialty_es')
                    ->label('Specialty (ES)')
                    ->required()
                    ->maxLength(100),
                TextInput::make('specialty_en')
                    ->label('Specialty (EN)')
                    ->maxLength(100),
                TextInput::make('language_es')
                    ->label('Language (ES)')
                    ->maxLength(100),
                TextInput::make('language_en')
                    ->label('Language (EN)')
                    ->maxLength(100),
                TextInput::make('github_url')
                    ->label('GitHub URL')
                    ->url()
                    ->maxLength(100),
                TextInput::make('linkedin_url')
                    ->label('LinkedIn URL')
                    ->url()
                    ->maxLength(100),
                TextInput::make('instagram_url')
                    ->label('Instagram URL')
                    ->url()
                    ->maxLength(100),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->profile()->fill($data)->save();

        Notification::make()
            ->title('Profile saved')
            ->success()
            ->send();
    }

    /**
     * Connects the `MaintenanceToggler` service (PR5) to a real panel UI: a
     * single header action that flips between activate/deactivate depending
     * on the app's current maintenance state.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleMaintenanceMode')
                ->label(fn (): string => app()->isDownForMaintenance()
                    ? 'Deactivate maintenance mode'
                    : 'Activate maintenance mode')
                ->color(fn (): string => app()->isDownForMaintenance() ? 'success' : 'danger')
                ->icon(fn (): string => app()->isDownForMaintenance()
                    ? 'heroicon-o-lock-open'
                    : 'heroicon-o-lock-closed')
                ->requiresConfirmation()
                ->action(function (MaintenanceToggler $toggler): void {
                    if (app()->isDownForMaintenance()) {
                        $toggler->deactivate();

                        Notification::make()
                            ->title('Maintenance mode deactivated')
                            ->success()
                            ->send();

                        return;
                    }

                    $toggler->activate();

                    Notification::make()
                        ->title('Maintenance mode activated')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
