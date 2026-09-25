<?php

namespace Database\Seeders;

use App\Models\Band;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Testdata voor de demo: php artisan migrate:fresh --seed
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test Gebruiker',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $tweede = User::factory()->create([
            'name' => 'Tweede Gebruiker',
            'email' => 'tweede@example.com',
            'password' => 'password',
        ]);

        $band1 = Band::create([
            'name' => 'The Night Owls',
            'description' => 'Indie rock band uit Drenthe.',
            'biography' => 'The Night Owls werd opgericht in 2019 en speelt op festivals door heel Nederland.',
            'background_color' => '#1e1e2f',
            'text_color' => '#f5f5f5',
        ]);
        $band1->users()->attach([$user->id, $tweede->id]); // twee beheerders
        $band1->videos()->create(['youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);

        $band2 = Band::create([
            'name' => 'Blue Harbour',
            'description' => 'Jazz en soul kwartet.',
            'biography' => 'Blue Harbour brengt klassieke jazz met een moderne twist.',
            'background_color' => '#dbeafe',
            'text_color' => '#1e3a8a',
        ]);
        $band2->users()->attach($user->id);
    }
}