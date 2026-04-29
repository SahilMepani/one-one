# Swiper Standards — Transfer Notes for Another Claude

> **To the receiving Claude:** This document describes the swiper coding conventions used in the source project. Please update the target project's `.cursor/` folder so these rules apply there too. The primary action is to create/replace `.cursor/rules/swiper-standards.mdc` with the content in §A, and update the swiper variant inside `.cursor/rules/examples/js-module-template.js` as shown in §B.
>
> Adapt `{block}` and any project-specific paths (e.g. `src/js/swiper-init.js`, `src/sass/partials/...`) to match the target project's structure. Do **not** copy literal block names — they're placeholders.

---

## Project context you need to know

- The source project uses **Swiper 12.x** exposed as a global `window.Swiper` (loaded once from `src/js/swiper-init.js`).
- Modules (Navigation, Pagination, Autoplay, FreeMode, Scrollbar, A11y, EffectFade, Thumbs) are registered globally via `Swiper.use([...])` in that init file — **no per-block module imports**.
- Block SCSS rules are always nested inside an outer `.{block}-section` wrapper; child classes are plain (`.slide`, `.controls`, `.card`) — **no BEM `__` prefixes**.
- Navigation wrapper is always `.swiper-navigation`; buttons are always `.swiper-button-prev` / `.swiper-button-next`. Never block-specific nav class names.
- Global pagination/nav styles live in:
    - `src/sass/partials/js-plugins/_swiper-custom.scss` (pagination + scrollbar, including directional fill animation)
    - `src/sass/partials/template-parts/_swiper-navigation.scss` (nav buttons)

If the target project differs (different paths, different Swiper loader), keep the **rules** the same and adjust path references only.

---

## A. File to create/replace: `.cursor/rules/swiper-standards.mdc`

Write this file verbatim (update paths if needed):

````markdown
---
description: Swiper slider standards - HTML structure, JS init pattern, SCSS styling, navigation/pagination, responsive destroy/init, accessibility
globs: 'blocks/**/*.{php,js,scss}'
alwaysApply: false
---

# Swiper Standards

This project uses **Swiper 12.x** via a global `window.Swiper` (loaded from `src/js/swiper-init.js`). Modules (Navigation, Pagination, Autoplay, FreeMode, Scrollbar, A11y, EffectFade, Thumbs) are registered globally — no per-block imports needed.

---

## 1. HTML Structure (PHP partial)

```php
<section class="{block}-section">
	<div class="container">
		<!-- Slider -->
		<div class="{block}-slider swiper">
			<div class="swiper-wrapper">
				<div class="slide swiper-slide">
					<!-- slide content -->
				</div>
			</div>
		</div>

		<!-- Controls (outside .swiper so they aren't clipped by overflow:hidden) -->
		<div class="controls">
			<!-- Add swiper-pagination-line for bar shape; directional animation is automatic -->
			<div class="swiper-pagination swiper-pagination-line"></div>
			<div class="swiper-navigation">
				<button type="button" class="swiper-button-prev" aria-label="Previous slide">
					<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				<button type="button" class="swiper-button-next" aria-label="Next slide">
					<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</div>
		</div>
	</div>
</section>
```

### Key points

- The slider container gets **two classes**: `{block}-slider` (JS hook) and `swiper` (Swiper core).
- Each slide gets **two classes**: `slide` (styling — scoped by nesting inside `.{block}-section`) and `swiper-slide` (Swiper core).
- Child class names are **plain** (`.slide`, `.controls`, `.card`) — not BEM (`{block}__slide`). Uniqueness comes from nesting inside `.{block}-section` in SCSS.
- Navigation/pagination elements sit **outside** the `.swiper` wrapper to avoid `overflow: hidden` clipping.
- Navigation wrapper is always `.swiper-navigation` — **never** use block-specific nav wrapper classes.
- Navigation buttons are always `.swiper-button-prev` and `.swiper-button-next` — **never** use block-specific button classes.
- Buttons are `<button type="button">` with `aria-label`.
- SVG icons use `aria-hidden="true"` and `stroke="currentColor"`.

---

## 2. JS Init Pattern

