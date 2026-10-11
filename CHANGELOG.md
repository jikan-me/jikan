---
status: stub
# Scaffolded from a template, not yet written. Delete this whole
# frontmatter block once you have authored the document. Until then it
# resolves with exit 5 and counts as design debt, never as coverage.
---
# Jikan — Changelog

What changed in each version, written for the people who have to decide whether to upgrade.

Mostly a **format contract**. The prose matters less than the consistency: a consumer scanning ten
versions to find what will break them needs every entry in the same shape, and one entry that
improvises costs more than ten that are terse.

Distinct from the decision log, which records *why* things were decided and is read by contributors.
This is read by consumers and records *what they now have to do*.

---

## 1. What earns an entry

<!-- DECIDE: the bar. The useful test is consumer-visible: if nothing outside this project could
     observe the change, it does not belong here. Internal refactors, test changes and
     documentation fixes usually fail that test — and including them trains readers to skim, which
     is how a breaking change gets missed. -->

An entry is required when: <!-- DECIDE -->

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

The link between the two — the reason this document is a contract rather than a diary.

<!-- DECIDE: this project's versioning scheme, and what each kind of change forces. If the scheme
     is semantic versioning the mapping is standard, but write it down anyway: the disputes are
     never about the rule, they are about whether a specific change is breaking.

     Then settle the cases that actually cause arguments, because they recur:
     - a changed default value — visible to every consumer who did not set it
     - a repointed semantic role — every surface reading it changes with no call site changing
     - a newly enforced rule that previously passed silently
     - a bug fix that some consumer had built on top of
     Each of these looks minor and behaves as breaking. Decide now, once. -->

| Kind of change | Version part it increments |
|---|---|
| | |

## 4. Unreleased

<!-- DECIDE: does this project accumulate entries under an "Unreleased" heading as changes land, or
     write the changelog at release time? Accumulating is strongly preferable — a changelog written
     at release time is written from `git log`, and `git log` does not know which changes a
     consumer can see. Whichever you choose, state it, because the two do not mix: half-accumulated
     entries plus a release-time sweep produces duplicates. -->

## Unreleased

<!-- Entries land here as changes merge. -->

---

<!-- Released versions below, newest first. Never reorder, never edit a released entry. -->

## <!-- DERIVED: the current version --> — <!-- DERIVED: today's date -->

<!-- DECIDE: if this project has released before but has no changelog yet, do NOT reconstruct one
     from git history and present it as a record. A reconstructed changelog is a guess about what
     consumers could see, and it is indistinguishable from an authored one. Start from this version,
     and say in one line that entries before it were not kept. -->
