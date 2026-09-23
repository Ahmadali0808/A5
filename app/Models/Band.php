<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

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
}