```javascript
(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const sliders = document.querySelectorAll('.{block}-slider');

	sliders.forEach((el, i) => {
		const swiperClass = `{block}-slider-${i}`;
		el.classList.add(swiperClass);

		const section = el.closest('.{block}-section');
		const prevBtn = section?.querySelector('.swiper-button-prev');
		const nextBtn = section?.querySelector('.swiper-button-next');
		const pagination = section?.querySelector('.swiper-pagination');
		const scrollbar = section?.querySelector('.swiper-scrollbar');

		if (prevBtn) prevBtn.classList.add(`${swiperClass}-prev`);
		if (nextBtn) nextBtn.classList.add(`${swiperClass}-next`);
		if (pagination) pagination.classList.add(`${swiperClass}-pagination`);
		if (scrollbar) scrollbar.classList.add(`${swiperClass}-scrollbar`);

		const slides = el.querySelectorAll('.swiper-slide');
		if (slides.length <= 0) return;

		// Single slide — just mark active, no Swiper needed
		if (slides.length === 1) {
			slides.forEach(slide => slide.classList.add('swiper-slide-active'));
			return;
		}

		const swiper = new Swiper(`.${swiperClass}`, {
			slidesPerView: 'auto',
			spaceBetween: 16,
			speed: 500,
			grabCursor: true,
			navigation: {
				prevEl: `.${swiperClass}-prev`,
				nextEl: `.${swiperClass}-next`
			},
			pagination: {
				el: `.${swiperClass}-pagination`,
				clickable: true,
				type: 'bullets'
			}
		});

		// Directional animation — bind AFTER init using snapIndexChange
		// (slideChange + activeIndex is unreliable with slidesPerView: 'auto')
		if (pagination || scrollbar) {
			let prevSnap = swiper.snapIndex;

			swiper.on('snapIndexChange', () => {
				const direction =
					swiper.snapIndex >= prevSnap ? 'forward' : 'backward';

				if (pagination) {
					pagination.setAttribute('data-direction', direction);
				}

				if (scrollbar) {
					scrollbar.setAttribute('data-direction', direction);
				}

				prevSnap = swiper.snapIndex;
			});
		}
	});
})();
```

### Rules

- **Guard** — always check `typeof Swiper === 'undefined'` first.
- **Unique selectors** — append `-${i}` index to the slider class so multiple instances on the same page don't collide.
- **Parent lookup** — use `el.closest('.{block}-section')` to scope nav/pagination lookups.
- **Single slide bailout** — if only 1 slide, mark it active and skip Swiper instantiation.
- **No module imports** — Swiper + modules are global; just call `new Swiper(...)`.
- **IIFE** — wrap everything in `(() => { ... })()`.
- **Direction handler** — bind `snapIndexChange` on the instance **after** init (not in the config `on` object); `slideChange`/`activeIndex` is unreliable with `slidesPerView: 'auto'`.

---

## 3. Responsive Destroy / Init (Mobile-Only Slider)

When a slider should only be active below a breakpoint, use this pattern:

```javascript
(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const BP = 1024; // breakpoint in px
	const sliders = document.querySelectorAll('.{block}-slider');

	sliders.forEach((el, i) => {
		const swiperClass = `{block}-slider-${i}`;
		el.classList.add(swiperClass);

		let swiperInstance = null;

		function initSwiper() {
			if (swiperInstance) return;
			swiperInstance = new Swiper(`.${swiperClass}`, {
				slidesPerView: 'auto',
				spaceBetween: 24,
				speed: 500,
				grabCursor: true
			});
		}

		function destroySwiper() {
			if (!swiperInstance) return;
			swiperInstance.destroy(true, true);
			swiperInstance = null;
		}

		function handleResize() {
			if (window.innerWidth >= BP) {
				destroySwiper();
			} else {
				initSwiper();
			}
		}

		handleResize();
		window.addEventListener('resize', handleResize);
	});
})();
```

- Call `destroy(true, true)` to clean up DOM and event listeners.
- Track the instance in a `let` so you can null-check before init/destroy.

---

## 4. SCSS Rules

### Slide sizing

Use `slidesPerView: 'auto'` in JS and set slide width via SCSS (all rules nested inside `.{block}-section`):

```scss
.{block}-section {
	.slide {
		inline-size: fluid(280, 400);  // responsive slide width
		max-inline-size: 95%;
	}
}
```

### Flex/grid parent fix

If the `.swiper` sits inside a flex or grid container, add:

```scss
.{block}-section {
	.{block}-slider {
		min-inline-size: 0;
	}
}
```

Without this, `min-inline-size: auto` prevents the slider from shrinking and breaks scrolling.

### Navigation styling

