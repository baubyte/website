<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ExperienceResource\Pages\CreateExperience;
use App\Filament\Resources\ExperienceResource\Pages\EditExperience;
use App\Filament\Resources\ExperienceResource\Pages\ListExperiences;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Full CRUD round-trip against `ExperienceResource` through Filament's own
 * Livewire pages, as an authenticated admin user.
 */
class ExperienceResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeExperience(): Experience
    {
        return Experience::create([
            'company' => 'Baubyte',
            'specialty_es' => 'Backend',
            'specialty_en' => 'Backend',
            'start_date' => '2020-01-01',
            'end_date' => '2022-01-01',
        ]);
    }

    public function test_it_can_list_experiences(): void
    {
        $experience = $this->makeExperience();

        Livewire::test(ListExperiences::class)
            ->assertCanSeeTableRecords([$experience]);
    }

    public function test_it_can_create_an_experience(): void
    {
        Livewire::test(CreateExperience::class)
            ->fillForm([
                'company' => 'Baubyte',
                'specialty_es' => 'Backend',
                'specialty_en' => 'Backend',
                'start_date' => '2020-01-01',
                'end_date' => '2022-01-01',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('experiences', [
            'company' => 'Baubyte',
        ]);
    }

    public function test_it_can_edit_an_experience(): void
    {
        $experience = $this->makeExperience();

        Livewire::test(EditExperience::class, ['record' => $experience->getRouteKey()])
            ->fillForm(['company' => 'Renamed Company'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'company' => 'Renamed Company',
        ]);
    }

    public function test_it_can_soft_delete_an_experience(): void
    {
        $experience = $this->makeExperience();

        Livewire::test(ListExperiences::class)
            ->callTableAction('delete', $experience);

        $this->assertSoftDeleted('experiences', ['id' => $experience->id]);
    }
}
