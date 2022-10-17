<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserLikesToPlaylistRequest;
use App\Services\Playlist;
use App\Services\TopTracks;
use Illuminate\Support\Collection;

class GroupPlaylistController extends Controller
{
    public function store(AddUserLikesToPlaylistRequest $request, TopTracks $topTracks, Playlist $playlist)
    {
        $topTracks->fromArtists($request->collect('likes'))
            ->whenNotEmpty(fn (Collection $tracks) => $playlist->addTracks($tracks));
    }
}
