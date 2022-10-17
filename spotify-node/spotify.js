import fetch from 'node-fetch';

export const fetchTopTracksFor = async (user) => {
  const artistUrls = user.likes.map(like => `https://api.spotify.com/v1/artists/${like.id}/top-tracks?market=${process.env.SPOTIFY_MARKET}`);

  const tracks = await Promise.all(
    artistUrls.map(url => fetch(url, {
      headers: {
        Authorization: `Bearer ${process.env.SPOTIFY_ACCESS_TOKEN}`,
      }
    }))
  );

  return await tracks.reduce(async (acc, res) => {
    const body = await res.json();
    return acc.concat(body.tracks);
  }, []);
};

export const addTracksToPlaylist = async (tracks) => {
  await fetch(`https://api.spotify.com/v1/playlists/${process.env.PLAYLIST_ID}/tracks`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${process.env.SPOTIFY_ACCESS_TOKEN}`,
    },
    body: JSON.stringify({
      uris: tracks,
      position: 0,
    })
  });
};
