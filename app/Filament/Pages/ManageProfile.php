<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use App\Services\MaintenanceToggler;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * `profiles` is a singleton-in-practice table (see `Profile` model
 * docblock): a single owner row, no listing, no create/delete UI. This is a
 * custom `Filament\Pages\Page` (not a `Resource`) with its own form that
 * loads and saves that one row directly, instead of a full CRUD Resource.
 */
class ManageProfile extends Page implements HasForms
{
    use HasUnsavedDataChangesAlert;
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected string $view = 'filament.pages.manage-profile';

    protected static ?string $navigationLabel = 'Perfil';

    protected static ?string $title = 'Perfil';

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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getPersonalInfoSection(),
                $this->getBioSection(),
                $this->getSocialLinksSection(),
            ])
            ->statePath('data');
    }

    private function getPersonalInfoSection(): Section
    {
        return Section::make('Información Personal')
            ->description('Datos personales, contacto y foto de perfil')
            ->columns(2)
            ->schema([
                FileUpload::make('avatar')
                    ->label('Foto de perfil')
                    ->avatar()
                    ->helperText('')
                    ->required()
                    ->disk('public')
                    ->directory('profiles')
                    ->visibility('public')
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->rules(['regex:/^[\p{L}\s]+$/u'])
                    ->minLength(2)
                    ->maxLength(120),
                TextInput::make('surname')
                    ->label('Apellido')
                    ->required()
                    ->rules(['regex:/^[\p{L}\s]+$/u'])
                    ->minLength(2)
                    ->maxLength(120),
                TextInput::make('email_contact')
                    ->label('Correo electrónico de contacto')
                    ->rules([
                        'email',
                        'min:2',
                        'max:100',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private function getBioSection(): Section
    {
        return Section::make('Bio & Especialidades')
            ->description('Contenido bilingüe presentado en el sitio web')
            ->columns(2)
            ->schema([
                Textarea::make('description_es')
                    ->label('Descripción (ES)')
                    ->rules([
                        'min:10',
                        'max:500',
                    ])
                    ->rows(6),
                Textarea::make('description_en')
                    ->label('Descripción (EN)')
                    ->rules([
                        'min:10',
                        'max:500',
                    ])
                    ->rows(6),
                TextInput::make('specialty_es')
                    ->label('Especialidad (ES)')
                    ->required()
                    ->rules([
                        'min:2',
                        'max:100',
                    ]),
                TextInput::make('specialty_en')
                    ->label('Especialidad (EN)')
                    ->required()
                    ->rules([
                        'min:2',
                        'max:100',
                    ]),
                TextInput::make('language_es')
                    ->label('Idioma (ES)')
                    ->required()
                    ->rules([
                        'min:2',
                        'max:100',
                    ]),
                TextInput::make('language_en')
                    ->label('Idioma (EN)')
                    ->required()
                    ->rules([
                        'min:2',
                        'max:100',
                    ]),
            ]);
    }

    private function getSocialLinksSection(): Section
    {
        return Section::make('Redes & Enlaces')
            ->description('Perfiles y presencia online')
            ->columns(3)
            ->schema([
                TextInput::make('github_url')
                    ->label('GitHub URL')
                    ->url()
                    ->rules([
                        'max:100',
                    ]),
                TextInput::make('linkedin_url')
                    ->label('LinkedIn URL')
                    ->url()
                    ->rules([
                        'max:100',
                    ]),
                TextInput::make('instagram_url')
                    ->label('Instagram URL')
                    ->url()
                    ->rules([
                        'max:100',
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->profile()->fill($data)->save();

        $this->rememberData();

        Notification::make()
            ->title('Perfil guardado')
            ->success()
            ->send();
    }

    /**
     * Header actions for the profile page
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->downloadCvAction(),
            $this->toggleMaintenanceModeAction(),
        ];
    }

    /**
     * Toggles the application's maintenance mode.
     */
    protected function toggleMaintenanceModeAction(): Action
    {
        return Action::make('toggleMaintenanceMode')
            ->label(fn (): string => app()->isDownForMaintenance()
                ? 'Desactivar modo mantenimiento'
                : 'Activar modo mantenimiento')
            ->color(fn (): string => app()->isDownForMaintenance() ? 'success' : 'danger')
            ->icon(fn (): string => app()->isDownForMaintenance()
                ? 'heroicon-o-lock-open'
                : 'heroicon-o-lock-closed')
            ->requiresConfirmation()
            ->modalHeading(fn (): string => app()->isDownForMaintenance()
                ? 'Desactivar modo mantenimiento'
                : 'Activar modo mantenimiento')
            ->modalDescription(fn (): string => app()->isDownForMaintenance()
                ? '¿Estás seguro de que deseas desactivar el modo mantenimiento? El sitio volverá a estar público.'
                : '¿Estás seguro de que deseas activar el modo mantenimiento? Los visitantes no podrán acceder al sitio público.')
            ->modalSubmitActionLabel(fn (): string => app()->isDownForMaintenance()
                ? 'Sí, desactivar'
                : 'Sí, activar')
            ->modalCancelActionLabel('Cancelar')
            ->modalIcon(fn (): string => app()->isDownForMaintenance()
                ? 'heroicon-o-check-circle'
                : 'heroicon-o-exclamation-triangle')
            ->modalIconColor(fn (): string => app()->isDownForMaintenance() ? 'success' : 'danger')
            ->action(function (MaintenanceToggler $toggler): void {
                if (app()->isDownForMaintenance()) {
                    $toggler->deactivate();

                    Notification::make()
                        ->title('Modo mantenimiento desactivado')
                        ->body('El sitio volverá a estar público.')
                        ->success()
                        ->send();

                    return;
                }

                $toggler->activate();

                Notification::make()
                    ->title('Modo mantenimiento activado')
                    ->body('El sitio pasará a modo mantenimiento.')
                    ->warning()
                    ->send();
            });
    }

    protected function downloadCvAction(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('downloadCvEs')
                ->label('Español')
                ->icon('heroicon-o-document-text')
                ->url(route('cv.download', ['locale' => 'es']))
                ->openUrlInNewTab(),
            Action::make('downloadCvEn')
                ->label('Ingles')
                ->icon('heroicon-o-globe-alt')
                ->url(route('cv.download', ['locale' => 'en']))
                ->openUrlInNewTab(),
        ])
            ->label('Descargar CV')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->button();
    }
}
