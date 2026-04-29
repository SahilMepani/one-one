---
name: acf-block-from-figma
description: Translates Figma designs into WordPress ACF block code (PHP, SCSS, JSON) following the Skeleton theme conventions. Only invoke when explicitly called via /acf-block-from-figma.
metadata:
    mcp-server: figma, figma-desktop
---

# Implement Design — ACF Block Workflow

## Overview

This skill translates Figma designs into production-ready WordPress ACF block code for the Skeleton (skel) theme. Output is PHP + SCSS + JSON — never React or Tailwind. Follow all steps in order.

## Prerequisites

- Figma MCP server must be connected
- The user provides a Figma URL (e.g. `https://figma.com/design/:fileKey/:fileName?node-id=1-2`)
- The block slug and name must be identified (from conversation context or the Figma frame name)
- Block files already exist at `blocks/{slug}/` with boilerplate (auto-generated from `blocks/blank/`)
- The block is already registered in `blocks/config.php`

If the block does not exist yet, ask the user for the block name, add it to `blocks/config.php`, and let the auto-generation create the boilerplate files before proceeding.

## Required Workflow

**Follow these steps in order. Do not skip steps.**

### Step 1: Get Figma Design Context

Extract `fileKey` and `nodeId` from the Figma URL and run these in parallel:

1. `get_design_context(fileKey, nodeId)` — layout, typography, colors, spacing specs
2. `get_screenshot(fileKey, nodeId)` — visual reference image

Study the design carefully: identify all visual elements, text content, images, spacing, colors, and layout structure.

If the response is too large or truncated, use `get_metadata` first to get the node map, then fetch individual child nodes.

**Save the screenshot** — after `get_screenshot` returns, save the image to `blocks/{slug}/preview.png` using Bash base64 decode:

```bash
echo "<base64_image_data>" | base64 -d > "blocks/{slug}/preview.png"
```

Replace `<base64_image_data>` with the raw base64 string from the tool response (no data URI prefix). This gives each block a design reference snapshot alongside its source files.

### Step 2: Identify Block Slug & Read Existing Files

Determine the block slug from the block name (e.g. "About Bio" → `about-bio`).

Read existing files in parallel:

- `blocks/{slug}/{slug}.php`
- `blocks/{slug}/{slug}.scss`
- `blocks/{slug}/{slug}.json`
- `blocks/{slug}/{slug}.js` — auto-generated JS stub (read before potentially writing)

**Read token partials first** (before any SCSS work) so you reuse existing tokens instead of inlining values:

- `src/sass/partials/config/_typography.scss` — font families, font-size scale, weights, line-heights
- `src/sass/partials/config/_colors.scss` — color tokens (match Figma colors to these first)
- `src/sass/partials/config/_variables.scss` — shared spacing, radii, misc variables

Also read these reference files for theme conventions:

- `.cursor/rules/snippets.mdc` — block patterns, repeater, link, image snippets, JS patterns (swiper, accordion, dialog)
- `.cursor/rules/swiper-standards.mdc` — full Swiper slider standards (HTML, JS init, SCSS, navigation/pagination, accessibility) — consult when the design includes a slider
- `.cursor/rules/acf-json-format.mdc` — JSON field structure
- `.cursor/rules/helpers-reference.mdc` — available helper functions
- `.cursor/rules/theme-config.mdc` — colors, typography scale, breakpoints (critical for Figma color matching)
- `.cursor/rules/examples/acf-block-template.php` — full real-world PHP block example

Understand what boilerplate exists before editing.

> **Token rule:** If Figma uses a color/font-size/spacing that matches an existing token, **use the token**. Only fall back to raw hex/values when no token matches — and in that case consider whether the new value should be added to the config partial as a shared token rather than inlined.

> Also note any interactive patterns in the design (toggles, accordions, sliders, modals) — these will require both ARIA attributes in PHP and JavaScript in Step 5.5.

