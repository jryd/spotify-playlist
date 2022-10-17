# Spotify Node

Despite working with Javascript on the FE for years, I've done very little with it from a BE perspective.

This is an attempt at writing a Lambda function in Node that achieves the goal of adding a users liked artists to a 
shared playlist.

## Installation / Running

1. Clone the repo
2. `cd spotify-node`
3. `npm install`
4. `cp .env.example .env`
5. Fill in the `.env` file with a Spotify access token, a playlist ID, and the "market" you listen to music in (i.e AU for Australia)
6. `npm run start`
