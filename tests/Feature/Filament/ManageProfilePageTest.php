<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\ManageProfile;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * `profiles` is a singleton-in-practice table (one owner row). This suite
 * seeds a row the same shape `legacy:import` (PR3) leaves behind, then
 * confirms the custom `ManageProfile` page loads and updates that exact
 * row — no listing, no create/delete UI.
 */
class ManageProfilePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeProfile(): Profile
    {
        return Profile::create([
            'name' => 'Martin',
            'surname' => 'Paredes',
            'avatar' => 'avatar.webp',
            'email_contact' => 'contact@example.test',
            'specialty_es' => 'Desarrollador Backend',
        ]);
    }

    public function test_it_loads_the_existing_profile_data_into_the_form(): void
    {
        $profile = $this->makeProfile();

        Livewire::test(ManageProfile::class)
            ->assertFormSet([
                'name' => $profile->name,
                'surname' => $profile->surname,
                'email_contact' => $profile->email_contact,
            ]);
    }

    public function test_it_can_update_the_existing_profile(): void
    {
        $profile = $this->makeProfile();

        Livewire::test(ManageProfile::class)
            ->fillForm(['specialty_es' => 'Desarrollador Full Stack'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'specialty_es' => 'Desarrollador Full Stack',
        ]);
    }
}
