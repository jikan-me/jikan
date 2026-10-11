# Jikan — Decision Log

Every significant decision made **after** the initial specification — scope changes, design pivots,
technical choices, client approvals — and the reasoning behind each. This is the bridge between what
was originally agreed and what actually exists.

Read it when the code does something surprising and you want to know whether it was deliberate.

**This document is append-only.** Correcting an entry means adding a correction, never rewriting the
original — see §Corrections. A decision log that can be quietly edited is not a log.

---

## The format matters more than the prose

Every entry uses the same five fields. The consistency is the point: `/sync-docs` writes entries,
`/review-docs` checks them for staleness, and release notes are derived from them. A hand-shaped
entry breaks all three.

```markdown
### Short title in the imperative or as a statement
**Date:** YYYY-MM-DD
**Source:** <where this came from — see below>
**Decision:** What was decided, specifically enough to act on.
**Reason:** Why. The field that earns this document its keep.
**Impact:** What changed as a result — files, docs, behaviour. And what deliberately did not.
```

### On each field

**`Date`** — when it was decided, not when it was written up. Absolute dates only, never "last
Tuesday": this document is read years later.

**`Source`** — who decided, and in what setting. Distinguish at minimum:

| Source | Meaning |
|---|---|
| `client-call (YYYY-MM-DD)` | Agreed with the client live |
| `client Q&A` | Answered in writing |
| `internal-decision (team)` | Ours to make |
| `external constraint` | Forced by a platform, regulation, or dependency |

This field looks like bookkeeping and is not. Revisiting a decision, the first question is always
*whose was it* — a client commitment and an internal preference carry completely different weight,
and six months on nobody remembers which this was.

**`Decision`** — specific enough that a reader can tell whether the code complies. "Improve
onboarding" is not a decision; "onboarding is four steps, and payment moves to the end" is.

**`Reason`** — the load-bearing field. A decision without its reason gets reversed by the next
person who finds it inconvenient, then reversed back when the original problem resurfaces. Include
the alternatives considered and why they lost — that is what stops the same debate recurring.

**`Impact`** — what actually changed. Also state what deliberately did *not* change, because a
reader otherwise assumes the decision propagated further than it did.

## Organisation

Entries are grouped **topically**; the `Date` fields carry all the chronology. A new entry goes in
the section it answers, creating a section only when none fits. This document exists to answer
questions, not to review periods — that is the reason the template's usual default of "topical is
usually right" applies here outright.

Whichever you choose: **never reorder or reword existing entries** to fit. Append.

## Corrections

An entry that turns out to be wrong gets a correction, not an edit. Two acceptable forms:

- A new entry that supersedes it, with the old one marked `**Superseded by:** <link>`
- An inline `**Correction (YYYY-MM-DD):**` paragraph appended to the original, stating what was
  wrong and what is true

Never delete the original claim. Someone acted on it, and a reader who remembers it needs to find
out it changed — silently removing it makes the log untrustworthy for everything else it says.

**The standard here is the inline correction:** `**Correction (YYYY-MM-DD):**` appended to the
original entry, stating what was wrong and what is true. Used consistently, so an entry with no
correction paragraph reads as "still true as far as anyone knows". A superseding entry is reserved
for the case where later work builds on the corrected decision rather than replacing it.

## Growth

This document only grows. That is correct — but it means length is not a defect to be fixed by
deleting history.

If it becomes unwieldy:

- Split by topic into separate files, keeping this one as the index
- Mark superseded entries clearly at the top of each, so a reader stops early
- **Never** prune old entries to save space. The old ones are the valuable ones; recent decisions are
  still in people's heads, and decade-old ones are the only record

**Split trigger:** when any single topical section reaches 25 entries, that section moves to its own
file with this document as the index and a pointer link where the section was.

## What belongs here, and what does not

**Belongs:** anything a future reader would otherwise mistake for an accident. Scope changes, pivots,
rejected alternatives, first-of-a-kind dependency additions, deliberate deviations from the spec, and
decisions to *not* do something.