### Step 3: Write ACF JSON Fields

Edit `blocks/{slug}/{slug}.json` with fields matching the design.

**JSON rules:**

- Key prefix: `field_{block_slug}_{field_name}`
- Sub-fields in repeaters: `field_{slug}_{repeater}_{field}`
- Structure: accordion (open, multi_expand) > tab > fields
- **DO NOT** add Settings/Spacing/Display fields — auto-injected at registration
- Images: `"return_format": "id"`, `"preview_size": "w200"`
- Links: `"return_format": "array"`
- Repeaters: set `"collapsed"` to the key of the first sub-field

### Step 4: Write the SCSS

Edit `blocks/{slug}/{slug}.scss`. The import line already exists in the boilerplate.

> It should be `@use '../../src/sass/partials/abstracts-blocks' as *;` — if you see `abstracts` instead of `abstracts-blocks`, correct it.

**Spacing & value rules:**

| Property           | Format            | Example                          |
| ------------------ | ----------------- | -------------------------------- |
| Most px values     | `fluid(0, value)` | `font-size: fluid(0, 18)`        |
| Margins            | `fluid(0, value)` | `margin-block-end: fluid(0, 16)` |
| Letter-spacing     | Raw `em`          | `letter-spacing: -0.02em`        |
| Line-height        | Unitless ratio    | `line-height: 1.5`               |
| Very small (1-3px) | Raw px OK         | `border: 1px solid`              |

**CSS logical properties (MANDATORY):**

| Physical (NEVER)             | Logical (ALWAYS)                                  |
| ---------------------------- | ------------------------------------------------- |
| `margin-top/bottom`          | `margin-block-start/end`                          |
| `margin-left/right`          | `margin-inline-start/end`                         |
| `padding-top/bottom`         | `padding-block-start/end`                         |
| `padding-left/right`         | `padding-inline-start/end`                        |
| `width` / `height`           | `inline-size` / `block-size`                      |
| `min-width` / `max-width`    | `min-inline-size` / `max-inline-size`             |
| `min-height` / `max-height`  | `min-block-size` / `max-block-size`               |
| `top/bottom/left/right`      | `inset-block-start/end`, `inset-inline-start/end` |
| `border-top/bottom`          | `border-block-start/end`                          |
| `border-left/right`          | `border-inline-start/end`                         |
| `border-radius: TL TR BR BL` | `border-start-start-radius`, etc.                 |

**Structural rule (MANDATORY — non-negotiable):**

ALL CSS for the block MUST live nested inside the outer `.{slug}-section { … }` selector. No top-level selectors outside that wrapper. Every child, descendant, pseudo-class, media query, and modifier is nested under `.{slug}-section`. Uniqueness is achieved via the parent selector, not BEM prefixes.

CSS inside the wrapper may target direct children or any descendant depth. Nothing lives outside `.{slug}-section`.

Minimal skeleton (shape only — use your actual tokens/breakpoints):

```scss
.{slug}-section {
    // base section styles

    .child { /* descendant */
        .grandchild { /* deeper nesting is fine */ }
    }

    .child--modifier { /* full class, not `&--modifier` */ }

    @media (width >= $bp-md) { /* layout flips — still nested */ }
}
```

**Class naming rules:**

- **No BEM `__` separator.** Outer section is `.{slug}-section`. Children use **plain descriptive class names** (`.card`, `.image`, `.body`, `.tag`, `.brand`, `.price`) — NEVER BEM-prefixed like `.{slug}__card`. Uniqueness is achieved via SCSS nesting inside `.{slug}-section`, not via long class prefixes.
- **Modifiers keep `--`:** variant classes still use the `--` suffix (`.tag--dark`, `.tag--sand`, `.btn--primary`). Only the `__` BEM separator is banned.
- **No SCSS `&__` / `&--` shorthand** — write the full modifier class.

**Layout & token rules:**

