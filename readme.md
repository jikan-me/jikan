[![Jikan](https://i.imgur.com/ccx3pxo.png)](#jikan---unofficial-myanimelistnet-php-api)

# Jikan - Unofficial MyAnimeList.net PHP API

[![build](https://github.com/jikan-me/jikan/actions/workflows/tests.yaml/badge.svg?branch=master)](https://github.com/jikan-me/jikan/actions/workflows/tests.yaml) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP-^%208.0-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](https://discord.gg/4tvCr36)

Jikan is a PHP API for [MyAnimeList.net](https://myanimelist.net). It scrapes the website to satisfy the need for API functionality that MyAnimeList.net lacks.

The raison d'être of Jikan is to assist developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

**Notice**: Jikan does not support authenticated requests. You can not update your lists.

## Getting Started

| Version   | Support | PHP | Lumen/Laravel |
|------------|----------|----------|----------| 
| [`^3` (master)]([https://github.com/jikan-me/jikan/tree/3.0.0](https://github.com/jikan-me/jikan)) | ✅ New features | [![8.0](https://img.shields.io/badge/PHP-^%208.0-blue.svg?style=flat)]() | `^8`, `^9` |
| [`^2`](https://github.com/jikan-me/jikan/tree/2.19.4)      | ❌ No longer maintained or supported | [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() | `^6`
| [`~1`](https://github.com/jikan-me/jikan/tree/1.16.3)      | ❌ No longer maintained or supported | [![stable](https://img.shields.io/badge/PHP-^%207.0-blue.svg?style=flat)]() | `5.5.*` |


1. `composer require jikan-me/jikan ^3`
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

### Sugoi (すごい) Patrons

- [Jared Allard (jaredallard)](https://github.com/jaredallard)
- [hugonun (hug_onun)](https://twitter.com/hug_onun)

### Patrons

- Aaron Treinish
- Abdelhafid Achtaou
- Aika Fujiwara
- Bobby Williams
- Cesar Irad Mendoza
- CrafterSama
- Fro116
- Jason Weatherly
- Jesse
- Kundan Chintamaneni
- Kururin
- Purplepinapples
- Ryo Ando
- Sakamotodesu
- TeraNovaLP

## Development

|||
|------------|----------|
| ![JetBrain](https://user-images.githubusercontent.com/9166451/126047249-9e5bdc63-ae91-4082-bca5-ffe271b421da.png) | Jikan's development is powered by [JetBrain's Open Source License](https://jb.gg/OpenSource) |

A shoutout to their amazing products and for supporting Jikan since early versions!

---

# DISCLAIMER

- Jikan is not affiliated with MyAnimeList.net
- You are responsible for the usage of this API. Please be respectful towards MyAnimeList's [Terms Of Service](https://myanimelist.net/about/terms_of_use)
