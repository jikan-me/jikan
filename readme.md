![Jikan](http://i.imgur.com/ctoJ3Jp.png)

# Jikan - The Unofficial MyAnimeList.net PHP API
[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![stable](https://img.shields.io/badge/jikanPHP-v1.15.9-blue.svg?style=flat)]()  [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP->=%207.0-blue.svg?style=flat)]() 


Jikan is a **depenency free**, PHP API with easy-to-use syntax that scrapes and parses requests from [MyAnimeList.net](https://myanimelist.net).

The raison d'être of Jikan is to help developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

### Getting Started
1. `composer require jikan-me/jikan`
2. [Documentation](https://jikan.moe/docs)


## Jikan REST API [![REST PHP](https://img.shields.io/badge/JikanPHP-1.15.9-blue.svg?style=flat)](https://jikan.moe)
If you don't want to handle PHP, you're in luck! Jikan has it's own RESTful API service (CORS enabled + JSON response) hosted by [Hibiki](https://github.com/assintates)

**[REST DOCUMENTATION](https://jikan.docs.apiary.io)**

**[See which apps are using JikanREST](https://jikan.moe/showcase)**

## Wrappers
- **[.NET]** [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie
- **[Python]** [JikanPy](https://github.com/AWConant/jikanpy) by Andrew Conant
- **[Ruby]** [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi

Contributions to Jikan by making wrappers in programming languages of your choice are much appreciated! Do let me know if you've made one and I'll include it here.

## Features
- Anime Parsing
    - Characters & Staff
    - Episodes
    - News
    - Videos/PV/Episodes
    - Pictures
    - Stats
    - Forum Topics
    - More Info
- Manga Parsing
    - Characters
    - News
    - Stats
    - Pictures
    - Forum Topics
    - More Info
- Character Parsing
    - Pictures
- People Parsing
    - Pictures
- Search (Anime/Manga/Character/Person)
    - Filters (Advanced Search)
    - Pagination Support
    - No.# of pages
- Seasonal Anime (Season + Year)
- Anime Scheduling (for current season)
- Top
    - Anime
    - Manga
    - Sub Types & Pagination Support

- Modular scraping methods for developers to easily extend the API
- JSON format! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

## Roadmap
- Most Favorited
    - Characters
    - People
- User Profile
- Command Line Usage
- [PThreads](https://github.com/krakjoe/pthreads) (Multi-threaded) Support (CLI ONLY!)

## Changelog
### 1.15.9 stable - May 14, 18
- **[Search]** 
    - Bug fix for `genre`, `genreInclude`, `startDate` & `endDate`
- **[SearchConfig]** `Jikan\Helpers\SearchConfig`
    - `setGenre` now no longer takes an array for multiple genres, but rather Variadic arguments.
        e.g `setGenre(1, 18)`

**[Read More](https://github.com/jikan-me/jikan/tree/master/changelog.md)**

## Usage 
- [PHP](https://github.com/jikan-me/jikan/tree/master/examples)
- [Ruby](https://github.com/jikan-me/jikan.rb#usage)
- [Python](https://github.com/jikan-me/jikanpy#jikanpy)
- [.NET](https://github.com/Ervie/jikan.net/wiki)

## Contributions
I would like to thank these 3 for graciously hosting Jikan REST for free!
* [Assintates](https://twitter.com/Assintates)
* [Sif](https://myanimelist.net/profile/ArtoriasMoreder)
* [BroHosting](https://brohosting.eu)

## DISCLAIMER
- Jikan is in no way affiliated with MyAnimeList. 
- I am not responsible for what you do with this library, so use it responsibly as per MyAnimeList's [TOS](https://myanimelist.net/about/terms_of_use)
- Use the REST API responsibly, bulk data downloading is only allowed under conditions. (Refer to the REST docs)
