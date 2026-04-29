(() => {
	const targets = document.querySelectorAll('[data-modal-backdrop]');
	if (!targets.length) return;

	const body = document.body;
	let savedScrollY = 0;
	let isLocked = false;

	function lock() {
		if (isLocked) return;
		isLocked = true;
		savedScrollY = window.scrollY || window.pageYOffset || 0;
		body.style.position = 'fixed';
		body.style.insetInlineStart = '0';
		body.style.insetInlineEnd = '0';
		body.style.insetBlockStart = `-${savedScrollY}px`;
	}

	function unlock() {
		if (!isLocked) return;
		isLocked = false;
		body.style.position = '';
		body.style.insetInlineStart = '';
		body.style.insetInlineEnd = '';
		body.style.insetBlockStart = '';
		window.scrollTo({ top: savedScrollY, left: 0, behavior: 'instant' });
	}

	function update() {
		const anyActive = Array.from(targets).some(el =>
			el.classList.contains('js-active')
		);
		if (anyActive) lock();
		else unlock();
	}

	const observer = new MutationObserver(update);
	targets.forEach(el => {
		observer.observe(el, { attributes: true, attributeFilter: ['class'] });
	});
})();
