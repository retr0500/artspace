class FavoritesManager {
    constructor(storageKey = 'artspace_favorites') {
        this.storageKey = storageKey;
        this.favorites = this.load();
    }

    load() {
        try {
            const raw = localStorage.getItem(this.storageKey);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed.map(Number) : [];
        } catch {
            return [];
        }
    }

    save() {
        localStorage.setItem(this.storageKey, JSON.stringify(this.favorites));
    }

    toggle(id) {
        const numericId = Number(id);
        const index = this.favorites.indexOf(numericId);
        if (index === -1) {
            this.favorites.push(numericId);
        } else {
            this.favorites.splice(index, 1);
        }
        this.save();
        this.updateUI();
    }

    has(id) {
        return this.favorites.includes(Number(id));
    }

    updateUI() {
        const counter = document.getElementById('fav-count');
        if (counter) {
            counter.textContent = this.favorites.length;
        }

        document.querySelectorAll('[data-fav-id]').forEach((btn) => {
            const id = btn.getAttribute('data-fav-id');
            const active = this.has(id);
            btn.textContent = active ? '★ Guardada' : '☆ Favorita';
            btn.classList.toggle('btn-secondary', active);
            btn.classList.toggle('btn-outline', !active);
        });
    }
}

class FavoritesPageRenderer {
    constructor(containerId, favManager) {
        this.container = document.getElementById(containerId);
        this.favManager = favManager;
        if (this.container) {
            this.renderFavorites();
        }
    }

    async renderFavorites() {
        const ids = this.favManager.load();
        if (!ids.length) {
            this.container.innerHTML = '<div class="alert alert-info">Todavía no has guardado ninguna obra en favoritos. Pulsa "☆ Favorita" en cualquier obra del catálogo.</div>';
            return;
        }

        this.container.innerHTML = '<div class="alert alert-info">Cargando tus obras guardadas...</div>';

        try {
            const response = await fetch('/api/obras');
            if (!response.ok) throw new Error('Error al cargar el catálogo');
            const payload = await response.json();
            const savedArtworks = (payload.data || []).filter((obra) => ids.includes(Number(obra.id)));

            if (!savedArtworks.length) {
                this.container.innerHTML = '<div class="alert alert-info">No se encontraron obras guardadas disponibles.</div>';
                return;
            }

            const cards = savedArtworks.map((obra) => `
                <article class="card card-clickable" data-href="/obra/${obra.id}">
                    ${obra.imagen
                        ? `<img src="/storage/${obra.imagen}" alt="${obra.titulo}" class="card-img">`
                        : `<div class="card-placeholder"><i class="fa-regular fa-image fa-2x"></i></div>`}
                    <div class="card-body">
                        <div>
                            <h3 class="card-title"><a href="/obra/${obra.id}">${obra.titulo}</a></h3>
                            <p>${obra.tecnica} &bull; <small>${obra.user ? obra.user.name : 'Artista'}</small></p>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.75rem">
                            <span class="price-tag">${Number(obra.precio).toFixed(2)} €</span>
                            <button type="button" class="btn btn-secondary" data-fav-id="${obra.id}">★ Guardada</button>
                        </div>
                    </div>
                </article>
            `).join('');

            this.container.innerHTML = `<div class="gallery-grid">${cards}</div>`;
        } catch (error) {
            this.container.innerHTML = `<div class="alert alert-error">Error al cargar favoritos: ${error.message}</div>`;
        }
    }
}

class CookieConsentManager {
    constructor(bannerId, acceptBtnId, rejectBtnId) {
        this.banner = document.getElementById(bannerId);
        this.acceptBtn = document.getElementById(acceptBtnId);
        this.rejectBtn = document.getElementById(rejectBtnId);
        this.storageKey = 'artspace_rgpd_consent';
        this.init();
    }

    init() {
        if (!this.banner || !this.acceptBtn || !this.rejectBtn) return;
        if (!localStorage.getItem(this.storageKey)) {
            this.banner.classList.remove('hidden');
        }
        this.acceptBtn.addEventListener('click', () => this.saveChoice('accepted'));
        this.rejectBtn.addEventListener('click', () => this.saveChoice('rejected'));
    }

    saveChoice(status) {
        localStorage.setItem(this.storageKey, JSON.stringify({
            status,
            updatedAt: new Date().toISOString()
        }));
        this.banner.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const favManager = new FavoritesManager();
    favManager.updateUI();

    const favPage = new FavoritesPageRenderer('favorites-list-container', favManager);

    // Delegación global de clics (Favoritos + Contenedores clicables)
    document.addEventListener('click', (e) => {
        const favBtn = e.target.closest('[data-fav-id]');
        if (favBtn) {
            e.preventDefault();
            e.stopPropagation();
            favManager.toggle(favBtn.getAttribute('data-fav-id'));
            if (favPage.container) {
                favPage.renderFavorites();
            }
            return;
        }

        // Si se pulsa dentro de una tarjeta clicable (fuera de botones/enlaces), abre la obra
        const clickableCard = e.target.closest('[data-href]');
        if (clickableCard && !e.target.closest('a, button, input, select, textarea, form')) {
            window.location.href = clickableCard.getAttribute('data-href');
        }
    });

    // Visor de imagen ampliada (Lightbox) en el detalle de obra
    const zoomImg = document.getElementById('artwork-zoom-trigger');
    const lightbox = document.getElementById('image-lightbox');
    if (zoomImg && lightbox) {
        zoomImg.addEventListener('click', () => lightbox.classList.remove('hidden'));
        lightbox.addEventListener('click', () => lightbox.classList.add('hidden'));
    }

    new CookieConsentManager('cookie-banner', 'btn-accept-cookies', 'btn-reject-cookies');

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }
});