<?php

namespace Tests\Unit;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Unit test van het model Band: relaties en de regel isManagedBy()
class BandTest extends TestCase
{
    use RefreshDatabase;

    public function test_band_can_have_multiple_users(): void
    {
        $band = Band::factory()->create();
        $users = User::factory()->count(2)->create();

        $band->users()->attach($users);

        $this->assertCount(2, $band->users);
    }

    public function test_user_can_manage_multiple_bands(): void
    {
        $user = User::factory()->create();
        $bands = Band::factory()->count(3)->create();

        $user->bands()->attach($bands);

        $this->assertCount(3, $user->bands);
    }

    public function test_band_has_videos(): void
    {
        $band = Band::factory()->create();
        $band->videos()->create(['youtube_url' => 'https://youtu.be/dQw4w9WgXcQ']);

        $this->assertCount(1, $band->videos);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $band->videos->first()->embedUrl());
    }

    public function test_is_managed_by(): void
    {
        $band = Band::factory()->create();
        $manager = User::factory()->create();
        $other = User::factory()->create();
        $band->users()->attach($manager);

        $this->assertTrue($band->isManagedBy($manager));
        $this->assertFalse($band->isManagedBy($other));
        $this->assertFalse($band->isManagedBy(null)); // gast
    }

    public function test_videos_are_deleted_with_band(): void
    {
        $band = Band::factory()->create();
        $band->videos()->create(['youtube_url' => 'https://youtu.be/dQw4w9WgXcQ']);

        $band->delete();

        $this->assertDatabaseCount('videos', 0); // cascadeOnDelete werkt
    }
}