**Does not belong:** routine implementation choices with no alternatives worth recording, anything
already obvious from the code, and — importantly — plans. A plan is what you intend; a decision is
what you settled. Plans belong in a working-notes directory where they can be superseded freely.

**The significance threshold is the template's own test, kept:** *would someone reading the code a
year from now assume this was a mistake?* If yes, it is logged. That test is why the constants
decision and the exemptions decision above both appear — each is a deliberate choice that the code
does not explain on its own.

---

## Error-handling audit exemptions

### Keep the BadResponseException / ParserException dead-state exemptions in exceptions.json
**Date:** 2026-10-11
**Source:** internal-decision (backend-quality-suite onboarding)
**Decision:** The two entries in `.backend-suite/exceptions.json` → `errorHandling.intentionalDeadStates` stay. The audit's dead-state check classifies a class as dead when nothing type-tests it with `instanceof`; jikan's two exception types are consumed by throwing and by a consumer's `catch (\Exception)`, so the check would report them as dead on every run.
**Reason:** The check's consumption model is screen-shaped — it describes a UI state being pattern-matched, not a library exception being caught. Reported, they would be misleading findings; exempted, they carry the explicit "N/A for a library" record the suite's own mechanism exists to hold. Removing them would re-introduce two permanent false findings that every `audit_error_handling` run repeats.
**Impact:** `.backend-suite/exceptions.json` unchanged. `audit_error_handling` reports 2 documented dead states every run; 0 false findings over 388 files. Reviewed at this decision point and kept as-is.

## Scraped display strings

### Compare MyAnimeList display strings through named constants, never raw literals
**Date:** 2026-10-11
**Source:** internal-decision (backend-quality-suite onboarding)
**Decision:** The display strings MyAnimeList writes on the page and jikan compares by value — `Unknown`, `N/A`, `Filler`, `Recap`, `Currently Airing`, `Publishing`, `Not available`, `None`, `Synonyms` — are referenced only through `Jikan\Helper\Constants::DISPLAY_*` constants. Every comparison *and* every emission of one of these sentinels uses the constant, so the two cannot diverge.
**Reason:** 20 findings from backend-suite `audit_api_consistency` (load-bearing-copy) measured the defect: the literal on the wire and the comparison sentinel were the same hand-typed string at 21 sites. These values are upstream data, not jikan's own copy — a consumer cannot re-voice them — so the failure mode is precisely the one the audit guards: an upstream rename (e.g. `Unknown` → `Not available`) silently drifts, and the parser keeps matching a string nobody emits. Constants make the rename a one-line change in one file. An enum was considered and rejected: the values are upstream strings, not jikan's vocabulary, so a typed enum would add a conversion at every call site for no consumer-visible gain.
**Impact:** Added the `DISPLAY_*` block to `src/Helper/Constants.php`; replaced 21 literals across 10 parser/model files (including the `AnimeCardParser` emission, so comparison and emission share the constant). `audit_api_consistency` load-bearing-copy is now clean (exit 0, 388 files). Behaviour unchanged — the constants carry the identical strings and the full PHPUnit suite still passes 394/394.

## Image placeholders

