<?php

namespace App\Services;

use App\DTO\Track;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TopTracks
{
    /**
     * @param Collection $artists
     * @return Collection<Track>
     */
    public function fromArtists(Collection $artists)
    {
        $responses = Http::pool(fn (Pool $pool) => $artists
            ->map(fn ($artist) =>
                $pool->withToken(config('services.spotify.token'))
                    ->get(
                        sprintf(
                            'https://api.spotify.com/v1/artists/%s/top-tracks?market=%s',
                            $artist['id'],
                            config('services.spotify.market')
                        )
                    )
            )
            ->toArray()
        );

        return collect($responses)
            ->map(fn ($response) => $response->json()['tracks'] ?? [])
            ->flatten(1)
            ->mapInto(Track::class);
    }
}
