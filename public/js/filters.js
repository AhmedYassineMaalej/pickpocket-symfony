function applyFilters() {
    const url = new URL(window.location.href);

    const category = document.getElementById('category').value;
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }

    const minPrice = document.getElementById('minPriceInput').value;
    const maxPrice = document.getElementById('maxPriceInput').value;
    url.searchParams.set('minPrice', minPrice);
    url.searchParams.set('maxPrice', maxPrice);

    const q = url.searchParams.get('q');
    if (q) {
        url.searchParams.set('q', q);
    }

    window.location.href = url.toString();
}