- **Tokens first** — reuse variables/mixins from `_typography.scss`, `_colors.scss`, `_variables.scss`. Only inline raw values when no token matches. When a new shared value is needed, add it to the config partial, not the block file.
- **Colors:** match Figma colors to `_colors.scss` tokens first; fall back to hex only when no token matches (and consider adding a new token).
- **Typography:** reuse mixins/variables from `_typography.scss` — font families (`$sans-serif-font-family`, `$serif-font-family`), weights, sizes.
- **Mobile-first only** — if an `@media` query is genuinely needed for a layout change, it must be `@media (width >= $bp)`. Never desktop-first (`width < $bp`). Most blocks shouldn't need `@media` at all — prefer `fluid()` for values.
- **`@media` is for layout, not values** — use `fluid(min, max)` to scale font-size, padding, gap, margin. Only reach for `@media` when the layout genuinely restructures (1 col → 3 col, stacked → side-by-side). Nest the media query inside `.{slug}-section` (or inside a nested child) — never at the file root.
- **NO flex for gap-only spacing** — only use `display: flex` for actual row/column layouts
- Vertical spacing between stacked elements: use `margin-block-end` on the element
- Functions: `rem-calc(16)` for fixed values, `fluid(min, max)` for responsive values
- No stylelint directives

### Step 5: Write the PHP

Edit `blocks/{slug}/{slug}.php`. Keep existing boilerplate (preview check, display check, dev options).

**PHP rules:**

- Tab indentation (not spaces)
- Spaces inside parentheses: `if ( $condition ) { }`, `get_field( 'name' )`
- Escape ALL output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Text domain: `'skel'`

**Default/fallback data (CRITICAL) — every variable MUST have a default:**

```php
// Text fields
$heading = get_field( 'heading' ) ?: 'Your Heading Here';
$description = get_field( 'description' ) ?: 'Lorem ipsum dolor sit amet.';

// Images — use DEFAULT_THUMBNAIL_ID constant
$image_id = get_field( 'image' ) ?: DEFAULT_THUMBNAIL_ID;

// Links — provide full array
$link = get_field( 'link' ) ?: array( 'url' => '#', 'title' => 'Learn More', 'target' => '' );

// Repeaters — provide default array with sample items
$items = get_field( 'items' ) ?: array(
    array( 'title' => 'Item One', 'description' => 'Description here.' ),
    array( 'title' => 'Item Two', 'description' => 'Description here.' ),
);

// True/False
$show_title = get_field( 'show_title' ) ?? true;
```

Use actual placeholder text from the Figma design whenever possible.

**Image output — always use `wp_get_attachment_image()`:**

```php
echo wp_get_attachment_image( $image_id, 'w1920', false, array(
    'class'   => 'img-responsive',
    'loading' => 'lazy',
    'sizes'   => 'auto',
) );
```

**Link output:**

```php
if ( is_array( $link ) && $link['url'] ) {
	<a
		href="<?php echo esc_url( $link['url'] ); ?>"
		target="<?php echo esc_attr( $link['target'] ); ?>"
		<?php echo ( '_blank' === $link['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
		class="btn">
		<?php
		$text = ( $link['title'] ) ? $link['title'] : __( 'Read More', 'skel' );
		echo '<span>' . esc_html( $text ) . '</span>';
		if ( '_blank' === $link['target'] ) {
			echo '<span class="sr-only">' . esc_html__( '(opens in a new tab)', 'skel' ) . '</span>';
		}
		?>
	</a>
}
```

**Section wrapper — keep existing boilerplate:**

```php
<section
    class="{slug}-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
    style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
    id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">
```

**Container rule (non-negotiable):** Every block section MUST have `.container` as its **first and only direct child**. Every other element from the design — headings, images, grids, buttons, everything — must be a descendant of `.container`. Never place siblings next to `.container` inside the section, and never omit the `.container` wrapper.

