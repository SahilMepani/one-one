document.addEventListener("DOMContentLoaded", function() {
(() => {
	const btnMorePost = document.getElementById('ajax-more-post');
	const formSearchPost = document.getElementById('ajax-search-post');
	const loadingDots = document.querySelector('.loading-dots');
	const ajaxListPost = document.getElementById('ajax-list-post');

	// Load More Post
	btnMorePost?.addEventListener('click', e => {
		e.preventDefault();
		btnMorePost.classList.add('disabled');
		updatePost(e.currentTarget, 'filter_more');
	});

	// Filter Search Post
	formSearchPost?.addEventListener('submit', e => {
		e.preventDefault();

		document.getElementById('ajax-submit-block')?.classList.add('d-none');
		document
			.getElementById('ajax-search-clear')
			?.classList.remove('js-active');

		formSearchPost
			.querySelector('.loading-spinner')
			?.classList.add('js-active');

		const searchValue =
			formSearchPost.querySelector('.input-search')?.value || '';
		document.getElementById('filter-search').value = searchValue;

		updatePost(formSearchPost, 'filter_search');
	});

	// Clear Search Post
	document
		.getElementById('ajax-search-clear')
		?.addEventListener('click', e => {
			e.preventDefault();

			formSearchPost.querySelector('.input-search').value = '';
			document.getElementById('filter-search').value = '';

			formSearchPost.dispatchEvent(
				new Event('submit', { bubbles: true })
			);
		});

	// Filter Categories Post
	document
		.getElementById('ajax-filter-cat')
		?.addEventListener('change', e => {
			const selected = e.target.options[e.target.selectedIndex];
			const term = selected?.dataset.term || '';
			document.getElementById('filter-term').value = term;
			updatePost(selected, 'filter_term');
		});

	function updatePost(triggerElement, triggerType) {
		document.getElementById('alert-no-data')?.classList.add('d-none');
		loadingDots?.classList.add('js-active');

		const isSearchOrTerm =
			triggerType === 'filter_search' || triggerType === 'filter_term';

		if (isSearchOrTerm) {
			btnMorePost?.style.setProperty('display', 'none');
			Array.from(ajaxListPost.children).forEach(li => {
				li.style.opacity = '0';
				setTimeout(() => li.remove(), 400);
			});
			document.getElementById('filter-pagenum').value = 1;
		}

		const getVal = id => document.getElementById(id)?.value || '';
		const getData = (el, attr) => el?.dataset?.[attr] || '';

		const data = {
			action: 'update_post_ajax',
			cpt: getData(triggerElement, 'cpt'),
			tax: getData(triggerElement, 'tax'),
			term: getVal('filter-term'),
			authorID: getVal('filter-author-id'),
			tagID: getVal('filter-tag-id'),
			search: getVal('filter-search'),
			pageNumber: getVal('filter-pagenum'),
			postsPerPage: getVal('filter-posts-per-page')
		};

		let unseenPostCount =
			parseInt(getVal('filter-unseen-post-count'), 10) || 0;

		fetch(localize_var.ajax_url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			body: new URLSearchParams(data)
		})
			.then(res => res.text())
			.then(html => {
				const temp = document.createElement('div');
				temp.innerHTML = html.trim();
				const newItems = Array.from(temp.children);

				if (newItems.length) {
					loadingDots?.classList.remove('js-active');

					if (triggerType === 'filter_more') {
						unseenPostCount -= newItems.length;
						document.getElementById('filter-pagenum').value =
							parseInt(data.pageNumber) + 1;
						document.getElementById(
							'filter-unseen-post-count'
						).value = unseenPostCount;

						newItems.forEach(el => ajaxListPost.appendChild(el));

						window.scrollTo({
							top: newItems[0].offsetTop - 40,
							behavior: 'auto'
						});
					}

					if (triggerType === 'filter_search') {
						setTimeout(() => {
							if (data.search !== '') {
								document
									.getElementById('ajax-search-clear')
									?.classList.add('js-active');
							} else {
								document
									.getElementById('ajax-submit-block')
									?.classList.remove('d-none');
							}
							formSearchPost
								.querySelector('.loading-spinner')
								?.classList.remove('js-active');
							newItems.forEach(el =>
								ajaxListPost.appendChild(el)
							);
							ajaxListPost.style.opacity = 1;
							btnMorePost?.style.removeProperty('display');
						}, 300);
					}

					if (triggerType === 'filter_term') {
						ajaxListPost.innerHTML = '';
						setTimeout(() => {
							newItems.forEach(el =>
								ajaxListPost.appendChild(el)
							);
							ajaxListPost.style.opacity = 1;
							btnMorePost?.style.removeProperty('display');
						}, 300);
					}

					if (unseenPostCount > 0) {
						btnMorePost?.classList.remove('disabled');
					}
				} else {
					if (
						formSearchPost
							.querySelector('.loading-spinner')
							?.classList.contains('js-active')
					) {
						document
							.getElementById('ajax-search-clear')
							?.classList.add('js-active');
					}
					formSearchPost
						.querySelector('.loading-spinner')
						?.classList.remove('js-active');
					document
						.getElementById('alert-no-data')
						?.classList.remove('d-none');
					loadingDots?.classList.remove('js-active');
					btnMorePost?.style.setProperty('display', 'none');
				}
			})
			.catch(err => {
				console.error('AJAX Error:', err);
				loadingDots?.classList.remove('js-active');
				btnMorePost?.classList.remove('disabled');
				formSearchPost
					.querySelector('.loading-spinner')
					?.classList.remove('js-active');
				const alertNoData = document.getElementById('alert-no-data');
				if (alertNoData) {
					alertNoData.textContent =
						'Failed to load content. Please try again.';
					alertNoData.classList.remove('d-none');
				}
			});
	}
})();

(() => {
	const elements = document.querySelectorAll('[data-close-click-out]');

	document.addEventListener('click', function (event) {
		// Skip if clicked element or its parent has data-toggle-click (i.e., inside the toggle trigger)
		if (
			event.target.hasAttribute('data-toggle-click') ||
			event.target.closest('[data-toggle-click]')
		) {
			return;
		}

		elements.forEach(function (element) {
			// Check if the click was outside the element
			if (!element.contains(event.target)) {
				element.classList.remove('js-active');

				// If it has data-toggle-link, find matching toggle-click element and remove js-active
				const toggleLinkValue =
					element.getAttribute('data-toggle-link');
				if (toggleLinkValue) {
					document
						.querySelectorAll(
							`[data-toggle-click="${toggleLinkValue}"]`
						)
						.forEach(toggleClickEl => {
							toggleClickEl.classList.remove('js-active');
						});
				}
			}
		});
	});
})();

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

(function () {
	// One observer per unique (margin + thresholds) key
	const observers = new Map();

	function observeInviewElements(context = document) {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			console.info(
				'[InView] Reduced motion preference detected, skipping observers.'
			);
			return;
		}

		const elements = context.querySelectorAll(
			'[data-inview]:not([data-inview-initialized]), [data-inview-repeat]:not([data-inview-initialized])'
		);

		elements.forEach(el => {
			// Accept px ("56"), percent ("7%"), or vh ("7vh") for convenience.
			const offsetRaw = el.getAttribute('data-inview-offset') || '7%';
			const thresholdAttr = el.getAttribute('data-inview-threshold');

			// Safari sometimes misses bare 0; give it a tiny ladder of thresholds
			const thresholds = thresholdAttr
				? thresholdAttr.split(',').map(v => parseFloat(v.trim()))
				: [0, 0.001, 0.01];

			// Optional: set a scroll container as root
			// Example: data-inview-root="#scrollWrap"
			const rootSel = el.getAttribute('data-inview-root');
			const rootEl = rootSel ? document.querySelector(rootSel) : null;

			// Build a stable rootMargin string that avoids visualViewport quirks.
			// IntersectionObserver supports px and % in rootMargin. We’ll translate:
			const margin = normalizeRootMargin(offsetRaw);

			const key = `${rootSel || 'viewport'}|${margin}|${thresholds.join('/')}`;
			let observer = observers.get(key);

			if (!observer) {
				observer = new IntersectionObserver(handleIntersect, {
					root: rootEl || null,
					rootMargin: margin,
					threshold: thresholds
				});
				observers.set(key, observer);
			}

			el.setAttribute('data-inview-initialized', 'true');

			// Defer observe to next frame to ensure layout is settled (iOS quirk)
			requestAnimationFrame(() => {
				observer.observe(el);
				// Manual kickstart: if already in view at load, flip it on.
				if (isVisiblyInViewport(el, rootEl, offsetRaw)) {
					el.dataset.inview = 'true';
				}
			});
		});
	}

	function handleIntersect(entries) {
		for (const entry of entries) {
			const target = entry.target;
			const repeat = target.hasAttribute('data-inview-repeat');

			if (entry.isIntersecting) {
				target.dataset.inview = 'true';
			} else if (repeat) {
				target.removeAttribute('data-inview');
			}
		}
	}

	// Translate various offset formats into a rootMargin string.
	// input like "7%", "7vh", "56", "56px"
	function normalizeRootMargin(offset) {
		const toNumber = v =>
			parseFloat(String(v).replace(/[^\d.\-]/g, '')) || 0;

		if (String(offset).endsWith('vh') || String(offset).endsWith('svh')) {
			// Convert vh-ish to percent. 7vh ≈ 7%.
			const v = toNumber(offset);
			return `${v}% 0px -${v}% 0px`;
		}
		if (String(offset).endsWith('%')) {
			const v = toNumber(offset);
			return `${v}% 0px -${v}% 0px`;
		}
		// px (number or "px")
		const v = toNumber(offset);
		return `${v}px 0px -${v}px 0px`;
	}

	// Manual visibility check to kickstart iOS when observer doesn’t fire yet.
	function isVisiblyInViewport(el, rootEl, offset) {
		const rect = el.getBoundingClientRect();
		const rootRect = rootEl
			? rootEl.getBoundingClientRect()
			: {
					top: 0,
					left: 0,
					right: window.innerWidth,
					bottom: window.innerHeight,
					height: window.innerHeight
				};

		// Convert offset to px relative to root height for a conservative test
		const toPx = v => {
			const s = String(v);
			const n = parseFloat(s);
			if (s.endsWith('vh') || s.endsWith('svh'))
				return (n / 100) * rootRect.height;
			if (s.endsWith('%')) return (n / 100) * rootRect.height;
			return n || 0;
		};
		const offPx = toPx(offset);

		const topLimit = rootRect.top + offPx;
		const bottomLimit = rootRect.bottom - offPx;

		const verticallyIn = rect.bottom >= topLimit && rect.top <= bottomLimit;
		const horizontallyIn =
			rect.right >= rootRect.left && rect.left <= rootRect.right;

		return verticallyIn && horizontallyIn;
	}

	// Rebuild observers on viewport changes (iOS toolbars/orientation)
	function rebuild() {
		// Disconnect all old observers
		observers.forEach(obs => obs.disconnect());
		observers.clear();
		// Remove initialized flag so elements get reattached cleanly
		document
			.querySelectorAll('[data-inview-initialized]')
			.forEach(el => el.removeAttribute('data-inview-initialized'));
		observeInviewElements();
	}

	// Init
	if ('IntersectionObserver' in window) {
		observeInviewElements();
		window.observeInviewElements = observeInviewElements;

		// Resize/orientation changes on iOS can invalidate margins
		window.addEventListener('orientationchange', rebuild, {
			passive: true
		});

		let rebuildTimer;
		window.addEventListener('resize', () => {
			clearTimeout(rebuildTimer);
			rebuildTimer = setTimeout(rebuild, 200);
		}, { passive: true });

		// If supported, also listen to visualViewport changes
		if (window.visualViewport) {
			let vpTimer;
			window.visualViewport.addEventListener('resize', () => {
				clearTimeout(vpTimer);
				vpTimer = setTimeout(rebuild, 200);
			}, { passive: true });
		}
	} else {
		console.warn(
			'[InView] IntersectionObserver not supported by this browser.'
		);
	}
})();

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

