/**
 * Scroll to a specific section from #ID at the end of Window URL
 */
(() => {
	const current_url = window.location.href;
	if (current_url.includes('#')) {
		const hashIndex = current_url.indexOf('#');
		const elementId = current_url.substring(hashIndex + 1);
		const element = document.getElementById(elementId);
		if (element) {
			setTimeout(() => {
				element.scrollIntoView({
					behavior: 'smooth',
					block: 'center'
				});
			}, 100);
		}
	}
})();
