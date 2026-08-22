<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\SkillResource\Pages\CreateSkill;
use App\Filament\Resources\SkillResource\Pages\EditSkill;
use App\Filament\Resources\SkillResource\Pages\ListSkills;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Full CRUD round-trip against `SkillResource` through Filament's own
 * Livewire pages, as an authenticated admin user.
 */
class SkillResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_it_can_list_skills(): void
    {
        $skill = Skill::create(['name' => 'Laravel', 'percentage' => 80]);

        Livewire::test(ListSkills::class)
            ->assertCanSeeTableRecords([$skill]);
    }

    public function test_it_can_create_a_skill(): void
    {
        Livewire::test(CreateSkill::class)
            ->fillForm([
                'name' => 'PHP',
                'percentage' => 90,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('skills', [
            'name' => 'PHP',
            'percentage' => 90,
        ]);
    }

    public function test_it_can_edit_a_skill(): void
    {
        $skill = Skill::create(['name' => 'PHP', 'percentage' => 50]);

        Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
            ->fillForm(['percentage' => 95])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'percentage' => 95,
        ]);
    }

    public function test_it_can_soft_delete_a_skill(): void
    {
        $skill = Skill::create(['name' => 'PHP', 'percentage' => 50]);

        Livewire::test(ListSkills::class)
            ->callTableAction('delete', $skill);

        $this->assertSoftDeleted('skills', ['id' => $skill->id]);
    }
}
