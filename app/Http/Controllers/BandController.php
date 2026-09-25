<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\User;
use Illuminate\Http\Request;

class BandController extends Controller
{

    // Gast: de EPK pagina van één band
    public function show(Band $band)
    {
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

        $band = Band::create($data);
        $band->users()->attach($request->user()->id); // maker wordt beheerder

        return redirect()->route('home')->with('status', 'Band is aangemaakt.');
    }

    // Formulier band aanpassen
    public function edit(Request $request, Band $band)
    {
        $this->checkManager($request, $band);

        return view('bands.edit', compact('band'));
    }

    // Aanpassingen opslaan
    public function update(Request $request, Band $band)
    {
        $this->checkManager($request, $band);

        $band->update($this->validateBand($request));

        return redirect()->route('home')->with('status', 'Band is aangepast.');
    }

    // Band verwijderen
    public function destroy(Request $request, Band $band)
    {
        $this->checkManager($request, $band);

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
        ]);
    }

    // Alleen beheerders van deze band mogen hem aanpassen
    private function checkManager(Request $request, Band $band): void
    {
        if (! $band->isManagedBy($request->user())) {
            abort(403, 'Je beheert deze band niet.');
        }
    }
}