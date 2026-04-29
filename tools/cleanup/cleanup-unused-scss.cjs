#!/usr/bin/env node
/**
 * Cleanup Unused SCSS Files
 *
 * This script:
 * 1. Finds all SCSS files in src/sass/partials that are not imported in style.scss
 * 2. Deletes those unused files
 * 3. Removes empty directories that no longer contain SCSS files
 *
 * Usage: node cleanup-unused-scss.cjs
 */

const fs = require('fs');
const path = require('path');

// Configuration
const STYLE_SCSS_PATH = 'src/sass/style.scss';
const PARTIALS_DIR = 'src/sass/partials';

// Colors for terminal output
const colors = {
	reset: '\x1b[0m',
	red: '\x1b[31m',
	green: '\x1b[32m',
	yellow: '\x1b[33m',
	blue: '\x1b[34m',
	cyan: '\x1b[36m'
};

function log(message, color = 'reset') {
	console.log(`${colors[color]}${message}${colors.reset}`);
}

function error(message) {
	log(`❌ Error: ${message}`, 'red');
	process.exit(1);
}

function success(message) {
	log(`✅ ${message}`, 'green');
}

function info(message) {
	log(`ℹ️  ${message}`, 'blue');
}

function warning(message) {
	log(`⚠️  ${message}`, 'yellow');
}

// Check if file/directory exists
function exists(filePath) {
	try {
		return fs.existsSync(filePath);
	} catch {
		return false;
	}
}

// Read style.scss and extract imported files
function getImportedFiles() {
	if (!exists(STYLE_SCSS_PATH)) {
		error(`Style file not found: ${STYLE_SCSS_PATH}`);
	}

	const styleScss = fs.readFileSync(STYLE_SCSS_PATH, 'utf8');
	const lines = styleScss.split('\n');
	const importedFiles = new Set();

	for (const line of lines) {
		const trimmed = line.trim();

		// Skip commented lines (starting with // or /*)
		if (trimmed.startsWith('//') || trimmed.startsWith('/*')) {
			continue;
		}

		// Match @use or @use statements
		const match = trimmed.match(/@(import|use)\s+['"]([^'"]+)['"]/);
		if (match) {
			const importPath = match[2];

			// Only process imports from partials/
			if (importPath.startsWith('partials/')) {
				// Convert import path to actual file path
				// e.g., 'partials/mixins/rem' -> 'mixins/_rem.scss'
				let filePath = importPath.replace('partials/', '');
				const parts = filePath.split('/');
				const dir = parts.slice(0, -1).join('/');
				const name = parts[parts.length - 1];
				const fullPath = dir ? `${dir}/_${name}.scss` : `_${name}.scss`;

				importedFiles.add(fullPath);
			}
		}
	}

	return importedFiles;
}

// Get all SCSS files recursively
function getAllScssFiles(dir, baseDir = '') {
	const files = [];

	if (!exists(dir)) {
		return files;
	}

	try {
		const entries = fs.readdirSync(dir, { withFileTypes: true });

		for (const entry of entries) {
			const fullPath = path.join(dir, entry.name);
			const relativePath = baseDir
				? `${baseDir}/${entry.name}`
				: entry.name;

			if (entry.isDirectory()) {
				files.push(...getAllScssFiles(fullPath, relativePath));
			} else if (entry.name.endsWith('.scss')) {
				files.push(relativePath);
			}
		}
	} catch (err) {
		warning(`Could not read directory: ${dir} - ${err.message}`);
	}

	return files;
}

// Delete a file
function deleteFile(filePath) {
	const fullPath = path.join(PARTIALS_DIR, filePath);

	try {
		if (exists(fullPath)) {
			fs.unlinkSync(fullPath);
			return true;
		}
		return false;
	} catch (err) {
		warning(`Could not delete file: ${fullPath} - ${err.message}`);
		return false;
	}
}

// Get all directories recursively
function getAllDirectories(dir, baseDir = '') {
	const directories = [];

	if (!exists(dir)) {
		return directories;
	}

	try {
		const entries = fs.readdirSync(dir, { withFileTypes: true });

		for (const entry of entries) {
			if (entry.isDirectory()) {
				const relativePath = baseDir
					? `${baseDir}/${entry.name}`
					: entry.name;
				directories.push(relativePath);
				directories.push(
					...getAllDirectories(
						path.join(dir, entry.name),
						relativePath
					)
				);
			}
		}
	} catch (err) {
		warning(`Could not read directory: ${dir} - ${err.message}`);
	}

	return directories;
}

// Check if directory is empty (no files or subdirectories)
function isDirectoryEmpty(dirPath) {
	try {
		const entries = fs.readdirSync(dirPath);
		return entries.length === 0;
	} catch {
		return false;
	}
}

// Remove empty directories
function removeEmptyDirectories() {
	if (!exists(PARTIALS_DIR)) {
		return [];
	}

	const allDirs = getAllDirectories(PARTIALS_DIR);
	const removedDirs = [];

	// Sort by depth (deepest first) to remove nested directories first
	allDirs.sort((a, b) => {
		const depthA = (a.match(/\//g) || []).length;
		const depthB = (b.match(/\//g) || []).length;
		return depthB - depthA;
	});

	for (const dir of allDirs) {
		const fullPath = path.join(PARTIALS_DIR, dir);

		if (isDirectoryEmpty(fullPath)) {
			try {
				fs.rmdirSync(fullPath);
				removedDirs.push(dir);
			} catch (err) {
				warning(
					`Could not remove directory: ${fullPath} - ${err.message}`
				);
			}
		}
	}

	return removedDirs;
}

// Main execution
function main() {
	log('\n🧹 Starting SCSS cleanup...\n', 'cyan');

	// Step 1: Get imported files
	info('Step 1: Analyzing imports in style.scss...');
	const importedFiles = getImportedFiles();
	log(`   Found ${importedFiles.size} imported files`, 'blue');

	// Step 2: Get all SCSS files
	info('Step 2: Scanning for all SCSS files...');
	const allFiles = getAllScssFiles(PARTIALS_DIR);
	log(`   Found ${allFiles.length} total SCSS files`, 'blue');

	// Step 3: Find unused files
	info('Step 3: Identifying unused files...');
	const unused = allFiles.filter(f => !importedFiles.has(f));

	if (unused.length === 0) {
		success('No unused SCSS files found!');
	} else {
		log(`   Found ${unused.length} unused files:`, 'yellow');
		unused.forEach(f => log(`     - ${f}`, 'yellow'));

		// Step 4: Delete unused files
		log('\n', 'reset');
		info('Step 4: Deleting unused files...');
		let deletedCount = 0;

		for (const file of unused) {
			if (deleteFile(file)) {
				deletedCount++;
				log(`   Deleted: ${file}`, 'green');
			}
		}

		success(`Deleted ${deletedCount} unused file(s)`);
	}

	// Step 5: Remove empty directories
	log('\n', 'reset');
	info('Step 5: Removing empty directories...');
	const removedDirs = removeEmptyDirectories();

	if (removedDirs.length === 0) {
		success('No empty directories found!');
	} else {
		log(`   Removed ${removedDirs.length} empty directory(ies):`, 'yellow');
		removedDirs.forEach(d => log(`     - ${d}`, 'yellow'));
		success(`Removed ${removedDirs.length} empty directory(ies)`);
	}

	// Summary
	log('\n', 'reset');
	log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'cyan');
	log('✨ Cleanup complete!', 'green');
	log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n', 'cyan');
}

// Run the script
try {
	main();
} catch (err) {
	error(`Unexpected error: ${err.message}`);
	console.error(err);
	process.exit(1);
}
