// Auto-submit search form functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per_page');

    if (!searchForm || !searchInput || !perPageSelect) {
        console.warn('Search form elements not found');
        return;
    }

    let debounceTimer;

    // Function to submit the form with debounce
    function submitForm() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            searchForm.submit();
        }, 500); // 500ms delay
    }

    // Listen for input changes on search field
    searchInput.addEventListener('input', submitForm);

    // Listen for changes on per_page select
    perPageSelect.addEventListener('change', submitForm);
});
