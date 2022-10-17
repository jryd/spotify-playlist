# Spotify Playlist

This is a sample project that uses the Spotify API to create a playlist, based on an incoming request that contains a
list of artists that a user likes.

The request assumes a format like so:

```json
{
    "likes": [
        {
            "id":123,
            "name":"Tame Impala"
        }
    ]
}
```

We take the artist(s) that the user likes and then lookup the top tracks for that artist.

These tracks are then pushed into a shared playlist.
