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

<!-- Entries land here as changes merge. -->

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