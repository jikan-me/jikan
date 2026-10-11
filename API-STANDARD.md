# Jikan — API Standard

The rules any public entry point must follow to be accepted by this library. Separate from the
API surface document on purpose: `readme.md` records what exists; this document records what is
required. Reviewing a proposed public class or method is a question for this document.

jikan does not set standards for its backend: it consumes MyAnimeList.net, and this document
governs the library's OWN public API — the `Jikan\MyAnimeList\MalClient` surface, the Request /
Parser / Model contract, and the exception contract (see `.backend-suite/arch-rules.json`'s
`errorHandling` section). Anything said about the upstream payload is a record of what the source
serves, not a rule jikan can impose.

Every rule below is **measured from the `^5` codebase** — it describes the convention the code
already follows, so a review rejects code that drifts away from reality rather than toward
aspiration. Where a choice is genuinely still open, §7 names it for maintainer sign-off.

---

## 1. Naming

| Layer | Convention | Example |
|---|---|---|
| Request value objects | `{Domain}{Thing}Request` under `Jikan\Request\{Domain}`, implements `Jikan\Request\RequestInterface` (a `getPath()` returning the `myanimelist.net` path) | `Jikan\Request\Anime\AnimeRequest` |
| Parsers | `{Thing}Parser` under `Jikan\Parser\{Domain}`, implements `Jikan\Parser\ParserInterface`, maps a `Crawler` to a model | `Jikan\Parser\Anime\AnimeParser` |
| Models | `Jikan\Model\{Domain}`, named after the entity; built via a `public static function fromParser(...)` factory | `Jikan\Model\Anime\Anime::fromParser(...)` |
| Client methods | `get{Thing}(Request\... $request): Model\...` on `Jikan\MyAnimeList\MalClient` | `$client->getAnime(new AnimeRequest(1))` |
| Constants | `Jikan\Helper\Constants`, `UPPER_SNAKE`; upstream display strings are `DISPLAY_*` (see `DECISIONS.md`) | `Constants::DISPLAY_N_A` |
| Identifier casing | PSR-1/PSR-12: PascalCase classes, camelCase methods, explicit nullable types (`?string`) | `getTitleJapanese(): ?string` |

## 2. Required of every public entry point

- Takes a **Request value object** — never a raw URL, path, or parameter list.
- Returns a **Model or collection** — never a `Crawler` or a raw scalar drawn from the page.
- **Failures surface through the shared exception pair**: `BadResponseException` for HTTP status, and
  `ParserException` for anything after fetch (wrapped via `ParserException::fromRequest(...)`, as
  every `MalClient` method already does). A new failure mode maps onto one of the two rather than
  inventing a third shape. The `errorHandling` arch-rules and its pre-commit gate enforce the
  wrapper call on every new method.
- **Absence is typed, not guessed** — nullability is explicit in signatures (`?string` for a field
  the page can lack) and the `'Unknown'`/`'N/A'` sentinels on the page map to `null` via
  `Constants::DISPLAY_*`, never to a string a consumer has to special-case. The same holds for
  image absence: MyAnimeList's placeholder-image assets (entities without artwork) map to `null`
  through the single `Jikan\Helper\ImagePlaceholder` helper, never to the placeholder URL
  (see `DECISIONS.md`, "Image placeholders").
- **Scraped display strings are compared through constants**, not raw literals — the 
  `load-bearing-copy` rule that cleared 20 findings in 2026-10-11 (see `DECISIONS.md`).

## 3. Pagination

Paged endpoints return a `Jikan\Model\Common\Collection\Results` wrapper carrying a
`Jikan\Model\Common\Collection\Pagination`; `page` is expressed on the Request value object
(e.g. `AnimeSearchRequest::$page`). New paged endpoints must match this `Results`/`Pagination`
shape — one list shape for the whole library, so a consumer's list handling is written once.

## 4. Errors

One envelope, two types, already fixed by the code:

| Type | When | Message shape |
|---|---|---|
| `Jikan\Exception\BadResponseException` | HTTP `>= 400` from the wrapper | `"<status> on <uri>"`, code = status |
| `Jikan\Exception\ParserException` | anything after fetch fails to map | `"Failed to parse '<path>'"` (+ previous as `$previous`) |

Both extend `\Exception`; consumers catch the base. See the `errorHandling` section of
`.backend-suite/arch-rules.json` for the enforced vocabulary.

## 5. Versioning and breaking changes

Semantic versioning on the `^5` line; **breaking = a consumer must change code or runtime**, decided
per kind in `CHANGELOG.md` §3. Announcement order, in one flow (also see `DEPRECATIONS.md` §4):

1. `@deprecated` tag in code naming the replacement;
2. a row in `DEPRECATIONS.md` §1 (with a real `Use instead`);
3. a `### Deprecated` block in the next `CHANGELOG.md` entry;
4. removal only at a **major** release, only after the migration path is confirmed working.

## 6. What this client will reject (in review)

- A parser comparing an upstream display string as a raw literal (`'Unknown'`, `'N/A'`, …) instead
  of the `Constants::DISPLAY_*` constant.
- A client method reaching the HTTP layer directly — outbound goes through the single
  `HttpClientWrapper` funnel (`audit_error_handling` gate).
- A model (or parser) leaking raw HTML or a `Crawler` to callers.
- A test that can reach the live site — the suite is hermetic against recorded fixtures, and the
  `test_network_coupling` gate rejects any `HttpClient::create()` fallback returning.
- A public method with no fixture-backed test.

## 7. Decisions on this standard (resolved 2026-10-11)

- **The deprecated title accessors stay "not scheduled" indefinitely.** No removal version
  will be set for `Anime::getTitle*`, `Manga::getTitle*` or `Producer::getName()`; the public
  surface stays a compatibility superset (see `DECISIONS.md`, "Deprecation and release policy").
- **This standard is approved as written** and is the review benchmark: code that drifts from any
  convention above is rejected in review, and changing a convention means changing this document
  and the code together, deliberately.

---

_Last updated: 2026-10-11_