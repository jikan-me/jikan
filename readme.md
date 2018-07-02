![Jikan](http://i.imgur.com/ctoJ3Jp.png)

# Jikan - The Unofficial MyAnimeList.net PHP API
[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP->=%207.0-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](https://discord.gg/4q8E4Gg)


Jikan is a PHP API with easy-to-use syntax that scrapes and parses requests from [MyAnimeList.net](https://myanimelist.net).

The raison d'être of Jikan is to help developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

### Getting Started
1. `composer require jikan-me/jikan`
2. [Documentation](https://jikan.moe/docs)

## Jikan REST API [![REST PHP](https://img.shields.io/badge/JikanPHP-1.16.2-blue.svg?style=flat)](https://jikan.moe)
If you don't want to handle PHP, you're in luck! Jikan has it's own RESTful API service (CORS enabled + JSON response) hosted by [Hibiki](https://github.com/assintates)

- **[REST DOCUMENTATION](https://jikan.docs.apiary.io)**
- **[See which apps are using JikanREST](https://jikan.moe/showcase)**
- **[Want to host your own instance of Jikan REST?](https://github.com/jikan-me/jikan-rest)**

### WARNING: BULK REQUESTS
If your use of the API is **NOT** for an app/bot/etc that makes consistent amount of requests but **rather** for the sake of making bulk requests, keep in mind to have a **4 second delay between EACH request** otherwise **your IP will be blacklisted**.

Remember, you're not the only one using the API. Be responsible.

## Wrappers
- **[.NET]** [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie
- **[Python]** [JikanPy](https://github.com/AWConant/jikanpy) by Andrew Conant & Abhinav Kasamsetty
- **[Ruby]** [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi
- **[JavaScript]** [JikanJS](https://github.com/zuritor/jikanjs) by Zuritor

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
### 1.16.2 stable - July 3, 17
- Bug fix [#163](/../../issues/163)
- Fix HTTP responses

**[Read More](https://github.com/jikan-me/jikan/tree/master/changelog.md)**

## Usage 
- [.NET](https://github.com/Ervie/jikan.net/wiki)
- [PHP](https://github.com/jikan-me/jikan/tree/master/examples)
- [Python](https://github.com/jikan-me/jikanpy#jikanpy)
- [Ruby](https://github.com/jikan-me/jikan.rb#usage)
- [JavaScript](https://github.com/zuritor/jikanjs/blob/master/readme.md)

## Contributions
I would like to thank these people for hosting the Jikan REST service!
* [Hibiki](https://twitter.com/Assintates)
* [Sif](https://myanimelist.net/profile/ArtoriasMoreder)
* [BroHosting](https://brohosting.eu)

## Dependencies
- Guzzle

## DISCLAIMER
- Jikan is in no way affiliated with MyAnimeList. 
- I am not responsible for what you do with this library, so use it responsibly as per MyAnimeList's [TOS](https://myanimelist.net/about/terms_of_use)
- Use the REST API responsibly, bulk requesting data for building your own database/datasets is only allowed under conditions. [Read More](https://jikan.docs.apiary.io/#introduction/information/rate-limiting)

## [Donate](https://liberapay.com/Nekomata/donate)
If you found this useful, please feel free to donate to help with development!

[![Donate](https://liberapay.com/assets/widgets/donate.svg)](https://liberapay.com/Nekomata/donate "Donate using Liberapay")
