# Spotify Node

Despite working with Javascript on the FE for years, I've done very little with it from a BE perspective.

This is an attempt at writing a Lambda function in Node that achieves the goal of adding a users liked artists to a 
shared playlist.

## Installation / Running

I wasn't sure what the "best" was to run lambda functions locally.

Whilst the `index.js` file represents how this would look in a Lambda function, to run it locally, I was editing the
`index.js` file to provide a hardcoded user object and updating the function to be an anonymous function that I then 
invoked.

```javascript
import { addTracksToPlaylist, fetchTopTracksFor } from './spotify.js';

(async () => {
  const user = {likes: [{id: '0JXDwBs1sEp6UKoAP58UdF'}]};

  const topTracks = await fetchTopTracksFor(user);

  await addTracksToPlaylist(topTracks.map(track => track.uri));
})();
```

1. Clone the repo
2. `cd spotify-node`
3. `npm install`
4. `cp .env.example .env`
5. Fill in the `.env` file with a Spotify access token, a playlist ID, and the "market" you listen to music in (i.e AU for Australia)
6. `npm run start`
