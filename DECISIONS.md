---
status: stub
# Scaffolded from a template, not yet written. Delete this whole
# frontmatter block once you have authored the document. Until then it
# resolves with exit 5 and counts as design debt, never as coverage.
---
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

<!-- DECIDE: group entries by topic, or list them chronologically?

     Topical scales better for reading — someone asking "how does auth work" finds everything in one
     place. Chronological scales better for auditing — "what changed in March" is trivial.

     Topical is usually right, since this document is read to answer questions rather than to review
     a period. But topical requires discipline: a new entry must go in the right section, and the
     Date fields carry all the chronology. Pick one and say which here, because a document that is
     half-topical and half-chronological is neither. -->

Whichever you choose: **never reorder or reword existing entries** to fit. Append.

## Corrections

An entry that turns out to be wrong gets a correction, not an edit. Two acceptable forms:

- A new entry that supersedes it, with the old one marked `**Superseded by:** <link>`
- An inline `**Correction (YYYY-MM-DD):**` paragraph appended to the original, stating what was
  wrong and what is true

Never delete the original claim. Someone acted on it, and a reader who remembers it needs to find
out it changed — silently removing it makes the log untrustworthy for everything else it says.

<!-- DECIDE: which of the two correction forms is standard here? Both work; using both
     inconsistently means a reader cannot tell whether an uncorrected entry is still true or just
     never revisited. -->

## Growth

This document only grows. That is correct — but it means length is not a defect to be fixed by
deleting history.

If it becomes unwieldy:

- Split by topic into separate files, keeping this one as the index
- Mark superseded entries clearly at the top of each, so a reader stops early
- **Never** prune old entries to save space. The old ones are the valuable ones; recent decisions are
  still in people's heads, and decade-old ones are the only record

<!-- DECIDE: at what size does this get split, and along what lines? Answering now is easier than
     answering at 3,000 lines under pressure. -->

## What belongs here, and what does not

**Belongs:** anything a future reader would otherwise mistake for an accident. Scope changes, pivots,
rejected alternatives, first-of-a-kind dependency additions, deliberate deviations from the spec, and
decisions to *not* do something.

**Does not belong:** routine implementation choices with no alternatives worth recording, anything
already obvious from the code, and — importantly — plans. A plan is what you intend; a decision is
what you settled. Plans belong in a working-notes directory where they can be superseded freely.

<!-- DECIDE: what is the threshold for "significant"? Too low and this fills with noise nobody reads;
     too high and the interesting decisions never get written down. A useful test: would someone
     reading the code in a year assume this was a mistake? Then log it. -->

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

---

_Last updated: <!-- DERIVED: install date -->_
