---
name: mobile-block
description: Resolves the mobile responsive pass for an existing, desktop-coded block in this project. Fills the mobile (first) argument of fluid() calls from a mobile design and adds mobile-first media queries for layout flips only. Triggers on "make {block} responsive", "mobile pass", "resolve mobile values", or explicit /mobile-block invocation. Only for this static HTML/SCSS project — never React/Tailwind/ACF.
metadata:
    mcp-server: figma, figma-desktop, playwright
---

# Mobile Block — Responsive Pass

Fill `fluid(0, X)` placeholders with real mobile values and add mobile-first `@media` for layout flips. That's the entire scope.

> Static HTML/SCSS project. PHP = `require` partials only. No WordPress/ACF. Only edit `blocks/{slug}/{slug}.scss`.

**All SCSS rules are inlined here — do NOT read `scss-standards.mdc` or any other config/rules file.**

## Structural invariant (MANDATORY)

**All CSS in `blocks/{slug}/{slug}.scss` MUST stay nested inside the outer `.{slug}-section { … }` selector.** Never add a top-level selector outside that wrapper — not even `@media` queries. If you find an existing rule outside the wrapper, move it in as part of the mobile pass.

Classes use **plain child names** (`.card`, `.image`, `.tag`, `.body`) — not BEM (`.{slug}__card`). Modifiers still use `--` (`.tag--dark`). Don't rename existing `__` classes during a mobile pass — flag them if you see them.

## `fluid()` mechanics

`fluid($min, $max, $min-bp: 'md', $max-bp: 'xl')` → `clamp()`. Below 768px → `$min`. Above 1200px → `$max`. Between → interpolation. `0` in `fluid(0, X)` = placeholder meaning "mobile TBD".

## Token reference (DO NOT re-read config files)

| Category | Values |
|---|---|
| **Colors** | `$pale #fbf8f3` `$mist #f7f1e8` `$sand #eae2d7` `$muted #8c807d` `$deep #362925` `$primary-color #1f2261` `$secondary-color #fdba00` `$body-color #080c11` |
| **Fonts** | `$serif-font-family: 'Libre Baskerville'` `$sans-serif-font-family: 'Geist', system stack` |
| **Typography** | h1:`fluid(32,48)/1.2/-0.96px` h2:`fluid(24,40)/1.4/-0.8px` h3:`fluid(20,32)/1.4/-0.64px` h4:`fluid(18,22)/1.4` h5:`fluid(16,18)/1.4` h6:`fluid(14,16)/1.4` text-small:`fluid(12,14)` text-medium:`fluid(14,16)` |
| **Spacing** | `$container-padding-x: fluid(20, 40)` |
| **Figma tokens** | `sp-N` = N px (2,4,6,8,12,16,24,32,48). Radius: `xxs=4 xs=6 sm=8 md=12 lg=16 xl=24` |

## Breakpoints

`$sm: 576px` `$md: 768px` `$lg: 1024px` `$xl: 1200px` `$xxl: 1400px` — no custom breakpoints, always use the variable name.

## House-style patterns (from existing blocks)

All patterns nested inside `.{slug}-section { … }` — shown unwrapped for brevity.

**Section padding** — always `fluid()`, never MQ:
```scss
padding-block: fluid(48, 80);
padding-inline: fluid(16, 48);
```

**Label badge** — shared pattern across blocks:
```scss
display: inline-flex;
align-items: center;
padding-block: fluid(2, 4);
padding-inline: fluid(6, 8);
border-radius: rem-calc(4);
font-size: fluid(12, 14);
```

**Header with desktop max-width** — LAYOUT, not VALUE:
```scss
.header {
	display: flex;
	flex-direction: column;
	@media (width >= $md) {
		max-inline-size: rem-calc(540);
	}
}
```

**Grid: 1-col mobile → multi-col desktop** — LAYOUT:
```scss
.grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: rem-calc(24);
	@media (width >= $md) {
		grid-template-columns: repeat(4, 1fr);
		gap: rem-calc(16);
	}
}
```

**Flex column→row flip** — LAYOUT:
```scss
.wrap {
	display: flex;
	flex-direction: column;
	gap: fluid(8, 16);
	@media (width >= $md) {
		flex-direction: row;
	}
}
```

**100% mobile → fixed desktop** — VALUE first, MQ only if `fluid()` can't express it:
```scss
inline-size: min(100%, #{rem-calc(528)}); // VALUE approach — preferred
```

**Card image** — `block-size` via `fluid()`, position absolute `img`:
```scss
block-size: fluid(320, 420);
border-radius: fluid(8, 12);
position: relative;
overflow: hidden;
img { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; object-fit: cover; }
```

