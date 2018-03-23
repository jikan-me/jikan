
# Jikan - The Unofficial MyAnimelist PHP API 
[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![stable](https://img.shields.io/badge/jikanPHP-v1.13.1-blue.svg?style=flat)]()  [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP->=%207.0-blue.svg?style=flat)]() 




Jikan is an OOP based, **dependency free**, PHP library with easy-to-use syntax that scrapes and parses data from MyAnimeList back to you.

The raison d'être for Jikan is to allow developers to easily get stuff from the website without having to depend on the official MyAnimeList API (which lacks Jikan's functions), unstable APIs, or side tracking your own application to create parsers.

PHP Documentation - [Get Started](https://jikan.me/docs)

## [JikanREST API v2.1](https://jikan.docs.apiary.io) [![REST PHP](https://img.shields.io/badge/JikanPHP-1.7.1-blue.svg?style=flat)](https://jikan.me)
Jikan even has it's own RESTful API service! - [Get Started](https://jikan.docs.apiary.io)

[See which apps are using JikanREST](https://jikan.me/showcase)

# Features
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
    - Pagination Support
    - No.# of pages
- Seasonal Anime (Season + Year)
- Anime Scheduling (for current season)

- Modular scraping method for developers to extend the API
- JSON format! ლ( ͡⎚ ͜ʖ ͡⎚ ლ)

## Planned Features
- Anime & Manga
    - User Reviews
    - Recommendations
- Top
    - Anime
    - Manga
- Most Favorited
    - Characters
    - People
- User Profile
- Command Line Usage
- [PThreads](https://github.com/krakjoe/pthreads) (Multi-threaded) Support (CLI ONLY!)

## Wrappers
- **[Ruby]** [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi
- **[Python]** [JikanPy](https://github.com/AWConant/jikanpy) by Andrew Conant

## Changelog
### 1.13.1 stable - March 23, 18
- **[Search]**
    - Changed `id` to `mal_id` for Anime & Manga results
    - Added `mal_id` for Character results
    - Added full URL path for Anime & Manga results in Character search
    - Removed empty Manga/Anime from Character Search
    - Fix [#111](/../../issues/111) - 1.13.1
    - 
[Read More](https://github.com/jikan-me/jikan/tree/master/changelog.md)

### Examples: [PHP](https://github.com/jikan-me/jikan/tree/master/examples)