(() => {
	// Select all elements that should have sticky detection
	const stickyElements = document.querySelectorAll('[data-sticky]');
	if (!stickyElements.length) return;

	// Store observers (useful if you ever want to disconnect later)
	const observers = [];

	stickyElements.forEach(stickyEl => {
		// Read the computed `top` value of the sticky element
		// This ensures correct behavior even if top is not 0
		const style = getComputedStyle(stickyEl);
		const topOffset = parseFloat(style.top) || 0;

		// --------------------------------------------------
		// Create a sentinel element
		// --------------------------------------------------
		// The sentinel acts as a reference point:
		// When it scrolls out of view, the sticky element
		// has entered its "stuck" state
		const sentinel = document.createElement('div');
		sentinel.setAttribute('aria-hidden', 'true');

		// Keep sentinel invisible and non-intrusive
		sentinel.style.position = 'absolute';
		sentinel.style.top = '0';
		sentinel.style.left = '0';
		sentinel.style.width = '1px';
		sentinel.style.height = '1px';
		sentinel.style.pointerEvents = 'none';

		// Insert sentinel directly before the sticky element
		stickyEl.parentNode.insertBefore(sentinel, stickyEl);

		// Track current sticky state to avoid unnecessary DOM updates
		let isSticky = false;

		// --------------------------------------------------
		// Create IntersectionObserver
		// --------------------------------------------------
		const observer = new IntersectionObserver(
			entries => {
				const entry = entries[0];

				// When intersectionRatio === 0:
				// → sentinel is outside the viewport
				// → sticky element is currently stuck
				const currentlySticky = entry.intersectionRatio === 0;

				// Only update DOM if sticky state has changed
				if (currentlySticky !== isSticky) {
					isSticky = currentlySticky;

					// Toggle class on the sticky element
					stickyEl.classList.toggle('is-sticky', isSticky);
				}
			},
			{
				// Observe viewport (use a container here if needed)
				root: null,

				// Trigger callback exactly when sentinel enters or leaves
				threshold: [0],

				// Offset observer by the sticky `top` value
				// This aligns detection with CSS `position: sticky`
				rootMargin: `-${topOffset}px 0px 0px 0px`
			}
		);

		// Start observing the sentinel
		observer.observe(sentinel);

		// Store observer reference
		observers.push(observer);
	});
})();

