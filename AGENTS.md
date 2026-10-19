# AGENTS.md — Jikan (v5)

## 0. What this file is — read first

A **navigation map**, nothing more. Every fact below that matters in review lives in the documents
this file points you to; this file tells you **what to read, in what order**, and never restates the
rules. If a document and this file ever disagree, **the document wins** — fix this file, not the
document.

## 1. The repository in one paragraph

PHP ^8.4 Composer **library** that scrapes `myanimelist.net`. No authentication, no server, no
live-fetch in tests. The public path is: `Jikan\MyAnimeList\MalClient` facade → `Jikan\Request\*`
value objects (a `getPath()` per request) → `Jikan\Parser\*` (Symfony `DomCrawler`) →
`Jikan\Model\*` (built via `fromParser()` factories). Tests are hermetic against recorded fixtures
(`composer link-fixtures`); the fixture cache is a pure reader and never fetches live.

## 2. Read before you write — the baseline

Read all five before making changes, in this order, then cut a branch:

| # | Document | What it is for |
|---|---|---|
| 1 | `readme.md` | What the library is; the public surface (entry-point / `api` doc-role) |
| 2 | `CONTRIBUTING.md` | How work flows: the endpoint spine, hermetic fixtures, pre-commit gates |
| 3 | `API-STANDARD.md` | Conventions every public entry point must satisfy — the review benchmark |
| 4 | `DECISIONS.md` | Why things are the way they are; read it when something looks surprising |
| 5 | `DEPRECATIONS.md` | The running deprecation ledger — one row per `@deprecated` tag in `src/Model` |

`CHANGELOG.md` (consumer-visible format contract) and `.backend-suite/` (machine config: audits,
arch-rules, doc manifest) matter when your task touches the public surface or the tooling.

## 3. Task router

| "I want to…" | Read | Notes |
|---|---|---|
| Add an endpoint | `CONTRIBUTING.md` §3 + `API-STANDARD.md` §1–2 | Follow the spine in order; each step has a gate |
| Where does X live | `CONTRIBUTING.md` §4 | Location table |
| Is X deprecated / why | `DEPRECATIONS.md` + the `@deprecated` tag | Rationale in `DECISIONS.md` ("not scheduled" policy) |
| Does this conform | `API-STANDARD.md` + `.backend-suite/arch-rules.json` | Review rejects drift |
| What is the whole public surface | `llm.txt` (generated catalog) + `readme.md` (authored) | `llm.txt` is derived — regenerate via `scripts/generate_endpoints.php`, never hand-edit |
| Change consumer-visible behaviour | `CHANGELOG.md` §1–2 | An entry is required, or the change does not belong |
| Run the gates | `CONTRIBUTING.md` §5–6 | `composer test`, php-cs-fixer, the backend-suite audits, per-install harness wiring |

## 4. Derived collateral — regenerate, never hand-edit

Files produced by running something, never written by hand, and safe to delete and regenerate:
`.backend-suite/endpoints.json` (the endpoint catalog), `llm.txt` (its human/agent-readable digest),
`.backend-suite/survey.json` and the recorded fixtures
(`vendor/jikan-me/jikan-fixtures/fixtures/`). Regenerate with `composer endpoints:write` /
`scripts/generate_endpoints.php --write`; keep it honest with `composer endpoints:check`. If you need
to change what they say, change the code or docs that produce them — not the file.

## 5. Rules that live only here

Kept deliberately short — do NOT move CONTRIBUTING's branch workflow or API-STANDARD's rules here.

- Work happens on **`master`**, which hosts the v5 line (currently `5.0.0`; the old `5.0.0`
  branch name was retired after the v4→v5 migration). **`master` is the base for every PR.**
- `CLAUDE.md` is an `@AGENTS.md` import — keep them in sync by keeping the canonical text here.
- The backend-suite harness files (`.codex/`, `.gemini/`, `.cursor/`, … and the generated AGENTS.md
  suite block) are **per-install, machine-specific** — wire them locally with
  `backend-suite harness --status` / `--apply` and never commit them (see `CONTRIBUTING.md` §6).

## 6. Backend-suite gates

Fast, read-only audits configured in `.backend-suite/` (`profile.json`, `arch-rules.json`,
`hooks.map.json`): `error_handling`, `test_network_coupling`, `api_consistency`, `toolchain_gates`,
plus `survey.py --check` and `doctor.py`. Run them with the installed suite root — exact commands and
the pre-commit wiring are in `CONTRIBUTING.md` §6.