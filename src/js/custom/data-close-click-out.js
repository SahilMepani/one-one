(() => {
	const elements = document.querySelectorAll('[data-close-click-out]');

	document.addEventListener('click', function (event) {
		// Skip if clicked element or its parent has data-toggle-click (i.e., inside the toggle trigger)
		if (
			event.target.hasAttribute('data-toggle-click') ||
			event.target.closest('[data-toggle-click]')
		) {
			return;
		}

		elements.forEach(function (element) {
			// Check if the click was outside the element
			if (!element.contains(event.target)) {
				element.classList.remove('js-active');

				// If it has data-toggle-link, find matching toggle-click element and remove js-active
				const toggleLinkValue =
					element.getAttribute('data-toggle-link');
				if (toggleLinkValue) {
					document
						.querySelectorAll(
							`[data-toggle-click="${toggleLinkValue}"]`
						)
						.forEach(toggleClickEl => {
							toggleClickEl.classList.remove('js-active');
						});
				}
			}
		});
	});
})();
