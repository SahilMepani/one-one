# Skeleton Theme Documentation

## 1. Project Overview

**Theme Name:** Skeleton
**Version:** 1.0
**Author:** Sahil Mepani
**Description:** A responsive custom WordPress theme designed as a starting point for development. It features a modular architecture, Gulp-based build system, and integration with ACF Blocks.

## 2. Getting Started

### Prerequisites

- **Node.js**: Ensure Node.js is installed. Run `node -v` to check.
- **WordPress**: A local or remote WordPress installation.
- **ACF Pro**: Required for block functionality (path configured in VS Code settings).

### Installation

1.  Download the theme and place it in `wp-content/themes/`.
2.  Navigate to the theme directory in your terminal.
3.  Install dependencies:
    ```bash
    npm i --force
    ```
4.  Start the development server:
    ```bash
    npm start
    ```

### Theme Activation

1.  Rename the theme folder to your project name.
2.  Update `style.css` with your theme details.
3.  Add `screenshot.png` (600x600px), `favicon.png`, and `favicon.ico` to the root.
4.  Activate the theme in WordPress.

## 3. Development Workflow

### Build System (Gulp)

The theme uses Gulp for compiling Sass, bundling JavaScript, linting, and optimizing assets.

**Key Tasks:**

- **`npm start` / `npm run dev`**:
    - Compiles Sass (expanded).
    - Bundles JS (with sourcemaps).
    - Lints JS.
    - Starts BrowserSync for live reloading.
- **`npm run build`**:
    - Compiles Sass (compressed).
    - Runs Autoprefixer and CSSNano.
    - Bundles and uglifies JS.
    - Runs PurgeCSS to remove unused styles.
    - Generates RTL styles.

### File Structure

- **`src/`**: Source files for Sass and JS.
    - `src/sass/`: SCSS partials.
    - `src/js/plugins/`: Vendor/plugin scripts (concatenated to `js/plugins.js`).
    - `src/js/custom/`: Custom scripts (concatenated to `js/custom.js`).
- **`functions/`**: Modular PHP functions included by `functions.php`.
- **`acf-blocks/`**: Directory for custom ACF blocks.
- **`templates/` & `template-parts/`**: Page templates and reusable parts.

## 4. Theme Architecture

### Functions (`functions.php`)

The logic is split into single-purpose files within the `functions/` directory for better maintainability.

**Core Modules:**

- **`enqueue-scripts.php`**: Loads CSS and JS files.
- **`register-nav-menus.php`**: Registers theme menus.
- **`add-image-sizes.php`**: Defines custom image sizes.
- **`add-svg-support.php`**: Enables SVG uploads.
- **`helpers.php`**: Utility functions.
- **`hooks.php`**: Custom action and filter hooks.

### ACF Blocks Integration

The theme has a robust system for managing ACF blocks:

- **`register-acf-blocks.php`**: Registers blocks found in `acf-blocks/`.
- **`create-acf-block-files.php`**: Automates the creation of block files.
- **`delete-unwanted-acf-block-files.php`**: Cleans up unused block files.
- **`allowed-blocks.php`**: Controls which blocks are available in the editor.

### Security & Cleanup

- **`remove-junk-from-head.php`**: Cleans up the `<head>` tag.
- **`disable-auto-embed-script.php`**: Disables oEmbed scripts.
- **`disable-wp-generated-image-sizes.php`**: Prevents generation of default WP image sizes.
- **`security.php`**: Applied in production environment.

## 5. Coding Standards

### CSS (Sass)

- **Units**: Use `rem` for everything except breakpoints and line-heights. Use `rem-calc()` function.
- **Structure**: Partials in `src/sass/partials/`, imported in `src/sass/style.scss`.
- **Naming**: Lowercase with dashes (kebab-case).

### JavaScript

- **Location**:
    - Plugins: `src/js/plugins/`
    - Custom: `src/js/custom/`
- **Naming**: Kebab-case for classes and IDs.
- **Prefixes**: Use `js-` prefix for classes targeted by JavaScript (e.g., `js-active`).

### HTML

- **Semantic Tags**: Use `<section>` for edge-to-edge areas.
- **Classes**: Kebab-case.
- **Comments**: Optional closing comments for complex blocks (e.g., `<!-- .hero-slider-section -->`).

## 6. Features

### Animation System

Uses data attributes for scroll-triggered animations:

- `data-inview`: Triggers when element enters viewport.
- `data-aos`: Specifies animation type (e.g., `fade-up`).
- `data-aos-stagger-item`: For staggered child animations.

### Toggle System

Interactivity via data attributes:

- `data-toggle-click`: Toggles `js-active` on click.
- `data-toggle-group`: Ensures exclusive active state within a group.
- `data-toggle-hover`: Toggles on hover.