**Corollary — never set `padding-inline` on `.{slug}-section`.** Horizontal gutters are owned by `.container` globally. If the Figma design shows side padding on the section, that spacing is already handled by `.container`'s own `padding-inline`; do not re-declare it on the section. Only `padding-block-*` (top/bottom) belongs on the section.

**Accessibility basics (MANDATORY):**

- Images: descriptive `alt` on informative images, `alt=""` on decorative ones
- Interactive elements (toggles/accordions): `aria-expanded="false"` + `aria-controls` pointing to panel ID
- Icon-only buttons: `<span class="screen-reader-text">Label</span>` inside
- External links: the link boilerplate already includes `(opens in a new tab)` sr-only — keep it
- Heading hierarchy: don't skip levels (`h2` for section headings, `h3` for cards)
- For full patterns: `.cursor/rules/accessibility.mdc`

**Available helpers:**

- `skel_get_svg( 'icon-name' )` — SVG icon from sprite
- `skel_get_svg_content( 'filename' )` — raw SVG from `/images/svg/`
- `skel_get_the_excerpt( 20 )` — truncated excerpt
- `skel_get_text_shorter( $text, 100 )` — shorten text
- `DEFAULT_THUMBNAIL_ID` — fallback image constant
- `wp_kses_post()` — for WYSIWYG/rich text output

### Step 5.5: Write JavaScript (if needed)

Assess from the Figma design and PHP markup whether the block requires JS:

**Requires JS:** Swiper slider, accordion, tab switching, dialog, counter/scroll animations
**Does NOT require JS:** Static grids, text/image layouts, pure CSS layouts

If **not needed**: leave the auto-generated stub as an empty IIFE — `(() => { })();`

If **needed**, write `blocks/{slug}/{slug}.js`. All JS follows the IIFE pattern.

**Swiper slider pattern** (copy this exactly, adapt options to the design):

```js
(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const sliders = document.querySelectorAll('.{slug}-slider');

	sliders.forEach((el, i) => {
		const swiperClass = `{slug}-slider-${i}`;
		el.classList.add(swiperClass);

		const slides = el.querySelectorAll('.swiper-slide');

		if (slides.length <= 0) return;

		if (slides.length === 1) {
			slides.forEach(slide => slide.classList.add('swiper-slide-active'));
			return;
		}

		if (slides.length > 1) {
			new Swiper(`.${swiperClass}`, {
				slidesPerView: 'auto',
				spaceBetween: 16,
				speed: 500,
				grabCursor: true
			});
		}
	});
})();
```

- Add `min-inline-size: 0` to `.swiper` in SCSS when the slider is inside a flex/grid parent
- If prev/next buttons or pagination exist, scope them with per-instance classes — see `.cursor/rules/swiper-standards.mdc` §2 for the full pattern (parent-scoped `.closest()` lookup, `snapIndexChange` direction handler, and the §3 responsive destroy/init variant)

### Step 5.6: Load Block on "Claude" Preview Page

Write the block slug to a trigger file so WordPress auto-inserts it into the "claude" page on the next request:

- Write file: `blocks/.claude-preview-pending`
- Content: just the block slug (e.g. `about-bio`) — no quotes, no newline

An `init` hook in `functions/claude-preview.php` reads this file, updates the "claude" page, then deletes the trigger file. This all happens before the page query runs, so the block renders on the very first visit.

The `data` object is intentionally empty — the PHP template has default/fallback values for all fields.

### Step 6: Visual Verification Loop (MANDATORY)

After writing JSON/PHP/SCSS/JS, verify the rendered output matches the Figma design by screenshotting the live preview page and comparing it side-by-side with the Figma reference. Iterate until it matches.

**1. Navigate to the preview page:**

Use **`{preview_url}/claude/`** (the third argument appended with `/claude/`, or `http://localhost:3000/claude/` by default). The block renders automatically via the `blocks/.claude-preview-pending` trigger file written in Step 5.6.