### Placeholder images parse to null, from one detection point
**Date:** 2026-10-11
**Source:** external constraint (upstream data + issue jikan-me/jikan#488)
**Decision:** MyAnimeList's "no image" placeholder assets are never surfaced. `Jikan\Helper\ImagePlaceholder` owns the one fragment list — `/images/questionmark_23.gif`, `/images/questionmark_50.gif`, `/images/qm_50.gif`, `/images/company/placeholder.png`, `/images/na.gif`, `/images/na_series_` (the last covers anime/manga missing art in `*.png` and `*.json` form), and `/img/sp/icon/apple-touch-icon-256.png` (the `og:image` fallback served on anime/manga detail pages that have no cover). Every image-resource factory and every raw-string image getter/property funnels through it and returns `null` on a match. Where a model property was `string` it becomes `?string` (absence is typed); `__toString()` on a null image returns `''` instead of crashing.
**Reason:** A placeholder URL carries no information — a consumer renders a "?" gif where their UI expected artwork, which is the bug reported in jikan#488. Matching *fragments*, not exact URLs, is what keeps the list false-positive-free: MAL rewrites these assets with `/r/WxH/` size prefixes and `?s=` cache-busting queries, and serves `na.gif` from both cdn.myanimelist.net and the akamai-origin host, so an exact-URL list (or host check) would silently miss live variants. Alternatives considered: (a) exact-URL matching — rejected, too brittle against MAL rewrites; (b) nulling only the image resources and leaving raw `getImage()`/`getImageUrl()` string getters returning the placeholder — rejected, the parser API would contradict the resource model; (c) leaving placeholders in and letting consumers filter — rejected, that is precisely what the issue asked jikan to stop doing.
**Impact:** Added `src/Helper/ImagePlaceholder.php`. Applied in the 8 URL-based image-resource factories (`CommonImageResource`, `WrapImageResource`, `CharacterImageResource`, `AnimeImageResource`, `ClubImageResource`, `NewsImageResource`, `PersonImageResource`, `UserImageResource`); their `__toString()` now returns `''` for a null URL. Parser getters for characters, people/voice actors, anime, manga, club members/clubs, user avatars (profile/search/friends/reviewers/recently-updated) and search/top/recommendation list items are now `?string` and normalize placeholders; `AnimeMeta`, `MangaMeta`, `CommonMeta`, `CharacterMeta`, `PersonMeta`, `UserMeta`, `ItemMeta`, `BaseLastUpdate` and the `Favorite*` subclass constructors accept `?string` image URLs. `Webp::factory()` in the Character/Anime/User resources is now null-safe (previously did `str_replace` before the null check — a latent PHP 8.4 deprecation that normalization would have triggered). News, Article and Watch (YouTube promo) thumbs are deliberately untouched — they are not MAL placeholder assets. `PersonSearchTest::it_gets_the_image_url` now asserts `null` (its recording shows ARA's search thumbnail is `questionmark_23.gif`); new `ImagePlaceholderTest` and a synthetic-Crawler parser test pin the measured URL shapes.

## News and featured-article re-alignment

### News index items are div.news-unit; featured articles still exist on /featured/
**Date:** 2026-10-12
**Source:** external constraint (live markup survey for jikan-me/jikan#629 — `news` index, `featured/{id}`, `news/{id}` pages)
**Decision:** The main news index (`/news`) renders items as `div.news-unit` with exact class semantics — the old `js-scrollfix-bottom-rel > .clearfix` selector matched nothing on today's markup, so `getNewsList` returned an empty list. `ResourceNewsListParser` was re-pointed to the exact-class `news-unit` selector, filtering the ad placements MAL injects under the same class (a unit only counts when it carries a title link), and its pager reads the real `?p=` range links. `ResourceNewsListItemParser` and `NewsListItemParser` now read the `news-unit` fields directly (title link, `image-link` img `src`, `information` author/comments/tags). `featured/{id}` is **not** dead — the featured-article stream still serves classic article pages (with the view count in the article's first `.information` block); only contemporary news ids 404 on it. `ArticleRequest` keeps `featured/{malId}`.
**Reason:** The endpoint must parse what the site actually serves. The featured pages remain the correct source for `getArticle`; the news index is the correct source for `getNewsList`. `ArticleParser::getViews()` and `News/Article` date getters were also stripping child nodes from the live DOM (`Parser::removeChildNodes` mutates the crawler), which broke any getter called after `getDate()` on the same parser instance — they now parse text without mutating.
**Out of scope, deliberately:** Featured articles that MAL no longer serves a field for — an article page without a view count now resolves `getViews()` to `null` (`ResourceArticle::getViews(): ?int`) rather than a fabricated number. `ArticleListItemParser::getViews()` stays `int` (related-article blocks on featured pages still serve counts; not under endpoint tests). News search/tags parsers and the news *item* page (`news/{id}`) were not re-aligned — `getArticle`'s source is the featured stream, and `news/{id}` blurbs are a separate layout. The eight `NewsListItemParserTest` cases were re-pointed at the recorded `news` index and un-skipped.

## Deprecation and release policy

### Deprecated title accessors stay available indefinitely — no removal version
**Date:** 2026-10-11
**Source:** internal-decision (maintainer sign-off on API-STANDARD.md §7)
**Decision:** `Anime::getTitle*`, `Manga::getTitle*` and `Producer::getName()` are deprecated and stay that way with `Removal: not scheduled`, permanently. No removal version is planned.
**Reason:** jikan's public surface is a compatibility superset — the v4→v5 diff removed nothing. Setting removal dates on these stubs would force a breaking consumer migration at some future major for accessors that cost nothing to keep and that a long tail of ^4-era consumers still call.
**Impact:** `DEPRECATIONS.md` §1 rows keep `not scheduled`; the deprecated accessors remain in code and in the standard. Nothing in the API is scheduled for removal.

## Documentation standards

### API-STANDARD.md approved as written; it becomes the review benchmark
**Date:** 2026-10-11
**Source:** internal-decision (maintainer review of the 6-PR stack)
**Decision:** The API standard, authored from measured conventions of the `^5` codebase in `API-STANDARD.md`, is approved as written. Convention drift is now rejected in review.
**Reason:** Every rule in it was measured from code that already follows the rule, so approval describes reality rather than imposing a new regime; a written benchmark stops the next endpoint from quietly re-deciding naming, error shape or pagination.
**Impact:** `API-STANDARD.md` §7 changed from "open decisions" to "resolved decisions"; future code review uses it as the reference.

## Documentation infrastructure

### Ship a machine-readable endpoint catalog as derived collateral
**Date:** 2026-10-11
**Source:** internal-decision (LLM-friendly work, P2)
**Decision:** The public surface is enumerated once, mechanically, into a derived manifest
(`.backend-suite/endpoints.json`) plus an `llm.txt` digest, generated by
`scripts/generate_endpoints.php` and gated by `composer endpoints:check`. `MalClient` method →
Request → Parser → Model → parser-test rows are extracted from the tree, not hand-maintained;
`getAnimeSearchAlt`'s dead `Model\Search\AnimeSearchAlt` reference surfaced by this gate was fixed
to the parser's actual return type.
**Reason:** The discovery gap that docs alone cannot close — a fresh agent had to grep 77 methods
across `Request`/`Parser`/`Model`/`test` every time. The suite's derived-collateral rule applies:
regenerable, source-stamped, disposable; `--check` makes staleness a hard failure. Parser-test
coverage gaps are **reported, not gating** (36 endpoints uncovered today) — closing them is a
separate fixture-work stream, and gating on it would have made the manifest unmergeable from day one.
**Impact:** `endpoints.json` + `llm.txt` added; `scripts/generate_endpoints.php`; `composer
endpoints:write|check`; AGENTS.md router/derived sections updated; one latent runtime bug found and
fixed (`getAnimeSearchAlt` return type).

## Toolchain gates

### Scope git-blacklist debug keywords to their actual call forms
**Date:** 2026-10-11
**Source:** internal-decision (maintainer review; grumphp blacklist)
**Decision:** `grumphp.yml` blacklist keywords change from bare words (`var_dump`, `exit`, `die`, `ini_set`, `error_reporting`) to their call forms (`var_dump(`, `die(`, `ini_set(`, `error_reporting(`, `exit(`, `exit;`). `else` is kept.
**Reason:** grumphp passes the keywords to `git grep --cached -G`, so they are POSIX basic regexes matched as substrings. A bare `exit`/`var_dump` blocked any changed PHP line mentioning the word in prose (a docblock saying "exits with exit code N") even when no debug statement existed. Scoping to the call form keeps the ban (a real `var_dump(` still blocks the commit) and stops prose from blocking. `else` stays because jikan is an early-return codebase (zero `else` in `src/`) and the gate is what enforces that.
**Impact:** `grumphp.yml` keywords tightened; verified no current `src/` line matches any tightened pattern (git grep returns no matches for all). No behaviour loss — every real debug call form is still caught.

---

_Last updated: 2026-10-11_
