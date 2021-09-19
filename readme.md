[![Jikan](http://i.imgur.com/ctoJ3Jp.png)](#jikan---unofficial-myanimelistnet-php-api)

# Jikan - Unofficial MyAnimeList.net PHP API

[![build](https://travis-ci.org/jikan-me/jikan.svg?branch=master)](https://travis-ci.org/jikan-me/jikan?branch=master) [![build](https://ci.appveyor.com/api/projects/status/github/jikan-me/jikan?branch=master&svg=true)](https://ci.appveyor.com/project/irfan-dahir/jikan) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](https://discord.gg/4tvCr36)

Jikan is a PHP API for [MyAnimeList.net](https://myanimelist.net). It scrapes the website to satisfy the need for an API - which MyAnimeList lacks.

The raison d'être of Jikan is to assist developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

**Notice**: Jikan does not support authenticated requests. You can not update your lists.

## Getting Started

| Version   | Support | PHP | Lumen/Laravel |
|------------|----------|----------|----------| 
| [`^3` (in Beta)](https://github.com/jikan-me/jikan/tree/3.0.0) | ✅ New features | [![beta](https://img.shields.io/badge/PHP-^%207.4-blue.svg?style=flat)]() | `^8` |
| [`^2` (master)](https://github.com/jikan-me/jikan)      | ⚠️ Maintenance only | [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() | `^6` 
| [`~1`](https://github.com/jikan-me/jikan/tree/1.16.3)      | ❌ No longer maintained or supported | [![stable](https://img.shields.io/badge/PHP-^%207.0-blue.svg?style=flat)]() | `5.5.*` |


1. `composer require jikan-me/jikan` (This will install  version `^2`)
2. [Documentation](http://docs.jikan.moe)

# Jikan REST API

A REST API service is available as well

- **[REST API DOCUMENTATION](https://jikan.docs.apiary.io)**
- **[Apps/Projects using the REST API](https://jikan.moe/showcase)**
- **[Host the REST API yourself](https://github.com/jikan-me/jikan-rest)**

## Wrappers

| Language   | Wrappers |
|------------|----------|
| JavaScript | [JikanJS](https://github.com/zuritor/jikanjs) by Zuritor |
| Java       | [Jikan4java](https://github.com/Doomsdayrs/Jikan4java) by Doomsdayrs<br>[reactive-jikan](https://github.com/SandroHc/reactive-jikan) by Sandro Marques<br>[Jaikan](https://github.com/ShindouMihou/Jaikan) by ShindouMihou |
| Python     | [JikanPy](https://github.com/abhinavk99/jikanpy) by Abhinav Kasamsetty |
| Node.js    | [jikan-node](https://github.com/xy137/jikan-node) by xy137<br>[jikan-nodejs](https://github.com/ribeirogab/jikan-nodejs) by ribeirogab |
| TypeScript | [jikants](https://github.com/Julien-Broyard/jikants) by Julien Broyard<br>[jikan-client](https://github.com/javi11/jikan-client) by Javier Blanco |
| PHP        | [jikan-php](https://github.com/janvernieuwe/jikan-jikanPHP) by Jan Vernieuwe |
| .NET       | [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie |
| Elixir     | [JikanEx](https://github.com/seanbreckenridge/jikan_ex) by Sean Breckenridge |
| Go         | [jikan-go](https://github.com/darenliang/jikan-go) by Daren Liang<br>[jikan2go](https://github.com/nokusukun/jikan2go) by nokusukun |
| Ruby       | [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi |
| Dart       | [jikan-dart](https://github.com/charafau/jikan-dart) by Rafal Wachol |
| Kotlin     | [JikanKt](https://github.com/GSculerlor/JikanKt) by Ganedra Afrasya |

[Add your wrapper here](https://github.com/jikan-me/jikan/edit/master/readme.md)

# Features

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

[View RoadMap](https://github.com/jikan-me/jikan/projects/4)

# Running Tests

## PHPUnit

`php vendor/bin/phpunit`

## GrumPHP

PHPCS, PHPLint & PHPUnit

`php vendor/bin/grumphp run`

---

# Backers

A huge thank you to all our Patrons! 🙏 This project wouldn't be running without your support.

We have a free [REST API service](https://jikan.moe), if you wish to support us you can [become a Patron!](https://patreon.com/jikan)

## Sugoi (すごい) Patrons

- [Jared Allard (jaredallard)](https://github.com/jaredallard)
- [hugonun (hug_onun)](https://twitter.com/hug_onun)

## Patrons

- Aaron Treinish
- Cesar Irad Mendoza
- Jason Weatherly
- Jesse
- Kundan Chintamaneni
- Kururin
- Purplepinapples
- Sakamotodesu
- TeraNovaLP

## Development

|||
|------------|----------|
| ![JetBrain](https://user-images.githubusercontent.com/9166451/126047249-9e5bdc63-ae91-4082-bca5-ffe271b421da.png) | Jikan's development is powered by [JetBrain's Open Source License](https://jb.gg/OpenSource) |

A shoutout to their amazing products and for supporting Jikan since early versions!

---

# Release Changelog

## 2.17.0 - Sep 16, 21

- Added support for MAL's new [genre overhaul](https://myanimelist.net/forum/?topicid=1956762): `themes`, `demographics`, `explicitGenres`
- Updated Constants to reflect new and modified genres (while retaining BC). Some genres have been deleted or merged with others so they may return 404, check [Constants](https://github.com/jikan-me/jikan/blob/62f3e12cbcc8d841b3f923e4317f0b50f28f0574/src/Helper/Constants.php) for details
- Anime/Manga Genres List now returns additional arrays for `themes`, `demographics`, `explicitGenres` as they have been split up from `genres`
- Added parser support for Anime and Manga External Links https://github.com/jikan-me/jikan/issues/353
- Parser bug fixes

[Read More](https://github.com/jikan-me/jikan/blob/master/changelog.md)

# DISCLAIMER

- Jikan is not affiliated with MyAnimeList.net
- You are responsible for the usage of this API. Please be respectful towards MyAnimeList's [Terms Of Service](https://myanimelist.net/about/terms_of_use)
