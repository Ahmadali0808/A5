<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// UI tests voor het beheren van bands
class ManageBandTest extends TestCase
{
    use RefreshDatabase;

    // Standaard formuliergegevens, per test aan te passen
    private function bandData(array $extra = []): array
    {
        return array_merge([
            'name' => 'Test Band',
            'description' => 'Korte beschrijving',
            'biography' => 'Lange biografie',
            'background_color' => '#000000',
            'text_color' => '#ffffff',
            'videos' => ['https://youtu.be/dQw4w9WgXcQ', '', ''],
        ], $extra);
    }

    public function test_user_can_create_band_with_photo(): void
    {
        Storage::fake('public'); // nep-opslag, zodat er geen echte bestanden komen
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData([
            'photo' => UploadedFile::fake()->create('band.jpg', 100, 'image/jpeg'),
        ]));

        $response->assertRedirect(route('home'));
        $band = Band::first();
        $this->assertSame('Test Band', $band->name);
        $this->assertTrue($band->isManagedBy($user));  // maker is beheerder
        $this->assertCount(1, $band->videos);          // lege velden niet opgeslagen
        Storage::disk('public')->assertExists($band->photo);
    }

    public function test_band_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData(['name' => '']));

        $response->assertSessionHasErrors('name');
        $this->assertSame(0, Band::count());
    }

    public function test_photo_must_be_an_image(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData([
            'photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]));

        $response->assertSessionHasErrors('photo');
    }

    public function test_not_more_videos_than_maximum(): void
    {
        $user = User::factory()->create();
        $videos = array_fill(0, Band::MAX_VIDEOS + 1, 'https://youtu.be/dQw4w9WgXcQ');

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData(['videos' => $videos]));

        $response->assertSessionHasErrors('videos');
    }

    public function test_video_must_be_youtube_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData([
            'videos' => ['https://www.google.com'],
        ]));

        $response->assertSessionHasErrors('videos.0');
    }

    public function test_color_must_be_valid(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bands.store'), $this->bandData([
            'background_color' => 'red; display:none',
        ]));

        $response->assertSessionHasErrors('background_color');
    }

    public function test_manager_can_update_band(): void
    {
        $user = User::factory()->create();
        $band = Band::factory()->create(['photo' => 'photos/oud.jpg']);
        $band->users()->attach($user);

        $response = $this->actingAs($user)->put(route('bands.update', $band), $this->bandData([
            'name' => 'Nieuwe Naam',
            'background_color' => '#ff0000',
        ]));

        $response->assertRedirect(route('home'));
        $band->refresh();
        $this->assertSame('Nieuwe Naam', $band->name);
        $this->assertSame('#ff0000', $band->background_color);
        $this->assertSame('photos/oud.jpg', $band->photo); // foto blijft staan
    }

    public function test_other_user_cannot_change_band(): void
    {
        $other = User::factory()->create();
        $band = Band::factory()->create();

        $this->actingAs($other)->get(route('bands.edit', $band))->assertForbidden();
        $this->actingAs($other)->put(route('bands.update', $band), $this->bandData())->assertForbidden();
        $this->actingAs($other)->delete(route('bands.destroy', $band))->assertForbidden();
    }

    public function test_manager_can_delete_band(): void
    {
        $user = User::factory()->create();
        $band = Band::factory()->create();
        $band->users()->attach($user);

        $this->actingAs($user)->delete(route('bands.destroy', $band))->assertRedirect(route('home'));

        $this->assertDatabaseMissing('bands', ['id' => $band->id]);
    }

    public function test_manager_can_add_second_manager(): void
    {
        $user = User::factory()->create();
        $second = User::factory()->create();
        $band = Band::factory()->create();
        $band->users()->attach($user);

        $this->actingAs($user)->post(route('bands.users.add', $band), ['email' => $second->email]);

        $this->assertTrue($band->isManagedBy($second));
        $this->assertCount(2, $band->fresh()->users);
    }
}