(() => {
	let leaveTimeout;

	// ==========================
	// CLICK HANDLER
	// ==========================
	function handleClickToggle(element) {
		const toggleClick = element.getAttribute('data-toggle-click');
		const toggleGroup = element.getAttribute('data-toggle-group');

		// If already active and has a data-tab attribute, do nothing
		if (
			element.classList.contains('js-active') &&
			element.hasAttribute('data-tab')
		) {
			return;
		}

		// Determine if we are activating or deactivating
		const isNowActive = !element.classList.contains('js-active');

		// 1. Deactivate other items in the same group
		if (toggleGroup) {
			document
				.querySelectorAll(`[data-toggle-group="${toggleGroup}"]`)
				.forEach(groupElement => {
					if (groupElement !== element) {
						groupElement.classList.remove('js-active');
						groupElement.setAttribute('aria-expanded', 'false');
					}
				});
		}

		// 2. Toggle all elements with the same data-toggle-click
		if (toggleClick) {
			document
				.querySelectorAll(`[data-toggle-click="${toggleClick}"]`)
				.forEach(toggleElement => {
					toggleElement.classList.toggle('js-active', isNowActive);
					toggleElement.setAttribute(
						'aria-expanded',
						isNowActive ? 'true' : 'false'
					);
				});
		}

		// 3. Toggle corresponding data-toggle-link elements
		if (toggleClick) {
			document
				.querySelectorAll(`[data-toggle-link="${toggleClick}"]`)
				.forEach(linkedElement => {
					const linkedGroup =
						linkedElement.getAttribute('data-toggle-group');

					// Deactivate others in the same linked group
					if (linkedGroup) {
						document
							.querySelectorAll(
								`[data-toggle-group="${linkedGroup}"]`
							)
							.forEach(sibling => {
								if (sibling !== linkedElement) {
									sibling.classList.remove('js-active');
									sibling.setAttribute('aria-hidden', 'true');
									// Also manage lenis for siblings if they were linked and active
									if (
										sibling.hasAttribute(
											'data-toggle-lenis'
										)
									) {
										sibling.removeAttribute(
											'data-lenis-prevent'
										); // remove data-lenis when inactive
									}
								}
							});
					}

					// Apply new state
					linkedElement.classList.toggle('js-active', isNowActive);
					linkedElement.setAttribute(
						'aria-hidden',
						isNowActive ? 'false' : 'true'
					);

					// === NEW LOGIC FOR DATA-LENIS ===
					if (linkedElement.hasAttribute('data-toggle-lenis')) {
						if (isNowActive) {
							// If it's becoming active, set data-lenis
							linkedElement.setAttribute(
								'data-lenis-prevent',
								'true'
							);
						} else {
							// If it's becoming inactive, remove data-lenis
							linkedElement.removeAttribute('data-lenis-prevent');
						}
					}
				});
		}
	}

	document.querySelectorAll('[data-toggle-click]').forEach(element => {
		element.addEventListener('click', function (event) {
			event.preventDefault(); // prevent form submission or anchor behavior
			handleClickToggle(event.currentTarget);
		});
	});

	// ==========================
	// HOVER HANDLER
	// ==========================
	function handleMouseEnter(element) {
		const toggleHover = element.getAttribute('data-toggle-hover');
		element.classList.add('js-active');

		if (toggleHover) {
			document
				.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
				.forEach(linkedElement => {
					linkedElement.classList.add('js-active');
				});
		}

		if (leaveTimeout) {
			clearTimeout(leaveTimeout);
		}
	}

	function handleMouseLeave(element) {
		const toggleHover = element.getAttribute('data-toggle-hover');

		leaveTimeout = setTimeout(() => {
			let isMouseOverLinked = false;

			if (element.matches(':hover')) {
				isMouseOverLinked = true;
			} else if (toggleHover) {
				document
					.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
					.forEach(linkedElement => {
						if (linkedElement.matches(':hover')) {
							isMouseOverLinked = true;
						}
					});
			}

			if (!isMouseOverLinked) {
				element.classList.remove('js-active');

				if (toggleHover) {
					document
						.querySelectorAll(`[data-toggle-link="${toggleHover}"]`)
						.forEach(linkedElement => {
							linkedElement.classList.remove('js-active');
						});
				}
			}
		}, 50);
	}

	document.querySelectorAll('[data-toggle-hover]').forEach(element => {
		element.addEventListener('mouseenter', event =>
			handleMouseEnter(event.currentTarget)
		);
		element.addEventListener('mouseleave', event =>
			handleMouseLeave(event.currentTarget)
		);

		// Touch support
		element.addEventListener('touchstart', event => {
			event.preventDefault();
			handleMouseEnter(event.currentTarget);
		});
		element.addEventListener('touchend', event => {
			event.preventDefault();
			handleMouseLeave(event.currentTarget);
		});
	});

	// ==========================
	// HOVER: LINKED ELEMENT SUPPORT
	// ==========================
	document.querySelectorAll('[data-toggle-link]').forEach(element => {
		element.addEventListener('mouseenter', () => {
			if (leaveTimeout) clearTimeout(leaveTimeout);
		});

		element.addEventListener('mouseleave', () => {
			// Try to find its associated hover origin (if exists)
			document
				.querySelectorAll('[data-toggle-hover]')
				.forEach(hoverElement => {
					const hoverTarget =
						hoverElement.getAttribute('data-toggle-hover');
					const linkedTarget =
						element.getAttribute('data-toggle-link');
					if (hoverTarget === linkedTarget) {
						handleMouseLeave(hoverElement);
					}
				});
		});
	});
})();

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

