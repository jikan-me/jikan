# Jikan v5 — Improvement Plan

Status: draft, agreed 2026-10-13. Constraints decided by the owner:

- **Stay on Symfony DomCrawler** for parsing. No engine swap.
- **`MalClient` facade stays stable** (methods, `Request\*`, `Model\*` getters, exceptions).
- **No concurrency/batching.** MAL rate-limits; callers keep >= 3s between requests, so parallel fetch has no value.
- **No built-in throttle.** Rate limiting is left to consumers; document the 3s guidance.
- Free model overhaul (readonly, enums, normalizers) is **RFC-track**, not part of this plan.

Figures marked *estimate* come from reading the code, not from measurement.

## 0. Baseline (do first, in order)

- [x] Stale-fixture check (local, read-only). **Result: fixtures are NOT stale** — the Anime fixtures contain
      the synopsis, `og:url` and `dark_text` selectors. The earlier `getModel()` failure was a real bug:
      **`getModel()` is not idempotent.** Parsers mutate the shared `Crawler` (`Helper\Parser::removeChildNodes`),
      so a second `getModel()` on the same crawler throws "The current node list is empty" (run 1 ok, runs 2-3 fail).
      Live drift is still unchecked (fixture-green is not live-green).
- [x] Fix/guard the mutation (branch fix/parser-idempotent-getmodel; `removeChildNodes` now works on a clone; regression test added in AnimeParserTest; other endpoints not yet covered by a parse-twice test): make parsing non-destructive (or clone the node) and add a regression test
      that calls `getModel()` twice on one crawler.
- Measured baseline (Trigun anime page, 177 KB, PHP 8.4.1, WSL): DOM build ~35 ms, parse+model ~36 ms,
  ~70 ms per page total, peak ~5.5 MB. DOM build is about half the cost, so node caching alone cannot
  more than roughly halve parse time. Numbers are noisy single-machine runs; the harness will replace them.
- [x] Add end-to-end `getModel()` tests: a generated parse-twice test in 59 parser test classes (60 with
      AnimeParserTest). Without the `removeChildNodes` fix, 14 of the 60 fail, so the mutation bug hit
      14 parsers, not only Anime. Six tests (News, RecentRecommendations, UserSearch, Friends) compare with
      `assertEqualsWithDelta` because relative dates ("5 minutes ago") resolve against the clock.
      Not covered yet: ~30 test classes with no single `$this->parser` (Search, Top, Forum, Watch, Seasonal,
      Pictures, some Manga/Anime list parsers) and parsers without a test class. Remaining gap: these tests
      check repeatability, not that every field is correct.
- [x] Add a benchmark harness: `composer benchmark` / `scripts/benchmark.php` (12 endpoints, fixtures only,
      median of N runs, DOM build and parse+model timed separately, `--json` and `--compare=` for before/after).
      Run-to-run noise on identical code is roughly +-10-15% on this WSL machine, so ignore smaller deltas.
      `peak_mb` only counts PHP's allocator, not libxml's DOM memory, so it understates real memory use.
      **Finding from the harness:** the first clone-based `removeChildNodes` fix made anime/manga parse ~4x
      slower (18 -> 71 ms) because `AnimeParser::getBackground()` passes a 13,860-element container.
      Fixed by shallow-cloning and deep-copying only the surviving children: back to baseline (-1% / +7%).
      **Hot spots to attack next (parse ms, median):** seasonal ~1200, producer ~310, person ~215,
      genre-anime ~200, anime-characters ~190. Seasonal is 5x the DOM build time.
- [x] Add per-fixture output snapshots as a diff target for refactors: `test/unit/ModelSnapshot.php`, one
      snapshot test in each of the 60 parse-twice classes. The 60 snapshot files (7 MB; Person alone is 1.8 MB)
      live in the **jikan-fixtures repo** (`snapshots/`, local branch `feat/model-snapshots` in
      `~/projects/jikan-fixtures`, commit 83d7a2f), read through `vendor/jikan-me/jikan-fixtures/snapshots`.
      **Merge order matters:** the fixtures PR must merge before this one, or CI's `dev-master` fixtures have no
      snapshots and all 60 snapshot tests fail with "No snapshot". Locally `vendor/jikan-me/jikan-fixtures` is a
      symlink to the sibling clone. Do not run `composer link-fixtures` against that clone: it runs
      `git reset --hard origin/master` and would discard the unpushed snapshots commit.
      Update deliberately with `JIKAN_UPDATE_SNAPSHOTS=1 composer test` and review the diff. Verified: output is deterministic across runs, and breaking one selector fails the snapshot
      (`score` 8.22 -> null). Dates are masked in the 6 relative-date classes. Same coverage gap as the parse-twice tests.