## Output policy

- **Silence is the default.** No audit trail, no diff table, no narration, no recap.
- **Only output = problems.** Flag guesses, failures, dead CSS, ambiguity, SCSS compile errors, Playwright failures, design mismatches you couldn't resolve.
- **Step 7 report**: screenshots only. Add max 3 bullet flags ONLY if something needs the user's attention. If the pass was clean, just show screenshots — no text.

## Workflow

### Step 1 — Gather

1. Confirm block slug (ask once if missing).
2. Read `blocks/{slug}/{slug}.scss` and `blocks/{slug}/{slug}.php` in parallel.
3. Get mobile design:
   - **Figma URL** → `get_design_context` only (includes screenshot — no separate `get_screenshot`).
   - **Image** → Read tool.
   - **Spec text** → use verbatim.

### Step 2 — Classify changes

For every `fluid(0, X)`, raw value differing from Figma mobile, or structural property:

| Kind | Meaning | Treatment |
|---|---|---|
| **VALUE** | Same layout, different number | `fluid(mobile, desktop)` |
| **LAYOUT** | Structural flip (direction, order, display, grid cols, wrap, position, show/hide) | Mobile-first `@media (width >= $bp)` |

Rules:
- `font-size, line-height, padding, margin, gap, border-radius, fixed sizes` → VALUE
- `flex-direction, grid-template-columns, order, display changes, flex-wrap, position` → LAYOUT
- `max-inline-size: unconstrained → fixed` → LAYOUT (clamp can't express "no constraint")
- Default to VALUE when unsure. Goal: minimize media queries.

### Step 3 — Internal audit (silent)

Build diff table internally: `Selector | Property | Mobile → Desktop | Kind`. Never output. Note non-obvious items for Step 7 flags.

### Step 4 — VALUE edits

- `fluid(0, X)` → `fluid(mobileVal, X)`.
- If `mobileVal == desktopVal` → collapse to static: `rem-calc(X)` for px, plain number for unitless. Never leave `fluid(X, X)`.
- Use tokens when available (`$sand`, `$serif-font-family`, etc.).
- Keep default `md → xl` window. Only override with explicit reason.

### Step 5 — LAYOUT edits

- Invert: move desktop structural props into `@media (width >= $md)`. Base = mobile.
- MQ contains **only** differing structural properties. No font-sizes/padding/gap inside `@media`.
- If markup wrapper needed → ask user first.

### Step 6 — Verify

1. Read `.env` for `LOCAL_URL`.
2. Always take a **375px screenshot** → `screenshots/{slug}-responsive-375.png`. Take a **768px screenshot** only if you added at least one `@media` block → `screenshots/{slug}-responsive-768.png`.
   ```
   mkdir -p screenshots/
   playwright-cli run-code "async page => { await page.locator('.{slug}-section').screenshot({ path: '...' }); }"
   playwright-cli close  // at end
   ```
3. Compare: 375 = Figma mobile, 768 = clean flip (if taken), no overflow.
4. Iterate SCSS up to **2 rounds**. Surface remaining diffs after round 2 rather than iterating further.

### Step 7 — Report

Screenshot(s). No text unless something needs attention — max 3 flag bullets for issues only.

## Constraints

**Scope**: Only edit `blocks/{slug}/{slug}.scss`. Never touch:
- Wiring files (`index.php`, `header.php`, `footer.php`, `src/sass/style.scss`)
- Config partials (`src/sass/**`)
- Other blocks (`blocks/*/`)

**Buttons**: Never add/edit `.btn*` rules (owned by `_buttons.scss`). No block-scoped `.btn` overrides. Flag button sizing diffs in report.

**Code style**:
- Logical properties only (`margin-block-end`, `inline-size`, not `margin-bottom`, `width`)
- Images → `assets/images/`, not block folder
- `@media` scalar form: `width >= $md`, not `map.get($grid-breakpoints, 'md')`
- `#{…}` interpolation required inside CSS `min()`/`max()` for Sass compatibility

## Gotchas

- **Container padding** (`$container-padding-x: fluid(20, 40)`) is site-wide. Don't match Figma p-16 by editing the block.
- **Dead legacy BEM rules**: Old blocks may still have `__button-label` / `__button-icon` selectors — flag as dead CSS, don't delete.
- **Top-level selectors in `.scss`**: If `blocks/{slug}/{slug}.scss` has rules outside the `.{slug}-section` wrapper, flag it — fix only if trivial.
- **`LOCAL_URL`** lives in `.env` — grep it, don't assume.
- **`fluid()` scaling window**: widening third/fourth args needs explicit approval.
