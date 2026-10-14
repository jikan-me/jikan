# Jikan — Changelog

What changed in each version, written for the people who have to decide whether to upgrade.

Distinct from the decision log (`DECISIONS.md`), which records *why* things were decided and is read
by contributors. This is read by consumers and records *what they now have to do*.

> Entries before v5.0.0 were not kept. This changelog starts at the `^5` line; earlier releases
> (through v4.x) shipped without one, and reconstructing them from git history would present a
> guess as a record.

---

## 1. What earns an entry

The bar is **consumer-visible**: if nothing outside this project could observe the change, it does
not belong here. Internal refactors, test changes and documentation fixes usually fail that test —
including them trains readers to skim, which is how a breaking change gets missed.

An entry is required when:

- a consumer must do something to keep working (breaking);
- a consumer can do something they could not before (added);
- something a consumer observes behaves differently (changed);
- something a consumer could observe was wrong (fixed);
- something a consumer uses is being retired (deprecated).

By that bar, jikan's own internal work — parser refactors, fixture handling, tooling bumps for
contributors, `.backend-suite` audit configuration — deliberately does not appear here.

## 2. The format

Every entry, without exception:

```markdown
## <version> — YYYY-MM-DD

### Breaking
- <what changed> — **you must** <what the consumer has to do>. <link to the deprecation entry, if there was one>

### Added
- <what is new>

### Changed
- <what behaves differently, and how to tell>

### Fixed
- <what was wrong>

### Deprecated
- <what is now discouraged> — use <what instead>; removal in <version or date>
```

Rules that are not negotiable:

1. **Breaking goes first**, always, even when it is the shortest section. It is the section a reader
   came for, and it is the one they must not be able to miss.
2. **A breaking entry states the consumer's action**, not just the change. "Renamed X to Y" leaves
   work undone; "Renamed X to Y — rename every call site" finishes the sentence.
3. **Omit empty sections** rather than writing "none". A version with no breaking section says what
   it needs to say by not having one.
4. **Entries are never edited after release.** A released version's entry is what consumers read
   against a version they already have. Wrong information about a released version is corrected by
   a new entry in the next version, not by rewriting history.

## 3. Severity and version number

jikan uses **semantic versioning** on the `5.0.0` line (PHP floor, public API surface, dependencies).

The cases that actually argue:

- **A changed default value** — breaking. Visible to every consumer who did not set it.
- **A repointed semantic role** — breaking. Every surface reading it changes with no call site
  changing.
- **A newly enforced rule that previously passed silently** — breaking.
- **A bug fix some consumer built on top of** — breaking for that consumer. Call it out.
- **An upstream change on MyAnimeList's side** — **not** a version decision. The scraped page is
  external; jikan fixes breakage in a patch regardless of which version part it would otherwise be.

| Kind of change | Version part it increments |
|---|---|
| Consumer must change code / runtime | Major |
| New consumer-visible capability | Minor |
| Bug fix with no consumer action | Patch |

## 4. Unreleased

Entries **accumulate** under `## Unreleased` as changes land, and a release simply renames the
heading. A changelog written at release time is written from `git log`, and `git log` does not know
which changes a consumer can see.

---

## Unreleased

### Breaking
- **`AnimeCard::getLicensors()` is removed**, and so `SeasonalAnime::getLicensors()` too — **you must** stop calling it. Anime cards on genre, producer and seasonal lists no longer show licensors on MyAnimeList, and the method has returned an empty list since. Licensors are still on the anime itself: `Anime::getLicensors()` from `getAnime()`.
- **PHP floor raised to 8.4.4** — **you must** run PHP 8.4.4 or later; Composer no longer installs on 8.4.0–8.4.3. Those patch levels have a bug in PHP's own HTML5 parser (`Dom\HTMLDocument`, php-src GH-17481) that corrupts some 4-byte characters (emoji, rare scripts) depending on where they fall in the page, so a parsed field can contain invalid UTF-8 and `json_encode()` fails on the model. Seen on a real MyAnimeList manga review.

### Added
- `Jikan\Helper\Time` resolves MyAnimeList's relative and partial dates ("Yesterday, 9:25 PM", "5 hours ago", "Oct 13", weekdays) against a PSR-20 clock from the new `symfony/clock` dependency. By default that is the system clock, so results are unchanged. You can pin "now", for example in your own tests, with `Symfony\Component\Clock\Clock::set(new MockClock(...))`.