- [x] Seasonal hot spot: `AnimeCardParser::isContinuing()` re-read the whole season section per card
      (quadratic). Seasonal parse ~1200 -> ~370 ms. The remaining slow parsers (producer, person, genre,
      anime-characters) are linear: ~25 XPath calls per item at ~0.1 ms each, which the section-map helper targets.
- [ ] Refresh fixtures only if drift is confirmed. Needs live requests: confirm with the owner first, 3s gap.

## 1. v5.x, no breaking changes

- [x] PHPStan level 6 with a baseline (`composer phpstan`, part of `composer quality`; `phpstan-baseline.neon`
      holds 429 pre-existing findings; regenerate with `composer phpstan:baseline` after fixing some).
      Level 0 alone found real bugs, fixed with regression tests (they fail on the old code):
      `MalClient::getNewsList()` read a non-existent `$this->ghoutte` (always failed); both `Jpg::__toString()`
      called a method that does not exist; `AnimeCardParser::getMembers()` fell off the end (TypeError that
      bypassed `catch (\Exception)`), now throws a RuntimeException so it surfaces as a ParserException.
      Unfixed, see "Open decisions" below.
- [x] Live drift canaries: `scripts/drift_check.php --live` (14 pages: anime x3, manga x2, character, person,
      club, producer, genre, seasonal, schedule, search, top; GET only, 3.5 s apart, `--live` required, not part of
      `composer test`; `--json` / `--compare` flag a null-rate rise of >10 points). **First live run
      (2026-10-13): 14 of 14 parsed, no failures.** Null rates: nearly all 0-8%; producer/1 is 27.7% (probably
      normal for that page, but it is the number to watch). Scheduling it (cron/CI) is not done; that needs an
      owner decision on where it runs.
      Same run answers the `getMembers()` question: the card parser's no-count path did not trigger on live
      producer, genre, seasonal or top pages, so a missing count looks rare; it stays a catchable exception.
- [x] Live probe (one-off, 8 requests): MAL sends **no ETag or Last-Modified** and `Cache-Control: no-cache`
      (it does set MALSESSIONID / mal_cache_key / is_logged_in cookies), so conditional-request revalidation
      is not possible. Anime, manga and top-anime parse fine with the cookie jar cleared before every request,
      so cookies are not required (only 3 pages tested). The jar does not grow (same names are replaced), so
      it is left alone.
- [x] Section-map helper, scoped version: `Parser\Common\IndexesSidebarLabels` evaluates ONE XPath for all
      the labels a parser asks for and buckets the spans, so `label('Genres:')` returns exactly the nodes
      `filterXPath('//span[text()="Genres:"]')` did (unit-tested on duplicates, split text nodes, absent
      labels; all 60 model snapshots unchanged). Applied to AnimeParser (all 25 label scans) and the 8
      page-wide label scans in MangaParser. Anime parse 23.6 -> ~17 ms (-26 to -28%); manga is within noise.
      **Measured before building it:** `filterXPath` is 64-93% of parse time, but only 1-21% of calls repeat
      the same expression on the same node, so a memoizing crawler would gain little; the cost is in distinct
      queries. Card-list pages (producer: ~6,900 calls/parse, ~0.04 ms each) are not helped by this helper.
      Not converted: the 14 manga lookups scoped to the sidebar cell (`filterXPath(td)->filterXPath('//span..')`),
      since indexing them page-wide could change which spans match.
      Honest scale: the best case saves ~6 ms on a page whose network fetch takes far longer, so this matters
      for CPU use at volume, not for single-request latency.
