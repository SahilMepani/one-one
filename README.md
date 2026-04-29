# Skeleton - WordPress Theme

A responsive custom WordPress theme built with vanilla JavaScript, SCSS, and PHP.

## Initial Setup

1. [Download WordPress](https://wordpress.org/latest.zip) and install it on your local server
2. [Download the Theme](https://github.com/SahilMepani/skeleton/archive/refs/heads/blocks.zip) and move it inside `wp-content/themes/`
3. Run `node -v` to check if you have Node installed. If not, [Download Node](https://nodejs.org/en/)
4. Install packages and start watching:

```powershell
cd /path/to/your/wp-content/themes/skeleton
npm i
npm start
```

## Theme Activation

1. Rename the theme folder to match your project name
2. Create `screenshot.png` [1200x900]px at the root
3. Create `favicon.png` and `favicon.ico` at the root
4. Update theme name and details in `src/sass/style.scss`
5. Run `npm start` and activate the theme

---

## VS Code - Formatting

1. **Install extensions**

- EditorConfig for VS Code
- ESLint
- Formatting Toggle
- PHP Sniffer & Beautifier
- PostCSS Sorting
- Prettier - Code Formatter
- Stylelint
- Auto Align
- Intellicode
- PHP Intelephense
- SASS (.sass only)
- SCSS IntelliSense
- WordPress Hooks Intellisense

1. **Install PHPCS package in your machine**

```bash
# phpcs
composer global require squizlabs/php_codesniffer
# wpcs and others
composer g require --dev automattic/vipwpcs dealerdirect/phpcodesniffer-composer-installer
```

1. \*\*Add the PHPCS to the global PATH(environment variable) on your system

Windows - PowerShell**
Most probably your PHPCS executable files will be stored in the below folder. But confirm below adding it to the path. **Take a backup of existing environment variables below running the command please\*\*

```powershell
[System.Environment]::SetEnvironmentVariable("PATH", $env:PATH + ";C:\Users\YOUR_USERNAME\AppData\Roaming\Composer\vendor\bin", [System.EnvironmentVariableTarget]::User)
```

To verify that the PATH variable has been set correctly, type:

```
echo $Env:PATH
```

**Mac - Terminal**

```powershell
# open the file
nano ~/.bash_profile
# add the path
export PATH="$PATH:/User/YOUR_USERNAME/.composer/vendor/bin"
# after saving and closing the file
source ~/.bash_profile
```

Verify the changes

```powershell
env
```

**Add the following code in your settings.json file**

```php
  "files.trimTrailingWhitespace": true,
  "editor.trimAutoWhitespace": true,
  "editor.useTabStops": true,
  "javascript.format.insertSpaceAfterOpeningAndBeforeClosingNonemptyBrackets": true,
  "javascript.format.insertSpaceAfterOpeningAndBeforeClosingNonemptyParenthesis": true,
  "javascript.format.insertSpaceAfterOpeningAndBeforeClosingNonemptyBraces": false,
  "javascript.preferences.quoteStyle": "single",
  "css.lint.duplicateProperties": "warning",
  "css.lint.ieHack": "warning",
  "css.lint.zeroUnits": "warning",
  "html-css-class-completion.enableFindUsage": true,
  "html-css-class-completion.enableEmmetSupport": true,
  "html-css-class-completion.enableScssFindUsage": true,
  "editor.insertSpaces": false,
  "editor.indentSize": "tabSize",
  "autoalign.minSeparationLeft": 1,
  "autoalign.columnWidth": 1,
  "autoalign.moveableItems": [
    "=>",
    "=",
    ":",
    "+=",
    "-="
  ],
  "[php]": {
    "editor.quickSuggestions": {
      "strings": true
    },
    "editor.defaultFormatter": "valeryanm.vscode-phpsab",
    "editor.formatOnSave": true,
    "editor.formatOnPaste": true,
    "editor.tabSize": 4
  },
  "[html]": {
    "editor.defaultFormatter": "vscode.html-language-features",
    "editor.formatOnSave": false
  },
  // Set Prettier as default formatter
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  // Only format if config file found in project
  "prettier.requireConfig": true,
  // Auto format on save
  "editor.formatOnSave": true,
  "diffEditor.ignoreTrimWhitespace": false,
  // disabled vscode default validation
  "css.validate": false,
  "scss.validate": false,
  "javascript.validate.enable": false,
  // Run stylelint on save
  "editor.codeActionsOnSave": {
    "source.fixAll.stylelint": "explicit"
  },
  "stylelint.validate": [
    "css",
    "postcss",
    "scss"
  ],
  "editor.formatOnPaste": true,
  "editor.formatOnType": true,
  "intelephense.stubs": [
    "apache",
    "bcmath",
    "bz2",
    "calendar",
    "com_dotnet",
    "Core",
    "ctype",
    "curl",
    "date",
    "dba",
    "dom",
    "enchant",
    "exif",
    "FFI",
    "fileinfo",
    "filter",
    "fpm",
    "ftp",
    "gd",
    "gettext",
    "gmp",
    "hash",
    "iconv",
    "imap",
    "intl",
    "json",
    "ldap",
    "libxml",
    "mbstring",
    "meta",
    "mysqli",
    "oci8",
    "odbc",
    "openssl",
    "pcntl",
    "pcre",
    "PDO",
    "pdo_ibm",
    "pdo_mysql",
    "pdo_pgsql",
    "pdo_sqlite",
    "pgsql",
    "Phar",
    "posix",
    "pspell",
    "random",
    "readline",
    "Reflection",
    "session",
    "shmop",
    "SimpleXML",
    "snmp",
    "soap",
    "sockets",
    "sodium",
    "SPL",
    "sqlite3",
    "standard",
    "superglobals",
    "sysvmsg",
    "sysvsem",
    "sysvshm",
    "tidy",
    "tokenizer",
    "xml",
    "xmlreader",
    "xmlrpc",
    "xmlwriter",
    "xsl",
    "Zend OPcache",
    "zip",
    "zlib",
    "wordpress"
  ],
  // PHPCS
  "phpsab.standard": "WordPress",
  "intelephense.environment.includePaths": [
    // point to any folder in your drive and add the ACF Pro plugin inside it
    "C:\\Users\\SAHIL\\Desktop\\intelephense"
  ],
```

Change the directory structure for some of the settings above to match yours.

---

## SCSS Rules

### Never Use Raw px Values

```scss
// ❌ NEVER
.component {
	font-size: 18px;
	padding: 24px;
}

// ✅ Use rem-calc() for fixed values
.component {
	gap: rem-calc(16);
	border-radius: rem-calc(8);
}

// ✅ Use fluid() for responsive values. If you don't have the mobileValue then keep its value 0
.component {
	font-size: fluid(16, 18);
	padding: fluid(16, 24);
}
```

### Always Use Mobile-First Breakpoints

```scss
// ❌ NEVER use values for breakpoint
@media (min-width: 768px) {
}
@media (width < $md) {
}

// ✅ ALWAYS use $grid-breakpoints map and custom function alreadt defined
@media (width >= $md) {
}
@media (width >= $lg) {
}
```

---

## JavaScript

All JS files are in `/src/js/plugins` and `/src/js/custom` folders and compile to `/js/plugins.js` and `/js/custom.js`.

- **Plugin Code** - Create files in `/src/js/plugins`
- **Custom Code** - Create files in `/src/js/custom`

Files automatically compile - no manual imports needed.

---

## HTML - Class/ID Naming

**All classes and IDs should be lowercase and separated by a dash.**

Any design that covers the entire viewport(edge to edge) should use the section tag and should be post-fixed using the section. e.g. \*-section. Generally, it sits outside the container.

```html
<section class="hero-slider-section">
	<div class="container"></div>
</section>
```

Any tag inside the section that wraps the container should be called inner-section. It happens in a very rare scenario

```html
<section class="hero-slider-section">
	<div class="inner-section">
		<div class="container"></div>
	</div>
</section>
```

- Classes/IDs targeted by JavaScript should use `js-` prefix (e.g., `js-active`)
- Avoid ID selectors for styling

---

## Animation Data Attributes

### data-inview / data-aos

| Attribute               | Description                                                                 |
| ----------------------- | --------------------------------------------------------------------------- |
| `data-inview`           | Element observed for viewport entry. Sets `data-inview="true"` when visible |
| `data-inview-repeat`    | Removes attribute when element exits viewport                               |
| `data-inview-offset`    | Offset for when element is considered in view (px or %)                     |
| `data-inview-threshold` | Proportion visible before triggering. Default: `0.05` (5%)                  |
| `data-aos`              | Animation type (e.g., `fade-up`). Runs when `data-inview="true"`            |
| `data-aos-stagger-item` | Staggered animations among child elements                                   |

### CSS Custom Properties

```scss
--aos-duration: 1000ms;
--aos-delay: 0ms;
--aos-stagger-interval: 100ms;
--aos-distance: 40px;
```

---

## Toggle Data Attributes

| Attribute           | Description                                          |
| ------------------- | ---------------------------------------------------- |
| `data-toggle-click` | Toggles `js-active` class when clicked               |
| `data-toggle-group` | Groups elements - only one has `js-active` at a time |
| `data-toggle-link`  | Links elements to toggle `js-active` together        |
| `data-toggle-hover` | Toggles `js-active` on hover                         |

### Usage Examples

```html
<!-- Click toggle with group -->
<div data-toggle-click="example" data-toggle-group="group1"></div>

<!-- Linked elements -->
<div data-toggle-click="example"></div>
<div data-toggle-link="example"></div>

<!-- Hover toggle -->
<div data-toggle-hover="example"></div>
<div data-toggle-link="example"></div>
```

---

## File Structure

```
skeleton/
├── acf-blocks/           # ACF block templates
├── functions/            # PHP function files
├── src/
│   ├── js/
│   │   ├── custom/       # Custom JS files
│   │   └── plugins/      # Third-party plugins
│   └── sass/
│       ├── partials/
│       │   ├── config/   # colors, maps, variables
│       │   ├── mixins/   # breakpoints, rem-calc, fluid
│       │   ├── components/
│       │   └── acf-blocks/
│       └── style.scss
├── js/                   # Compiled JS
├── template-parts/
├── functions.php
└── style.css             # Compiled CSS
```

---

## AI Rules

Configuration files for AI coding assistants:

- `.cursor/rules/` - Cursor IDE rules (SCSS, JS, PHP, accessibility)
- `.claude/skills/design-patterns.md` - Comprehensive patterns guide
- `AGENTS.md` - AI agent guidelines

---

## Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