### Changed
- `symfony/dom-crawler` is now a declared requirement (`^7.4 || ^8.0`). The library has always used it directly but only received it through `symfony/browser-kit`, so nothing changes in your tree — no action needed unless you were excluding it.
- A seasonal page parses roughly 3x faster (about 1.2 s down to 0.4 s in our benchmark). The marker for "continuing" shows was found by reading the text of the whole season section once per anime card; it is now read once per section. The returned `Seasonal` model is identical.
- An anime card with no member count now raises `ParserException` from `MalClient` (a `RuntimeException` from `AnimeCardParser::getMembers()`), where it used to raise an uncaught `\TypeError` that `catch (\Exception)` and `catch (ParserException)` both missed. **If you caught `\TypeError` around client calls to work around this**, catch `ParserException` instead.
- `Jikan\Model\Manga\MangaReviewer` and `Jikan\Parser\Manga\MangaReviewerParser` are removed. They extended classes that were deleted earlier, so loading either one was a fatal error and nothing referenced them; no working code can have depended on them.
- `Jikan\Model\User\Friend::getFriendsSince()` returns `?\DateTimeImmutable`: `null` when MyAnimeList shows no "Friends since" date. It used to declare a non-null date and throw a `TypeError` in that case.

### Fixed
- Reading `getSmallImageUrl()`/`getLargeImageUrl()`/WebP image URLs on an entity whose image is a MyAnimeList placeholder (normalized to `null`) no longer throws "typed property must not be accessed before initialization" — those getters now return `null`, matching the `?string` image contract (jikan#488).
- Anime/manga detail pages with no cover no longer surface the MAL site icon (`og:image` → `/img/sp/icon/apple-touch-icon-256.png`) as the entity's image — it is now treated as a placeholder and resolved to `null` (jikan#488).
- `Jikan\Model\Watch\EpisodeListItem::getEpisodes()` returns the aired episode links as an array — every call previously threw a `TypeError` because the declared return type was `RecentEpisodeListItem`.
- `getAnimeGenres()`/`getMangaGenres()` no longer read the wrong page section when MAL prepends extra `genre-link` blocks before the genre lists (e.g. the "adapted from" block on `manga.php`) — the four sections are now anchored by content, not position.
- `getNewsList()` now parses the current MAL news index (`div.news-unit` items, ad placements filtered out) — the previous `js-scrollfix-bottom-rel` layout selector matched nothing on today's markup and returned an empty list (jikan#629).
- `ResourceArticle::getViews()` returns `null` instead of throwing when an article page has no view count — its type is now `?int` (jikan#629).
- News-item and article `getDate()` no longer mutate the shared DOM, so reading other info getters (author, views, comments) after the date on the same parser instance no longer fails (jikan#629).
- Calling `getModel()` a second time on the same parser (or reading getters in a different order) no longer fails with "The current node list is empty" for 14 parsers, including `AnimeParser`, `MangaParser`, `CharacterParser`, the genre, club, news, recommendation and review parsers. `Jikan\Helper\Parser::removeChildNodes()` stripped elements out of the shared page, so whatever was read after it saw a changed page; it now works on a copy. Getters that ran after a mutating one now see the original markup.
- `MalClient::getNewsList()` no longer fails before sending its request — it referenced a `$this->ghoutte` property left over from the old Goutte client.
- Casting a `PersonImageResource\Jpg` or `UserImageResource\Jpg` to string returns its image URL (an empty string when it is a placeholder) instead of throwing "call to undefined method `getJpg()`".
- `Jikan\Http\HttpClientWrapper` no longer records browser history. It kept one entry per request for the lifetime of the client (about 2.5 KB each), so a long-running process that reused one `MalClient` grew without bound. `request()` now defaults `$changeHistory` to `false`.
- Two-digit years in anime/manga search results and user list dates are no longer read as the 2040s–2060s: "Kaguya-hime" (1961) came back as 2061. A year more than 10 years in the future is now read as 19xx; this also corrects `isAiring()` for those titles.
- Anime and manga search with no matches return an empty result (`getResults()` is `[]`) instead of throwing `BadResponseException`. MyAnimeList answers those searches with a 404 page.
- Person search with no matches returns an empty result instead of throwing `ParserException`. MyAnimeList shows a "No results returned" row there.
- Character and person search no longer turn a non-404 HTTP error, such as a 429 or 500, into a `TypeError`. The `BadResponseException` now reaches you.
- Anime and manga genre lists report pagination again: `hasNextPage()` was always `false` and `getLastVisiblePage()` always `1`, though a genre spans many pages (20 for Action). A positional selector no longer matched MyAnimeList's layout.
- News lists, featured-article lists and "recently updated by users" (anime and manga) report pagination instead of a hard-coded `false`/`1`. For "recently updated by users" the page only links the next page, so `getLastVisiblePage()` is the next page when there is one.
- `Person::getPublishedManga()` lists a person's manga, for example 14 for Hayao Miyazaki. It was always empty because the selector expected table rows without the `<tbody>` that HTML5 parsing inserts.
- Manga related entries include the related-entries table (Side Story, Spin-Off, Alternative Version, Character and so on), not only the related tiles. The table was skipped for the same `<tbody>` reason.
- Anime and manga cards (genre, producer, magazine and seasonal lists) fill `getType()` again, for example "TV" or "Manga". Seasonal cards take it from their section header. Manga cards fill `getAuthors()` and `getSerialization()`. All three had been stubbed to `null`/empty after an earlier layout change, and MyAnimeList shows them again.
- Top characters and top people fill `getNameKanji()`, for example "ルルーシュ・ランペルージ". It was always `null` because the name moved to a `div`.
- `Profile::getAbout()` returns `null` instead of `""` when the user wrote nothing.
- A user profile's last anime and manga updates keep the episode or chapter count when the total is not known yet ("Reading 92/?"). It used to be `null`, because the progress was only read when both numbers were present.
- `getAnime()` and `getManga()` no longer fail when a page has no "Aired:" or "Published:" row. `getAired()`/`getPublished()` return an empty range, both dates `null`, which is what MyAnimeList shows as "Not available".

---

## v5.0.0 — 2026-10-11

First release on the `^5` line. The public PHP surface (`MalClient` methods, `RequestInterface`,
`ParserInterface`, the `Jikan\Model\*` classes for existing endpoints) is **unchanged from `^4`** —
the v4→v5 diff adds endpoints and replaces the HTTP layer; it removes nothing from the API.

### Breaking
- **PHP floor raised to 8.4** — **you must** require PHP ^8.4. `composer require jikan-me/jikan ^5`
  will enforce it, so an existing `^4` tree must be upgraded in the same change.
- **The HTTP layer swapped from `fabpot/goutte` to the Symfony components**
  — **you must** run `composer update` (and remove any transitive reliance on the abandoned
  `fabpot/goutte` package). `MalClient` still takes the same
  `Symfony\Component\HttpClient\HttpClientInterface`; the concrete
  `Symfony\Component\BrowserKit\HttpBrowser` crawler is now used internally instead of Goutte.

### Added
- **Content endpoints** — Article and News support, as 11 new `MalClient` methods:
  `getArticle`, `getArticleSearch`, `getArticleTags`, `getArticlesByTag`, `getNews`,
  `getNewsByTag`, `getNewsSearch`, `getNewsTags`, `getPinnedArticles`, `getRecentArticles`,
  `getRecentNews` (each with a matching `Jikan\Request\*` value object).

### Changed
- Test suite relocated from `test/JikanTest` to `test/unit`; tests stay hermetic against recorded
  fixtures (`composer link-fixtures`) and never hit the live site.
- `composer test` now runs the PHPUnit suite directly (PHPUnit via paratest `^7` — the old
  `phpunit ~9` dev constraint was dropped), grumphp moved to `^2`, php-parallel-lint to `^1.4`.

### Fixed
- **Opening/ending theme lists no longer include empty entries** — current MAL markup interleaves
  empty `<td>` cells with the real theme rows, so a one-theme title used to return
  `['', theme, '']`. Parser now filters them (jikan#593).
- **Episode `title_romanji` / `title_japanese` no longer end with a trailing non-breaking space**
  — the episodes-list parser now cleanses the captured titles exactly like the single-episode
  parser does (jikan#564).
- **AnimeCard`type` no longer TypeErrors on cards without a type** — `AnimeCardParser::getType()` returns `?string` but the model declared `string`; on pages with no card type the assignment would fatal. `type` is now `?string`, matching the source (malId/type consistency sweep).
- **Date ranges preserve precision** — a year-only or month-only date (`1989`, `May, 1979`) no
  longer surfaces as `January 1 of the year`; `from_prop`/`to_prop` `month`/`day` are `null` when
  the source date omitted them (jikan#486).
- **`getAnimeSearchAlt()` no longer references a non-existent model class** — its declared return
  type pointed at `Model\Search\AnimeSearchAlt`, which did not exist, so calling it would fatal;
  it now declares what the shared parser actually returns (`Model\Search\AnimeSearch`). Found by the
  new endpoint-manifest gate.
- **Placeholder images are no longer parsed (jikan#488)** — MyAnimeList's "no image" placeholder
  assets (`questionmark_23.gif`, `questionmark_50.gif`, `qm_50.gif`,
  `company/placeholder.png`, `na.gif`, `na_series_*.png|json`) are now surfaced as `null` instead
  of the placeholder URL for characters, people/voice actors, anime/manga, users, clubs and
  producers without artwork. Both `…->getImages()->getJpg()->getImageUrl()` resource getters and
  the raw string `getImageUrl()`/`getImage()` getters return `null`; the raw string getters and the
  affected image-resource properties are now `?string`.