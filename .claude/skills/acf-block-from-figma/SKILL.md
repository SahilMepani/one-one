---
name: acf-block-from-figma
description: Translates Figma designs into WordPress ACF block code (PHP, SCSS, JSON) following the Skeleton theme conventions. Only invoke when explicitly called via /acf-block-from-figma.
metadata:
    mcp-server: figma, figma-desktop
---

# Implement Design — ACF Block Workflow

Translates a Figma node into PHP + SCSS + JSON for the Skeleton (skel) theme. Output is never React or Tailwind. **General SCSS / PHP / JS / accessibility / ACF JSON rules already live in `.cursor/AGENTS.md` (always loaded) and `.cursor/rules/*.mdc` (read on demand) — this skill covers only block-specific workflow.**

## Prerequisites

Figma MCP connected. Block exists at `blocks/{slug}/` and is registered in `blocks/config.php` — if not, ask the user to register it first.

## Step 1 — Fetch Figma context

Extract `fileKey` and `nodeId` from the URL (`figma.com/design/:fileKey/...?node-id=1-2` → `1:2`). Call `get_design_context(fileKey, nodeId)` once — it returns the screenshot, layout, typography, colors, and asset URLs. Don't also call `get_screenshot`.

If the response is truncated, fall back to `get_metadata` and fetch children individually.

## Step 2 — Read tokens

**Always read:**

- `src/sass/partials/config/_colors.scss` — 21 lines; needed to match Figma hex values to project tokens

**Read conditionally (only if needed):**

- `src/sass/partials/config/_typography.scss` — read only when the Figma design has custom text styles that don't map to a standard `h1`–`h6` heading class. For most blocks, apply heading classes in markup; no SCSS needed.
- `src/sass/partials/helpers/_miscellaneous.scss` — read only when the Figma design includes micro-components that may already have a helper class: tags/labels (`.text-label`), image hover zoom (`.img-link`), styled underline links (`.text-link`), list resets. Skip for layouts that are straightforward grids or content blocks.
- `.cursor/rules/theme-config.mdc` — read instead of `_typography.scss` when you need the heading fluid() scale to match Figma text styles. Compact 70-line reference covering colors, heading scale, breakpoints, and spacing.

**Do not read** existing `{slug}.{php,scss,json,js}` (boilerplate is identical, just overwrite) or `_variables.scss` (nothing in it applies to standard blocks).

**Read on demand** — fetch from `.cursor/rules/*.mdc` only when the block needs an unfamiliar pattern:
- `swiper-standards.mdc` for sliders — read §2 (JS init) and §4 (SCSS rules); skip the rest
- `snippets.mdc` for standard PHP output patterns and accordion/dialog/toggle
- `acf-json-format.mdc` for complex/rare field types only: group, flexible content, gallery, conditional logic, WYSIWYG, post object, relationship, textarea, number, true/false, oEmbed, select, button group
- `helpers-reference.mdc` if a helper signature is unclear
- `accessibility.mdc` for complex interactive components
- A sibling block file only when the new block clearly mirrors an existing one

> **Token rule:** Match Figma colors / sizes / spacing to existing tokens first. Inline raw values only when no token matches; consider adding to the config partial when the value will be reused.

> **DRY helper class rule:** Before writing any block SCSS, check if the Figma design contains micro-components that look like `.text-label`, `.img-link`, or `.text-link`. If so, read `_miscellaneous.scss` and apply the existing class in markup instead of duplicating the styles. If a new pattern appears in ≥2 blocks or is clearly reusable, add it to the helper file rather than the block file.

> **Dimension fidelity rule:** Preserve explicit Figma frame/text dimensions exactly for layout constraints such as `width`, `max-width`, image height, grid/card size, and fixed gaps. Convert px to `rem-calc()` in SCSS, but do not visually estimate or narrow these values from the screenshot. If the Figma MCP output omits a visible dimension, call `get_metadata` for the specific node before choosing the value.

## Step 3 — Write `{slug}.json`

- Field key prefix: `field_{slug}_{field_name}` — sub-fields: `field_{slug}_{repeater}_{field}`
- Structure: `accordion` (open=1, multi_expand=1) → `tab` → fields
- **Never add Settings/Spacing/Display fields** — auto-injected at registration
- Images: `"return_format": "id"`, `"preview_size": "w200"`
- Links: `"return_format": "array"`
- Repeaters: `"collapsed"` = key of first sub-field
- Repeaters with generic repeated content should use neutral editor labels: label `"Items"` and button `"Add Item"` unless the block needs a more specific content label.
- If a repeater only has one sub-field named `image`, call the repeater `"images"` with label `"Images"` and button `"Add Image"`; do not use `"items"` for image-only repeaters.

## Step 4 — Write `{slug}.scss`

General SCSS rules (logical properties, `fluid()`, mobile-first `@media`, no flex-for-gap) are in AGENTS.md / `scss-standards.mdc`. **Block-specific rules:**

