<?php

namespace Tests\Unit;

use App\Models\Video;
use PHPUnit\Framework\TestCase;

// Unit test: test alleen de functie youtubeId(), zonder database
class VideoTest extends TestCase
{
    public function test_youtube_id_from_normal_link(): void
    {
        $this->assertSame('dQw4w9WgXcQ', Video::youtubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
    }

    public function test_youtube_id_from_short_link(): void
    {
        $this->assertSame('dQw4w9WgXcQ', Video::youtubeId('https://youtu.be/dQw4w9WgXcQ'));
    }

    public function test_youtube_id_from_link_with_extra_parameters(): void
    {
        $this->assertSame('dQw4w9WgXcQ', Video::youtubeId('https://www.youtube.com/watch?list=abc&v=dQw4w9WgXcQ&t=10'));
    }

    public function test_no_youtube_id_for_other_website(): void
    {
        $this->assertNull(Video::youtubeId('https://www.google.com'));
    }
}