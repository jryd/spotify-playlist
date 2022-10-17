<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GroupPlaylistControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function test_it_fetches_the_top_tracks_for_the_provided_artists(): void
    {
        Http::fake([
            'api.spotify.com/v1/artists/*' => Http::sequence()
                ->push(['tracks' => [
                    ['uri' => 'spotify:track:1'],
                    ['uri' => 'spotify:track:2'],
                ]])
                ->push(['tracks' => [
                    ['uri' => 'spotify:track:3'],
                    ['uri' => 'spotify:track:4'],
                ]]),
            'api.spotify.com/v1/playlists/*' => Http::response([]),
        ]);

        $this->postJson(route('group-playlist.store'), [
            'likes' => [
                ['id' => '0JXDwBs1sEp6UKoAP58UdF'],
                ['id' => '39a4hGdTS669oJBra6j9Ru'],
            ]
        ])->assertSuccessful();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.spotify.com/v1/artists/0JXDwBs1sEp6UKoAP58UdF/top-tracks?market=AU');
        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.spotify.com/v1/artists/39a4hGdTS669oJBra6j9Ru/top-tracks?market=AU');
    }

    public function test_it_updates_the_shared_playlist_with_the_top_tracks(): void
    {
        Http::fake([
            'api.spotify.com/v1/artists/*' => Http::sequence()
                ->push(['tracks' => [
                    ['uri' => 'spotify:track:1'],
                    ['uri' => 'spotify:track:2'],
                ]])
                ->push(['tracks' => [
                    ['uri' => 'spotify:track:3'],
                    ['uri' => 'spotify:track:4'],
                ]]),
            'api.spotify.com/v1/playlists/*' => Http::response([]),
        ]);

        $this->postJson(route('group-playlist.store'), [
            'likes' => [
                ['id' => '0JXDwBs1sEp6UKoAP58UdF'],
                ['id' => '39a4hGdTS669oJBra6j9Ru'],
            ]
        ])->assertSuccessful();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.spotify.com/v1/playlists/0RGv0insWyRwR9gerBgWBB/tracks'
            && $request['uris'] === [
                'spotify:track:1',
                'spotify:track:2',
                'spotify:track:3',
                'spotify:track:4',
            ],
        );
    }

    public function test_it_updates_the_shared_playlist_with_the_top_tracks_that_were_fetched_when_there_was_errors_fetching_some_tracks(): void
    {
        Http::fake([
            'api.spotify.com/v1/artists/*' => Http::sequence()
                ->push(['tracks' => [
                    ['uri' => 'spotify:track:1'],
                    ['uri' => 'spotify:track:2'],
                ]])
                ->push([], 500),
            'api.spotify.com/v1/playlists/*' => Http::response([]),
        ]);

        $this->postJson(route('group-playlist.store'), [
            'likes' => [
                ['id' => '0JXDwBs1sEp6UKoAP58UdF'],
                ['id' => '39a4hGdTS669oJBra6j9Ru'],
            ]
        ])->assertSuccessful();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.spotify.com/v1/playlists/0RGv0insWyRwR9gerBgWBB/tracks'
            && $request['uris'] === [
                'spotify:track:1',
                'spotify:track:2',
            ],
        );
    }

    public function test_it_does_not_attempt_to_update_the_shared_playlist_if_no_tracks_were_able_to_be_fetched(): void
    {
        Http::fake([
            'api.spotify.com/v1/artists/*' => Http::response([], 500),
        ]);

        $this->postJson(route('group-playlist.store'), [
            'likes' => [
                ['id' => '0JXDwBs1sEp6UKoAP58UdF'],
            ]
        ])->assertSuccessful();

        Http::assertNotSent(fn (Request $request) => $request->url() === 'https://api.spotify.com/v1/playlists/0RGv0insWyRwR9gerBgWBB/tracks');
    }

    /**
     * @dataProvider validationFailures
     */
    public function test_it_validates_the_request(array $payload, array $errors): void
    {
        Http::fake();

        $this->postJson(route('group-playlist.store'), $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors($errors);

        Http::assertNothingSent();
    }

    public function validationFailures()
    {
        return [
            'Missing likes' => [
                [],
                ['likes' => ['The likes field is required.']],
            ],
            'Likes not an array' => [
                ['likes' => 'not an array'],
                ['likes' => ['The likes must be an array.']],
            ],
            'Likes missing an id' => [
                ['likes' => [['name' => 'Missing ID field']]],
                ['likes.0.id' => ['The likes.0.id field is required.']],
            ],
        ];
    }
}
