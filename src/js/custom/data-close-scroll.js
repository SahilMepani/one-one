(() => {
	const elements = document.querySelectorAll('[data-close-scroll]');
	if (!elements.length) return;

	let hasScrolled = false;
	window.addEventListener('scroll', () => {
		if (!hasScrolled && window.scrollY > 10) {
			hasScrolled = true;
			elements.forEach(el => el.classList.remove('js-active'));
		}
	});
})();
