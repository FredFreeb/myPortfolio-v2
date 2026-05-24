import { Controller } from '@hotwired/stimulus';

const SCHEMES = ['auto', 'light', 'dark', 'a11y'];
const STORAGE_KEY = 'color-scheme';

export default class extends Controller {
    static targets = ['button'];

    connect() {
        const stored = localStorage.getItem(STORAGE_KEY);
        this._current = SCHEMES.includes(stored) ? stored : 'auto';
        this._apply(this._current);
        this._render();
    }

    set(event) {
        const scheme = event.currentTarget.dataset.scheme;
        if (!SCHEMES.includes(scheme)) return;
        this._current = scheme;
        localStorage.setItem(STORAGE_KEY, scheme);
        this._apply(scheme);
        this._render();
    }

    _apply(scheme) {
        if (scheme === 'auto') {
            document.documentElement.removeAttribute('data-color-scheme');
        } else {
            document.documentElement.setAttribute('data-color-scheme', scheme);
        }
    }

    _render() {
        this.buttonTargets.forEach(btn => {
            const active = btn.dataset.scheme === this._current;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', String(active));
        });
    }
}
