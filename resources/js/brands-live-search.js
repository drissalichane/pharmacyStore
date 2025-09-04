document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per_page');
    const brandsTableBody = document.querySelector('tbody');
    const paginationContainer = document.querySelector('.pagination-container') || null;

    if (!searchInput || !perPageSelect || !brandsTableBody) {
        console.warn('Live search elements not found');
        return;
    }

    let debounceTimer;

    function fetchBrands() {
        const search = searchInput.value;
        const perPage = perPageSelect.value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('per_page', perPage);

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.querySelector('tbody');
                const newPagination = doc.querySelector('.pagination-container');
                const newResultsInfo = doc.querySelector('.results-info');

                if (newTbody) {
                    brandsTableBody.innerHTML = newTbody.innerHTML;
                }
                if (paginationContainer && newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
                if (newResultsInfo) {
                    const resultsInfo = document.querySelector('.results-info');
                    if (resultsInfo) {
                        resultsInfo.innerHTML = newResultsInfo.innerHTML;
                    }
                }
            })
        .catch(error => {
            console.error('Error fetching brands:', error);
        });
    }

    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchBrands, 300);
    }

    // Remove form submission on enter key press
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    // Fix: prevent form submission on enter key and trigger live search on input
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            debounceFetch();
        }
    });

    // Fix: Also listen to 'keyup' event to trigger live search on typing
    searchInput.addEventListener('keyup', debounceFetch);

    searchInput.addEventListener('input', debounceFetch);
    perPageSelect.addEventListener('change', function() {
        // Reset to first page when per_page changes
        const url = new URL(window.location.href);
        url.searchParams.set('page', 1);
        fetchBrands();
    });

    // Intercept pagination links to use AJAX
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            const url = new URL(e.target.href);
            const search = document.getElementById('search').value;
            const perPage = document.getElementById('per_page').value;
            url.searchParams.set('search', search);
            url.searchParams.set('per_page', perPage);

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.querySelector('tbody');
                const newPagination = doc.querySelector('.pagination-container');

                if (newTbody) {
                    document.querySelector('tbody').innerHTML = newTbody.innerHTML;
                }
                if (newPagination) {
                    const paginationContainer = document.querySelector('.pagination-container');
                    if (paginationContainer) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching brands:', error);
            });
        }
    });

    perPageSelect.addEventListener('change', fetchBrands);
});
