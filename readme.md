# Jikan - The Unofficial MyAnimelist PHP API
[![build](https://travis-ci.org/irfan-dahir/jikan.svg?branch=master)](https://travis-ci.org/irfan-dahir/jikan) [![version](https://img.shields.io/badge/ver-0.2.4-blue.svg?style=flat)]() [![status](https://img.shields.io/badge/status-alpha-red.svg?style=flat)]() 
[![REST API](https://img.shields.io/badge/jikan.me-available-brightgreen.svg?style=flat)](http://jikan.me)

## The REST API & Documentation is available at [https://jikan.me](http://jikan.me)

Jikan is an OOP based class written in PHP that scrapes and parses data out of MyAnimeList. It provides you an easy syntax that can fetch Anime, Manga, People, Character details and even user lists. It returns the data in an array and has a built-in method that converts it to JSON.

The raison d'être for Jikan is to provide an easy API for being able to get stuff that the official API of MyAnimeList lacks.

Jikan even has it's own REST API that responds in JSON - [Get Started](http://jikan.me)

This is a Beta version and is in WIP.


# Features
- Anime + Characters/Staff + Episodes
- Manga + Characters/Staff
- Character
- Person
- User Anime/Manga List
- Modular scraping method for developers to extend the API
- JSON formats! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

## Planned Features
- Fetch Anime + Manga Reviews, Recommendations, Stats, News, Pictures, etc
- Search results w/ pagination
- Command Line Usage


## Changelog
### 0.2.4 beta - June 17, 17
- Fixed anime/manga `type` fetch
- Fixed parsing issue with some `licensors` names


### 0.2.3 alpha - June 2, 17
- Serialization will return empty array if there's none
- Replaced `is_link` check with `is_link2`
	- `is_link` uses the default php function `filter()` to validate URLS
	- `filter()` returns false on some correct URLS that have UTF8 characters (which a lot of anime/manga do)
	- `is_link2` simply checks for `http(s)://` via ReGex
- Refactored `authors` array response for manga & fixed critical parsing issues
	- Check response types @ [http://jikan.me/docs#rest-manga](http://jikan.me/docs#rest-manga)
	- Returns empty array if there's no authors set
	- `authors` is renamed to `author`
- Refactors `genres` array response 
	- Check response types @ [http://jikan.me/docs#rest-manga](http://jikan.me/docs#rest-manga)
	- `genres` is renamed to `genre` 
- `background` returns empty string if there's no background available

### 0.2.2 alpha - June 2, 17
- Fix manga override for `setParentFile`

### 0.2.1 alpha - June 1, 17
- Added method `setParentFile($type, $value)` and `setChildFile($page, $value)`
	- This method pre-sets links/file paths to the parent/child methods, if you don't want to pass ID params to them
	- More info in the docs
- Added extended method `episodes`
	- Only works for the parent method `anime`
	- Recursively makes requests if the episodes are more than 100 to fetch all episodes
	- More info in the docs
- Added extended method `characters_staff`
	- Works for the parent methods; `anime` and `manga`
	- More info in the docs
- If premiered is unknown, it'll return an empty string
- If ranking is N/A, it'll return -1
- Refactored `related` for manga
- Fixed bugs of related anime
- Added `title-english` for anime/manga
- Added 'status' for manga (e.g completed, publishing, etc)
- For Mangas, `volumes` and `chapters` will return 'Unknown' if so otherwise it will return integers
- Following Semantic Versioning

### 0.1.5 alpha - May 26, 17
- Method **list** renamed to **user_list**
	- Reason: Issues with PHP 5.6
- Fixed critical bugs

### 0.1.4.5 alpha - May 21, 17
- Added user anime/manga list fetch & parsed as JSON
- A few more stuff can be parsed from the Anime/Manga pages
	- Background
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

