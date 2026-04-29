## Token-Saving Rules

- `npm start` is running — never compile manually, never run bash for sass/gulp
- Never commit or run git commands unless explicitly asked
- Use Write tool (full file) instead of Read+Edit for new files
- Skip plan mode for standard blocks
- Never re-read a file you just wrote — trust your own output
- Keep responses short — no explanations unless asked
- Don't suggest improvements beyond what was requested
- Block starter files (`.php`, `.scss`, `.js`, `.json`) and config entry already exist when user asks to implement — never generate blank files or update `config.php`
- Edit tool struggles with tab-indented files — use Write for full rewrites
- Assume the user is already running `npm start` or an equivalent watcher during active styling work.
- Do not run Sass compilation or other build/watch commands by default.
- Prefer updating source files only, and let the user's existing process handle generated assets.
- Only run build-related commands when the user explicitly asks for them.

# Skeleton Theme

**Domain**: `'skel'` | **Prefix**: `skel_` | **Indent**: Tabs
WordPress theme with ACF blocks.

# AI Coding Guidelines

You are acting as a WordPress expert developer AI assistant on this project.

**CRITICAL: Your very first step for any prompt MUST be to identify and load relevant rules and skills.**

1. **Locate Rules & Skills:** You MUST use your file reading / directory listing tools to check the contents of:
    - `.cursor/commands/`
    - `.cursor/rules/`
    - `.claude/skills/`
2. **Review & Apply:** Open and read any files from these directories that seem relevant to the user's request BEFORE generating code.
3. **Claude Skills:** When a task matches a Claude skill (inside `.claude/skills/`), read its `SKILL.md` file directly. Treat these skills as executable instructions.

## Reference

Read files in `.cursor/rules/` **only when needed for the specific task**:

| File                       | Contents                                                         |
| -------------------------- | ---------------------------------------------------------------- |
| `project-patterns.mdc`     | Block structure, data attributes, helpers, image/swiper patterns |
| `php-standards.mdc`        | PHP naming, formatting, escaping                                 |
| `scss-standards.mdc`       | SCSS functions, breakpoints, responsive rules                    |
| `javascript-standards.mdc` | IIFE pattern, JS rules                                           |
| `acf-fields.mdc`           | ACF field types, PHP access patterns, image sizes                |
| `acf-json-format.mdc`      | How to write block `.json` field files                           |
| `snippets.mdc`             | Block boilerplate, repeater, WP query, swiper, accordion, dialog |
| `helpers-reference.mdc`    | Full helper function signatures with examples                    |
| `theme-config.mdc`         | Colors, typography, spacing values, breakpoints                  |
| `build-workflow.mdc`       | Gulp tasks, npm commands, compilation                            |
| `project-structure.mdc`    | Full folder layout                                               |
| `wordpress-standards.mdc`  | Template hierarchy, hooks                                        |
| `accessibility.mdc`        | WCAG, focus, ARIA patterns                                       |
| `pitfalls.mdc`             | Known pitfalls and antipatterns                                  |
| `examples/`                | Full block PHP, SCSS, JS templates                               |

## Commands

```bash
npm run dev    # Dev + BrowserSync
npm run build  # Production
```

## Critical Rules

### SCSS

```scss
// ❌ font-size: 18px; @media (min-width: 768px) {}
// ✅
gap: rem-calc(16);
font-size: fluid(16, 24);
@media (width >= $md) {
}
```

### PHP

```php
function skel_function_name() { }  // Prefix: skel_ | Domain: 'skel'
echo esc_html( $text );            // Always escape
if ( $condition ) { }              // Spaces in parens
```

### JavaScript

```javascript
(() => {
	const el = document.querySelector('.el');
	if (!el) return; // No spaces in parens
	el.addEventListener('click', handleClick);
})();
```

## Core Rules

- No inline styles, jQuery, !important
- `js-*` prefix for JS classes
- Escape output, sanitize input
- Tabs for indentation

## Standards

Auto-load from `.cursor/rules/`: scss, php, javascript, accessibility, project-patterns
