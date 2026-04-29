(() => {
	const siteHeader = document.querySelector('.site-header');
	if (!siteHeader) return;

	// Variable to store the last known vertical scroll position
	let lastScrollTop = 0;
	// Flag to prevent multiple requestAnimationFrame calls
	let ticking = false;

	// Function to handle the scroll event
	function handleScroll() {
		// Get the current scroll position
		const st = window.pageYOffset || document.documentElement.scrollTop;
		// Determine the scroll direction
		const direction = st >= lastScrollTop ? 'down' : 'up';
		// Toggle site header class
		if (siteHeader) {
			if (st > lastScrollTop) {
				// scrolling down
				siteHeader.classList.add('js-user-scroll-down');
				siteHeader.classList.remove('js-user-scroll-up');
			} else if (st < lastScrollTop) {
				// scrolling up
				siteHeader.classList.add('js-user-scroll-up');
				siteHeader.classList.remove('js-user-scroll-down');
			}
		}
		// Update lastScrollTop to the current position, ensuring it is non-negative
		lastScrollTop = st <= 0 ? 0 : st;
		// Reset the ticking flag
		ticking = false;
	}

	// Function to be called on the scroll event
	function onScroll() {
		// If a scroll event is not already being processed
		if (!ticking) {
			// Schedule handleScroll to be called just before the next repaint
			requestAnimationFrame(handleScroll);
			// Set the ticking flag to true to prevent multiple calls
			ticking = true;
		}
	}

	// Attach the onScroll function to the scroll event
	window.addEventListener('scroll', onScroll);
})();
