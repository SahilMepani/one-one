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
