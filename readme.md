[![Jikan](http://i.imgur.com/ctoJ3Jp.png)](#jikan---unofficial-myanimelistnet-php-api)

# Jikan - Unofficial MyAnimeList.net PHP API
[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![build](https://ci.appveyor.com/api/projects/status/github/jikan-me/jikan?branch=master&svg=true)](https://ci.appveyor.com/project/irfan-dahir/jikan) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](https://discord.gg/4tvCr36)


Jikan is a PHP API for [MyAnimeList.net](https://myanimelist.net). It scrapes the website to satisfy the need for an API - which MyAnimeList lacks.

The raison d'être of Jikan is to assist developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)


**Notice**: Jikan does not support authenticated requests. You can not update your lists.


## Getting Started
1. `composer require jikan-me/jikan`
2. [Documentation](http://docs.jikan.moe)

:exclamation: Version `~1.0` is no longer maintained, it's required you use `^2.0`.

#### Dependencies
- [Goutte](https://github.com/FriendsOfPHP/Goutte)
- PHP 7.1-7.3

:exclamation: PHP 7.4 is not fully tested yet.

## Jikan REST API
A REST API service is available as well

- **[REST API DOCUMENTATION](https://jikan.docs.apiary.io)**
- **[Apps/Projects using the REST API](https://jikan.moe/showcase)**
- **[Host the REST API yourself](https://github.com/jikan-me/jikan-rest)**

### Wrappers
- **[.NET]** [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie
- **[Python]** [JikanPy](https://github.com/AWConant/jikanpy) by Andrew Conant & Abhinav Kasamsetty
- **[Ruby]** [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi
- **[JavaScript]** [JikanJS](https://github.com/zuritor/jikanjs) by Zuritor
- **[Java]** [Jikan4java](https://github.com/Doomsdayrs/Jikan4java) by Doomsdayrs
- **[PHP]** [jikan-php](https://github.com/janvernieuwe/jikan-jikanPHP) by Jan Vernieuwe
- **[Node.js]** [jikan-node](https://github.com/xy137/jikan-node) by xy137
- **[Dart]** [jikan-dart](https://github.com/charafau/jikan-dart) by Rafal Wachol
- **[TypeScript]** [jikants](https://github.com/Julien-Broyard/jikants) by Julien Broyard
- **[TypeScript]** [jikan-client](https://github.com/javi11/jikan-client) by Javier Blanco
- **[Go]** [jikan-go](https://github.com/darenliang/jikan-go) by Daren Liang

[Add your wrapper here](https://github.com/jikan-me/jikan/edit/master/readme.md)


## Features
- Anime
    - Main Information
    - Characters & Staff
    - Episodes
    - Episode Details
    - News
    - Videos/PV/Episodes
    - Pictures
    - Stats
    - Forum Topics
    - More Info
    - Recommendations
    - Reviews
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
    - Reviews
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
    - Advanced Search
        - Filters
        - Order By
        - Sorting (Ascending/Descending)
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
    - Anime Listing (All Anime by Genre)
    - Anime Genre Listing (All Genres + metadata) 
    - Manga Listing (All Anime by Genre)
    - Manga Genre Listing (All Genres + metadata)
- Producer
    - Anime Listing (All Anime by a Producer)
    - Producers Listing (All Producers + metadata)
    - Manga Listing (All Manga by a Producer)
    - Magazines Listing (All Magazines + metadata)
- User
    - Profile
    - Friends
        - Pagination support
    - History
        - All
        - Anime
        - Manga
    - Anime & Manga Lists
        - Pagination Support
- Club
    - Main Information
    - User List
    
[View RoadMap](https://trello.com/b/Jw1rs467/jikan-api)

## Running Tests

###### PHPUnit
`php vendor/bin/phpunit`

###### GrumPHP
PHPCS, PHPLint & PHPUnit

`php vendor/bin/grumphp run`

---

# Sugoi Backers
Thank you to all our **sugoi** backers! 🙏 [[Become a sugoi backer](https://patreon.com/jikan)]
- [Jared Allard (jaredallard)](https://github.com/jaredallard)

# Backers
Thank you to all our backers! 🙏 [[Become a backer](https://patreon.com/jikan)]
- [PurplePinapples](https://github.com/purplepinapples/)
- [Barkdoll (Jesse)](https://github.com/barkdoll/)
- [Piotr Szymczak (Drutol)](https://github.com/Drutol)
- [Jason Weatherly (jamesthebard)](https://twitter.com/jamesthebard)
- [Cesar Irad Mendoza (aberuwu)](https://github.com/aberuwu)


# Sponsors
Thank you to all our sponsors! [[Become a sponsor](https://patreon.com/jikan)]
- [Hibiki Matsujo](https://github.com/assintates)

---

## Release Changelog

### 2.15.0 - Jan 10, 20
- Add **Producer List** parsing
```php
$producers = $jikan->getProducers(
    new \Jikan\Request\Producer\ProducersRequest()
);
```
- Add **Magazine List** parsing
```php
$magazines = $jikan->getMagazines(
    new \Jikan\Request\Magazine\MagazinesRequest()
);
```
- Add **Anime Genre List** parsing
```php
$genres = $jikan->getAnimeGenres(
    new \Jikan\Request\Genre\AnimeGenresRequest()
);
```

- Add **Manga Genre List** parsing
```php
$genres = $jikan->getMangaGenres(
    new \Jikan\Request\Genre\MangaGenresRequest()
);
```

- Parser bug fixes
- Improved image quality parsing for some requests
    `\Jikan\Helper\Parser::parseImageQuality($imageUrl)` now removes `v` from the end of images, which resulted in a smaller thumbnail

[Read More](https://github.com/jikan-me/jikan/blob/master/changelog.md)


## DISCLAIMER
- Jikan is not affiliated with MyAnimeList.net 
- You are responsible for the usage of this API. Please be respectful towards MyAnimeList's [Terms Of Service](https://myanimelist.net/about/terms_of_use)
