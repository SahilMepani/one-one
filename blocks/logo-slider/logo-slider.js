(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const sliders = document.querySelectorAll('.logo-slider');

	sliders.forEach((el, i) => {
		const swiperClass = `logo-slider-${i}`;
		el.classList.add(swiperClass);

		new Swiper(`.${swiperClass}`, {
			speed: 3000,
			loop: true,
			autoplay: {
				delay: 0
			},
			slidesPerView: 'auto',
			spaceBetween: 40,
			allowTouchMove: false,
			// disableOnInteraction: false,
			breakpoints: {
				992: {
					spaceBetween: 80
				}
			}
		});
	});
})();
