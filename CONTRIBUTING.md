---
status: stub
# Scaffolded from a template, not yet written. Delete this whole
# frontmatter block once you have authored the document. Until then it
# resolves with exit 5 and counts as design debt, never as coverage.
---
# Jikan — Contributing

How to work in this repository: what to do at the start of a session, what must be
true before a PR, and which helper to use instead of a raw framework primitive.

Written for someone who has the repo cloned and nothing else. Owned by the
codebase owners; must change whenever the toolchain or the checks change.

---

## 1. Start of session

<!-- DERIVED: the real commands, from the project's own tooling. Confirm each one
     actually runs before writing it down — a checklist with a broken command in it
     stops being read. -->

```bash
# Get current
git checkout <integration-branch> && git pull

# Install / restore dependencies
```

**Never commit directly to the integration branch.** Cut a feature branch from it
and land changes through a reviewed PR.

<!-- DECIDE: name the integration branch. `main`? `dev`? Both, with a promotion
     step? Get this wrong and every contributor gets it wrong. -->

## 2. Before you open a PR

Every one of these must pass. A PR that fails them wastes a reviewer's time.

<!-- DERIVED: the project's real check commands — formatter, analyzer, tests,
     any audits. -->

```bash
# format
# analyze / lint
# test
```

- [ ] Branch is cut from the current integration branch
- [ ] The documents affected by this change are updated **in the same PR**
- [ ] No secrets, tokens, or client data in the diff
- [ ] New user-facing copy has been checked against the brand-voice document

## 3. The spine: adding one unit of work

Everything above is universal. **This section is not** — it is the ordered steps for adding the
one thing this project mostly adds, and that thing differs by what the project is. It is the
section contributors actually come back to, so it is worth being specific rather than general.

<!-- DECIDE: what is the unit? For a product that renders an interface it is usually a surface or
     a feature; for a design-system package a component; for a content site a page or a section.
     Name it, then write the ordered steps below. One unit only — a document that describes three
     spines describes none. -->

**Adding a <!-- DECIDE: the unit -->:**

<!-- DERIVED: the real ordered steps, from how the last few were actually added — read the commits,
     do not imagine the process. Include the gates each step must clear, since a step with no gate
     gets skipped. The shapes these usually take:

     - a surface: create it → wire it up so it is reachable → handle every state it can be in →
       register it in the surface inventory → test
     - a component: define its API → implement it → document its page under the component schema →
       add it to the gallery or example → changelog entry → test
     - a page: write it → place it in the information architecture → check its inbound and outbound
       links resolve → publish

     Replace this comment entirely with this project's own list. -->

1. <!-- DECIDE -->
2. …

**What must be true before it counts as added:**

<!-- DECIDE: the per-unit checklist. This is distinct from §2's pre-PR checks, which apply to any
     change; these are the things specific to this unit that reviewers keep having to ask for. -->

## 4. Component lookup table

Use the project's own component instead of the framework primitive. This table is
the fastest way to stop the design system being quietly re-implemented.

<!-- DERIVED: scan the shared component directory and list what exists. -->
<!-- Read by /review-docs, which flags entries here that no longer exist in code,
     so keep the class names exact. -->

| Instead of | Use | Why |
|---|---|---|
| | | |

## 5. Where things go

<!-- DERIVED: from profile.json's `source` section. -->

<!-- DECIDE: the kinds of file this project actually has. The first row is whatever §3's unit of
     work produces. Every row must name a real location, not an intended one. -->

| Kind of file | Location |
|---|---|
| The unit named in §3 | |
| Shared components | |
| Tests | |

## 6. Things that will fail review

<!-- DECIDE: the recurring review comments on this project. Each entry should be
     something that has actually been sent back more than once. If you cannot think
     of any yet, leave this empty rather than inventing rules. -->

---

_Last updated: <!-- DERIVED --> _
