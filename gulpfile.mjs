import gulp from 'gulp';
import { exec } from 'child_process';
import * as dartSass from 'sass-embedded';
import gulpSass from 'gulp-sass';
import concat from 'gulp-concat';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import sortMediaQueries from 'postcss-sort-media-queries';
import purgecss from 'gulp-purgecss';
import { PurgeCSS } from 'purgecss';
import { statSync, writeFileSync } from 'fs';
import { promisify } from 'util';
import _glob from 'glob';
const globAsync = promisify(_glob);
import rtlcss from 'gulp-rtlcss';
import gulpIf from 'gulp-if';
import terser from 'gulp-terser';
import { deleteAsync } from 'del';
import browserSync from 'browser-sync';
import gulpEsbuild from 'gulp-esbuild';
import through from 'through2';
import { generate } from 'critical';

// Critical CSS task
function criticalTask() {
	return generate({
		inline: false,
		base: './',
		src: process.env.LOCAL_URL,
		css: ['style.css'],
		target: {
			css: 'critical.css'
		},
		width: 1300,
		height: 900,
		extract: false,
		ignore: {
			atrule: ['@font-face'],
			decl: (node, value) => /url\(/.test(value)
		}
	});
}

// Swiper JS task using esbuild
function swiperJsTask() {
	return gulp
		.src('src/js/swiper-init.js')
		.pipe(
			gulpEsbuild({
				bundle: true,
				minify: isProduction,
				format: 'iife',
				sourcemap: !isProduction,
				outfile: 'swiper-bundle.js'
			})
		)
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSyncInstance.stream());
}

// Load environment variables from .env file
import { config } from 'dotenv';
config();

const browserSyncInstance = browserSync.create();
const sassCompiler = gulpSass(dartSass);
const env = process.env.NODE_ENV?.trim() || 'local';
const isProduction = env === 'production';
console.log(`[Gulp] Running in ${env.toUpperCase()} mode`);

// BrowserSync task
function serve(done) {
	browserSyncInstance.init({
		proxy: process.env.LOCAL_URL // Adjust this to your local WordPress URL
	});
	done();
}

// Clean task
function clean() {
	return deleteAsync(['dist']);
}

// Remove source map files — runs in production only
function cleanMaps() {
	return deleteAsync(['**/*.css.map', '**/*.js.map', '!node_modules/**']);
}

// Sass task
function sassTask() {
	let stream = gulp.src('src/sass/style.scss').pipe(
		sassCompiler({
			outputStyle: isProduction ? 'compressed' : 'expanded',
			includePaths: ['node_modules']
		}).on('error', sassCompiler.logError)
	);

	if (isProduction) {
		stream = stream.pipe(
			postcss([autoprefixer(), sortMediaQueries(), cssnano()])
		);
	}

	return stream.pipe(gulp.dest('./')).pipe(browserSyncInstance.stream());
}

// Block Sass task - compiles individual block SCSS files
function blockSassTask() {
	let stream = gulp
		.src('blocks/**/*.scss', { since: gulp.lastRun(blockSassTask) })
		.pipe(
			sassCompiler({
				outputStyle: isProduction ? 'compressed' : 'expanded',
				includePaths: ['node_modules']
			}).on('error', sassCompiler.logError)
		);

	if (isProduction) {
		stream = stream.pipe(
			postcss([autoprefixer(), sortMediaQueries(), cssnano()])
		);
	}

	return stream.pipe(gulp.dest('blocks')).pipe(browserSyncInstance.stream());
}