Global navigation styles live in `src/sass/partials/template-parts/_swiper-navigation.scss`. Wrap nav buttons in a `.swiper-navigation` container to inherit these styles:

- 44×44px circular buttons, `all: unset` base
- Disabled state: `opacity: 0.5; pointer-events: none`
- Hover micro-animations on SVG arrows
- Style variants: `.style-floating` (absolute positioned), `.style-dark` (dark background)

### Pagination styling

Global pagination styles live in `src/sass/partials/js-plugins/_swiper-custom.scss`. Three classes are available:

| Class                    | Look                | When to use              |
| ------------------------ | ------------------- | ------------------------ |
| `swiper-pagination-dot`  | Circular dots       | Compact pagination       |
| `swiper-pagination-line` | Equal-width bars    | Default for most sliders |
| `swiper-scrollbar`       | Draggable scrollbar | Long carousels           |

### Directional animation (automatic)

Forward/backward fill animation is **applied by default** to all `swiper-pagination-line`, `swiper-pagination-dot`, and `swiper-scrollbar` elements. No extra class needed.

How it works:

- Forward swipe: active bullet fills **left → right**, previous bullet empties **right → left**
- Backward swipe: directions reverse
- The JS `snapIndexChange` handler sets `data-direction` on the pagination/scrollbar container (see §2)
- The handler is bound on the Swiper instance **after** init, not in the config `on` object

Color overrides — the global animation uses `#000` for the fill. Override per-block in the block's SCSS (nested inside `.{block}-section`):

```scss
.{block}-section {
	.swiper-pagination .swiper-pagination-bullet {
		background-color: $your-track-color;

		&::after {
			background-color: $your-fill-color;
		}
	}
}
```

### Custom overrides

Block-specific pagination/nav tweaks go in the block's own SCSS file, not in the global partials.

---

## 5. Accessibility Checklist

- [ ] Navigation buttons have `aria-label` describing direction ("Previous slide", "Next slide")
- [ ] SVG icons have `aria-hidden="true"`
- [ ] Buttons are `<button type="button">`, not `<div>` or `<a>`
- [ ] The `A11y` module is registered globally (already done in `swiper-init.js`)
- [ ] Each slide uses semantic markup (`<article>`, `<figure>`, etc.) where appropriate

---

## 6. Available Modules (registered globally)

| Module     | Use case                       |
| ---------- | ------------------------------ |
| Navigation | Prev/next arrow buttons        |
| Pagination | Bullets, fraction, progressbar |
| Autoplay   | Auto-advance slides            |
| FreeMode   | Momentum-based free scrolling  |
| Scrollbar  | Draggable scrollbar indicator  |
| A11y       | ARIA attributes & keyboard nav |
| EffectFade | Crossfade transition           |
| Thumbs     | Thumbnail gallery syncing      |

To enable a new module, uncomment it in `src/js/swiper-init.js` and add it to the `Swiper.use([])` array.

---

## 7. Common Options Reference

```javascript
{
	slidesPerView: 'auto',    // let CSS control width (preferred)
	spaceBetween: 16,         // gap in px
	speed: 500,               // transition ms
	grabCursor: true,         // shows grab cursor on hover
	loop: false,              // avoid unless design requires it (duplicates DOM)
	autoplay: {
		delay: 5000,
		disableOnInteraction: true,
		pauseOnMouseEnter: true
	},
	breakpoints: {            // use sparingly — prefer CSS fluid() for sizing
		768: { slidesPerView: 2 },
		1024: { slidesPerView: 3 }
	}
}
```

### Prefer `slidesPerView: 'auto'` over numeric values

Set slide `inline-size` in SCSS with `fluid()` — this gives smooth responsive scaling instead of jarring breakpoint jumps. Only use numeric `slidesPerView` + `breakpoints` when the design explicitly requires fixed column counts.
````

---

## B. File to update: `.cursor/rules/examples/js-module-template.js` (swiper variant)

At the bottom of that template there's a commented-out "SWIPER VARIANT" block. Replace it so the example matches the init pattern above (unique selectors via `-${i}`, parent-scoped lookup via `.closest()`, single-slide bailout, `slidesPerView: 'auto'`, and the `snapIndexChange` direction handler). Use the §2 JS snippet as the body — keep it wrapped in `/* ... */` so it remains a reference variant rather than active code.

Do **not** change the non-swiper part of the template.

---

## C. What to verify in the target project before applying

