document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per_page');
    const productsTableBody = document.querySelector('tbody');
    const paginationContainer = document.querySelector('.pagination-container') || null;

    if (!searchInput || !perPageSelect || !productsTableBody) {
        console.warn('Live search elements not found');
        return;
    }

    let debounceTimer;

    function fetchProducts() {
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
                productsTableBody.innerHTML = newTbody.innerHTML;
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
            console.error('Error fetching products:', error);
        });
    }

    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchProducts, 300);
    }

    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            debounceFetch();
        }
    });

    searchInput.addEventListener('keyup', debounceFetch);
    searchInput.addEventListener('input', debounceFetch);

    perPageSelect.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 1);
        fetchProducts();
    });

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
                console.error('Error fetching products:', error);
            });
        }
    });
});
