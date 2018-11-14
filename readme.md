![Jikan](http://i.imgur.com/ctoJ3Jp.png)

# Jikan - Unofficial MyAnimeList.net PHP API
[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![build](https://ci.appveyor.com/api/projects/status/github/irfan-dahir/jikan?branch=2.0.0-dev&svg=true)](https://ci.appveyor.com/project/irfan-dahir/jikan) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](https://discord.gg/4q8E4Gg)


Jikan is a PHP API with scrapes and parses requests from [MyAnimeList.net](https://myanimelist.net).

The raison d'être of Jikan is to help developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

## Getting Started
1. `composer require jikan-me/jikan`
2. [Documentation](http://docs.jikan.moe)

:exclamation: If you are using `dev-master` and are not ready for v2 yet, require version `~1.0` until you are.

#### Dependencies
- [Goutte](https://github.com/FriendsOfPHP/Goutte)
- PHP 7.1+

## Jikan REST API
There's a REST service available, as well as a few wrappers built around it.

- **[REST DOCUMENTATION](https://jikan.docs.apiary.io)**
- **[See which apps are using JikanREST](https://jikan.moe/showcase)**
- **[Want to host your own instance of Jikan REST?](https://github.com/jikan-me/jikan-rest)**

### Wrappers
- **[.NET]** [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie
- **[Python]** [JikanPy](https://github.com/AWConant/jikanpy) by Andrew Conant & Abhinav Kasamsetty
- **[Ruby]** [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi
- **[JavaScript]** [JikanJS](https://github.com/zuritor/jikanjs) by Zuritor
- **[Java]** [Jikan4java](https://github.com/Doomsdayrs/Jikan4java) by Doomsdayrs

## Features
- Anime
    - Main Information
    - Characters & Staff
    - Episodes
    - News
    - Videos/PV/Episodes
    - Pictures
    - Stats
    - Forum Topics
    - More Info
    - Recommendations
    - Recent List Updates By Users
- Manga
    - Main Information
    - Characters
    - News
    - Stats
    - Pictures
    - Forum Topics
    - More Info
    - Recommendations
    - Recent List Updates By Users
- Character
    - Main Information
    - Pictures
- People
    - Main Information
    - Pictures
- Search
    - Anime
    - Manga
    - Character
    - Person
    - Pagination Support
    - Advanced Search (Filters)
- Seasonal Anime (Season + Year)
- Season List/Archive
- Anime Scheduling (for current season)
- Top
    - Anime
    - Manga
    - Characters
    - People
    - Sub Types & Pagination Support
- Genre
    - Anime
    - Manga
- Producers (Anime Listing)
- Magazines (Manga Listing)
- User
    - Profile
    - Friends
        - Pagination support
    - History
        - All
        - Anime
        - Manga
    - Anime & Manga Lists


## Changelog

### v2.3.0 stable - Nov 14, 18
- **[Anime|Manga]** Add **Recommendations** parsing
- **[Anime|Manga]** Add **Recently Updated By Users** parsing - 2.2.0
- **[User List]** Fix manga list parsing - 2.1.3

- [Read More](https://github.com/jikan-me/jikan/blob/master/changelog.md)


## DISCLAIMER
- Jikan is not affiliated with MyAnimeList.net 
- I am not responsible for how you do use this library. Please be respectful towards MyAnimeList's [Terms Of Service](https://myanimelist.net/about/terms_of_use)
