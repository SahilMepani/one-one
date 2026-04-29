(() => {
	// Add an event listener to the document to listen for keydown events
	document.addEventListener('keydown', function (event) {
		// Check if the key pressed is the Escape key
		if (event.key === 'Escape' || event.key === 'Esc') {
			// Select all elements that have the attribute data-esc
			const elements = document.querySelectorAll(
				'[data-toggle-click], [data-toggle-group], [data-toggle-link], .modal-backdrop'
			);

			// Loop through each of the selected elements
			elements.forEach(element => {
				// Remove the 'js-active' class from the element
				element.classList.remove('js-active');
			});
		}
	});
})();
