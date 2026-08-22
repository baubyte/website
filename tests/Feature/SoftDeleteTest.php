<?php

namespace Tests\Feature;

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_skill_soft_deletes_it_instead_of_removing_the_row(): void
    {
        $skill = Skill::create(['name' => 'Laravel', 'percentage' => 80]);

        $skill->delete();

        $this->assertNull(Skill::find($skill->id));
        $this->assertFalse(Skill::all()->contains('id', $skill->id));

        $trashed = Skill::withTrashed()->find($skill->id);
        $this->assertNotNull($trashed);
        $this->assertNotNull($trashed->deleted_at);
    }
}
