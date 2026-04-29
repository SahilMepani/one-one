(() => {
	let leaveTimeout;

	// ==========================
	// CLICK HANDLER
	// ==========================
	function handleClickToggle(element) {
		const toggleClick = element.getAttribute('data-toggle-click');
		const toggleGroup = element.getAttribute('data-toggle-group');

		// If already active and has a data-tab attribute, do nothing
		if (
			element.classList.contains('js-active') &&
			element.hasAttribute('data-tab')
		) {
			return;
		}

		// Determine if we are activating or deactivating
		const isNowActive = !element.classList.contains('js-active');

		// 1. Deactivate other items in the same group
		if (toggleGroup) {
			document
				.querySelectorAll(`[data-toggle-group="${toggleGroup}"]`)
				.forEach(groupElement => {
					if (groupElement !== element) {
						groupElement.classList.remove('js-active');
						groupElement.setAttribute('aria-expanded', 'false');
					}
				});
		}

		// 2. Toggle all elements with the same data-toggle-click
		if (toggleClick) {
			document
				.querySelectorAll(`[data-toggle-click="${toggleClick}"]`)
				.forEach(toggleElement => {
					toggleElement.classList.toggle('js-active', isNowActive);
					toggleElement.setAttribute(
						'aria-expanded',
						isNowActive ? 'true' : 'false'
					);
				});
		}

		// 3. Toggle corresponding data-toggle-link elements
		if (toggleClick) {
			document
				.querySelectorAll(`[data-toggle-link="${toggleClick}"]`)
				.forEach(linkedElement => {
					const linkedGroup =
						linkedElement.getAttribute('data-toggle-group');

					// Deactivate others in the same linked group
					if (linkedGroup) {
						document
							.querySelectorAll(
								`[data-toggle-group="${linkedGroup}"]`
							)
							.forEach(sibling => {
								if (sibling !== linkedElement) {
									sibling.classList.remove('js-active');
									sibling.setAttribute('aria-hidden', 'true');
									// Also manage lenis for siblings if they were linked and active
									if (
										sibling.hasAttribute(
											'data-toggle-lenis'
										)
									) {
										sibling.removeAttribute(
											'data-lenis-prevent'
										); // remove data-lenis when inactive
									}
								}
							});
					}

					// Apply new state
					linkedElement.classList.toggle('js-active', isNowActive);
					linkedElement.setAttribute(
						'aria-hidden',
						isNowActive ? 'false' : 'true'
					);

					// === NEW LOGIC FOR DATA-LENIS ===
					if (linkedElement.hasAttribute('data-toggle-lenis')) {
						if (isNowActive) {
							// If it's becoming active, set data-lenis
							linkedElement.setAttribute(
								'data-lenis-prevent',
								'true'
							);
						} else {
							// If it's becoming inactive, remove data-lenis
							linkedElement.removeAttribute('data-lenis-prevent');
						}
					}
				});
		}
	}

	document.querySelectorAll('[data-toggle-click]').forEach(element => {
		element.addEventListener('click', function (event) {
			event.preventDefault(); // prevent form submission or anchor behavior
			handleClickToggle(event.currentTarget);
		});
	});

	// ==========================
	// HOVER HANDLER
	// ==========================
	function handleMouseEnter(element) {
		const toggleHover = element.getAttribute('data-toggle-hover');
		element.classList.add('js-active');

		if (toggleHover) {
			document
				.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
				.forEach(linkedElement => {
					linkedElement.classList.add('js-active');
				});
		}

		if (leaveTimeout) {
			clearTimeout(leaveTimeout);
		}
	}

	function handleMouseLeave(element) {
		const toggleHover = element.getAttribute('data-toggle-hover');

		leaveTimeout = setTimeout(() => {
			let isMouseOverLinked = false;

			if (element.matches(':hover')) {
				isMouseOverLinked = true;
			} else if (toggleHover) {
				document
					.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
					.forEach(linkedElement => {
						if (linkedElement.matches(':hover')) {
							isMouseOverLinked = true;
						}
					});
			}

			if (!isMouseOverLinked) {
				element.classList.remove('js-active');

				if (toggleHover) {
					document
						.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
						.forEach(linkedElement => {
							linkedElement.classList.remove('js-active');
						});
				}
			}
		}, 50);
	}

	document.querySelectorAll('[data-toggle-hover]').forEach(element => {
		element.addEventListener('mouseenter', event =>
			handleMouseEnter(event.currentTarget)
		);
		element.addEventListener('mouseleave', event =>
			handleMouseLeave(event.currentTarget)
		);

		// Touch support
		element.addEventListener('touchstart', event => {
			event.preventDefault();
			handleMouseEnter(event.currentTarget);
		});
		element.addEventListener('touchend', event => {
			event.preventDefault();
			handleMouseLeave(event.currentTarget);
		});
	});

	// ==========================
	// HOVER: LINKED ELEMENT SUPPORT
	// ==========================
	document.querySelectorAll('[data-toggle-link]').forEach(element => {
		element.addEventListener('mouseenter', () => {
			if (leaveTimeout) clearTimeout(leaveTimeout);
		});

		element.addEventListener('mouseleave', () => {
			// Try to find its associated hover origin (if exists)
			document
				.querySelectorAll('[data-toggle-hover]')
				.forEach(hoverElement => {
					const hoverTarget =
						hoverElement.getAttribute('data-toggle-hover');
					const linkedTarget =
						element.getAttribute('data-toggle-link');
					if (hoverTarget === linkedTarget) {
						handleMouseLeave(hoverElement);
					}
				});
		});
	});
})();
