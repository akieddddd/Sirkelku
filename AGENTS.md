# Workspace Rules for Sirkelku

## 1. UI Color Theme (Permanently Locked: Charcoal & Sage Green)
- **Do NOT revert or modify the Charcoal & Sage Green color palette** unless the user explicitly requests a color theme overhaul in a prompt.
- **Primary Background**: `#F8FAFC` (`slate-50`)
- **Structure & Text**: Dark Charcoal (`#1E293B` / `slate-800` & `#0F172A` / `slate-900`)
- **Primary CTA & Accent**: Muted Sage (`#588157`), hover (`#476A46`), active (`#385437`)
- **Surface / Card**: Solid Pure White (`#FFFFFF`) with thin neutral border (`#E2E8F0` / `slate-200`)
- **Hobby Pills / Badges**: Light Sage (`bg-emerald-50 text-emerald-800 border-emerald-100` / `.sk-pill`)
- **Room Chat**:
  - Sender: `#588157` with white text
  - Receiver: `#F1F5F9` with `#1E293B` text
- **Pagination**:
  - Clean white cards with `border-slate-200`, active page in `#588157`. No dark-mode overrides (`dark:bg-gray-800`).

## 2. Testing & Data Integrity
- Test suite in `tests/Feature/SirkelkuTest.php` seeds test fixtures only during test execution if tables are empty. Do not inject hardcoded test dummy records directly into production database tables without user request.
