<?php

namespace Tests\Feature;

use App\Models\Band;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// UI tests voor een gast (niet ingelogd)
class GuestTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_band_overview(): void
    {
        Band::factory()->create(['name' => 'The Night Owls', 'description' => 'Indie rock']);

        $response = $this->get(route('bands.index'));

        $response->assertStatus(200);
        $response->assertViewIs('bands.index');
        $response->assertSeeText('The Night Owls');
        $response->assertSeeText('Indie rock');
    }

    public function test_guest_can_search_on_band_name(): void
    {
        Band::factory()->create(['name' => 'The Night Owls']);
        Band::factory()->create(['name' => 'Blue Harbour']);

        $response = $this->get(route('bands.index', ['search' => 'Owls']));

        $response->assertSeeText('The Night Owls');
        $response->assertDontSeeText('Blue Harbour');
    }

    public function test_search_without_results(): void
    {
        Band::factory()->create(['name' => 'The Night Owls']);

        $response = $this->get(route('bands.index', ['search' => 'xyzxyz']));

        $response->assertSeeText('Geen bands gevonden.');
    }

    public function test_search_result_shows_photo(): void
    {
        Band::factory()->create(['name' => 'Foto Band', 'photo' => 'photos/test.jpg']);

        $response = $this->get(route('bands.index', ['search' => 'Foto']));

        $response->assertSee('storage/photos/test.jpg');
    }

    public function test_guest_can_view_epk_page(): void
    {
        $band = Band::factory()->create([
            'name' => 'The Night Owls',
            'description' => 'Indie rock',
            'biography' => 'Opgericht in 2019',
            'background_color' => '#123456',
            'text_color' => '#abcdef',
        ]);
        $band->videos()->create(['youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);

        $response = $this->get(route('bands.show', $band));

        $response->assertStatus(200);
        $response->assertSeeText('The Night Owls');
        $response->assertSeeText('Indie rock');
        $response->assertSeeText('Opgericht in 2019');
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ');
        $response->assertSee('#123456');
        $response->assertSee('#abcdef');
    }

    public function test_unknown_band_gives_404(): void
    {
        $this->get('/bands/999')->assertNotFound();
    }

    public function test_guest_cannot_see_dashboard(): void
    {
        $this->get(route('home'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_band(): void
    {
        $this->get(route('bands.create'))->assertRedirect(route('login'));
    }
}