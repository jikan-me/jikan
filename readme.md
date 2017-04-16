# Unofficial MyAnimelist PHP API

This PHP based API works through extracting data through scraping-forms of data that the official API does not provide, such as Anime, Manga, Person, Character details.

This is a very early alpha version and is in WIP. The only functional part getting anime details from it's page.

More to follow.

## What data does this scrap?
### Anime
- Canonical Name (for links)
- Title
- Alternative Titles
- Japanese/Kanji Title
- Image link
- Synopsis
- Episodes
- Status
- Aired
- Premiered
- Broadcast
- Producers + Links to them
- Licensors + Links to them
- Studios + Links to them
- Source
- Genres + Links to them
- Duration
- Rating
- Score
- Ranked
- Popularity
- Members
- Favorites
- Related Anime (types + links to them); e.g, Adaption + Adaption link, etc

### Manga
- Canonical Name (for links)
- Title
- Alternative Title
- Japanese/Kanji Title
- Image link
- Synopsys
- Volumes
- Chapters
- Published
- Genres + Links to them
- Authors + Links to them
- Serialization + Links to them
- Score
- Ranked
- Popularity
- Members
- Favorites
- Related Manga


### Planned Features
- Fetch Anime + Manga Reviews, Recommendations, Stats, Characters, News, Pictures, etc
- Fetch Person & Character Details
- Search results w/ pagination
- Authentication Tasks using Official API
- Command Line Usage
- JSON formats! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

### Todo
- Fix synonyms breaking for certain anime/manga
- Fix authors breaking for mangas

## Changelog
### 0.1.1 alpha - Apr 17, 17
- Many bug fixes
- Manga fetching works without any errors now
- You can now fetch related mangas

### 0.1.0 alpha - Feb 2, 17
- You're now able to fetch manga details
- Bug fixes

