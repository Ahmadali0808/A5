<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BandController extends Controller
{
    // Gast: de EPK pagina van één band
    public function show(Band $band)
    {
        $band->load('videos');

        return view('bands.show', compact('band'));
    }

    // Formulier nieuwe band
    public function create()
    {
        return view('bands.create', ['band' => new Band()]);
    }

    // Nieuwe band opslaan
    public function store(Request $request)
    {
        $data = $this->validateBand($request);
        unset($data['photo'], $data['videos']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $band = Band::create($data);
        $band->users()->attach($request->user()->id); // maker wordt beheerder
        $this->saveVideos($band, $request->input('videos', []));

        return redirect()->route('home')->with('status', 'Band is aangemaakt.');
    }

    // Formulier band aanpassen
    public function edit(Request $request, Band $band)
    {
        $this->checkManager($request, $band);
        $band->load('videos', 'users');

        return view('bands.edit', compact('band'));
    }

    // Aanpassingen opslaan
    public function update(Request $request, Band $band)
    {
        $this->checkManager($request, $band);
        $data = $this->validateBand($request);
        unset($data['photo'], $data['videos']); // foto alleen aanpassen als er een nieuwe is

        if ($request->hasFile('photo')) {
            if ($band->photo) {
                Storage::disk('public')->delete($band->photo); // oude foto weg
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $band->update($data);
        $this->saveVideos($band, $request->input('videos', []));

        return redirect()->route('home')->with('status', 'Band is aangepast.');
    }

    // Band verwijderen
    public function destroy(Request $request, Band $band)
    {
        $this->checkManager($request, $band);

        if ($band->photo) {
            Storage::disk('public')->delete($band->photo);
        }
        $band->delete(); // videos en koppelingen gaan mee (cascade)

        return redirect()->route('home')->with('status', 'Band is verwijderd.');
    }

    // Andere gebruiker (via e-mail) toevoegen als beheerder
    public function addUser(Request $request, Band $band)
    {
        $this->checkManager($request, $band);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        $band->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('bands.edit', $band)->with('status', 'Beheerder toegevoegd.');
    }

    // ---------- hulpfuncties ----------

    private function validateBand(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'videos' => 'nullable|array|max:' . Band::MAX_VIDEOS,
            'videos.*' => ['nullable', 'url', 'regex:/(youtube\.com|youtu\.be)/'],
        ]);
    }

    // Alle oude video's weghalen en de ingevulde links opnieuw opslaan
    private function saveVideos(Band $band, array $urls): void
    {
        $band->videos()->delete();

        foreach (array_filter($urls) as $url) {
            $band->videos()->create(['youtube_url' => $url]);
        }
    }

    // Alleen beheerders van deze band mogen hem aanpassen
    private function checkManager(Request $request, Band $band): void
    {
        if (! $band->isManagedBy($request->user())) {
            abort(403, 'Je beheert deze band niet.');
        }
    }
}