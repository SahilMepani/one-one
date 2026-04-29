(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const sliders = document.querySelectorAll('.creative-slider');

	sliders.forEach((el, i) => {
		const swiperClass = 'creative-slider-' + i;
		el.classList.add(swiperClass);

		// navigation
		const parent = el.parentElement;
		const prevBtn = parent.querySelector('.swiper-button-prev');
		const nextBtn = parent.querySelector('.swiper-button-next');

		const prevClass = 'creative-button-prev-' + i;
		const nextClass = 'creative-button-next-' + i;

		if (prevBtn) prevBtn.classList.add(prevClass);
		if (nextBtn) nextBtn.classList.add(nextClass);

		// pagination
		const pagination = 'creative-pagination-' + i;
		const paginationEl = parent.querySelector('.swiper-pagination');
		if (paginationEl) paginationEl.classList.add(pagination);

		new Swiper('.' + swiperClass, {
			speed: 500,
			spaceBetween: 0,
			slidesPerView: 'auto',
			freeMode: {
				enabled: false,
				sticky: true
			},
			navigation: {
				prevEl: '.' + prevClass,
				nextEl: '.' + nextClass
			},
			pagination: {
				el: '.' + pagination,
				clickable: true
			},
			initialSlide: 3,
			effect: 'creative',
			centeredSlides: true,
			loop: true,

			creativeEffect: {
				prev: {
					translate: ['-100%', '55px', -500],
					scale: 0.8
				},
				next: {
					translate: ['100%', '55px', -500],
					scale: 0.8
				}
			}
		});
	});
})();

(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const imageSliders = document.querySelectorAll(
		'.text-image-slider-section .image-slider'
	);

	imageSliders.forEach((el, i) => {
		// Add unique class to image slider
		const imageSliderClass = 'image-slider-' + i;
		el.classList.add(imageSliderClass);

		// Find the .text-image-slider-section ancestor
		const section = el.closest('.text-image-slider-section');
		if (!section) return;

		// Add unique class to the corresponding text slider
		const textSliderEl = section.querySelector('.text-slider');
		const textSliderClass = 'text-slider-' + i;
		if (textSliderEl) textSliderEl.classList.add(textSliderClass);

		// Add unique class to navigation buttons
		const prevEl = textSliderEl?.querySelector('.swiper-button-prev');
		const nextEl = textSliderEl?.querySelector('.swiper-button-next');
		const prevClass = 'text-button-prev-' + i;
		const nextClass = 'text-button-next-' + i;
		if (prevEl) prevEl.classList.add(prevClass);
		if (nextEl) nextEl.classList.add(nextClass);

		// Initialize text (thumb) slider
		const swiperThumbInstance = new Swiper('.' + textSliderClass, {
			speed: 500,
			spaceBetween: 0,
			slidesPerView: 1,
			allowTouchMove: false,
			effect: 'fade',
			fadeEffect: {
				crossFade: true
			},
			navigation: {
				prevEl: '.' + prevClass,
				nextEl: '.' + nextClass
			}
		});

		// Initialize image slider
		const swiperInstance = new Swiper('.' + imageSliderClass, {
			speed: 500,
			slidesPerView: 1,
			grabCursor: true,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false
			},
			thumbs: {
				swiper: swiperThumbInstance
			}
		});

		// Sync image to text
		swiperInstance.on('slideChange', function () {
			swiperThumbInstance.slideTo(swiperInstance.realIndex);
		});

		// Sync text to image
		swiperThumbInstance.on('slideChange', function () {
			swiperInstance.slideTo(swiperThumbInstance.realIndex);
		});
	});
})();