(() => {
	// ------------------------------------
	// Constants
	// ------------------------------------
	const VIDEO_OPTIONS = {
		youtube:
			'?autoplay=1&rel=0&showinfo=0&iv_load_policy=3&modestbranding=1&disablekb=1',
		vimeo: '?autoplay=1&title=0&byline=0&portrait=0'
	};

	// ------------------------------------
	// Helper Functions
	// ------------------------------------

	// Helper function to stop Lenis scroll
	const handleLenisStop = () => {
		if (typeof lenis !== 'undefined' && lenis.stop) {
			lenis.stop();
		}
	};

	// Helper function to start Lenis scroll
	const handleLenisStart = () => {
		if (typeof lenis !== 'undefined' && lenis.start) {
			lenis.start();
		}
	};

	// Reset media (video and iframe) inside a dialog
	const resetMedia = dialog => {
		const video = dialog.querySelector('.js-video');
		if (video) {
			video.pause();
			video.currentTime = 0;
		}
		const iframe = dialog.querySelector('.js-iframe');
		if (iframe) {
			// Reset source to stop playing (by re-assigning the clean source if stored, or just src)
			// Ideally we want to remove the autoplay params, but resetting src works to stop it.
			// Best practice: store original src in data attribute if we want to reset completely,
			// but here we just need to stop playback.
			const src = iframe.src;
			iframe.src = src; // Reloading stops the video
		}
	};

	// Setup media for playback
	const playMedia = dialog => {
		const video = dialog.querySelector('.js-video');
		if (video) {
			video.play();
		}

		const iframe = dialog.querySelector('.js-iframe');
		if (iframe) {
			const baseVideoURL = iframe.getAttribute('data-video-url');
			if (baseVideoURL) {
				let embedOptions = '';
				if (baseVideoURL.includes('youtube')) {
					embedOptions = VIDEO_OPTIONS.youtube;
				} else if (baseVideoURL.includes('vimeo')) {
					embedOptions = VIDEO_OPTIONS.vimeo;
				}
				iframe.src = `${baseVideoURL}${embedOptions}`;
			}
		}
	};

	// ------------------------------------
	// Event Listeners
	// ------------------------------------

	document.addEventListener('click', e => {
		// 1. Handle Open Button
		const openBtn = e.target.closest('.js-dialog-open');
		if (openBtn) {
			e.preventDefault();
			const dialogId = openBtn.getAttribute('data-dialog');
			const targetDialog = document.querySelector(
				`.js-dialog[data-dialog="${dialogId}"]`
			);

			if (!targetDialog) {
				console.warn(`No dialog found for data-dialog="${dialogId}"`);
				return;
			}

			// Setup and Show
			playMedia(targetDialog);
			handleLenisStop();
			document.body.setAttribute('inert', ''); // This might be too aggressive if not polyfilled correctly or if it affects the dialog itself (the dialog should be outside the inert root usually, or inert logic needs to be mindful).
			// Standard HTML5 <dialog> handles inertness of background automatically when using showModal().
			// Manually setting inert on body might make the *dialog* inert if it is a child of body.
			// However, checking the original code, it was setting inert on body.
			// If <dialog> is a direct child of body, `body.setAttribute('inert')` makes the dialog inert too.
			// Assuming the modal is moved to top level or the user knows what they are doing.
			// I will keep the original behavior but add a safety check or comment.
			// Actually, removing 'inert' from body might be better if using showModal(), as showModal() handles backend interaction blocking.
			// But for now, I'll stick to the user's pattern but be aware.

			// Wait, if <dialog> is inside <body>, setting inert on <body> kills the dialog.
			// The original code did `document.body.setAttribute('inert', '')`. This is likely a bug unless the dialog is moved out of body (impossible) or the script removes inert from the dialog specifically (which you can't really do if parent is inert).
			// Maybe they meant `document.querySelector('main').setAttribute('inert', '')`?
			// I will REMOVE the inert setting on body because `showModal()` natively handles the backdrop.
			// The original code had it, maybe it was causing issues they didn't notice? or maybe they use a polyfill that handles it?
			// I'll comment it out for safety unless I see a specific reason.
			// actually, let's keep it but maybe it was a mistake in original. I will remove it to be "Standard".
			// `showModal` makes everything else inert.

			// Re-reading 'dialog-polyfill' or standard behavior: showModal makes the dialog the only interactive part.
			// So `document.body.setAttribute('inert', '')` is definitely WRONG if the dialog is in the body.
			// I will remove the `inert` manipulation on body.

			targetDialog.showModal();

			// Attach cleanup on close
			targetDialog.addEventListener(
				'close',
				() => {
					handleLenisStart();
					// document.body.removeAttribute('inert'); // Removed counterpart
					resetMedia(targetDialog);
				},
				{ once: true }
			);

			return;
		}

		// 2. Handle Close Button
		const closeBtn = e.target.closest('.js-dialog-close');
		if (closeBtn) {
			const dialog = closeBtn.closest('dialog');
			if (dialog) {
				e.preventDefault();
				dialog.close();
			}
			return;
		}

		// 3. Handle Backdrop Click
		if (
			e.target.tagName === 'DIALOG' &&
			e.target.classList.contains('js-dialog')
		) {
			e.target.close();
		}
	});
})();

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

