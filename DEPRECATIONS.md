---
status: stub
# Scaffolded from a template, not yet written. Delete this whole
# frontmatter block once you have authored the document. Until then it
# resolves with exit 5 and counts as design debt, never as coverage.
---
# Jikan — Deprecations

The running ledger: everything currently deprecated, when it was announced, when it goes, and what
to use instead. One row per deprecation, from announcement until removal.

**This is the list, not the policy.** How much notice a deprecation gets, what counts as breaking,
and how an announcement is made are *process*, and they live with the release role — see
`fragments/DEPRECATION-AND-BREAKING-CHANGES.md.template`. They are kept apart deliberately: the
process changes rarely and is read by maintainers, while this ledger changes constantly and is read
by consumers deciding whether they are affected. Folding the ledger into the process document buries
the rows a consumer came for under policy they do not need.

Owned by whoever releases. A deprecation is not announced until it has a row here.

---

## 1. Currently deprecated

Everything announced and not yet removed. **This is the table consumers read**, so keep it first and
keep it complete — something deprecated in a changelog entry but missing here is deprecated only for
readers who happened to see that release.

<!-- DECIDE: the removal-date column's form — an absolute date, a version number, or both. Both is
     usually right: a consumer pinned to a version needs the version, and a consumer planning work
     needs the date. Whichever you choose, "next major" is not an answer, because nobody outside
     the project knows when that is. -->

| What | Announced | Removal | Use instead | Migration |
|---|---|---|---|---|
| <!-- the deprecated thing, named exactly as a consumer writes it --> | | | | <!-- a link to real migration steps, not "see docs" --> |

**Every row needs a `Use instead`.** A deprecation with no replacement is not a deprecation, it is a
removal with a waiting period — and a consumer who cannot migrate will simply not upgrade. If there
genuinely is no replacement, say so explicitly in that cell and record why here, because it changes
what the notice period has to be.

## 2. Removed

Kept after removal rather than deleted. A consumer on an older version still encounters these, and a
reader who finds nothing concludes they imagined it.

<!-- DECIDE: how long a removed entry stays here — usually until no supported version still contains
     it. State the rule; otherwise this table is either pruned arbitrarily or grows forever. -->

| What | Removed in | Was replaced by |
|---|---|---|
| | | |

## 3. Deprecated and then un-deprecated

<!-- DECIDE: does this project ever reverse a deprecation? If it does, those reversals belong here
     with the reason, because a consumer who already migrated needs to know they were not wrong to.
     If it does not — if announcement is treated as irreversible — say so in one line, and treat
     that as a reason to be slow about announcing. -->

## 4. How a row gets here

<!-- DERIVED: the real steps, from how the last deprecation was actually done. -->

1. <!-- DECIDE: at minimum — who approves, what the notice period is per the release policy, which
        channels announce it, and what marks the thing as deprecated in code so a consumer hits it
        without reading anything. That last one matters most: a deprecation announced only in prose
        reaches the consumers who read prose. -->

**Before the removal date arrives:**

<!-- DECIDE: what has to be true before removal actually happens — known consumers migrated, the
     migration path confirmed to work end to end, a second go-ahead if the policy requires one.
     Removing on schedule when the migration path was never tested is how a deadline becomes an
     outage. -->

## 5. Checking this ledger against reality

<!-- DECIDE: what confirms that everything deprecated in code appears here, and that nothing here
     has already been quietly removed? If nothing does, write that down. A ledger nothing checks
     drifts in both directions, and the dangerous direction is silent: a consumer reading a complete-
     looking table concludes the things missing from it are safe. -->

---

_Last updated: <!-- DERIVED: install date -->_