1. Swiper is loaded as a global `window.Swiper` (not imported per-block). If the target uses per-file imports, the rules in §2 about "no module imports" and the `typeof Swiper === 'undefined'` guard need to be rewritten for the target's loader.
2. The global navigation and pagination partials exist (or create equivalents). If the target doesn't have them, the rule can still stand — just note that block SCSS will need to style nav/pagination locally until globals are added.
3. The target's block wrapper convention is `.{block}-section`. If it uses a different outer-wrapper convention, rename throughout §1, §2, §3, §4 consistently.
4. The target uses a `fluid()` SCSS helper for responsive sizing. If not, replace `fluid(280, 400)` examples with the target project's equivalent (e.g. `clamp()`).

---

## D. Nothing else needs to change in `.cursor/`

No other `.cursor/` rules reference swiper specifics. `project-patterns.mdc`, `pitfalls.mdc`, `javascript-standards.mdc`, and `snippets.mdc` either point at `swiper-standards.mdc` or are swiper-agnostic — leave them alone unless you spot a direct contradiction.

---

## E. Swiper references inside the `/acf-block-from-figma` skill

The source project also has a Claude Code skill at `.claude/skills/acf-block-from-figma/SKILL.md` that walks through Figma → block conversion. If the target project has an equivalent skill, mirror these swiper references into it. If it doesn't, **skip this section** — the `.cursor/rules/swiper-standards.mdc` file from §A is still authoritative.

### E1. Where swiper is mentioned in the skill

The skill refers to swiper in five places:

1. **Design-analysis step (≈ line 37)** — while noting interactive patterns in the Figma file:

    > "Also note any interactive patterns in the design (toggles, accordions, sliders, modals) — these will require both ARIA attributes in Step 3 markup and JavaScript in Step 5."

2. **Rules-index step (≈ line 62)** — when listing which `.cursor/rules/*.mdc` files to consult:

    > "- `.cursor/rules/snippets.mdc` — block patterns, repeater/link/image snippets, JS patterns (swiper, accordion, dialog)"

3. **JS-assessment step (≈ line 195)** — deciding whether the block needs JS at all:

    > **Requires JS:** Swiper slider, accordion, tab switching, dialog, counter/scroll animations
    > **Does NOT require JS:** Static grids, text/image layouts, pure CSS layouts

4. **JS-pattern step (≈ lines 202–239)** — the skill inlines a **simplified starter snippet** (intentionally shorter than `swiper-standards.mdc`; it skips nav/pagination wiring and the directional animation handler):

    ````markdown
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
    			slides.forEach(slide =>
    				slide.classList.add('swiper-slide-active')
    			);
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
    - If prev/next buttons or pagination exist, scope them with per-instance classes
    ````

5. **Final checklist (≈ line 352)** — the "JS" section of the completion checklist:
    > `- [ ] If slider — Swiper init implemented and \`min-inline-size: 0\` on \`.swiper\` in SCSS`

### E2. What to update in the target skill

If the target project's skill has matching sections, mirror the text above in the same spots. Key things to get right:

- **Keep the skill snippet simpler than `swiper-standards.mdc`.** The skill's job is a fast starter; detail lives in the `.cursor` rule. Don't copy the full nav/pagination/`snapIndexChange` pattern into the skill — just the minimal init. If a block needs nav/pagination, the skill should defer to `swiper-standards.mdc`.
- **Use `{slug}` as the placeholder** inside the skill (matching the skill's existing convention), even though `swiper-standards.mdc` uses `{block}`. They refer to the same thing; keep each file consistent with its own surrounding text.
- **The flex/grid parent note is worded two ways across the two files.** `swiper-standards.mdc` §4 says `min-inline-size: 0` on the `.{block}-slider`; the skill phrases it as `min-inline-size: 0` on `.swiper`. Both are valid because the slider element carries both classes (`{block}-slider swiper`). Don't "fix" one to match the other — leave each file as-is.
- **Checklist item** — keep the exact wording: `If slider — Swiper init implemented and min-inline-size: 0 on .swiper in SCSS`.
- **Rules-index entry** — if the target has a snippets rule file, keep the parenthetical `(swiper, accordion, dialog)` so users know where to find JS patterns.

### E3. If the target project has no equivalent skill

Do nothing for §E. The `.cursor/rules/swiper-standards.mdc` file in §A is the authoritative source; the skill is just a convenience entry-point for Figma-to-block workflows.
