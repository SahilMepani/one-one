<?php
/**
 * Template Name: Documentation
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

get_header();
the_content();
?>

<div class="container">

	<div class="row py-5">

		<div class="col-sm-4 sidebar-col">
			<div class="sidebar-inner-block">

				<div class="search-block">

				</div> <!-- .search-block -->

				<ul class="list-unstyled">
					<li><a href="#">1. Dev Handoff</a></li>
					<li><a href="#wp-installation">2. WP Installation</a></li>
					<ul>
						<li><a href="#local">2.1 Local</a></li>
						<li><a href="#development">2.2 Development</a></li>
						<li><a href="#production">2.3 Production</a></li>
					</ul>
					<li><a href="#skeleton-setup">3. Skeleton Setup</a></li>
					<ul>
						<li><a href="#skeleton-installation">3.1 Installation</a></li>
						<li><a href="#skeleton-repo">3.2 Creating Repo</a></li>
					</ul>
					<li><a href="#skeleton-sass">3. Skeleton SASS</a></li>
					<ul>
						<li><a href="#skeleton-installation">3.1 Installation</a></li>
						<li><a href="#skeleton-repo">3.2 Creating Repo</a></li>
					</ul>
					<li><a href="#css-styleguide">4. CSS/SASS Styleguide</a></li>
					<ul>
						<li><a href="#terminology">4.1 Terminology</a></li>
						<li><a href="#css">4.2 CSS</a></li>
						<li><a href="#sass">4.3 SASS</a></li>
					</ul>
					<li><a href="#">Pre Launch</a></li>
					<li><a href="#">Post Launch</a></li>
					<li><a href="#">Theme Usage</a></li>
				</ul> <!-- .list-unstyled -->

			</div> <!-- .sidebar-inner-block -->
		</div> <!-- .col-sm-4 -->

		<div class="col-sm-8 content-col">

			<h3 id="post-launch">1. Dev Handoff</h3>

			<ul>
				<li>Upload the design to avocode and forward the link to XD design a day before the handoff</li>
				<li>Organize the assets in Gdrive folders and forward the link</li>
				<ul>
					<li>While exporting assets please trim the whitespace around all the images. If required, it can be
						coded using CSS</li>
				</ul>
				<li>Styleguide should minimum include the following</li>
				<ul>
					<li>Colors</li>
					<li>Font Family</li>
					<li>Typography (IMPORTANT): Each set for desktop and mobile. <br /> Headings(h1 to h6), paragraph,
						blockquote, links and any other custom font styles/sizes can be defined as display-1, display-2,
						p1, p2, etc</li>
					<li>Icons</li>
					<li>Buttons</li>
					<li>Form Inputs</li>
					<li>Components with hover/active states. It can also be included in individual design</li>
				</ul>
			</ul>

			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="wp-installation">2. WP Installation</h3>

			<p>Work with 3 different WP installations. Local, Development and Production.</p>

			<h5 id="local">2.1 Local WP (development branch)</h5>
			<ol>
				<li>Use "LOCAL APP" to install WordPress</li>
				<li>Create the "omnia" username with password($$OmniaAE$$) with your omnia email
				</li>
				<li>Uncheck "Allow search engine..."</li>
				<li>Update "Site Title" <small>(ask manager)</small></li>
				<li>Go to Settings => General and update "Tagline" <small>(ask manager)</small></li>
				<li>Create pages "Home" and "Blog"</li>
				<li>Go to Settings => Reading</li>
				<ul>
					<li>Select "A static page" and select page home for "Homepage" and page blog for "Posts page"</li>
					<li>Set "Blog pages show at most" to "5"</li>
				</ul>
				<li>Go to Settings => Permalinks and select "Custom structure" and set its value to "/%postname%/"
					without quotes</li>
				<li>Go to Settings => Discussions</li>
				<ul>
					<li>If comments not allowed, Uncheck "Allow people to post comments on new articles"</li>
					<li>If comments not allowed, Uncheck "Show Avatars"</li>
				</ul>
			</ol>

			<h5 id="development">2.2 Development WP (development branch)</h5>

			<p>Once the site is coded and ready, migrate the local WP to development WP for internal QC and client
				review. If there are any bugs, fix them on local WP and push them to development WP. If there are any
				changes to content/media. First pull the database from development WP to local WP, make the changes on
				local WP and push back the database to development WP. It is vital that the local WP and development WP
				are always in sync</p>

			<p><a href="#wp-migrate-db-pro">WP Migrate DB Pro</a> plugin is used to push and pull database</p>

			<h5 id="production">2.3 Production WP (master branch)</h5>

			<p>After QC is completed, push the development WP to production WP. Also, merge the development branch with
				master branch <br /><code>git checkout master</code><br /><code>git merge development</code></p>

			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="skeleton-setup">3. Skeleton Setup</h3>

			<h5 id="skeleton-installation">3.1 Installation</h5>

			<ol>
				<li>Switch to themes directory <br />
					<code>cd /path/to/your/wp-content/themes</code>
				</li>
				<li>Download the skeleton theme and not clone
					<div class="mt-1">
						<code>https://bitbucket.org/threesixtyeight/skeleton-reloaded/src/master/</code>
					</div>
				</li>
				<li>Rename the theme folder to match the project name <small>(ask manager)</small></li>
				<li>Create a screenshot.png (1200 x 900) file in the theme directory <small>(ask designer)</small></li>
				<li>Create favicon.png and favicon.ico files <small>(ask designer)</small></li>
				<li>Run <code>node -v</code> anywhere to check if you have Node.js installed in your system. If not,
					download from <a href="https://nodejs.org/en/" target="_blank">Node.js</a> and install </li>
				<li>Install grunt <br /> <code>npm install grunt</code></li>
				<li>Go to theme src folder <br /> <code>cd /path/to/your/wp-content/themes/theme-name/src</code>
				</li>
				<li>Run the following command to install all the necessary packages to run grunt tasks <br />
					<code>npm install</code>
				</li>
				<li>Update theme info in sass/style.scss file. Theme name should match the project name</li>
				<li>
					Run the following command for grunt to compile all the scss/js files <br />
					<div class="mb-1">
						<code>cd /path/to/your/wp-content/themes/skeleton/src</code> <br />
						<code>grunt</code>
					</div>
					<p>All the scss files are created inside the src/sass/partials/ folder and its sub-folders. All
						the js files are divided into two folders src/js/plugins and src/js/custom. Gulp will
						compile all the src/sass/partials/.scss files into a single file named style.css and
						src/js/plugins/.js files into assets/js/plugins.js file and src/js/custom/.js files into
						assets/js/custom.js file.
				</li>
				<li>Make the Initial Commit</li>
				<li>Go to Appearance => Themes and activate the theme</li>
				<li class="text-highlight">Create all the pages from sitemap with heirarchy and required URL structure
					<small>(ask manager)</small>
				</li>
			</ol>

			<h5 id="skeleton-repo">3.2 Creating Repo</h5>

			<ol>
				<li>Create a new repo on bitbucket matching the project name</li>
				<li>Get your local Git repository on Bitbucket</li>
				<ul>
					<li>Switch to your theme folder <br />
						<code>cd /path/to/your/folder/theme</code>
					</li>
					<li>Connect your existing repository to Bitbucket <br />
						<code>git remote add origin "YOUR REPO LINK"</code>
						<code>git push -u origin master</code>
						</small>
					</li>
				</ul>
			</ol>

			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="skeleton-sass">Skeleton SASS</h3>

			<p>All sass files are stored inside src/sass/partials folder and its subfolders except the style.scss
				file</p>

			<ul>
				<li><b>styles.scss</b> - contains the theme information and imports all the partials which needs to be
					compiled into style.css</li>
				<li><b>partials/variables.scss</b> - setup the variables as per the styleguide and designs. Includes
					colors, breakpoints, sizes, spacing and many other utility classes variables too.</li>
				<li><b>partials/normalize.scss</b> - makes browsers render all elements more consistently and in line
					with modern standards. <a href="https://necolas.github.io/normalize.css/" target="_blank">Learn
						More</a></li>
				<li><b>partials/base-selectors.scss</b> - contains all the basic html selectors. Edit the headings
					styles as per the styleguide</li>
				<li><b>partials/base-styles.scss</b> - contains helpers classes which needs to compiled early so they
					can overridden if required</li>
				<li><b>partials/grid.scss</b> - contains all the grid system classes from Bootstrap. <a
						href="https://getbootstrap.com/docs/4.3/layout/grid/" target="_blank">Learn More</a></li>
				<li><b>partials/ie.scss</b> - contains IE specific styles</li>
				<li><b>partials/print.scss</b> - contains print styles</li>
				<li><b>partials/shame.scss</b> - contains last minute styles which needs to be moved to specific scss
					file</li>
				<li><b>partials/mixins/</b>- contains mixin files which are used to generate grid, breakpoints and other
					classes. Please go through their code to understand their function</li>
			</ul>

			<h5 id="skeleton-usage">3.3 Usage</h5>

			<ol>
				<li>Update variables.scss</li>
				<li>Update headings styles in base-selectors.scss file</li>
			</ol>

			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="skeleton-setup">3. Skeleton Setup</h3>

			<h5 id="skeleton-installation">3.1 Installation</h5>


			<h3 id="css-styleguide">CSS/SASS Styleguide</h3>
			<h4 id="terminology" class="mb-4">Terminology</h4>

			<h5>Rule Declaration</h5>
			<p>A "rule declaration" is the name given to a selector (or a group of selectors) with an accompanying group
				of properties. Here's an example:</p>
			<code class="d-block p-1">
<pre>
.listing {
	font-size: 18px;
	line-height: 1.2;
}
</pre>
</code>

			<h5>Selectors</h5>
			<p>In a rule declaration, “selectors” are the bits that determine which elements in the DOM tree will be
				styled by the defined properties. Selectors can match HTML elements, as well as an element's class, ID,
				or any of its attributes. Here are some examples of selectors:</p>
			<code class="d-block p-1">
<pre>
.my-element-class {
	/* ... */
}