- [x] (first half) Shared fetch/parse/wrap path: `MalClient::parse($request, Parser\X::class)`. 56 of the 76
      methods matched one exact pattern and are now one line each (MalClient 1580 -> 1198 lines). The 20
      special cases (404 -> empty model, JSON user lists, forum/more-info getters, id=0 guards) are untouched.
      Cost: the helper returns `mixed`, so PHPStan lost one type check (it used to flag `getPersonPictures()`
      returning `DefaultPicture[]` while declaring `Picture[]`). `scripts/generate_endpoints.php` learned the
      new call form; the endpoint manifest was regenerated (parser refs unchanged).
- [ ] (second half, needs owner decision on names) per-domain clients behind the facade, e.g.
      `$client->anime()->get(1)`. New public API: decide naming and whether `MalClient` methods stay un-deprecated.
- [ ] Fold the 404 policy into the helper (an option per request) so the remaining special cases shrink too.
- [x] HTTP state fix, small version. Measured: with the old wrapper the BrowserKit history grew one entry per
      request, unbounded (400 requests -> 400 entries, +1.0 MB vs +0.2 MB flat). `HttpClientWrapper` now defaults
      `changeHistory` to false and clears history after each request (regression test fails without the fix).
      No fetcher rewrite needed. NOT changed: the cookie jar still persists across requests on one client. Whether
      MAL needs those cookies is unverified (needs a live check), so it is left alone on purpose.
