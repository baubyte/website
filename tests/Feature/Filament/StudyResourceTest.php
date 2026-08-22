<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\StudyResource\Pages\CreateStudy;
use App\Filament\Resources\StudyResource\Pages\EditStudy;
use App\Filament\Resources\StudyResource\Pages\ListStudies;
use App\Models\Study;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Full CRUD round-trip against `StudyResource` through Filament's own
 * Livewire pages, as an authenticated admin user.
 */
class StudyResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeStudy(): Study
    {
        return Study::create([
            'entity' => 'UTN',
            'title_es' => 'Ingeniería en Sistemas',
            'title_en' => 'Systems Engineering',
            'start_date' => '2015-01-01',
            'end_date' => '2020-01-01',
        ]);
    }

    public function test_it_can_list_studies(): void
    {
        $study = $this->makeStudy();

        Livewire::test(ListStudies::class)
            ->assertCanSeeTableRecords([$study]);
    }

    public function test_it_can_create_a_study(): void
    {
        Livewire::test(CreateStudy::class)
            ->fillForm([
                'entity' => 'UTN',
                'title_es' => 'Ingeniería en Sistemas',
                'title_en' => 'Systems Engineering',
                'start_date' => '2015-01-01',
                'end_date' => '2020-01-01',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('studies', [
            'entity' => 'UTN',
        ]);
    }

    public function test_it_can_edit_a_study(): void
    {
        $study = $this->makeStudy();

        Livewire::test(EditStudy::class, ['record' => $study->getRouteKey()])
            ->fillForm(['entity' => 'Renamed Entity'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('studies', [
            'id' => $study->id,
            'entity' => 'Renamed Entity',
        ]);
    }

    public function test_it_can_soft_delete_a_study(): void
    {
        $study = $this->makeStudy();

        Livewire::test(ListStudies::class)
            ->callTableAction('delete', $study);

        $this->assertSoftDeleted('studies', ['id' => $study->id]);
    }
}
