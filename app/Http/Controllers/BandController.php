<?php

namespace App\Http\Controllers;

use App\Http\Requests\BandRequest;
use App\Models\Band;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BandController extends Controller
{

    // Gast: overzicht van bands + zoeken op bandnaam
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bands = Band::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('name')->get();

        return view('bands.index', compact('bands', 'search'));
    }
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
    public function store(BandRequest $request)
    {
        $data = $request->safe()->except(['photo', 'videos']);

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

    // Aanpassingen opslaan (BandRequest controleert al of je beheerder bent)
    public function update(BandRequest $request, Band $band)
    {
        $data = $request->safe()->except(['photo', 'videos']);

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