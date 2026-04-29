(() => {
	// Config
	const cell = 50;
	const decay = 0.92;
	const sigma = 120;
	const brushBoost = 0.6;

	// Elements
	const canvas = document.getElementById('c');
	const ctx = canvas.getContext('2d', { alpha: true });

	// Ensure page has no body margin side effects (optional — safe)
	document.documentElement.style.margin =
		document.documentElement.style.margin || '';
	document.body.style.margin = document.body.style.margin || '';

	// Grid & state
	let cols = 0,
		rows = 0;
	let intensity = new Float32Array(0),
		intensityPrev = new Float32Array(0);

	function fit() {
		// Use layout (clientWidth) to avoid scrollbar width mismatch
		const dpr = Math.max(1, window.devicePixelRatio || 1);
		const cssW = document.documentElement.clientWidth || window.innerWidth;
		const cssH = window.innerHeight;

		// Backing store sized by DPR (integer)
		canvas.width = Math.round(cssW * dpr);
		canvas.height = Math.round(cssH * dpr);

		// CSS size (explicit) to avoid subtle layout differences
		canvas.style.width = cssW + 'px';
		canvas.style.height = cssH + 'px';

		// set transform so 1 unit = 1 CSS px
		ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

		cols = Math.ceil(cssW / cell);
		rows = Math.ceil(cssH / cell);
		intensity = new Float32Array(cols * rows);
		intensityPrev = new Float32Array(cols * rows);
	}
	fit();
	window.addEventListener('resize', fit);

	// mouse state
	let mouseX = -9999,
		mouseY = -9999;
	let lastMouseX = -9999,
		lastMouseY = -9999;
	let hasMouse = false;

	window.addEventListener('mousemove', e => {
		hasMouse = true;
		mouseX = e.clientX;
		mouseY = e.clientY;
	});
	window.addEventListener('mouseleave', () => {
		hasMouse = false;
	});
	window.addEventListener(
		'touchmove',
		e => {
			const t = e.touches[0];
			if (t) {
				hasMouse = true;
				mouseX = t.clientX;
				mouseY = t.clientY;
			}
		},
		{ passive: true }
	);
	window.addEventListener(
		'touchend',
		() => {
			/* keep last pos */
		},
		{ passive: true }
	);

	const idx = (x, y) => y * cols + x;
	const clamp01 = v => Math.min(1, Math.max(0, v));

	// Bilinear sample
	function sampleGrid(grid, gx, gy) {
		if (gx < 0) gx = 0;
		if (gx > cols - 1) gx = cols - 1;
		if (gy < 0) gy = 0;
		if (gy > rows - 1) gy = rows - 1;
		const x0 = Math.floor(gx),
			y0 = Math.floor(gy);
		const x1 = Math.min(cols - 1, x0 + 1),
			y1 = Math.min(rows - 1, y0 + 1);
		const sx = gx - x0,
			sy = gy - y0;
		const i00 = idx(x0, y0),
			i10 = idx(x1, y0),
			i01 = idx(x0, y1),
			i11 = idx(x1, y1);
		const a = grid[i00] * (1 - sx) + grid[i10] * sx;
		const b = grid[i01] * (1 - sx) + grid[i11] * sx;
		return a * (1 - sy) + b * sy;
	}

	// 3x3 blur
	function blur3x3(src, dst) {
		for (let y = 0; y < rows; y++) {
			for (let x = 0; x < cols; x++) {
				let sum = 0;
				for (let oy = -1; oy <= 1; oy++) {
					const yy = Math.min(rows - 1, Math.max(0, y + oy));
					for (let ox = -1; ox <= 1; ox++) {
						const xx = Math.min(cols - 1, Math.max(0, x + ox));
						sum += src[idx(xx, yy)];
					}
				}
				dst[idx(x, y)] = sum / 9;
			}
		}
	}

	// main loop
	let lastFrameTime = performance.now();
	function loop() {
		// Use CSS sizes for all calculations
		const cssW =
			parseFloat(canvas.style.width) ||
			document.documentElement.clientWidth;
		const cssH = parseFloat(canvas.style.height) || window.innerHeight;

		// Clear visible area (prevents leftover backing pixels showing)
		ctx.clearRect(0, 0, Math.ceil(cssW), Math.ceil(cssH));

		// background gradient
		const bg = ctx.createLinearGradient(0, 0, 0, cssH);
		bg.addColorStop(0, '#a2c2e7');
		bg.addColorStop(1, '#e9eef5');
		ctx.fillStyle = bg;
		ctx.fillRect(0, 0, Math.ceil(cssW), Math.ceil(cssH));

		const twoSigmaSq = 2 * sigma * sigma;

		// frame time and mouse delta
		const now = performance.now();
		const frameDtMs = Math.max(1, now - lastFrameTime);
		lastFrameTime = now;

		let moveX = 0,
			moveY = 0;
		if (hasMouse && lastMouseX !== -9999) {
			moveX = mouseX - lastMouseX;
			moveY = mouseY - lastMouseY;
		}
		if (hasMouse) {
			lastMouseX = mouseX;
			lastMouseY = mouseY;
		}

		// 1) copy prev
		intensityPrev.set(intensity);

		// 2) add brush energy (centered at mouse)
		for (let cy = 0; cy < rows; cy++) {
			const y = cy * cell + cell / 2;
			for (let cx = 0; cx < cols; cx++) {
				const x = cx * cell + cell / 2;
				const id = idx(cx, cy);
				const dx = x - mouseX;
				const dy = y - mouseY;
				const d2 = dx * dx + dy * dy;
				const brush = Math.exp(-d2 / twoSigmaSq);
				intensityPrev[id] += brush * brushBoost * (1 - decay);
				intensityPrev[id] = clamp01(intensityPrev[id]);
			}
		}

		// 3) semi-lagrangian local advection
		const velGridX_total = moveX / cell;
		const velGridY_total = moveY / cell;
		const advectScale = 0.95;

		for (let cy = 0; cy < rows; cy++) {
			const gy = cy;
			const y = cy * cell + cell / 2;
			for (let cx = 0; cx < cols; cx++) {
				const gx = cx;
				const x = cx * cell + cell / 2;
				const id = idx(cx, cy);
				const dx = x - mouseX;
				const dy = y - mouseY;
				const d2 = dx * dx + dy * dy;
				const localWeight = Math.exp(-d2 / twoSigmaSq);
				const localVelX = velGridX_total * localWeight;
				const localVelY = velGridY_total * localWeight;
				const sampleX = gx - localVelX * advectScale;
				const sampleY = gy - localVelY * advectScale;
				const s = sampleGrid(intensityPrev, sampleX, sampleY);
				intensity[id] = clamp01(s);
			}
		}

		// 4) blur small
		blur3x3(intensity, intensityPrev);
		intensity.set(intensityPrev);

		// 5) decay
		for (let i = 0; i < intensity.length; i++) intensity[i] *= decay;

		// Draw soft tint — stronger near center
		for (let cy = 0; cy < rows; cy++) {
			for (let cx = 0; cx < cols; cx++) {
				const I = intensity[idx(cx, cy)];
				if (I < 0.01) continue;
				const curved = Math.pow(I, 1.9);
				ctx.fillStyle = `rgba(25, 104, 255, ${clamp01(curved * 2.6)})`;
				const drawX = cx * cell - 1;
				const drawY = cy * cell - 1;
				// Clip the width of the last cell so we never draw past cssW
				const maxAllowedW = Math.ceil(cssW) - drawX;
				const drawW = Math.max(0, Math.min(cell + 2, maxAllowedW));
				if (drawW > 0) ctx.fillRect(drawX, drawY, drawW, cell + 2);
			}
		}

		// Grid lines (make sure lines don't draw beyond cssW/cssH)
		ctx.strokeStyle = 'rgba(60,80,120,0.03)';
		ctx.lineWidth = 1;
		const W = Math.ceil(cssW);
		const H = Math.ceil(cssH);

		for (let x = 0; x <= W; x += cell) {
			ctx.beginPath();
			ctx.moveTo(x + 0.5, 0);
			ctx.lineTo(x + 0.5, H);
			ctx.stroke();
		}
		for (let y = 0; y <= H; y += cell) {
			ctx.beginPath();
			ctx.moveTo(0, y + 0.5);
			ctx.lineTo(W, y + 0.5);
			ctx.stroke();
		}

		requestAnimationFrame(loop);
	}

	requestAnimationFrame(loop);
})();
