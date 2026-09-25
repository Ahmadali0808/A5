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
        // Haalt het YouTube-id uit een link, bijv.
    // https://www.youtube.com/watch?v=abc123  of  https://youtu.be/abc123
    public static function youtubeId(string $url): ?string
    {
        $pattern = '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    // Link die je in een <iframe> kunt gebruiken
    public function embedUrl(): ?string
    {
        $id = self::youtubeId($this->youtube_url);

        return $id ? 'https://www.youtube.com/embed/' . $id : null;
    }
}