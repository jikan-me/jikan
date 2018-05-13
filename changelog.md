## Changelog

### 1.15.9 stable - May 14, 18
- **[Search]** 
	- Bug fix for `genre`, `genreInclude`, `startDate` & `endDate`
- **[SearchConfig]** `Jikan\Helpers\SearchConfig`
	- `setGenre` now no longer takes an array for multiple genres, but rather Variadic arguments.
		e.g `setGenre(1, 18)`

### 1.15.8 stable - May 12, 18
- **[Search]** 
	- Bug fix for [#139](/../../issues/139) - 1.15.6
	- Bug fix for [#138](/../../issues/138) - 1.15.7
	- HTML special character decode for some titles

### 1.15.5 stable - April 18, 18
- **[Manga]** Fix parsing bug with some serialization names - [#131](/../../issues/131) - 1.15.4
- **[Anime]** Fix parsing bug with some studio names - [#129](/../../issues/129)

### 1.15.3 stable - April 13, 18
- **[Search]**
	- **[People]** Fix single item results not showing due to MAL redirecting the page - [#120](/../../issues/120)

### 1.15.2 stable - April 8, 18
- **[Anime|Manga]**
	- Decode html characters from titles and other various strings - [#121](/../../issues/121) - 1.15.1
- **[Character]**
	- Fix `about` not returning for some characters - [#118](/../../issues/118)

### 1.15.0 stable - March 24, 18
- **[Top]**
	- Add Top for Anime & Manga w/ pagination support
	- Subtypes also supported
		- Airing, Upcoming, TV, Movies, OVA, Specials, Popular, Favorited
		- Manga, Novels, Oneshot, Doujinshi, Manhwa, Manhua, Popular, Favorited

### 1.13.1 stable - March 23, 18
- **[Search]**
	- Changed `id` to `mal_id` for Anime & Manga results
	- Added `mal_id` for Character results
	- Added full URL path for Anime & Manga results in Character search
	- Removed empty Manga/Anime from Character Search
	- Fix [#111](/../../issues/111) - 1.13.1

### 1.12.1 stable - March 22, 18
- Add advanced search options - [#97](/../../issues/97) - 1.12.0
	- This includes a query builder, `SearchConfig` in `Jikan/Helper/`
- **[Seasonal]**
	- Change `year` argument from String Type to Integer - 1.12.1

### 1.11.2 stable - March 4, 18
- Add **Seasonal Anime** parsing [#87](/../../issues/87) - 1.10.0
- Add **Airing Anime Schedule** parsing [#104](/../../issues/104) - 1.11.0
- Fixed a bug which slowed down the fetch for some parses - 1.11.1
- **[Search]**
	- Added urlencode() by default to query strings - 1.11.2

### 1.9.0 stable - March 3, 18
- **[Anime|Manga]**
	- Add extended data "forum topics" parsing [#19](/../../issues/19) - 1.8.0 
	- Add extended data "more_info" parsing [#17](/../../issues/17)

### 1.7.2 stable - February 25, 18
- Fix [#99](/../../issues/99)
- Fix [#102](/../../issues/102)
- Fix [#103](/../../issues/103)

### 1.7.1 stable - January 27, 18
- **[Anime|Manga]**
	- Fix for [#98](/../../issues/98) - 1.6.3
		- Return null for ISO8601 of aired_string that only has year
- **[Anime|Manga|Character|Person]** 1.7.0
	- Add gallery page parsing
- `unknown search type` -> `invalid search type`
- fixed anime pictures parsing

### 1.6.2 stable - January 26, 18
- **[Anime|Manga]**
	- Add stats parsing - 1.6.0
- **[Core]**	
	- Fixed an impactful bug which had the parser requesting the page *twice* in some extend cases (was i drunk coding this part?!) - 1.6.1
		- Was doubling chances of rate limiting
		- Slowing down the script 2x
	- Add accurate header response by MAL (429 means 429) - Core fix for [#91](/../../issues/91)

### 1.5.8 stable - January 25, 18
- **[CORE]**
	- Change error message 'ID/Path not given' -> 'No ID/Path given'
	- Change error message 'No query given' -> 'No Query Given'
- **[SEARCH]**
	- Fix `result_last_page` showing 0 on single page results - 1.5.7
- **[Schema]**
	- Add more `mal_id` & clean up html codes - 1.5.8

### 1.5.6 stable - January 24, 18
- **[Search]**
	- Character Search: `nickname` field to `nicknames` since there can be multiple - 1.5.4
	- Add `id` to Anime results 1.5.4
	- Add Person/People search functionality w/ pagination support. e.g `$jikan->Search('query', PEOPLE)` `$jikan->Search('query', PERSON)` (`PEOPLE` is an alias of `PERSON`) - 1.5.5
- **[Anime]**
	- Patch Video parsing breaking due to HTML change

### 1.5.3 stable - January 23, 18
- **[Core]** 1.4.6
	- Load files for parsing locally. e.g `$jikan->Anime('file/to/anime-1.html')` **Extend requests will load from MAL**
	- Prepare for header responses
- **[Search]**
	- Add Anime search functionality w/ pagination support. e.g `$jikan->Search('query', ANIME)`, `$jikan->Search('query', ANIME, 2) // page 2` - 1.5.0
	- Add Manga search functionality w/ pagination support. e.g `$jikan->Search('query', MANGA)` - 1.5.1
	- Add Character search functionality w/ pagination support. e.g `$jikan->Search('query', CHARACTER)` - 1.5.2
- **[Anime]**
	- Producer names with commas in them breaking parsing - [#95](/../../issues/95)

### 1.4.5 stable - January 21, 18
- **[Anime]**
	- Music type anime parsing broken due to HTML change

### 1.4.4 stable - January 14, 18
- **[Character|Person]**
	- Fix of changes in format of image links which were causing the parser to break. - 1.4.3 [#92](/../../issues/92)
	- Fix for base url duplication - [#93](/../../issues/93)

### 1.4.2 stable - January 9, 18
- **[Anime]**
	- Fix News without image not parsing - 1.4.1 [#83](/../../issues/83)
	- Fix Manga parsing not converting correctly to ISO for some published dates - 1.4.2 [#89](/../../issues/89)

### 1.4.0 stable - January 7, 18
- **[Anime|Manga]**
	- Add News Parsing - 1.3.0
	- Add Video Parsing - 1.4.0 [#12](/../../issues/12)

### 1.2.1 stable - December 13, 17
- **[Anime|Manga]**
	- Fixed parsing bug for anime and manga that only have one `aired` and `published` date.

### 1.2.0 stable - December 2, 17
- **[Anime|Manga]** 
	- Convert Dates to ISO Format - Enhancement [#72](/../../issues/72)
	- `aired` will now be an array returning ISO 8601
		- You can find the string version of the date, like before, in a new field; `aired_string`
	- Decode HTML Special characters for synopsis/background/episode title strings - Enhancement [#74](/../../issues/74)
	- In the related field, it now shows the relation item's type. - Feature [#75](/../../issues/75)
	- Remmoved extra forward slash in the related item URL
	- Added boolean field `airing` & `publishing` for anime & manga respectively
	- **[Manga]** Removed extra whitespace for `title_japanese`
- **[Person]** 
	- Fixed `More` field not parsing - Issue [#61](/../../issues/61)
	- Changed `website` to `website_url`

### 1.1.2 stable - December 1, 17
- **[Character]**
	- Fixed a critical bug which stopped parsing for characters without voice actors [#77](/../../issues/77)
	- Fixed "about" giving malformed HTML data for characters without voice actors
- **[Manga]** Fixed Score showing in pre v1.0.0 format - Issue [#73](/../../issues/73)
- **[Person]**
	- Parse Generic Name [#44](/../../issues/44)

### 1.1.1 stable - November 13, 17
- Added `mal_id` (integer) for items - Issue [#71](/../../issues/71)
- **[Anime|Manga]** Related `id` -> `mal_id`
- **[Fix]** Issue [#70](/../../issues/70)
- **[Character]** Added `link_canonical`

### 1.1.0 stable - November 9, 17
- **[Character]** 
	- Fixed name having whitespace appended to it
	- Fixed name not showing up for characters without kanji names
	- Rename name_japanese -> name_kanji (since that's what it actually is)
	- Added `nicknames`: parses every name/nickname/etc as per the title of the content, refer to https://myanimelist.net/character/5
- **[Person]** Fixed website malformed data
- **[Anime]** Fixed *ALL* HTML malformed data in opening/ending songs

### 1.0.1 stable - October 5, 17
- **[Anime]**
    - Fixed [#65](/../../issues/65)

### 1.0.0 stable - July 11, 17
- **Added** Composer
- **Refactored** entire code base to meet with proper PSR compliants
- **[Person]** 
    - Name Parsing [#44](/../../issues/44)
    - Alternate name issue fixed [#47](/../../issues/47)
- **[Anime/Manga]** 
    - Adaption contains HTML tags [#45](/../../issues/45)
    - Related anime/manga parse the ID now [#52](/../../issues/52)
    - **[Episodes]** Romanji/Japanese titles swapped now fixed [#46](/../../issues/46)
- **Fixed** Some responses showing HTML tags
- HTML special characters are now decoded to Unicode
- **[Character]** Canonical Link parsing [#50](/../../issues/50)
- **[User_List]** Appropriate HTTP Response headers
- **[Related/Producer/Licensor/Studio/Genre/]** Return the ***full*** URLs now. More info at [MIGRATION.md](https://github.com/jikan-me/jikan/tree/master/migration.md)
- Actually started following Semantic Versioning from here

### 0.3.0 beta - July 6, 17
- [Anime/Manga] HTML Tags are now stripped out from Synopsis & Background information
- [Anime/Manga] Background returns as an empty string if there's nothing to parse
- [Anime/Manga] Characters & Staff to return only 1 display image instead of the 2 different sizes in one string
- [Character/Person] Added picture parsing
- [User list] Bug fixes
- [REST] Added appropriate HTTP header responses

### 0.2.6 beta - June 21, 17
- `licensors` to `licensor`
- `authors` to `author`
- `genres` to `genre`
- `producers` to `producer`
- `studios` to `studio`

### 0.2.5 beta - June 17, 17
- Fixed `score` and `favorites` not showing up for manga
- Fixed parsing issue for some `serialization`

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

### 0.0.0 alpha - Initial Release
- You can fetch anime info
