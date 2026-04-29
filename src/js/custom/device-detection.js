(() => {
	/**
	 * Check if the user's screen primary input device is touch or not
	 * https://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript
	 */
	if (!window.matchMedia('(pointer: coarse)').matches) {
		document.documentElement.classList.add('js-no-touchevents');
	}

	// JavaScript to detect macOS and add a class to the body element
	function detectPlatform() {
		const platform = navigator.platform.toUpperCase();

		if (platform.indexOf('MAC') >= 0) {
			document.documentElement.classList.add('macos');
		} else if (platform.indexOf('WIN') >= 0) {
			document.documentElement.classList.add('windows');
		} else if (platform.indexOf('LINUX') >= 0) {
			document.documentElement.classList.add('linux');
		}
	}
	// Run the function on page load
	detectPlatform();
})();