(() => {
	// ------------------------------------
	// 1. Cache elements using const
	// ------------------------------------
	const headerNavToggle = document.querySelector('.header-nav-toggle');
	const headerNav = document.querySelector('.header-nav');
	const body = document.body;
	const headerNavClose = document.querySelector('.header-nav-close');
	const parentMenuItems = document.querySelectorAll(
		'.header-menu li:has(.header-sub-menu) > a'
	);

	// CSS classes for visual toggling
	const activeClass = 'js-active';
	const popupActiveClass = 'js-popup-active';

	// ------------------------------------
	// 2. Helper functions for navigation state
	// ------------------------------------

	/**
	 * Opens the main navigation menu, updating visual and ARIA states.
	 */
	const openNavigation = () => {
		// Add classes for visual styling (e.g., show menu, disable body scroll)
		headerNavToggle.classList.add(activeClass);
		headerNav.classList.add(activeClass);
		body.classList.add(popupActiveClass);
		body.setAttribute('data-lenis-prevent', 'true');

		// Update ARIA attributes for accessibility
		headerNavToggle.setAttribute('aria-expanded', 'true'); // Button now expands
		headerNav.setAttribute('aria-hidden', 'false'); // Menu is now visible to screen readers
		headerNavClose.setAttribute('aria-expanded', 'true'); // Close button's state reflects menu is open
	};

	/**
	 * Closes the main navigation menu, updating visual and ARIA states.
	 */
	const closeNavigation = () => {
		// Remove classes for visual styling
		headerNavToggle.classList.remove(activeClass);
		headerNav.classList.remove(activeClass);
		body.classList.remove(popupActiveClass);
		body.removeAttribute('data-lenis-prevent');

		// Update ARIA attributes for accessibility
		headerNavToggle.setAttribute('aria-expanded', 'false'); // Button now collapses
		headerNav.setAttribute('aria-hidden', 'true'); // Menu is now hidden from screen readers
		headerNavClose.setAttribute('aria-expanded', 'false'); // Close button's state reflects menu is closed
	};

	// ------------------------------------
	// 3. Event Listeners for main navigation toggle
	// ------------------------------------

	// Header navigation toggle button
	if (headerNavToggle) {
		// Ensure the element exists before adding listener
		headerNavToggle.addEventListener('click', e => {
			e.preventDefault(); // Stop default button behavior

			// Check if the menu is currently open to decide action
			const isMenuOpen = headerNav.classList.contains(activeClass);
			if (isMenuOpen) {
				closeNavigation();
			} else {
				openNavigation();
			}
		});
	}

	// Header navigation close button
	if (headerNavClose) {
		// Ensure the element exists before adding listener
		headerNavClose.addEventListener('click', e => {
			e.preventDefault(); // Stop default button behavior
			closeNavigation(); // Always close when this button is clicked
		});
	}

	// ------------------------------------
	// 4. Mobile parent menu dropdown functionality
	// ------------------------------------

	if (parentMenuItems) {
		// Ensure parent menu items exist
		parentMenuItems.forEach(anchorElement => {
			// Create the <chevron> element
			const chevron = document.createElement('span');
			chevron.classList.add('chevron');
			// Append the <chevron> to the <a>
			anchorElement.appendChild(chevron);

			// Add click listener to the newly created <chevron>
			chevron.addEventListener('click', function (e) {
				e.preventDefault(); // Prevent the main link from navigating

				// Toggle 'js-active' class on the parent <li>
				// The 'this' context refers to the <chevron>, so its parentElement is the <a>,
				// and the parentElement of the <a> is the <li>.
				this.closest('li').classList.toggle(activeClass);

				// IMPORTANT: Manage ARIA for the dropdown
				// Find the direct child <ul> (submenu) of the <li>
				const subMenu =
					this.closest('li').querySelector('ul.header-sub-menu');
				if (subMenu) {
					const isExpanded =
						this.closest('li').classList.contains(activeClass);
					// Set aria-expanded on the <a> (or the chevron, or the <li> if that's what controls the dropdown)
					// For a better experience, aria-expanded should be on the interactive element (the <a> or the span acting as a button).
					// And aria-hidden on the dropdown content (the <ul>).
					this.setAttribute('aria-expanded', isExpanded);
					subMenu.setAttribute('aria-hidden', !isExpanded);
				}
			});
		});
	}
})();

