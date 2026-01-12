document.addEventListener('DOMContentLoaded', async () => {

    const form = document.getElementById('global-search-form');
    const input = document.getElementById('global-search');
    const dropdown = document.getElementById('search-dropdown');

    if (!form || !input || !dropdown) return;

    let debounceTimer = null;

    // -------------------------
    // Submit (Search button / Enter)
    // -------------------------
    form.addEventListener('submit', async e => {
    e.preventDefault();

    const q = input.value.trim();
    if (!q) return;

    try {
        const res = await fetch(`/api/search?q=${encodeURIComponent(q)}`, {
            headers: {'Accept': 'application/json' }, // <-- add this
            credentials: 'include'
        
        });
        const data = await res.json();

        // 🔑 fallback if nothing found
        if (!data.length) {
            const fallback =
                window.location.pathname.includes('vegetables')
                    ? 'vegetables'
                    : 'fruits';

            window.location.href = `/user/${fallback}?search=${encodeURIComponent(q)}`;
            return;
        }

        const item = data[0];

        const url =
            item.category === 'fruit'
                ? `/user/fruits?search=${encodeURIComponent(q)}`
                : `/user/vegetables?search=${encodeURIComponent(q)}`;

        window.location.href = url;
    } catch (err) {
        console.error(err);
    }
});


    // -------------------------
    // Live dropdown search
    // -------------------------
    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        const query = input.value.trim();

        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`/api/search?q=${encodeURIComponent(query)}`, {
            headers: {'Accept': 'application/json'}, // <-- add this
            credentials: 'include'
            
        });
                const data = await res.json();

                dropdown.innerHTML = '';

                if (!data.length) {
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
        }, 350);
    });

    // -------------------------
    // Hide dropdown on outside click
    // -------------------------
    document.addEventListener('click', e => {
        if (
            !e.target.closest('#global-search') &&
            !e.target.closest('#search-dropdown')
        ) {
            dropdown.style.display = 'none';
        }
    });
});
