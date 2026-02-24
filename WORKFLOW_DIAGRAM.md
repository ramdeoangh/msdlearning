# Release Workflow Diagram

## Visual Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     DEVELOPMENT PHASE                            │
└─────────────────────────────────────────────────────────────────┘

    ┌──────────┐
    │   main   │  ← Development happens here
    └────┬─────┘
         │
         │ (commit, push, test)
         │
         ▼
    ┌──────────┐
    │   main   │  ← Changes tested and verified
    └────┬─────┘
         │
         │
┌────────┴────────────────────────────────────────────────────────┐
│                     RELEASE PHASE                                │
└──────────────────────────────────────────────────────────────────┘
         │
         │ (create release branch)
         │
         ▼
    ┌─────────────────┐
    │ Release-MSD-102 │  ← New release branch
    └────────┬────────┘
             │
             │ (create Pull Request)
             │
             ▼
    ┌────────────────────────────┐
    │  Pull Request Review       │
    │  Release-MSD-102 → prd     │
    └────────┬───────────────────┘
             │
             │ (merge PR)
             │
             ▼
    ┌──────────┐
    │   prd    │  ← Production branch updated
    └────┬─────┘
         │
         │
┌────────┴────────────────────────────────────────────────────────┐
│                   DEPLOYMENT PHASE                               │
└──────────────────────────────────────────────────────────────────┘
         │
         │ (SSH to Hostinger)
         │
         ▼
    ┌─────────────────────┐
    │  Hostinger Server   │
    │  git pull origin prd│
    └─────────┬───────────┘
              │
              ▼
    ┌─────────────────────┐
    │  Live Production    │  ← Site updated
    └─────────────────────┘
```

## Branch Lifecycle

```
main (always active)
  │
  ├─── Release-MSD-101 (created, merged to prd, can be deleted)
  │
  ├─── Release-MSD-102 (created, merged to prd, can be deleted)
  │
  ├─── Release-MSD-103 (created, merged to prd, can be deleted)
  │
  └─── ... (future releases)

prd (always active, receives merges from release branches)
```

## Timeline Example

```
Week 1-2: Development
├─ Day 1-10: Work on main branch
├─ Day 11: Testing on main
└─ Day 12: Ready for release

Week 3: Release
├─ Day 13: Create Release-MSD-102
├─ Day 13: Create PR (Release-MSD-102 → prd)
├─ Day 14: Code review
├─ Day 14: Merge PR
└─ Day 14: Deploy to Hostinger

Week 4: Monitoring
├─ Day 15-21: Monitor production
└─ Start next development cycle on main
```

## File Flow

```
Local Development
    │
    ├─ Edit files
    ├─ git add .
    ├─ git commit -m "message"
    └─ git push origin main
         │
         ▼
GitHub (main branch)
    │
    ├─ Testing complete
    └─ Create Release-MSD-102
         │
         ▼
GitHub (Release-MSD-102)
    │
    └─ Create PR to prd
         │
         ▼
GitHub (prd branch)
    │
    └─ Merge PR
         │
         ▼
Hostinger Server
    │
    ├─ git checkout prd
    ├─ git pull origin prd
    └─ Files updated
         │
         ▼
Live Website Updated ✓
```

## Decision Tree

```
Do you have changes to deploy?
│
├─ YES
│  │
│  ├─ Are changes on main branch? ──NO──► Push changes to main first
│  │                                │
│  │                               YES
│  │                                │
│  └─ Are changes tested? ──NO──► Test on main first
│                          │
│                         YES
│                          │
│                          ├─ Create release branch (Release-MSD-XXX)
│                          ├─ Create PR to prd
│                          ├─ Review PR
│                          ├─ Merge PR
│                          └─ Deploy on Hostinger
│
└─ NO
   │
   └─ Continue development on main
```

## Command Flow

```
┌─────────────────────────────────────────────────────────┐
│ LOCAL MACHINE                                           │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  $ git checkout main                                    │
│  $ git pull origin main                                 │
│  $ create-release.bat 102                               │
│                                                         │
│  ┌─────────────────────────────────────────┐           │
│  │ Script creates Release-MSD-102          │           │
│  │ and pushes to GitHub                    │           │
│  └─────────────────────────────────────────┘           │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│ GITHUB                                                  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  • Release-MSD-102 branch created                       │
│  • Create PR: Release-MSD-102 → prd                     │
│  • Review changes                                       │
│  • Merge PR                                             │
│  • prd branch updated                                   │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│ HOSTINGER SERVER                                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  $ ssh user@hostinger                                   │
│  $ cd /path/to/project                                  │
│  $ git checkout prd                                     │
│  $ git pull origin prd                                  │
│                                                         │
│  ┌─────────────────────────────────────────┐           │
│  │ Production site updated!                │           │
│  └─────────────────────────────────────────┘           │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## Rollback Flow

```
Issue Detected on Production
         │
         ▼
    ┌─────────────────────┐
    │ SSH to Hostinger    │
    └─────────┬───────────┘
              │
              ▼
    ┌─────────────────────────┐
    │ git log --oneline       │  ← Find previous commit
    └─────────┬───────────────┘
              │
              ▼
    ┌──────────────────────────────┐
    │ git reset --hard <commit>    │  ← Rollback
    └─────────┬────────────────────┘
              │
              ▼
    ┌─────────────────────┐
    │ Verify site works   │
    └─────────────────────┘
```

## Multi-Developer Workflow

```
Developer A (main)          Developer B (main)
    │                           │
    ├─ Feature 1                ├─ Feature 2
    ├─ Commit                   ├─ Commit
    ├─ Push                     ├─ Push
    │                           │
    └─────────┬─────────────────┘
              │
              ▼
         main branch
         (all features)
              │
              ├─ Testing
              ├─ QA
              │
              ▼
      Release-MSD-102
              │
              ▼
          prd branch
              │
              ▼
      Hostinger Deploy
```

---

**Legend:**
- `main` = Development branch
- `Release-MSD-XXX` = Release branch (temporary)
- `prd` = Production branch
- `→` = Flow direction
- `├─` = Branch or step
- `▼` = Next step