- [x] ~~PSR-18 adapter~~ **Dropped** (owner decision): the library stays on Symfony, so an adapter would only
      let jikan-rest drop a duplicate HTTP stack, at the cost of new psr/* dependencies and a custom browser class.
- [x] ~~PSR-16 cache decorator with ETag/Last-Modified revalidation~~ **Dropped:** verified live that MAL
      sends no validators (see above). A plain TTL cache would be a consumer concern (jikan-rest already
      caches); revisit only if the standalone library needs one.
- [ ] Opt-in lenient parse mode (`ParseMode`): strict stays default; lenient records diagnostics for
      optional fields instead of failing the whole entity.

## Open decisions (need the owner)

- ~~Delete the dead `Model\Manga\MangaReviewer` and `Parser\Manga\MangaReviewerParser`~~ **Done** (owner
  decision): they extended classes deleted in d8ec60b and nothing referenced them.
- `Parser\Reviews\ReviewerParser::getAnimeScores()` and `AnimeReviewParser::getAnimeScores()` instantiate
  `AnimeReviewScoresParser`, which does not exist (so those two methods always fail), and
  `Model\Anime\AnimeReviewScores::fromParser()` type-hints it. Restore the class or remove the methods?
- `getMembers()` now throws when no member count is found; should it return 0 / null instead?
- Snapshots add 7 MB to the repo. Acceptable, or keep them out of the repo?

## Test suite plan (audit 2026-10-14)

An audit found: almost every endpoint rests on ONE recorded page (no page 2, last page, zero-result search,
unaired/adult/no-score variants); about half the model getters are never asserted; only 6 of 77 `MalClient`
methods are called by tests; nothing checked that output is plausible, so invalid UTF-8, 2061 dates, empty
strings and inconsistent pagination sat in "passing" snapshots; 80 of 178 fixtures were never loaded.

### Phase 1, done in this branch
- [x] `test/unit/ModelInvariants.php`, run by every snapshot test: valid UTF-8, JSON-encodable, no NBSP / edge
      whitespace / empty strings / stray entities or tags, absolute URLs, positive ids, non-negative numbers,
      score 0-10, years 1900..now+5, consistent pagination, and every public getter (incl. `__toString`) callable.
      Known violations: `test/unit/__invariants__/` (11 empty-string, 6 pagination); may only shrink.
- [x] Parse-twice and snapshot coverage for 28 more classes (search, stats, seasonal, watch, forum, pictures,
      reviews, recommendations, user clubs/history, username lookup): 88 snapshots in total.
- [x] Bugs it found, fixed: two-digit years read as 20xx (1961 -> 2061, also breaks `isAiring`);
      `Friend::getFriendsSince()` threw `TypeError` on no date; PHP 8.4.0-8.4.3 HTML5 UTF-8 corruption
      (php-src GH-17481) -> PHP floor `^8.4.4`.
- [x] 80 dead fixtures deleted, `.gitattributes` in jikan-fixtures, fixture callback no longer creates dirs
      under vendor/, `JIKAN_FIXTURE_LOG` + `scripts/fixtures_unused.php`.
- [x] Report-only CI job: pcov line coverage, unused fixtures, `scripts/field_witness.php` (38 model fields no
      recorded page ever fills).

### Phase 2 (branch v5/test-suite-phase-2)
- [x] Clock: every date site (about 20) goes through `Jikan\Helper\Time` on `symfony/clock`. Equivalence checked on
      all 928 distinct date texts the suite parses (identical to the microsecond). Tests freeze it at
      2026-10-12T12:00Z; all 15 date masks and 6 tolerance comparisons are gone.
- [x] Corpus: `test/unit/Corpus/variants.json` (99 variants: one per `MalClient` method, all 76, plus page 2s,
      zero-result searches, airing/upcoming/movie/light-novel/author, old and future seasons, 404 and id 0).
      Pages live in jikan-fixtures `pages/<key>.html` + `.meta.json` (URL, final URL, HTTP status, content type,
      recording time); `RecordedPages` serves the recorded status and sets the clock to the recording time.
      `CorpusTest` runs each through `MalClient`: invariants, named snapshot, second call equal.
- [x] `scripts/record_variants.php` (`--dry-run`, `--live`, `--only`, `--force`): records exactly what the
      `MalClient` method requests. One-off live run on 2026-10-14: 98 pages, GET only, 3.5 s apart.
- Bugs the corpus found and fixed: anime/manga search with no matches threw `BadResponseException` (MAL returns
  404); person search with no matches threw `ParserException`; character/person search turned non-404 errors
  into a `TypeError`.
- Bugs the corpus found, NOT fixed yet (owner decision): anime genre page 1 reports `hasNextPage=false`,
  `lastVisiblePage=1` though the page links 20 pages (likely the same for manga genres); `Person::publishedManga`
  is empty for Hayao Miyazaki (1870) although the page has a "Published Manga" section with 28 links. 41 fields
  remain never filled (`scripts/field_witness.php`), several of them likely parser bugs (`AnimeCard::type`,
  `MangaCard::authors`, `Top*ListItem::nameKanji`).
- Not done: migrating the 98 per-class fixtures to the pages format (they keep working as they are).

### Phase 3 (branch v5/test-suite-phase-3)
- [x] Synthetic missing-field variants: `test/unit/Corpus/MissingFieldTest.php` removes each sidebar row from the
      8 recorded anime/manga detail pages (166 cases) and checks only that row's fields change. Found and fixed:
      `getAnime()` crashed without an "Aired:" row, `getManga()` without a "Published:" row.
- [x] Mutation testing: `infection.json5` + `.github/workflows/mutation.yaml` (changed lines on PRs touching
      `src/`, everything nightly; report-only). First real run happens in CI (no local coverage driver).
      Next: set `minMsi` from the first nightly score.
- [x] Property tests: `test/unit/Helper/HelperPropertiesTest.php` (~400 generated inputs per helper). Found and
      fixed: `parseDate()` returned `false` on "Unknown, 2026", `parseDateMDYReadable()` threw on it,
      `stringIdFromUrl()` warned without a path. Known and kept: `JString::cleanse()` is not idempotent.
- [ ] Exact-value assertions in the weakest test classes (lower value now that every class has a snapshot and
      invariants; mostly documentation of intent).

### Open questions from the audit
- The 6 lists with `hasNextPage=true` but `lastVisiblePage=1`: is `lastVisiblePage` meaningful there?
- The 38 never-filled fields: MAL removals or parser bugs (e.g. `AnimeCard::type`, `MangaCard::authors`)?

## 2. Explicitly not doing

- Concurrency / batching; built-in throttle; replacing DomCrawler; lazy hydration; selector DSL/config files;
  enums for the `DISPLAY_*` sentinels (rejected in DECISIONS.md).

## 3. RFC track (later, needs jikan-rest agreement)

Readonly models, domain enums with an unknown-value escape, a normalizer producing the REST API shape,
structured fields (broadcast, premiered, duration), mutation testing. Research agent output informs the RFC.

## Risks

- Fixture-green is not live-green (DECISIONS.md). Drift canaries are the mitigation.
- Fail-soft nulls can hide drift unless diagnostics are surfaced.
- Cookie jar removal could change MAL behaviour; verify before changing.
