/**
 * [Block Name] JavaScript Module
 *
 * Template for creating new ACF block JS files.
 * JS files are automatically generated and enqueued when block is used.
 *
 * @package Skeleton
 */

(() => {
	// ============================================
	// ELEMENT SELECTION
	// ============================================
	const sections = document.querySelectorAll('.block-name-section');

	// Early return if no elements
	if (!sections.length) return;

	// ============================================
	// MAIN LOGIC
	// ============================================
	sections.forEach((section, index) => {
		// Add unique identifier for multiple instances
		const uniqueClass = `block-name-${index}`;
		section.classList.add(uniqueClass);

		// Get child elements
		const items = section.querySelectorAll('.item');
		const buttons = section.querySelectorAll('.js-action-btn');

		// Early return if required elements missing
		if (!items.length) return;

		// ============================================
		// EVENT HANDLERS
		// ============================================
		const handleClick = event => {
			event.preventDefault();
			const target = event.currentTarget;
			target.classList.toggle('js-active');
		};

		const handleKeydown = event => {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				handleClick(event);
			}
		};

		// ============================================
		// BIND EVENTS
		// ============================================
		buttons.forEach(button => {
			button.addEventListener('click', handleClick);
			button.addEventListener('keydown', handleKeydown);
		});

		// ============================================
		// OPTIONAL: Intersection Observer
		// ============================================
		// const observer = new IntersectionObserver(
		// 	(entries) => {
		// 		entries.forEach((entry) => {
		// 			if (entry.isIntersecting) {
		// 				entry.target.classList.add('js-visible');
		// 				observer.unobserve(entry.target);
		// 			}
		// 		});
		// 	},
		// 	{ threshold: 0.1 }
		// );
		//
		// items.forEach((item) => observer.observe(item));
	});
})();

// ============================================
// SWIPER VARIANT (for slider blocks)
// See .cursor/rules/swiper-standards.mdc for the full pattern.
// ============================================
/*
(() => {
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper is not loaded');
		return;
	}

	const sliders = document.querySelectorAll('.{slug}-slider');

	sliders.forEach((el, i) => {
		const swiperClass = `{slug}-slider-${i}`;
		el.classList.add(swiperClass);

		const section = el.closest('.{slug}-section');
		const prevBtn = section?.querySelector('.swiper-button-prev');
		const nextBtn = section?.querySelector('.swiper-button-next');
		const pagination = section?.querySelector('.swiper-pagination');
		const scrollbar = section?.querySelector('.swiper-scrollbar');

		if (prevBtn) prevBtn.classList.add(`${swiperClass}-prev`);
		if (nextBtn) nextBtn.classList.add(`${swiperClass}-next`);
		if (pagination) pagination.classList.add(`${swiperClass}-pagination`);
		if (scrollbar) scrollbar.classList.add(`${swiperClass}-scrollbar`);

		const slides = el.querySelectorAll('.swiper-slide');
		if (slides.length <= 0) return;

		// Single slide — just mark active, no Swiper needed
		if (slides.length === 1) {
			slides.forEach(slide => slide.classList.add('swiper-slide-active'));
			return;
		}

		const swiper = new Swiper(`.${swiperClass}`, {
			slidesPerView: 'auto',
			spaceBetween: 16,
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

		// Directional animation — bind AFTER init using snapIndexChange
		// (slideChange + activeIndex is unreliable with slidesPerView: 'auto')
		if (pagination || scrollbar) {
			let prevSnap = swiper.snapIndex;

			swiper.on('snapIndexChange', () => {
				const direction =
					swiper.snapIndex >= prevSnap ? 'forward' : 'backward';

				if (pagination) {
					pagination.setAttribute('data-direction', direction);
				}

				if (scrollbar) {
					scrollbar.setAttribute('data-direction', direction);
				}

				prevSnap = swiper.snapIndex;
			});
		}
	});
})();
*/
