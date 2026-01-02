document.getElementById('global-search-form')
    .addEventListener('submit', e => {

        e.preventDefault();

        const q = document.getElementById('global-search').value.trim();

        if (!q) return;

        // Redirect to full search page
        window.location.href = `/user/fruits?search=${encodeURIComponent(q)}`;
    });

let debounceTimer = null;

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('global-search');
    const dropdown = document.getElementById('search-dropdown');

    if (!input || !dropdown) return;

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        const query = input.value.trim();

        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                dropdown.innerHTML = '';

                if (data.length === 0) {
                    dropdown.innerHTML = `
                        <div class="list-group-item text-muted">
                            No results found
                        </div>`;
                } else {
                    data.forEach(item => {
                        const url =
                            item.category === 'fruit'
                                ? `/user/fruits?search=${encodeURIComponent(item.name)}`
                                : `/user/vegetables?search=${encodeURIComponent(item.name)}`;

                        dropdown.innerHTML += `
                            <a href="${url}"
                                class="list-group-item list-group-item-action">
                                ${item.name}
                                <small class="text-muted">(${item.category})</small>
                            </a>`;
                    });
                }

                dropdown.style.display = 'block';

            } catch (e) {
                console.error(e);
            }
        }, 350); // debounce
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', e => {
        if (!e.target.closest('#global-search')) {
            dropdown.style.display = 'none';
        }
    });
});

