<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Formulier gebruikersgegevens
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    // Gebruikersgegevens opslaan
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Wachtwoord alleen aanpassen als er iets is ingevuld
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('home')->with('status', 'Gegevens zijn opgeslagen.');
    }
}
