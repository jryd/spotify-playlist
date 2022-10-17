<?php

namespace App\Services;

use App\DTO\Track;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Playlist
{
    /**
     * @param Collection<Track> $tracks
     * @return array
     */
    public function addTracks(Collection $tracks): array
    {
        return Http::withToken(config('services.spotify.token'))
            ->post(
                sprintf('https://api.spotify.com/v1/playlists/%s/tracks', config('services.spotify.playlist')),
                ['uris' => $tracks->pluck('uri')->toArray()]
            )
            ->json();
    }
}