// PurgeCSS task — touches style.css only (not block CSS files)
// To see what would be removed across style.css + all blocks/*.css, run: npx gulp purgeAudit
function purgeCSSTask() {
	return gulp
		.src('./style.css')
		.pipe(
			purgecss({
				content: [
					'./*.php',
					'./templates/**/*.php',
					'./template-parts/**/*.php',
					'./blocks/**/*.php',
					'./functions/**/*.php',
					'./src/js/**/*.js'
				],
				safelist: {
					standard: [
						'wp-post-image',
						'dark-mode-on',
						/^style-(.*)?$/,
						/^swiper-(.*)?$/,
						/^js-(.*)?$/,
						/^bg-(.*)?$/,
						/^layout-(.*)?$/,
						/^has-(.*)?$/,
						/^grid-(.*)?$/
					],
					deep: [
						// Match any string that ends with rtl
						/rtl$/,
						// Match any string that starts with lenis
						/^lenis/,
						/^wpml/,
						/^mfp/,
						// Match any string that starts with gform_ and has anything (or nothing) after it.
						/^gform_(.*)?$/,
						/^single-(.*)?$/,
						/^page-(.*)?$/,
						/^template-(.*)?$/,
						// Match any string containing header-nav anywhere
						/header-nav/,
						/dir-(rtl|ltr)/
					],
					greedy: [/\[data-slider-inview/, /\[data-inview/, /\[dir=/],
					keyframes: true,
					variables: true
				}
			})
		)
		.pipe(gulp.dest('./'))
		.pipe(browserSyncInstance.stream());
}

// PurgeCSS dry-run audit — scans style.css + all blocks/**/*.css, nothing is written to disk
async function purgeCSSAuditTask() {
	const contentPaths = [
		'./*.php',
		'./templates/**/*.php',
		'./template-parts/**/*.php',
		'./blocks/**/*.php',
		'./functions/**/*.php',
		'./src/js/**/*.js'
	];
	const safelist = {
		standard: [
			'wp-post-image',
			'dark-mode-on',
			/^style-(.*)?$/,
			/^swiper-(.*)?$/,
			/^js-(.*)?$/,
			/^bg-(.*)?$/,
			/^layout-(.*)?$/,
			/^has-(.*)?$/,
			/^grid-(.*)?$/
		],
		deep: [
			/rtl$/,
			/^lenis/,
			/^wpml/,
			/^mfp/,
			/^gform_(.*)?$/,
			/^single-(.*)?$/,
			/^page-(.*)?$/,
			/^template-(.*)?$/,
			/header-nav/,
			/dir-(rtl|ltr)/
		],
		greedy: [/\[data-slider-inview/, /\[data-inview/, /\[dir=/],
		keyframes: true,
		variables: true
	};

	const blockFiles = await globAsync('./blocks/**/*.css');
	const allFiles = ['./style.css', ...blockFiles.sort()];

	const rejectedResults = await new PurgeCSS().purge({
		content: contentPaths,
		css: allFiles,
		safelist,
		rejected: true
	});

	const purgedResults = await new PurgeCSS().purge({
		content: contentPaths,
		css: allFiles,
		safelist
	});

	let totalOriginal = 0,
		totalPurged = 0,
		totalSelectors = 0;
	const fileLines = [];

	for (let i = 0; i < allFiles.length; i++) {
		const originalBytes = statSync(allFiles[i]).size;
		const purgedBytes = Buffer.byteLength(purgedResults[i].css, 'utf8');
		const savedBytes = originalBytes - purgedBytes;
		const percent = ((savedBytes / originalBytes) * 100).toFixed(1);
		const selectors = rejectedResults[i].rejected ?? [];

		totalOriginal += originalBytes;
		totalPurged += purgedBytes;
		totalSelectors += selectors.length;

		if (selectors.length === 0) continue;

		fileLines.push(`── ${allFiles[i]}`);
		fileLines.push(
			`   ${(originalBytes / 1024).toFixed(2)} KB → ${(purgedBytes / 1024).toFixed(2)} KB  (saves ${(savedBytes / 1024).toFixed(2)} KB, ${percent}%)`
		);
		fileLines.push(`   ${selectors.length} selectors removed:`);
		selectors.sort().forEach(s => fileLines.push(`   - ${s}`));
		fileLines.push('');
	}

	const totalSaved = totalOriginal - totalPurged;
	const totalPercent = ((totalSaved / totalOriginal) * 100).toFixed(1);

	const report = [
		`PurgeCSS Dry Run — ${new Date().toLocaleString()}`,
		``,
		`SUMMARY`,
		`  Files audited : ${allFiles.length}`,
		`  Original      : ${(totalOriginal / 1024).toFixed(2)} KB`,
		`  After purge   : ${(totalPurged / 1024).toFixed(2)} KB`,
		`  Total savings : ${(totalSaved / 1024).toFixed(2)} KB  (${totalPercent}%)`,
		`  Selectors     : ${totalSelectors} removed`,
		``,
		`─────────────────────────────────────────`,
		``,
		...fileLines
	].join('\n');

	writeFileSync('./purge-audit.txt', report, 'utf8');

	console.log('\n╔══ PurgeCSS Dry Run (nothing written) ══╗');
	console.log(`  Files     : ${allFiles.length}`);
	console.log(`  Original  : ${(totalOriginal / 1024).toFixed(2)} KB`);
	console.log(`  After     : ${(totalPurged / 1024).toFixed(2)} KB`);
	console.log(
		`  Savings   : ${(totalSaved / 1024).toFixed(2)} KB  (${totalPercent}%)`
	);
	console.log(`  Selectors : ${totalSelectors} removed`);
	console.log(`\n  Full report saved to purge-audit.txt\n`);
}

// CSS RTL task — outputs style-rtl.css (WordPress convention), never overwrites style.css
function rtlCssTask() {
	return gulp
		.src('./style.css')
		.pipe(rtlcss())
		.pipe(
			through.obj(function (file, enc, cb) {
				file.path = file.path.replace('style.css', 'style-rtl.css');
				cb(null, file);
			})
		)
		.pipe(gulp.dest('./'))
		.pipe(browserSyncInstance.stream());
}

// JavaScript tasks
function pluginsJsTask() {
	return gulp
		.src('src/js/plugins/*.js')
		.pipe(concat('plugins.js'))
		.pipe(gulpIf(isProduction, terser()))
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSyncInstance.stream());
}

function customJsTask() {
	return gulp
		.src('src/js/custom/**/*.js')
		.pipe(concat('custom.js'))
		.pipe(
			through.obj(function (file, enc, cb) {
				if (file.isNull()) return cb(null, file);
				if (file.isStream())
					return cb(new Error('Streaming not supported'));

				const header =
					'document.addEventListener("DOMContentLoaded", function() {';
				const footer = '});';
				file.contents = Buffer.concat([
					Buffer.from(header + '\n'),
					file.contents,
					Buffer.from('\n' + footer)
				]);
				cb(null, file);
			})
		)
		.pipe(gulpIf(isProduction, terser()))
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSyncInstance.stream());
}

function lintJS(done) {
	// Use ESLint via CLI instead of gulp-eslint
	exec('npx eslint "src/js/custom/**/*.js"', (err, stdout, stderr) => {
		if (stdout) console.log(stdout);
		if (stderr) console.error(stderr);
		done(); // Don't fail the task on lint errors
	});
}

function lintCSS(done) {
	exec('npx stylelint "src/sass/**/*.scss"', (err, stdout, stderr) => {
		if (stdout) console.log(stdout);
		if (stderr) console.error(stderr);
		done(); // Don't fail the task on lint errors to keep the process running
	});
}

// Watch task
function watch() {
	const watchOpts = { usePolling: true, interval: 500 };
	gulp.watch('src/sass/**/*.{scss,sass}', watchOpts, gulp.series(sassTask));
	gulp.watch('blocks/**/*.scss', watchOpts, gulp.series(blockSassTask));
	gulp.watch('src/js/swiper-init.js', watchOpts, gulp.series(swiperJsTask));
	gulp.watch('src/js/**/*.js', watchOpts, gulp.series(jsTasks));
	gulp.watch(
		[
			'*.html',
			'*.php',
			'blocks/**/*.php',
			'templates/**/*.php',
			'template-parts/**/*.php',
			'functions/**/*.php',
			'assets/js/**/*.js',
			'assets/images/**/*.{png,jpg,jpeg,gif,webp,svg}'
		],
		watchOpts
	).on('change', browserSyncInstance.reload);
	gulp.watch('blocks/**/*.js', watchOpts).on(
		'change',
		browserSyncInstance.reload
	);
}

// Define complex tasks
const jsTasks = gulp.series(swiperJsTask, pluginsJsTask, customJsTask);

// Dev build sequence (no purgecss)
const buildDev = gulp.series(
	gulp.parallel(sassTask, blockSassTask),
	gulp.parallel(lintJS, jsTasks)
);

// Prod build sequence (includes purgecss)
const buildProd = gulp.series(
	cleanMaps, // Remove any leftover .css.map files before production build
	gulp.parallel(sassTask, blockSassTask),
	gulp.parallel(rtlCssTask, lintJS, jsTasks) // Run JS/Lint in parallel with SCSS
	// purgeCSSTask, // Run PurgeCSS after initial CSS is built
	// criticalTask // Generate critical CSS last
);

const prod = gulp.series(buildProd, serve, watch);
const dev = gulp.series(buildDev, serve, watch);

// Export tasks
export {
	clean,
	cleanMaps,
	sassTask as sass,
	blockSassTask as blockSass,
	purgeCSSTask as purgecss,
	purgeCSSAuditTask as purgeAudit,
	rtlCssTask as rtlcss,
	jsTasks as js,
	criticalTask as critical,
	lintJS,
	lintCSS,
	buildDev, // Export dev build
	buildProd, // Export prod build
	prod,
	dev
};

export default isProduction ? prod : dev;
