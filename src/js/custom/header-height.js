/**
 * Find the header height and set to
 * scroll-padding-top css property
 */
const siteHeader = document.querySelector('.site-header');

if (siteHeader) {
	(() => {
		const resizeHeaderHeight = () => {
			const headerHeight = siteHeader.offsetHeight;
			document.documentElement.style.setProperty(
				'--header-height',
				headerHeight + 'px'
			);
		};
		resizeHeaderHeight();
		let headerTimer;
		window.addEventListener('resize', () => {
			clearTimeout(headerTimer);
			headerTimer = setTimeout(resizeHeaderHeight, 100);
		}, { passive: true });
	})();
}
