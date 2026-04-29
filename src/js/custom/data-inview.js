(function () {
	// One observer per unique (margin + thresholds) key
	const observers = new Map();

	function observeInviewElements(context = document) {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			console.info(
				'[InView] Reduced motion preference detected, skipping observers.'
			);
			return;
		}

		const elements = context.querySelectorAll(
			'[data-inview]:not([data-inview-initialized]), [data-inview-repeat]:not([data-inview-initialized])'
		);

		elements.forEach(el => {
			// Accept px ("56"), percent ("7%"), or vh ("7vh") for convenience.
			const offsetRaw = el.getAttribute('data-inview-offset') || '7%';
			const thresholdAttr = el.getAttribute('data-inview-threshold');

			// Safari sometimes misses bare 0; give it a tiny ladder of thresholds
			const thresholds = thresholdAttr
				? thresholdAttr.split(',').map(v => parseFloat(v.trim()))
				: [0, 0.001, 0.01];

			// Optional: set a scroll container as root
			// Example: data-inview-root="#scrollWrap"
			const rootSel = el.getAttribute('data-inview-root');
			const rootEl = rootSel ? document.querySelector(rootSel) : null;

			// Build a stable rootMargin string that avoids visualViewport quirks.
			// IntersectionObserver supports px and % in rootMargin. We’ll translate:
			const margin = normalizeRootMargin(offsetRaw);

			const key = `${rootSel || 'viewport'}|${margin}|${thresholds.join('/')}`;
			let observer = observers.get(key);

			if (!observer) {
				observer = new IntersectionObserver(handleIntersect, {
					root: rootEl || null,
					rootMargin: margin,
					threshold: thresholds
				});
				observers.set(key, observer);
			}

			el.setAttribute('data-inview-initialized', 'true');

			// Defer observe to next frame to ensure layout is settled (iOS quirk)
			requestAnimationFrame(() => {
				observer.observe(el);
				// Manual kickstart: if already in view at load, flip it on.
				if (isVisiblyInViewport(el, rootEl, offsetRaw)) {
					el.dataset.inview = 'true';
				}
			});
		});
	}

	function handleIntersect(entries) {
		for (const entry of entries) {
			const target = entry.target;
			const repeat = target.hasAttribute('data-inview-repeat');

			if (entry.isIntersecting) {
				target.dataset.inview = 'true';
			} else if (repeat) {
				target.removeAttribute('data-inview');
			}
		}
	}

	// Translate various offset formats into a rootMargin string.
	// input like "7%", "7vh", "56", "56px"
	function normalizeRootMargin(offset) {
		const toNumber = v =>
			parseFloat(String(v).replace(/[^\d.\-]/g, '')) || 0;

		if (String(offset).endsWith('vh') || String(offset).endsWith('svh')) {
			// Convert vh-ish to percent. 7vh ≈ 7%.
			const v = toNumber(offset);
			return `${v}% 0px -${v}% 0px`;
		}
		if (String(offset).endsWith('%')) {
			const v = toNumber(offset);
			return `${v}% 0px -${v}% 0px`;
		}
		// px (number or "px")
		const v = toNumber(offset);
		return `${v}px 0px -${v}px 0px`;
	}

	// Manual visibility check to kickstart iOS when observer doesn’t fire yet.
	function isVisiblyInViewport(el, rootEl, offset) {
		const rect = el.getBoundingClientRect();
		const rootRect = rootEl
			? rootEl.getBoundingClientRect()
			: {
					top: 0,
					left: 0,
					right: window.innerWidth,
					bottom: window.innerHeight,
					height: window.innerHeight
				};

		// Convert offset to px relative to root height for a conservative test
		const toPx = v => {
			const s = String(v);
			const n = parseFloat(s);
			if (s.endsWith('vh') || s.endsWith('svh'))
				return (n / 100) * rootRect.height;
			if (s.endsWith('%')) return (n / 100) * rootRect.height;
			return n || 0;
		};
		const offPx = toPx(offset);

		const topLimit = rootRect.top + offPx;
		const bottomLimit = rootRect.bottom - offPx;

		const verticallyIn = rect.bottom >= topLimit && rect.top <= bottomLimit;
		const horizontallyIn =
			rect.right >= rootRect.left && rect.left <= rootRect.right;

		return verticallyIn && horizontallyIn;
	}

	// Rebuild observers on viewport changes (iOS toolbars/orientation)
	function rebuild() {
		// Disconnect all old observers
		observers.forEach(obs => obs.disconnect());
		observers.clear();
		// Remove initialized flag so elements get reattached cleanly
		document
			.querySelectorAll('[data-inview-initialized]')
			.forEach(el => el.removeAttribute('data-inview-initialized'));
		observeInviewElements();
	}

	// Init
	if ('IntersectionObserver' in window) {
		observeInviewElements();
		window.observeInviewElements = observeInviewElements;

		// Resize/orientation changes on iOS can invalidate margins
		window.addEventListener('orientationchange', rebuild, {
			passive: true
		});

		let rebuildTimer;
		window.addEventListener('resize', () => {
			clearTimeout(rebuildTimer);
			rebuildTimer = setTimeout(rebuild, 200);
		}, { passive: true });

		// If supported, also listen to visualViewport changes
		if (window.visualViewport) {
			let vpTimer;
			window.visualViewport.addEventListener('resize', () => {
				clearTimeout(vpTimer);
				vpTimer = setTimeout(rebuild, 200);
			}, { passive: true });
		}
	} else {
		console.warn(
			'[InView] IntersectionObserver not supported by this browser.'
		);
	}
})();
