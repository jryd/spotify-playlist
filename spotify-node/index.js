import { addTracksToPlaylist, fetchTopTracksFor } from './spotify.js';

exports.handler = async (user) => {
  const topTracks = await fetchTopTracksFor(user);

  await addTracksToPlaylist(topTracks.map(track => track.uri));
};
