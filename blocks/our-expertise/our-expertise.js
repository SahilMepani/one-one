(() => {
	const init = () => {
		if (typeof Swiper === 'undefined') {
			console.warn('Swiper is not loaded');
			return;
		}

		const sliders = document.querySelectorAll('.our-expertise-slider');

		sliders.forEach((el, i) => {
			const swiperClass = `our-expertise-slider-${i}`;
			el.classList.add(swiperClass);

			const section = el.closest('.our-expertise-section');
			const prevBtn = section?.querySelector('.swiper-button-prev');
			const nextBtn = section?.querySelector('.swiper-button-next');
			const pagination = section?.querySelector('.swiper-pagination');

			if (prevBtn) prevBtn.classList.add(`${swiperClass}-prev`);
			if (nextBtn) nextBtn.classList.add(`${swiperClass}-next`);
			if (pagination) pagination.classList.add(`${swiperClass}-pagination`);

			const slides = el.querySelectorAll('.swiper-slide');
			if (slides.length <= 0) return;

			if (slides.length === 1) {
				slides.forEach(slide => slide.classList.add('swiper-slide-active'));
				return;
			}

			const swiper = new Swiper(`.${swiperClass}`, {
				slidesPerView: 1,
				spaceBetween: 32,
				speed: 500,
				grabCursor: true,
				navigation: {
					prevEl: `.${swiperClass}-prev`,
					nextEl: `.${swiperClass}-next`
				},
				pagination: {
					el: `.${swiperClass}-pagination`,
					clickable: true,
					type: 'bullets'
				}
			});

			if (pagination) {
				let prevSnap = swiper.snapIndex;

				swiper.on('snapIndexChange', () => {
					const direction = swiper.snapIndex >= prevSnap ? 'forward' : 'backward';
					pagination.setAttribute('data-direction', direction);
					prevSnap = swiper.snapIndex;
				});
			}
		});
	};

	if (typeof Swiper !== 'undefined') {
		init();
	} else {
		window.addEventListener('load', init, { once: true });
	}
})();