**2. Screenshot the rendered block via `/playwright-cli`:**

Invoke the `playwright-cli` skill to:

- Navigate to `{preview_url}/claude/`
- Wait for the page to be fully loaded (fonts, images)
- Locate the block by its outer selector `.{slug}-section`
- Take an **element-scoped** screenshot of that selector (not a full-page screenshot)
- Capture at desktop width (1440px) — and also at ~768px if the design has any responsive considerations
- Save to `screenshots/{slug}-render.png`

**3. Compare against the Figma screenshot:**

Place the playwright screenshot next to the `get_screenshot` output from Step 1 and check:

- Layout / structure / element ordering
- Spacing (gaps, margins, padding) — measure proportionally, not pixel-perfect
- Typography (font family, size, weight, line-height, letter-spacing)
- Colors (backgrounds, text, borders)
- Image sizing, aspect ratios, and positioning
- Border radius, shadows, and other decorative details
- Alignment of text and elements within their containers

**4. Iterate:**

If discrepancies are found, edit the SCSS (or PHP markup if structural) to fix them, then repeat steps 2–3.

**Stopping criteria:**

- Stop when the rendered output visually matches the Figma reference with no meaningful discrepancies, OR
- Stop after 3 iterations and report remaining differences to the user for guidance (do not loop indefinitely)

Do not consider the block complete until this verification loop has run at least once and the result is acceptable.

## Validation Checklist

Before marking complete, verify:

- [ ] JSON: All field keys prefixed with `field_{slug}_`
- [ ] JSON: No Settings/Spacing/Display fields (auto-injected)
- [ ] JSON: Repeater `collapsed` set to first sub-field key
- [ ] JSON: Images use `return_format: id`, `preview_size: w200`
- [ ] SCSS: No raw px values (use `fluid()` or `rem-calc()`)
- [ ] SCSS: All logical properties (no physical margin/padding/width/height)
- [ ] SCSS: No `@media` queries
- [ ] SCSS: Import line is `abstracts-blocks` (not `abstracts`)
- [ ] SCSS: No flex used solely for gap spacing
- [ ] SCSS: All rules are nested inside `.{slug}-section { … }` — zero top-level selectors outside the wrapper
- [ ] SCSS: Plain child class names (`.card`, `.image`, `.body`) — no BEM `__` prefixes like `.{slug}__card`
- [ ] SCSS: Modifiers use `--` suffix (`.tag--dark`) — kept, not removed
- [ ] SCSS: No SCSS `&__` / `&--` nesting shorthand
- [ ] SCSS: No `padding-inline` on `.{slug}-section` (horizontal gutters come from `.container`)
- [ ] Tokens: `_typography.scss`, `_colors.scss`, `_variables.scss` read before writing SCSS
- [ ] Tokens: Figma colors matched to existing color tokens where possible (raw hex only as fallback)
- [ ] Tokens: Typography reuses existing font variables/mixins
- [ ] PHP: All fields have default/fallback values
- [ ] PHP: All output escaped (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`)
- [ ] PHP: Tab indentation, spaces inside parentheses
- [ ] PHP: Images via `wp_get_attachment_image()`
- [ ] PHP: Informative images have descriptive alt text; decorative images have `alt=""`
- [ ] JS: Assessed — either meaningful logic written or stub left as empty IIFE
- [ ] JS: If slider — Swiper init implemented and `min-inline-size: 0` on `.swiper` in SCSS
- [ ] `blocks/{slug}/preview.png` saved from Figma screenshot
- [ ] `blocks/.claude-preview-pending` written with block slug
- [ ] Visual verification loop ran via `/playwright-cli` against `{preview_url}/claude/`
- [ ] Element-scoped screenshot saved to `screenshots/{slug}-render.png`
- [ ] Rendered screenshot matches the Figma reference (or remaining diffs reported to the user after 3 iterations)
