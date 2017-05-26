# Jikan - The Unofficial MyAnimelist PHP API
[![build](https://travis-ci.org/irfan-dahir/jikan.svg?branch=master)]() [![coverage](https://img.shields.io/badge/coverage-50%25-yellow.svg?style=flat)]() [![version](https://img.shields.io/badge/ver-0.1.4-blue.svg?style=flat)]() [![status](https://img.shields.io/badge/status-alpha-red.svg?style=flat)]() 
[![REST API](https://img.shields.io/badge/jikan.me-available-brightgreen.svg?style=flat)](http://jikan.me)

## The REST API & Documentation is available at [https://jikan.me](https://jikan.me)

This PHP based API works by parsing data through the scraping of web pages from MyAnimeList. It gives you a simple and easy GET based methods that can fetch Anime, Manga, People & Character details. That's just the pinnacle, be sure to check out the planned features to see what's yet to come.

The reason of Jikan is quite simple. It's to cover what's lacking by the official API for developers.

This is a Beta version and is in WIP.

*More to follow!*

# Features
- Anime
- Manga
- Character
- Person
- User Anime/Manga List
- Extended data for Anime/Manga/Characters/People - **0%**
- Modular scraping method for extensions developers can add
- JSON formats! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

## Planned Features
- Fetch Anime + Manga Reviews, Recommendations, Stats, Characters, News, Pictures, etc
- Search results w/ pagination
- Command Line Usage

## What data can be returned?
### Anime
- Canonical Link
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
- Producers + Links
- Licensors + Links
- Studios + Links
- Source
- Genres + Links
- Duration
- Rating
- Score
- Ranked
- Popularity
- Members
- Favorites
- Related Anime
	- Name
	- Link
	- Type of Adaption

### Manga
- Canonical Link
- Title
- Alternative Title
- Japanese/Kanji Title
- Image
- Synopsys
- Volumes
- Chapters
- Published Dates
- Genres + Links
- Authors + Links
- Serialization + Links
- Score
- Ranked
- Popularity
- Members
- Favorites
- Related Manga
	- Name
	- Link
	- Type of Adaption

### Characters
- Canonical Link
- Name
- Japanese Name
- Biography
- Animeography
	- Name
	- Link
	- Image
- Mangaography
	- Name
	- Link
	- Image
- Voice Actors
	- Name
	- Link
	- Image
	- Language
- Member Favorites

### Person
- Canonical Link
- Given Name
- Family Name
- Alternative Names
- Birthday
- Website
- Member favorites
- More
- Voice Acting Roles
	- The Anime
		- Name
		- Link
		- Image
	- Character
		- Name
		- Link
		- Role
		- Image
- Anime Staff Positions
	- The Anime
		- Name
		- Link
		- Image
		- Role
- Published Manga
	- The Manga
		- Name
		- Link
		- Image
		- Role


## Changelog
### 0.1.5 alpha - May 26, 17
- Method **list** renamed to **user_list**
	- Reason: Issues with PHP 5.6

### 0.1.4.5 alpha - May 21, 17
- Added user anime/manga list fetch & parsed as JSON
- A few more stuff can be parsed from the Anime/Manga pages
	- Background
	- External Links
	- Opening Themes
	- Ending Themes

### 0.1.4 alpha - May 16, 17
- Jikan library is renamed from **mal-uapi.php** to **jikan.php**
- Namespace changed from **MAL** to **Jikan**
	```php
	$jikan = new \Jikan\GET;
	$jikan->anime(1);
	$anime = $jikan->data;
	```
- Main class is changed from **GET** to **Get**
- Completed person fetch
- Added canonical link for Characters in the return data
- Fixed parsing of related anime bug
- Here's the data you can fetch from a person
	- Canonical Link
	- Given Name
	- Family Name
	- Alternative Names
	- Birthday
	- Website
	- Member favorites
	- More
	- Voice Acting Roles
		- The Anime
			- Name
			- Link
			- Image
		- Character
			- Name
			- Link
			- Role
			- Image
	- Anime Staff Positions
		- The Anime
			- Name
			- Link
			- Image
			- Role
	- Published Manga
		- The Manga
			- Name
			- Link
			- Image
			- Role
- Replaced log method by thrown exception

### 0.1.3 alpha - May 15, 17
- Completed character fetch data
	- You can now fetch animeography, mangaography, voice actors and member favorites of that character
	```php
	$mal = new \MAL\GET;
	$character = $mal->character(1)->data;
	```

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