[aria-hidden] {
	/* ... */
}
</pre>
</code>

			<h5>Properties</h5>
			<p>Finally, properties are what give the selected elements of a rule declaration their style. Properties are
				key-value pairs, and a rule declaration can contain one or more property declarations. Property
				declarations look like this:</p>
			<code class="d-block p-1">
<pre>
/* some selector */ {
	background: #f1f1f1;
	color: #333;
}
</pre>
</code>

			<h4 id="css" class="mb-4">CSS</h4>

			<h5>Formatting</h5>
			<ul>
				<li>Use soft tabs (2 spaces) for indentation.</li>
				<li>Prefer dashes over camelCasing in class names.</li>
				<li>Do not use ID selectors.</li>
				<li>When using multiple selectors in a rule declaration, give each selector its own line.</li>
				<li>Put a space before the opening brace { in rule declarations.</li>
				<li>In properties, put a space after, but not before, the : character.</li>
				<li>Put closing braces } of rule declarations on a new line.</li>
				<li>Put blank lines between rule declarations.</li>
			</ul>

			<p class="mb-1"><b>Bad</b></p>
			<code class="d-block p-1 mb-2">
<pre>
.avatar{
		border-radius:50%;
		border:2px solid white; }
.no, .nope, .not_good {
		// ...
}
#lol-no {
	// ...
}
</pre>
</code>

			<p class="mb-1"><b>Good</b></p>
			<code class="d-block p-1">
