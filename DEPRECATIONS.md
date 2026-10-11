# Jikan — Deprecations

The running ledger: everything currently deprecated, when it was announced, when it goes, and what
to use instead. One row per deprecation, from announcement until removal.

**This is the list, not the policy.** How much notice a deprecation gets, what counts as breaking,
and how an announcement is made live with the release role — see the breaking-changes process in
`CHANGELOG.md` §3. **Owned by whoever releases.** A deprecation is not announced until it has a row
here.

---

## 1. Currently deprecated

Everything announced and not yet removed. Removal is expressed as a **version when one is known**;
until then the cell says "not scheduled", which is a real answer (the same one the code gives via
the `@deprecated` tag and no removal date).

All current rows describe the same migration: the four title accessors on `Anime` and `Manga` were
superseded by a `getTitles()` collection, and `Producer::getName()` by the producer's `getTitles()`.
The deprecated accessors are still present for ^4-era consumers and are stubs of convenience — they
return the first element / the English title of the collection (`getTitle()`), or the field as
parsed (`getTitleEnglish()`, `getTitleJapanese()`, `getTitleSynonyms()`, `Producer::getName()`).

| What | Announced | Removal | Use instead | Migration |
|---|---|---|---|---|
| `Anime::getTitle(): string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Anime::getTitles(): array` | read the collection; the primary title is `getTitles()[0]` (English-romanized title) |
| `Anime::getTitleEnglish(): ?string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Anime::getTitles(): array` | find `Title` entries whose `getType()` is `english` |
| `Anime::getTitleJapanese(): ?string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Anime::getTitles(): array` | find `Title` entries whose `getType()` is `japanese` |
| `Anime::getTitleSynonyms(): array` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Anime::getTitles(): array` | filter `Title` entries whose `getType()` is `synonym` |
| `Manga::getTitle(): string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Manga::getTitles(): array` | read the collection; the primary title is `getTitles()[0]` |
| `Manga::getTitleEnglish(): ?string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Manga::getTitles(): array` | find `Title` entries whose `getType()` is `english` |
| `Manga::getTitleJapanese(): ?string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Manga::getTitles(): array` | find `Title` entries whose `getType()` is `japanese` |
| `Manga::getTitleSynonyms(): array` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Manga::getTitles(): array` | filter `Title` entries whose `getType()` is `synonym` |
| `Producer::getName(): string` | ^4 (noted in code); recorded here 2026-10-11 | not scheduled | `Producer::getTitles(): array` | read the collection; the primary name is `getTitles()[0]` |

## 2. Removed

Nothing has been removed on the `^5` line (`Anime`, `Manga` and the interfaces are byte-compatible
with `^4`). Rows that arrive here stay for as long as any still-supported version contains the
removed thing — i.e. until consumers on that version are outside the support window.

| What | Removed in | Was replaced by |
|---|---|---|
| (none yet) | | |

## 3. Deprecated and then un-deprecated

This project does **not** reverse a deprecation once announced. That makes announcement the 
commitment it needs to be — deprecation without removal is a fiction, and so is an announcement
that can be walked back.

## 4. How a row gets here

A deprecation exists in one place (the `@deprecated` tag in code) before it exists here:

1. The `@deprecated` docblock tag is added on the member, naming the replacement (`Use ... instead.`).
2. A row lands in §1 **in the same change**, stating `Use instead` without a guess — a deprecation
   with no replacement is a removal with a waiting period, and needs a notice period to match.
3. The deprecation is announced in the next `CHANGELOG.md` entry under `### Deprecated`, so consumers
   who read release notes meet the same fact consumers who read the code do.

**Before the removal date arrives:** the migration path must have been confirmed working end to end
(consumers can make the switch from the collection accessors), and a removal only happens at a
**major** release.

## 5. Checking this ledger against reality

The reconciliation is a grep: every `@deprecated` tag in `src/Model` must have a row here, and every
row here must still exist in the code.

```bash
grep -rn "@deprecated" src/Model
```

Run it and reconcile §1 against the output before every release. Not yet automated — this check is a
candidate for the backend-suite `toolchainGates` section when the toolchain gates grow to cover the
repo's own conventions.

---

_Last updated: 2026-10-11_