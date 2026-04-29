(() => {
	// Select all elements that should have sticky detection
	const stickyElements = document.querySelectorAll('[data-sticky]');
	if (!stickyElements.length) return;

	// Store observers (useful if you ever want to disconnect later)
	const observers = [];

	stickyElements.forEach(stickyEl => {
		// Read the computed `top` value of the sticky element
		// This ensures correct behavior even if top is not 0
		const style = getComputedStyle(stickyEl);
		const topOffset = parseFloat(style.top) || 0;

		// --------------------------------------------------
		// Create a sentinel element
		// --------------------------------------------------
		// The sentinel acts as a reference point:
		// When it scrolls out of view, the sticky element
		// has entered its "stuck" state
		const sentinel = document.createElement('div');
		sentinel.setAttribute('aria-hidden', 'true');

		// Keep sentinel invisible and non-intrusive
		sentinel.style.position = 'absolute';
		sentinel.style.top = '0';
		sentinel.style.left = '0';
		sentinel.style.width = '1px';
		sentinel.style.height = '1px';
		sentinel.style.pointerEvents = 'none';

		// Insert sentinel directly before the sticky element
		stickyEl.parentNode.insertBefore(sentinel, stickyEl);

		// Track current sticky state to avoid unnecessary DOM updates
		let isSticky = false;

		// --------------------------------------------------
		// Create IntersectionObserver
		// --------------------------------------------------
		const observer = new IntersectionObserver(
			entries => {
				const entry = entries[0];

				// When intersectionRatio === 0:
				// → sentinel is outside the viewport
				// → sticky element is currently stuck
				const currentlySticky = entry.intersectionRatio === 0;

				// Only update DOM if sticky state has changed
				if (currentlySticky !== isSticky) {
					isSticky = currentlySticky;

					// Toggle class on the sticky element
					stickyEl.classList.toggle('is-sticky', isSticky);
				}
			},
			{
				// Observe viewport (use a container here if needed)
				root: null,

				// Trigger callback exactly when sentinel enters or leaves
				threshold: [0],

				// Offset observer by the sticky `top` value
				// This aligns detection with CSS `position: sticky`
				rootMargin: `-${topOffset}px 0px 0px 0px`
			}
		);

		// Start observing the sentinel
		observer.observe(sentinel);

		// Store observer reference
		observers.push(observer);
	});
})();
