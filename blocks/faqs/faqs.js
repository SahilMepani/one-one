(() => {
	function initAccordions(section) {
		const accordionContainer = section.querySelector('.list-accordion');
		if (!accordionContainer) return;

		let activeAccordion = null;

		function toggleAccordion(accordion, autoOpen = false) {
			const content = accordion.querySelector('.accordion-content');
			const heading = accordion.querySelector('.accordion-heading');
			const isOpening = !accordion.classList.contains('js-active');

			// Close the previously active accordion
			if (activeAccordion && activeAccordion !== accordion) {
				activeAccordion.classList.remove('js-active');
				activeAccordion.querySelector(
					'.accordion-content'
				).style.maxHeight = null;
			}

			// Toggle the clicked accordion
			accordion.classList.toggle('js-active');
			content.style.maxHeight = isOpening
				? `${content.scrollHeight}px`
				: null;

			activeAccordion = isOpening ? accordion : null;

			// Delay visibility check to allow layout update (especially for expanded content)
			if (isOpening) {
				setTimeout(() => {
					const rect = heading.getBoundingClientRect();
					const isFullyVisible =
						rect.top >= 0 &&
						rect.bottom <=
							(window.innerHeight ||
								document.documentElement.clientHeight);

					if (!isFullyVisible && !autoOpen) {
						heading.scrollIntoView({
							behavior: 'smooth',
							block: 'start'
						});
					}
				}, 250); // Slight delay to allow CSS transition / layout update
			}
		}

		// Use event delegation for click events
		accordionContainer.addEventListener('click', function (event) {
			const heading = event.target.closest('.accordion-heading');
			if (heading) {
				toggleAccordion(heading.closest('.accordion'));
			}
		});

		// Open the first accordion by default
		const firstAccordion = accordionContainer.querySelector('.accordion');
		if (firstAccordion) {
			toggleAccordion(firstAccordion, true);
		}

		// Function to update max-height on resize or orientation change
		const updateMaxHeight = debounce(function () {
			if (activeAccordion) {
				const content =
					activeAccordion.querySelector('.accordion-content');
				content.style.maxHeight = `${content.scrollHeight}px`;
			}
		}, 250);

		// Add event listeners for resize and orientationchange
		window.addEventListener('resize', updateMaxHeight);
		window.addEventListener('orientationchange', updateMaxHeight);
	}

	// Initialize accordions for each .faqs-section
	document.querySelectorAll('.js-faq-section').forEach(initAccordions);
})();
