# Jikan — Contributing

How to work in this repository: what to do at the start of a session, what must be
true before a PR, and how one unit of work (an endpoint) flows through it.

Written for someone who has the repo cloned and nothing else. Owned by the
codebase owners; must change whenever the toolchain or the checks change.

---

## 1. Start of session

```bash
# Get current
git checkout 5.0.0 && git pull

# Install dependencies (PHP ^8.4)
composer install

# Fetch the recorded test fixtures — REQUIRED, the tests never hit a live site
composer link-fixtures
```

`composer link-fixtures` clones `jikan-me/jikan-fixtures` and points the
`jikan-me/jikan-fixtures` package at it. Without it the test cache has no
recordings and any test its requests are missing **fails with a hard error**
(the cache is a pure reader; it never fetches live — see `.backend-suite`
`audit_test_network_coupling`).

**Never commit directly to `5.0.0`.** Cut a feature branch from it and land
changes through a reviewed PR into `5.0.0`.

## 2. Before you open a PR

Every one of these must pass. A PR that fails them wastes a reviewer's time.

```bash
# Tests (hermetic — run whenever fixtures are present)
composer test

# Code style (jikan's .php-cs-fixer.dist.php)
php vendor/bin/php-cs-fixer fix --dry-run

# The same checks run again automatically on commit via the grumphp pre-commit hook
# (php-cs-fixer, git blacklist, paratest, phplint).
```

The pre-commit hook also runs the backend-suite gates from `.backend-suite/hooks.map.json`
(`error_handling`, `test_network_coupling`). Those run through a hand-merged
`.git/hooks/pre-commit` (suite gate first, then grumphp) — see §6 to wire it on a
fresh checkout.

- [ ] Branch is cut from current `5.0.0`
- [ ] `composer test` passes (empty findings in `.backend-suite/scratch/`)
- [ ] The documents affected by this change are updated **in the same PR** (`CHANGELOG.md`
      for consumer-visible changes, `DECISIONS.md` for decisions, the `readme.md` usage table
      for the public surface)
- [ ] New public surface is reflected in `docs/API-STANDARD.md`'s conventions once authored
- [ ] No secrets, tokens, or client data in the diff — and no live `https://myanimelist.net`
      requests introduced in tests (recorded fixtures only)

## 3. The spine: adding one unit of work

The unit of work is **an endpoint** — one thing `MalClient` can answer. Follow this
order; each step has a gate.

**Adding an endpoint:**

1. **Request value object** — `src/Request/<Domain>/<Thing>Request.php` implementing
   `Jikan\Request\RequestInterface` (a `getPath()` returning the `myanimelist.net` path and any
   query it needs). Gate: it is a leaf — it imports only `Jikan\Request\RequestInterface`,
   `Jikan\Exception`, and `Jikan\Helper` (the `layerBoundaries` audit refuses anything else).
2. **Parser** — `src/Parser/<Domain>/<Thing>Parser.php` implementing `Jikan\Parser\ParserInterface`,
   mapping a `Symfony\Component\DomCrawler\Crawler` to a model. Gate: it may import
   `Jikan\Model`, `Jikan\Helper`, `Jikan\Exception` — never `Jikan\Request`.
3. **Model** — `src/Model/<Domain>/<Thing>.php` with a `public static function fromParser(...)`
   factory (the v5 shape). Gate: models are data-only; they import other models and helpers,
   not parsers beyond their own factory's type.
4. **Client method** — a `public function getThing(Request\... $request): Model\...` on
   `Jikan\MyAnimeList\MalClient`, wrapping the call so failures surface as
   `Jikan\Exception\ParserException`. Gate: `audit_error_handling` stays clean — the call goes
   through `$this->httpClientWrapper->request(...)` and the parse is wrapped in
   `try { } catch (\Exception $e) { throw ParserException::fromRequest($request, $e); }`.
5. **Test** — `test/unit/Parser/<Domain>/<Thing>ParserTest.php` extending `JikanTest\TestCase`,
   driven by a recorded fixture. If the request is new, record its response into
   `jikan-me/jikan-fixtures` (commit it there, then `composer link-fixtures` here).
6. **Docs** — a `CHANGELOG.md` entry (consumer-visible changes only) and the `readme.md` usage
   table if the method is user-facing.

**What must be true before it counts as added:** the test passes from fixtures alone
(no live requests), `composer test` is green, `php-cs-fixer fix --dry-run` is clean,
and the backend-suite gates report no findings on the changed files.

## 4. where things go

| Kind of file | Location |
|---|---|
| Request value objects | `src/Request/<Domain>/` |
| Parsers | `src/Parser/<Domain>/` |
| Models | `src/Model/<Domain>/` |
| HTTP layer / client | `src/Http/`, `src/MyAnimeList/` |
| Shared constants | `src/Helper/Constants.php` |
| Tests | `test/unit/` (mirrors the `src/` layout) |
| Recorded test fixtures | `vendor/jikan-me/jikan-fixtures/fixtures/` (via `composer link-fixtures`) |

## 5. Testing and fixtures

The suite is **hermetic**: `JikanTest\TestCase` injects a `MockHttpClient` over
`HttpFilesystemCacheClientCallback`, which serves only recorded responses keyed by
request hash. There is no live fallback.

- A missing recording raises a named `RuntimeException` telling you to run
  `composer link-fixtures` — never a silent network call.
- To add a new recording: capture the real response, commit it to
  `jikan-me/jikan-fixtures` under the correct fixture directory, then refresh the local
  checkout with `composer link-fixtures`.
- `audit_test_network_coupling` (`python3 <suite>/bin/audit_test_network_coupling.py`)
  blocks the reintroduction of any live `HttpClient::create()` fallback in `test/`.

## 6. The backend-suite gates

`.backend-suite/` is this project's audit configuration (see `profile.json`, `arch-rules.json`,
`hooks.map.json`). The audits are fast and read-only:

```bash
python3 <backend-suite-root>/bin/audit_error_handling.py          # clean → exit 0
python3 <backend-suite-root>/bin/audit_test_network_coupling.py   # clean → exit 0
python3 <backend-suite-root>/bin/audit_api_consistency.py         # clean → exit 0
python3 <backend-suite-root>/bin/survey.py --check                # clean → exit 0
python3 <backend-suite-root>/bin/doctor.py                        # profile healthy
```

`hooks.map.json` marks `error_handling` and `test_network_coupling` as `preCommit: true`.
On a fresh checkout, wire the gate by hand-merging the suite's runner into the existing
grumphp `.git/hooks/pre-commit` (grumphp owns the hook; the suite installer refuses to
clobber it):

```bash
python3 <backend-suite-root>/bin/run_precommit_audits.py --list    # shows the gates
```

The merged hook runs the suite gate first, then grumphp; the grumphp hook from a prior
install is preserved at `.git/hooks/pre-commit.grumphp.backup`.

## 7. Things that will fail review

<!-- Recurring review comments go here once they exist. Deliberately empty rather than
     invented: an invented rule is enforcement without evidence. A good first candidate:
     a parser comparing a wire literal by hand instead of via `Jikan\Helper\Constants::DISPLAY_*`. -->

---

_Last updated: 2026-10-11_