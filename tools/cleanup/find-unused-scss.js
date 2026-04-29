const fs = require('fs');
const path = require('path');

// Read style.scss
const styleScss = fs.readFileSync('src/sass/style.scss', 'utf8');

// Extract all @use or @use statements
const imports = styleScss.match(/@(import|use)\s+['"]([^'"]+)['"]/g) || [];

// Get imported paths (only those starting with 'partials/')
const importedPaths = imports
	.map(i => {
		const match = i.match(/['"]([^'"]+)['"]/);
		return match ? match[1] : null;
	})
	.filter(p => p && p.startsWith('partials/'));

// Convert import paths to actual file paths
const importedFiles = new Set();
importedPaths.forEach(ip => {
	// Remove 'partials/' prefix
	let filePath = ip.replace('partials/', '');

	// Convert path to actual file path
	// e.g., 'mixins/rem' -> 'mixins/_rem.scss'
	const parts = filePath.split('/');
	const dir = parts.slice(0, -1).join('/');
	const name = parts[parts.length - 1];
	const fullPath = dir ? `${dir}/_${name}.scss` : `_${name}.scss`;

	importedFiles.add(fullPath);
});

// Get all SCSS files in partials directory
function getAllScssFiles(dir, baseDir = '') {
	const files = [];
	const entries = fs.readdirSync(dir, { withFileTypes: true });

	for (const entry of entries) {
		const fullPath = path.join(dir, entry.name);
		const relativePath = baseDir ? `${baseDir}/${entry.name}` : entry.name;

		if (entry.isDirectory()) {
			files.push(...getAllScssFiles(fullPath, relativePath));
		} else if (entry.name.endsWith('.scss')) {
			files.push(relativePath);
		}
	}

	return files;
}

const allFiles = getAllScssFiles('src/sass/partials');

// Find unused files
const unused = allFiles.filter(f => !importedFiles.has(f));

console.log('=== Imported Files ===');
Array.from(importedFiles)
	.sort()
	.forEach(f => console.log(f));

console.log('\n=== Unused Files ===');
unused.sort().forEach(f => console.log(f));

console.log(`\nTotal imported: ${importedFiles.size}`);
console.log(`Total files: ${allFiles.length}`);
console.log(`Unused files: ${unused.length}`);

// Write unused files to a text file for reference
fs.writeFileSync('unused-scss-files.txt', unused.sort().join('\n'));