const lenis = new Lenis({
	wheelMultiplier: 1.1
});

function raf(time) {
	lenis.raf(time);
	requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

/*==============================================
=            Magnific Popup - Basic            =
==============================================*/
// $( '.popup-link' ).magnificPopup( {
// 	mainClass: 'mfp-fade'
// } );

/*====================================================
=            Manually Open Magnific Popup            =
====================================================*/
// $( '.trigger-form' ).on( 'click', function() {
// 	$.magnificPopup.open({
//     items: {
//       src: '.popup-block'
//     },
//     type: 'inline',
//     mainClass: 'mfp-zoom-in', // add class for animation
//     removalDelay: 500, // delay removal by X to allow out-animation
//   });
// } );

/*=======================================================
=            Open gallery from external link            =
=======================================================*/
// $( '.view-gallery' ).on( 'click', function() {
// 	$( '.gallery' ).magnificPopup( 'open' );
// 	// $(this).next().magnificPopup('open');
// } );

/*================================================
=            Magnific Popup - Gallery            =
================================================*/
// $( '.gallery-grid' ).magnificPopup( {
// 	delegate: 'a', // child items selector, by clicking on it popup will open
// 	type: 'image',
// 	gallery: {
// 		enabled: true,
// 		preload: [ 0, 1 ]
// 	},
// 	mainClass: 'mfp-zoom-in mfp-fade', // add class for animation
// 	removalDelay: 300, // delay removal by X to allow out-animation
// 	titleSrc: 'title', // custom function will nest inside image: {}
// 	callbacks: {
// 		imageLoadComplete: function() {
// 			var self = this;
// 			setTimeout( function() {
// 				self.wrap.addClass( 'mfp-image-loaded' );
// 			}, 16 );
// 		},
// 		close: function() {
// 			this.wrap.removeClass( 'mfp-image-loaded' );
// 		}
// 	}
// } );

/*==============================================
=            Magnific Popup - Video            =
==============================================*/
// $(".popup-video").magnificPopup({
// 	type: "iframe",
// 	removalDelay: 300,
// 	mainClass: "mfp-fade",
// 	fixedContentPos: false, // disable scrollbar
// 	iframe: {
// 		patterns: {
// 			youtube: {
// 				index: "youtube.com/", // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
// 				id: "v=" // String that splits URL in a two parts, second part should be %id%
// 				// Or null - full URL will be returned
// 				// Or a function that should return %id%, for example:
// 				// id: function(url) { return 'parsed id'; }
// 				// src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0' // URL that will be set as a source for iframe.
// 			},
// 			vimeo: {
// 				index: "vimeo.com/",
// 				id: "/",
// 				src: "//player.vimeo.com/video/%id%?autoplay=1"
// 			}
// 		}
// 	}
// })

/*====================================================
=            Magnific Popup - Members Bio            =
====================================================*/
// all the popup should have a same class
/*$( '.list-members' ).magnificPopup( {
  delegate: 'a',
  mainClass: 'mfp-move-from-top',
  removalDelay: 500, // delay removal by X to allow out-animation
  midClick: true,
  gallery: {
	enabled: true
  }
} );*/

/*====================================================
=            Open a popup after 2 seconds            =
====================================================*/
// setTimeout(function () {
// 	$.magnificPopup.open({
// 		items: {
// 			src: "#subscribe-modal"
// 		},
// 		removalDelay: 300,
// 		mainClass: "mfp-fade"
// 	});
// }, 2000);

/*====================================
=            Custom Title            =
====================================*/
// $( '.list-members' ).magnificPopup( {
// 	image: {
// 		titleSrc: function( item ) {
// 			return item.el.attr( 'title' ) + ' - <a href="' + item.el.parents( 'li' ).find( '.download-link' ).attr( 'href' ) + '">Download</a>';
// 		}
// 	}
// } );

/*=======================================================================
=            Next/Previous Arrows for gallery inside content            =
=======================================================================*/
// $( '.element' ).magnificPopup( {
// 	callbacks: {
// 		buildControls: function() {
// 			// re-appends controls inside the main container
// 			this.content.append( this.arrowLeft.add( this.arrowRight ) ); //content is predefined property. Check API
// 		}
// 	}
// } )

/*==============================================================================================
=            Open on load and custom close with cookie set - REQUIRES cookie plugin            =
==============================================================================================*/
// if ( $( 'body' ).hasClass( 'page-template-literature' ) ) {
// 	if ( Cookies.get( '2022' ) != '1' ) {
// 		$.magnificPopup.open( {
// 			items: {
// 				src: '#terms-modal'
// 			},
// 			removalDelay: 300,
// 			mainClass: 'mfp-fade terms-modal',
// 			closeOnBgClick: false,
// 			showCloseBtn: false,
// 			enableEscapeKey: false
// 		} );

// 		$( '.terms-modal .btn-accept' ).click( function( e ) {
// 			$.magnificPopup.close();
// 			Cookies.set( '2022', '1', {
// 				expires: 1
// 			} );
// 		} );
// 	}
// }

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

(() => {
	const siteHeader = document.querySelector('.site-header');
	if (!siteHeader) return;

	// Variable to store the last known vertical scroll position
	let lastScrollTop = 0;
	// Flag to prevent multiple requestAnimationFrame calls
	let ticking = false;

	// Function to handle the scroll event
	function handleScroll() {
		// Get the current scroll position
		const st = window.pageYOffset || document.documentElement.scrollTop;
		// Determine the scroll direction
		const direction = st >= lastScrollTop ? 'down' : 'up';
		// Toggle site header class
		if (siteHeader) {
			if (st > lastScrollTop) {
				// scrolling down
				siteHeader.classList.add('js-user-scroll-down');
				siteHeader.classList.remove('js-user-scroll-up');
			} else if (st < lastScrollTop) {
				// scrolling up
				siteHeader.classList.add('js-user-scroll-up');
				siteHeader.classList.remove('js-user-scroll-down');
			}
		}
		// Update lastScrollTop to the current position, ensuring it is non-negative
		lastScrollTop = st <= 0 ? 0 : st;
		// Reset the ticking flag
		ticking = false;
	}

	// Function to be called on the scroll event
	function onScroll() {
		// If a scroll event is not already being processed
		if (!ticking) {
			// Schedule handleScroll to be called just before the next repaint
			requestAnimationFrame(handleScroll);
			// Set the ticking flag to true to prevent multiple calls
			ticking = true;
		}
	}

	// Attach the onScroll function to the scroll event
	window.addEventListener('scroll', onScroll);
})();

});