# Spotify Laravel

Working with PHP/Laravel is my bread-and-butter and so this is an implementation utilising the tools I'm more familiar 
with.

Like the Node example, this exposes an API endpoint at `/api/group-playlist` that accepts a POST request with a JSON 
body that looks like so:

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

The API endpoint will then take the artist(s) that the user likes and then lookup the top tracks for that artist and add
them to a shared playlist.

## Installation / Running

1. Clone the repo
2. `cd spotify-laravel`
3. `composer install`
4. `cp .env.example .env`
5. `php artisan key:generate`
6. Fill in the `.env` file with a Spotify access token, a playlist ID, and the "market" you listen to music in (i.e AU for Australia)
7. You can now serve the API however you like and make requests to the `/api/group-playlist` endpoint following the structure above
8. Optionally, you can run the tests with `php artisan test`
