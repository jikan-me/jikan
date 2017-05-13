# Jikan - The Unofficial MyAnimelist PHP API
![build](https://img.shields.io/badge/build-passing-green.svg?style=flat) ![coverage](https://img.shields.io/badge/coverage-30%-red.svg?style=flat) ![version](https://img.shields.io/badge/ver-0.1.2-blue.svg?style=flat) ![status](https://img.shields.io/badge/status-alpha-red.svg?style=flat) 
[![REST API](https://img.shields.io/badge/jikan.me-development-red.svg?style=flat)](http://jikan.me)

This PHP based API works through extracting data through scraping of data that the official API does not provide, such as Anime, Manga, Person, Character details. It goes even further to extend these details by scraping dedicated pages of Anime/Manga such as videos, reviews, stats, recommendations, etc. It also provides a wrapper for the original MAL API.

This is a very early alpha version and is in WIP. The only functional part are getting anime and manga details

*More to follow!*

# Features ![person-coverage](https://img.shields.io/badge/coverage-31.6%-orange.svg?style=flat&maxAge=3600)
- ![anime-coverage](https://img.shields.io/badge/coverage-95%-green.svg?style=flat&maxAge=3600) **Anime** 
- ![manga-coverage](https://img.shields.io/badge/coverage-95%-green.svg?style=flat&maxAge=3600) **Manga** 
- ![extended-coverage](https://img.shields.io/badge/coverage-_0%-red.svg?style=flat&maxAge=3600) **Extended data for Anime/Manga**
- ![character-coverage](https://img.shields.io/badge/coverage-_50%-red.svg?style=flat&maxAge=3600) **Character**
- ![person-coverage](https://img.shields.io/badge/coverage-_0%-red.svg?style=flat&maxAge=3600) **Person**
- ![wrapper-coverage](https://img.shields.io/badge/coverage-_0%-red.svg?style=flat&maxAge=3600) **Official MAL Wrapper**

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

### Characters
- Name
- Japanese Name
- Biography


## Planned Features
- Fetch Anime + Manga Reviews, Recommendations, Stats, Characters, News, Pictures, etc
- Fetch Person & Character Details
- Search results w/ pagination
- Authentication Tasks using Official API
- Command Line Usage
- JSON formats! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

## Todo
- Fix synonyms breaking for certain anime/manga
- Fix authors breaking for mangas
- Add the other page gets such as videos, reviews, recommendations, etc
- Make it more OOP
- Complete Character GET - Animeography, Mangaography & voice actors list

## Changelog
### 0.1.2 alpha - May 12, 17
- Added Character fetch, you can now get character data.
	- Yet to fetch character animeography, mangaography & voice actors
- Updated resource for synopsis of anime/manga to something more complete and easier to match, the meta og tags
- Fetching anime data did not scrape related series (forgot to add it there, woops!) where as manga method did
- Added chaining methods (return $this)
	- Note that this only works for similar type gets, for example Anime, Manga, Characters
	- So you can fetch extra stuff such as videos, episodes, reviews, etc from their own respective pages like this:
	```php
		$mal = new \MAL\GET;
		$anime = $mal->anime(1)->videos()->episode()->reviews();
	```
	- This will be slower as every method is fetching a completely new page dedicated to that data
- Simply calling the anime or manga method like this,
	```php
		$anime = $mal->anime(1)
	```
	will no longer return the data! The data will be saved to its array, which you'll need to use a new method to return it.
	The reason for this is chained methods to fetch other related data as stated above!
	```php
		$anime = $mal->anime(1)->return();
		//or
		$anime = $mail->anime(1)->data;
		//same stuff
	```


### 0.1.1 alpha - Apr 17, 17
- Many bug fixes
- Manga fetching works without any errors now
- You can now fetch related mangas

### 0.1.0 alpha - Feb 2, 17
- You're now able to fetch manga details
- Bug fixes

