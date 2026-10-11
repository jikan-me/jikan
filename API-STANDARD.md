---
status: stub
# Scaffolded from a template, not yet written. Delete this whole
# frontmatter block once you have authored the document. Until then it
# resolves with exit 5 and counts as design debt, never as coverage.
---
# Jikan — API Standard

The rules any public entry point must follow to be accepted by this library. Separate from the
API surface document on purpose: that one records what exists (readme.md), this one records what
is required. Reviewing a proposed public class or method is a question for this document.

jikan does not set standards for its backend: it consumes MyAnimeList.net, and this document
governs the library's OWN public API — the `Jikan\\MyAnimeList\\MalClient` surface, the Request /
Parser / Model contract, and the exception contract (see `.backend-suite/arch-rules.json`'s
errorHandling section). Anything said about the upstream payload is a record of what was asked for
and what the source serves, not a rule jikan can impose.

---

## 1. Naming

<!-- DECIDE: casing for paths, query parameters, and JSON fields. Pick one per
     layer and never mix. Mixed casing in a payload costs a mapping layer forever. -->

| Layer | Convention | Example |
|---|---|---|
| Path segments | | |
| Query parameters | | |
| JSON fields | | |

## 2. Required of every response

<!-- DECIDE: the non-negotiables. Suggested starting points, each with a real cost
     if skipped:

     - A stable `id` on every entity. Without one the client cannot cache, diff, or
       reconcile a list, and every update becomes a full refetch.
     - Explicit nullability. "Field absent" and "field null" must mean the same
       thing or be documented as different.
     - Enums declared as enums. A free-string status is a client-side switch with a
       silent default, which is a bug waiting for a new value.
     - Timestamps in one format, with a timezone. -->

## 3. Pagination

<!-- DECIDE: one mechanism for the whole API. Offset and cursor pagination in the
     same API means every list screen needs to know which kind it is talking to. -->

## 4. Errors

<!-- DECIDE: one envelope for every error, including validation failures. A
     validation error shaped differently from a server error means the client
     handles failure twice. -->

## 5. Versioning and breaking changes

<!-- DECIDE: what counts as breaking, how a breaking change is announced, and how
     much notice the client gets. Agree this before it is needed, not during. -->

## 6. What this client will reject

Being explicit here is what makes the standard usable in review.

<!-- DECIDE: e.g. an endpoint with no error contract, a list with no pagination, a
     status field that is a free string. Each entry should be something worth
     blocking a merge over. -->

---

_Last updated: <!-- DERIVED --> _
