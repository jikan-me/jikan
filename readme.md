[![Jikan](https://i.imgur.com/WY8KuOp.png)](#jikan---unofficial-myanimelistnet-php-api)

# Jikan - Unofficial MyAnimeList.net PHP API

[![build](https://github.com/jikan-me/jikan/actions/workflows/tests.yaml/badge.svg?branch=master)](https://github.com/jikan-me/jikan/actions/workflows/tests.yaml) [![stable](https://img.shields.io/packagist/v/jikan-me/jikan.svg?style=flat)](https://packagist.org/packages/jikan-me/jikan) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/jikan-me/jikan.svg)](http://isitmaintained.com/project/jikan-me/jikan "Percentage of issues still open") [![stable](https://img.shields.io/badge/PHP-^%208.4-blue.svg?style=flat)]() [![Discord Server](https://img.shields.io/discord/460491088004907029.svg?style=flat&logo=discord)](http://discord.jikan.moe)

Jikan is a PHP API for [MyAnimeList.net](https://myanimelist.net). It scrapes the website to satisfy the need for API functionality that MyAnimeList.net lacks.

The raison d'être of Jikan is to assist developers easily get the data they need for their apps and projects without having to depend on the lackluster official API, unstable APIs, or sidetracking their projects to develop parsers.

The word _Jikan_ literally translates to _Time_ in Japanese (**時間**). And that's what this API saves you of. ;)

**Notice**: Jikan does not support authenticated requests. You can not update your lists.

**Status**: active development is on `master` — the v5 line, currently **5.0.0-rc.2**. The v4 line
is **end of life** and no longer supported. Jikan is a PHP **library**, not a hosted service; a
self-hosted REST API exists as a separate project.

## Getting Started

| Version                                                                                 | Support                             | PHP                                                                         | Lumen/Laravel |
|-----------------------------------------------------------------------------------------|-------------------------------------|-----------------------------------------------------------------------------|---------------| 
| [`^5` (master · 5.0.0-rc.2)]([https://github.com/jikan-me/jikan](https://github.com/jikan-me/jikan))  | 🚧 **Release candidate** · API frozen, bug fixes only | [![8.4](https://img.shields.io/badge/PHP-^%208.4-blue.svg?style=flat)]()    | `^11`,`^12`   |
| [`^4`](https://github.com/jikan-me/jikan/releases/tag/v4.0.13)                           | ❌ **End of life** — no longer supported | [![8.1](https://img.shields.io/badge/PHP-^%208.1-blue.svg?style=flat)]()    | `^9`,`^10`    |
| [`^3`](https://github.com/jikan-me/jikan/releases/tag/v3.3.2)                           | ❌ No longer maintained or supported | [![8.0](https://img.shields.io/badge/PHP-^%208.0-blue.svg?style=flat)]()    | `^8`, `^9`    |
| [`^2`](https://github.com/jikan-me/jikan/tree/2.19.4)                                   | ❌ No longer maintained or supported | [![stable](https://img.shields.io/badge/PHP-^%207.1-blue.svg?style=flat)]() | `^6`          
| [`~1`](https://github.com/jikan-me/jikan/tree/1.16.3)                                   | ❌ No longer maintained or supported | [![stable](https://img.shields.io/badge/PHP-^%207.0-blue.svg?style=flat)]() | `5.5.*`       |


1. `composer require jikan-me/jikan "^5.0@RC"`  <!-- the 5.0.0 line is pre-release; a plain `^5` has no stable tag to resolve to yet -->
2. Coming from v4? Read the [upgrade guide](UPGRADING.md).
3. Read the [Repository docs](#repository-docs) below — this repo holds the canonical documentation.

### Repository docs

- [Contributing](CONTRIBUTING.md) — the endpoint spine, hermetic-fixture workflow, pre-commit gates
- [Changelog](CHANGELOG.md) — what changed per version, written for consumers
- [Upgrading from v4](UPGRADING.md) — what to change in your code, checked against a side-by-side run of both versions
- [Decision log](DECISIONS.md) — why things are the way they are
- [Deprecations](DEPRECATIONS.md) — the running deprecation ledger
- [API standard](API-STANDARD.md) — conventions every public entry point must follow

### Usage

```php
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Anime\AnimeRequest;
use Jikan\Request\Search\AnimeSearchRequest;

$client = new MalClient(); // or new MalClient(HttpClientInterface $http) to inject the client

$anime = $client->getAnime(new AnimeRequest(1));          // Cowboy Bebop
$results = $client->getAnimeSearch(new AnimeSearchRequest())->getResults();
```

Every public endpoint is one `MalClient` method taking one `Jikan\Request\*` value object; the
method list, the class-per-endpoint surface, and the conventions each must follow are documented in
`readme` (conceptually), `API-STANDARD.md` (requirements) and `CONTRIBUTING.md` §3 (how to add one).
The full machine-readable catalog lives in `llm.txt` (and `.backend-suite/endpoints.json`) — derived,
regenerated with `composer endpoints:write`. On every change that touches the surface, run
`composer endpoints:write` and `composer endpoints:check`.

# REST API (self-hosted)

The library powers a separate, **self-hostable** REST API project:
[jikan-me/jikan-rest](https://github.com/jikan-me/jikan-rest).

No hosted/public instance of the REST API is provided.

## Wrappers

| Language   | Wrappers |
|------------|----------|
| JavaScript | [JikanJS](https://github.com/zuritor/jikanjs) by Zuritor |
| Java       | [Jikan4java](https://github.com/Doomsdayrs/Jikan4java) by Doomsdayrs<br>[reactive-jikan](https://github.com/SandroHc/reactive-jikan) by Sandro Marques<br>[Jaikan](https://github.com/ShindouMihou/Jaikan) by ShindouMihou |
| Python     | [JikanPy](https://github.com/abhinavk99/jikanpy) by Abhinav Kasamsetty |
| Deno       | [Jikan.js](https://github.com/RPDJF/Jikan.js) by RPDJF |
| Node.js    | [jikan-node](https://github.com/xy137/jikan-node) by xy137<br>[jikan-nodejs](https://github.com/ribeirogab/jikan-nodejs) by ribeirogab |
| TypeScript | [jikants](https://github.com/Julien-Broyard/jikants) by Julien Broyard<br>[jikan-client](https://github.com/javi11/jikan-client) by Javier Blanco |
| PHP        | [jikan-php](https://github.com/janvernieuwe/jikan-jikanPHP) by Jan Vernieuwe |
| .NET       | [Jikan.net](https://github.com/Ervie/jikan.net) by Ervie |
| Go         | [jikan-go](https://github.com/darenliang/jikan-go) by Daren Liang<br>[jikan2go](https://github.com/nokusukun/jikan2go) by nokusukun |
| Ruby       | [Jikan.rb](https://github.com/Zerocchi/jikan.rb) by Zerocchi |
| Dart       | [jikan-dart](https://github.com/charafau/jikan-dart) by Rafal Wachol |
| Kotlin     | [JikanKt](https://github.com/GSculerlor/JikanKt) by Ganedra Afrasya |

[Add your wrapper here](https://github.com/jikan-me/jikan/edit/master/readme.md)


[View RoadMap](https://github.com/orgs/jikan-me/projects/3)

# Running Tests

```bash
composer quality   # everything at once: php-cs-fixer dry-run + endpoint-manifest check + tests
composer test      # PHPUnit via paratest — hermetic against recorded fixtures
```

Quality gates run automatically on every commit (grumphp: php-cs-fixer, php-parallel-lint,
git blacklist, paratest; plus the backend-suite `error_handling`/`test_network_coupling`
audits). The same `composer quality` gate is enforced in CI. Full workflow:
[CONTRIBUTING.md](CONTRIBUTING.md).

---

# Backers

A huge thank you to all our Patrons! 🙏 This project wouldn't be running without your support.

If you'd like to support the project, you can [become a Patron!](https://patreon.com/jikan)

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
