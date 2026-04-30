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

**Always read** (values are inlined in SCSS):

- `src/sass/partials/config/_typography.scss`
- `src/sass/partials/config/_colors.scss`

That's it for required reads. **Do not read** existing `{slug}.{php,scss,json,js}` (boilerplate is identical, just overwrite) or `_variables.scss` (nothing in it applies to standard blocks).

**Read on demand** — fetch from `.cursor/rules/*.mdc` only when the block needs an unfamiliar pattern: `swiper-standards.mdc` for sliders, `snippets.mdc` for accordion/dialog/toggle, `acf-json-format.mdc` for non-standard ACF types (group, flexible content, gallery), `helpers-reference.mdc` if a helper signature is unclear, `accessibility.mdc` for complex interactive components. A sibling block file only when the new block clearly mirrors an existing one.

> **Token rule:** Match Figma colors / sizes / spacing to existing tokens first. Inline raw values only when no token matches; consider adding to the config partial when the value will be reused.

## Step 3 — Write `{slug}.json`

- Field key prefix: `field_{slug}_{field_name}` — sub-fields: `field_{slug}_{repeater}_{field}`
- Structure: `accordion` (open=1, multi_expand=1) → `tab` → fields
- **Never add Settings/Spacing/Display fields** — auto-injected at registration
- Images: `"return_format": "id"`, `"preview_size": "w200"`
- Links: `"return_format": "array"`
- Repeaters: `"collapsed"` = key of first sub-field

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

**Section wrapper — keep this exact boilerplate:**

```php
<section
	class="{slug}-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">
	<div class="container">
		<!-- everything from the design lives here -->
	</div>
</section>
```

`.container` MUST be the first and only direct child of `<section>`. Never place siblings next to it.

**Image output:** `wp_get_attachment_image( $image_id, 'w1920', false, array( 'class' => 'img-responsive', 'loading' => 'lazy', 'sizes' => 'auto' ) );`

**Link output:**

```php
<a
	href="<?php echo esc_url( $link['url'] ); ?>"
	target="<?php echo esc_attr( $link['target'] ); ?>"
	<?php echo ( '_blank' === $link['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
	class="btn">
	<span><?php echo esc_html( $link['title'] ?: __( 'Learn More', 'skel' ) ); ?></span>
	<?php if ( '_blank' === $link['target'] ) : ?>
		<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'skel' ); ?></span>
	<?php endif; ?>
</a>
```

**Helpers:** `skel_get_svg('icon-name')`, `skel_get_svg_content('filename')`, `DEFAULT_THUMBNAIL_ID`, `wp_kses_post()`. Full list in `helpers-reference.mdc`.

## Step 5.5 — Write `{slug}.js` (only if needed)

Needs JS: swiper, accordion, tab, dialog, scroll/counter animation. Otherwise leave the auto-generated stub as `(() => { })();`.

**Swiper init** (adapt options to design):

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

		new Swiper(`.${swiperClass}`, {
			slidesPerView: 'auto',
			spaceBetween: 16,
			speed: 500,
			grabCursor: true
		});
	});
})();
```

Add `min-inline-size: 0` to `.swiper` when inside a flex/grid parent. For scoped prev/next/pagination, see `swiper-standards.mdc` §2–§3.

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