<pre>
.avatar {
	border-radius: 50%;
	border: 2px solid white;
}

.one,
.selector,
.per-line {
	// ...
}
</pre>
</code>

			<h5>Comments</h5>
			<ul>
				<li>Prefer line comments (// in Sass-land) to block comments.</li>
				<li>Prefer comments on their own line. Avoid end-of-line comments.</li>
				<li>Write detailed comments for code that isn't self-documenting:</li>
				<ul>
					<li>Uses of z-index</li>
					<li>Compatibility or browser-specific hacks</li>
				</ul>
			</ul>

			<h5>ID selectors</h5>
			<p>While it is possible to select elements by ID in CSS, it should generally be considered an anti-pattern.
				ID selectors introduce an unnecessarily high level of specificity to your rule declarations, and they
				are not reusable.</p>

			<p>For more on this subject, read <a
					href="http://csswizardry.com/2014/07/hacks-for-dealing-with-specificity/">CSS Wizardry's article</a>
				on dealing with specificity.</p>

			<h5>JavaScript hooks</h5>
			<p>Avoid binding to the same class in both your CSS and JavaScript. Conflating the two often leads to, at a
				minimum, time wasted during refactoring when a developer must cross-reference each class they are
				changing, and at its worst, developers being afraid to make changes for fear of breaking functionality.
			</p>

			<p>We recommend creating JavaScript-specific classes to bind to, prefixed with .js-:</p>

			<h5>Border</h5>

			<p>Use <code>0</code> instead of <code>none</code> to specify that a style has no border.</p>
			<p class="mb-1"><b>Bad</b></p>
			<code class="d-block p-1 mb-2">
<pre>
.foo {
	border: none;
}
</pre>
</code>

			<p class="mb-1"><b>Good</b></p>
			<code class="d-block p-1">
<pre>
.foo {
	border: 0;
}
</pre>
</code>

			<h4 id="sass" class="mb-4">SASS</h4>

			<h5>Syntax</h5>

			<p>Use the <code>.scss</code> syntax, never the original .sass syntax</p>

			<h5>Variables</h5>
			<p>Prefer dash-cased variable names (e.g. <code>$my-variable</code>) over camelCased or snake_cased variable
				names. It is acceptable to prefix variable names that are intended to be used only within the same file
				with an underscore (e.g. <code>$_my-variable</code>).</p>

			<h5>Mixins</h5>
			<p>Mixins should be used to DRY up your code, add clarity, or abstract complexity--in much the same way as
				well-named functions. Mixins that accept no arguments can be useful for this, but note that if you are
				not compressing your payload (e.g. gzip), this may contribute to unnecessary code duplication in the
				resulting styles.</p>

			<h5>Extend directive</h5>
			<p><code>@extend</code> should be avoided because it has unintuitive and potentially dangerous behavior,
				especially when used with nested selectors. Even extending top-level placeholder selectors can cause
				problems if the order of selectors ends up changing later (e.g. if they are in other files and the order
				the files are loaded shifts). Gzipping should handle most of the savings you would have gained by using
				<code>@extend</code>, and you can DRY up your stylesheets nicely with mixins.
			</p>

			<h5>Nested selectors</h5>

			<p><b>Do not nest selectors more than three levels deep!</b></p>

			<code class="d-block p-1 mb-4">
<pre>
.page-container {
	.content {
		.profile {
			// STOP!
		}
	}
}
</pre>
</code>

			<p>When selectors become this long, you're likely writing CSS that is:</p>

			<ul>
				<li>Strongly coupled to the HTML (fragile) —OR—</li>
				<li>Overly specific (powerful) —OR—</li>
				<li>Not reusable</li>
			</ul>

			<p>Again: <b>never nest ID selectors!</b></p>

			<p>If you must use an ID selector in the first place (and you should really try not to), they should never
				be nested. If you find yourself doing this, you need to revisit your markup, or figure out why such
				strong specificity is needed. If you are writing well formed HTML and CSS, you should never <b>need</b>
				to do this.</p>

			<p>Nested selectors, if necessary, go last, and nothing goes after them. Add whitespace between your rule
				declarations and nested selectors, as well as between adjacent nested selectors. Apply the same
				guidelines as above to your nested selectors.</p>
			<p class="mb-1"><b>Bad</b></p>
			<code class="d-block p-1 mb-2">
<pre>
.btn {
	background: green;
	.icon {
		margin-inline-end: 10px;
	}
	font-weight: bold;
	@include transition(background 0.5s ease);
}
</pre>
</code>
			<p class="mb-1"><b>Good</b></p>
			<code class="d-block p-1 mb-4">
<pre>
.btn {
	background: green;
	font-weight: bold;
	@include transition(background 0.5s ease);

	.icon {
		margin-inline-end: 10px;
	}
}
</pre>
</code>

			<h4 id="sass" class="mb-4">Custom Mixins</h4>

			<div class="alert alert-info p-1">
				<p>The rem unit, which stands for "root em" is relative to the font-size of html element</p>
			</div> <!-- .alert alert-info -->

			<h5>rem-calc() function</h5>
			<p>Convert px units to rem. Divide the px value by $font-size-root. It is very important to wrap all the px
				values inside the rem-calc function for the scaling to work on 3K or higher screens</p>
			<h6 class="mt-0">Usage</h6>
			<code class="d-block p-1 mb-4">
<pre>
h6 {
	font-size: rem-calc( 20px ); // 20px/$font-size-root(10px) which outputs 2rem
}
</pre>
</code>


			<h5>fluid()</h5>

			<p>Scale any CSS property with px units values between 2 given breakpoints. Mostly it is used for font-size
				property</p>

			<h6 class="mt-0">Mixins</h6>
			<code class="overflow-auto d-block p-1 mb-4">
<pre>
@mixin fluid($properties, $min-screen, $max-screen, $min-value, $max-value, $default: true, $max-breakpoint: true) {
	& {

		@if ( $default ) {
			@each $property in $properties {
				#{$property}: $min-value;
			}
		}

		@media (min-width: $min-screen) {
			@each $property in $properties {
				#{$property}: calc-interpolation($min-screen, $min-value, $max-screen, $max-value);
			}
		}

		@if ( $max-breakpoint ) {
			@media (min-width: $max-screen) {
				@each $property in $properties {
					#{$property}: rem-calc( $max-value );
				}
			}
		}
	}
}
</pre>
</code>

			<h6 class="mt-0">Usage</h6>
			<code class="overflow-auto d-block p-1 mb-4">
<pre>
h6 {
	@include fluid(font-size, 375px, 1920px, 18px, 36px);
	// variables can also be used for any of the parameters
	// $max-screen cannot be larger than the $scaling variable set in variables.scss
	// sets font-size to $min-value(18px) as a default for anything smaller than $min-screen
	// px units are converted to rem units, similar to rem-calc()
	// start scaling font-size from $min-value(18px) on a device with width of $min-screen(375px) to $max-value(36px) on a device with width of $max-screeen(1920px)
	// scaling happens propotionately between the screen sizes. For eg. If $min-screen is 100px and $max-screen is 200px and the $min-value is 10px and $max-value is 20px. For every 1px change in screen size the font-size will be increase by 0.1px and for every 10px change in screen size the font-size be increase by 1px
	// above $max-screen(1920px) the font-size will scale depending on the font-size set on html element
}
</pre>
</code>

			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="js-styleguide">JS Styleguide</h3>
			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="pre-launch">Pre Launch</h3>
			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->


			<h3 id="post-launch">Post Launch</h3>
			<hr />
			<div class="mb-5"></div> <!-- .mb-5 -->

		</div> <!-- .col-sm-8 content-col -->

	</div> <!-- .row -->

</div> <!-- .container -->

<section class="spacing mb-9">
	<h3 class="mb-3">Spacing</h3>

	<div class="row mb-4">

		<div class="col">
			<p>The margin classes are used to create space around elements, outside of any defined borders. There are
				classes for setting the margin for each side of an element (top, right, bottom, and left).</p>

			<p><b>m-(number)</b> <br /> Set margin for all four sides (top, right, bottom, and left).</p>
			<p><b>mt-(number)</b> <br /> Sets the top margin of an element</p>
			<p><b>me-(number)</b> <br /> Sets the right margin of an element</p>
			<p><b>mb-(number)</b> <br /> Sets the bottom margin of an element</p>
			<p><b>ms-(number)</b> <br /> Sets the left margin of an element</p>

			<code class="mb-1">&lt;div class="mb-3"&gt;</code>
			<div class="bg-gray mb-3">I've a margin bottom of 30px</div>

			<code class="mb-1">&lt;div class="mb-2"&gt;</code>
			<div class="bg-gray mb-2">I've a margin bottom of 20px</div>

			<div class="bg-gray">Dummy Text</div>


			<code class="mb-1">&lt;div class="mt-4 mt-3 mb-2 ms-1"&gt;</code> <br />
			<div class="bg-gray mt-4 me-3 mb-2 ms-1">
				<p>I've a margin of 40px at top, 30px at right, 20px at bottom and 10px at left</p>
			</div>

			<div class="bg-gray">Dummy Text</div>
		</div> <!-- .col -->

		<div class="col">
			<p>The padding classes are used to generate space around an element's content, inside of any defined
				borders. There are classes for setting the classes for each side of an element (top, right, bottom, and
				left).</p>

			<p><b>p-(number)</b> <br /> Set padding for all four sides (top, right, bottom, and left).</p>
			<p><b>pt-(number)</b> <br /> Sets the top padding of an element</p>
			<p><b>pe-(number)</b> <br /> Sets the right padding of an element</p>
			<p><b>pb-(number)</b> <br /> Sets the bottom padding of an element</p>
			<p><b>ps-(number)</b> <br /> Sets the left padding of an element</p>

			<code class="mb-1">&lt;div class="p-3"&gt;</code>
			<div class="bg-gray p-3 mb-2">I've a padding of 30px at each side</div>

			<code class="mb-1">&lt;div class="pb-2"&gt;</code>
			<div class="bg-gray p-2 mb-2">I've a padding of 20px at each side</div>

			<code class="mb-1">&lt;div class="pt-2 pe-1-5 pb-3 ps-2-5"&gt;</code>
			<div class="bg-gray pt-2 pe-1-5 pb-3 ps-2-5 mb-2">
				<p>I've a padding of 20px at top, 15px at right, 50px at bottom and 25px at left</p>
			</div>
		</div> <!-- .col -->

	</div> <!-- .row -->

	<p>(number) can be replaced by any number starting from 0-5 to 20 at an interval of .5. For eg. 0-5, 1, 1-5, 2, 2-5
		etc.</p>

	<p>Each number gets multiplied by 10px. So a class "me-2" will set margin of 20px to the right of the element</p>
</section>

<?php get_footer(); ?>