- Import is `@use '../../src/sass/partials/abstracts-blocks' as *;` — fix to `abstracts-blocks` if boilerplate has plain `abstracts`
- **Everything nests inside `.{slug}-section { … }`** — zero top-level selectors. Media queries and modifiers nest too.
- **Plain child class names** (`.card`, `.image`, `.body`) — never BEM-prefixed (`.{slug}__card`). Modifiers keep `--` (`.tag--dark`). No `&__` / `&--` shorthand — write the full class.
- **Never `padding-inline` on `.{slug}-section`** — horizontal gutters come from `.container` globally. Only `padding-block-*` belongs on the section.

## Step 5 — Write `{slug}.php`

General PHP rules (tab indent, escape all output, `'skel'` text domain) are in AGENTS.md / `php-standards.mdc`. **Block-specific:**

**Default data — every field MUST have a fallback** so the block renders without ACF values:

```php
$heading    = get_field( 'heading' )    ?: 'Your Heading Here';
$image_id   = get_field( 'image' )      ?: DEFAULT_THUMBNAIL_ID;
$link       = get_field( 'link' )       ?: array( 'url' => '#', 'title' => 'Learn More', 'target' => '' );
$show_title = get_field( 'show_title' ) ?? true;
$items      = get_field( 'items' )      ?: array(
	array( 'title' => 'Item One', 'description' => 'Description here.' ),
	array( 'title' => 'Item Two', 'description' => 'Description here.' ),
);
```

Use placeholder text from the Figma design where possible.

**Repeater normalization:** keep render data as simple as the markup needs. If a repeater only exists to collect one useful value per row, normalize it before rendering instead of carrying nested row arrays through the loop.

```php
$images = wp_list_pluck( get_field( 'images' ) ?: array(), 'image' );

if ( ! is_array( $images ) || empty( $images ) ) {
	$images = array(
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
	);
}
```

When `$images` has been normalized to image IDs, loop over the IDs directly. Do not recreate `$item['image']` access patterns, temporary `$image_html` variables, or placeholder branches unless the design explicitly requires a separate placeholder state.

**Section wrapper:** Use the exact boilerplate from `project-patterns.mdc` §Block Template Structure. `.container` MUST be the first and only direct child of `<section>`. Never place siblings next to it.

**Image output:** See `project-patterns.mdc` §Image Pattern. Echo attachment images directly when the image ID already has a fallback.

**Link output:** See `snippets.mdc` §ACF Link Button for the standard pattern.

**Helpers:** `skel_get_svg('icon-name')`, `skel_get_svg_content('filename')`, `DEFAULT_THUMBNAIL_ID`, `wp_kses_post()`. Full list in `helpers-reference.mdc`.

## Step 5.5 — Write `{slug}.js` (only if needed)

Needs JS: swiper, accordion, tab, dialog, scroll/counter animation. Otherwise leave the auto-generated stub as `(() => { })();`.

**Swiper init:** Use `swiper-standards.mdc` §2 as the JS init pattern (includes scoped navigation/pagination wiring and single-slide bailout). Adapt options to the design. For `min-inline-size: 0` and SCSS slide sizing rules, see `swiper-standards.mdc` §4.

## Step 5.6 — Load on the "claude" preview page

Write the slug (no quotes, no newline) to `blocks/.claude-preview-pending`. The WP `init` hook in `functions/claude-preview.php` reads it and inserts the block on the "claude" page on the next request. The trigger file is **not consumed** — it persists across requests, so you can reload `/claude/` freely without rewriting it.

## Step 6 — Visual verification (opt-in)

**Default: skip.** The block is done after Step 5.6; the user previews in their browser.

**Only run when explicitly asked** ("verify", "screenshot it", "compare against Figma") — the playwright loop costs ~10–15 tool calls per pass.

When asked: invoke `playwright-cli` to navigate to `{preview_url}/claude/` (default `http://one-one.local/claude/`), viewport 1440×900, wait for fonts/images, take an element-scoped screenshot of `.{slug}-section`, save to `screenshots/{slug}-render.png`, compare to the Figma reference, fix and re-shot. Cap at 3 iterations. The trigger file written in Step 5.6 persists, so reloads work without rewriting it.

## Validation Checklist

- [ ] JSON: keys prefixed `field_{slug}_`; no Settings/Spacing/Display; repeater `collapsed` set to first sub-field; images `return_format: id`; links `return_format: array`
- [ ] SCSS: import is `abstracts-blocks`; everything nested inside `.{slug}-section`; plain child class names; no `padding-inline` on the section; Figma colors/typography mapped to tokens where possible
- [ ] PHP: every field has a default/fallback; `.container` is the only direct child of `<section>`; output escaped
- [ ] JS: stub left as empty IIFE OR slider/accordion logic written; `min-inline-size: 0` on `.swiper` if inside flex/grid
- [ ] `blocks/.claude-preview-pending` written with the slug
