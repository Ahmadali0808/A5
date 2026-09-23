<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['youtube_url'];

      public function band()
    {
        return $this->belongsTo(Band::class);
    }
}