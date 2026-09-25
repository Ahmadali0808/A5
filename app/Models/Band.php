<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;
        // Maximaal aantal video's per band.
    // De opdrachtgever twijfelt tussen 3 en 4: alleen dit getal aanpassen is genoeg.
    public const MAX_VIDEOS = 3;

    protected $fillable = [
        'name',
        'description',
        'biography',
        'photo',
        'background_color',
        'text_color',

        
    ];
     public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // One-to-many: een band heeft meerdere video's
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
        // Controleert of een gebruiker deze band mag beheren
    public function isManagedBy(?User $user): bool
    {
        return $user !== null && $this->users()->where('users.id', $user->id)->exists();
    }
}