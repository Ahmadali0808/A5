<?php

namespace App\Http\Requests;

use App\Models\Band;
use Illuminate\Foundation\Http\FormRequest;

class BandRequest extends FormRequest
{
    // Mag deze gebruiker dit formulier versturen?
    // Nieuwe band: altijd. Bestaande band: alleen als je beheerder bent.
    public function authorize(): bool
    {
        $band = $this->route('band');

        return $band === null || $band->isManagedBy($this->user());
    }

    // Validatieregels voor het bandformulier
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'videos' => 'nullable|array|max:' . Band::MAX_VIDEOS,
            'videos.*' => ['nullable', 'url', 'regex:/(youtube\.com|youtu\.be)/'],
        ];
    }
}