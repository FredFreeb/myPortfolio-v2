import { Controller } from '@hotwired/stimulus';

/**
 * Sélecteur des 24 langues officielles de l'UE.
 * Seul le français (fr) est actif — les autres affichent un toast "bientôt disponible"
 * et sont visuellement marquées comme telles.
 * Le choix est persisté en cookie (civitalisme_locale) pour préparer la future i18n.
 */

const LOCALES = {
    fr: 'Français',
    bg: 'Български',
    cs: 'Čeština',
    da: 'Dansk',
    de: 'Deutsch',
    el: 'Ελληνικά',
    en: 'English',
    et: 'Eesti',
    fi: 'Suomi',
    ga: 'Gaeilge',
    hr: 'Hrvatski',
    hu: 'Magyar',
    it: 'Italiano',
    lt: 'Lietuvių',
    lv: 'Latviešu',
    mt: 'Malti',
    nl: 'Nederlands',
    pl: 'Polski',
    pt: 'Português',
    ro: 'Română',
    sk: 'Slovenčina',
    sl: 'Slovenščina',
    es: 'Español',
    sv: 'Svenska',
};

export default class extends Controller {
    static targets = ['menu', 'toggle', 'current', 'notice'];
    static values  = {
        active:    { type: String, default: 'fr' },
        // Liste CSV des locales disponibles, injectée par le serveur dans
        // data-lang-switcher-available-value (ex: "fr,en,de"). Toujours 'fr' minimum.
        available: { type: String, default: 'fr' },
    };

    connect() {
        this._onDocClick = this._onDocClick.bind(this);
        this._onKeydown  = this._onKeydown.bind(this);
        document.addEventListener('click',   this._onDocClick);
        document.addEventListener('keydown', this._onKeydown);

        const stored = this._readCookie('civitalisme_locale');
        if (stored && LOCALES[stored]) {
            this.activeValue = stored;
        }
        this._available = new Set(
            (this.availableValue || 'fr').split(',').map(s => s.trim()).filter(Boolean)
        );
        this._render();
    }

    _isAvailable(locale) {
        return this._available?.has(locale) ?? (locale === 'fr');
    }

    disconnect() {
        document.removeEventListener('click',   this._onDocClick);
        document.removeEventListener('keydown', this._onKeydown);
    }

    toggle(event) {
        event.preventDefault();
        const isOpen = this.element.classList.toggle('is-open');
        this.toggleTarget?.setAttribute('aria-expanded', String(isOpen));
    }

    select(event) {
        event.preventDefault();
        const locale = event.currentTarget.dataset.locale;
        if (!locale || !LOCALES[locale]) return;

        this._writeCookie('civitalisme_locale', locale);
        this.close();

        if (this._isAvailable(locale)) {
            // Langue dispo : recharger la page pour appliquer la nouvelle locale
            this.activeValue = locale;
            window.location.reload();
        } else {
            // Langue indisponible : rester sur FR, afficher le toast "bientôt"
            this._showNotice(locale);
            this._render();
        }
    }

    close() {
        this.element.classList.remove('is-open');
        this.toggleTarget?.setAttribute('aria-expanded', 'false');
    }

    // -------------------------------------------------------------------------

    _render() {
        // Afficher le code de la langue active dans le bouton
        if (this.hasCurrentTarget) {
            this.currentTarget.textContent = this.activeValue.toUpperCase();
        }

        // Marquer la langue active et griser les langues non disponibles
        this.element.querySelectorAll('[data-locale]').forEach(node => {
            const locale    = node.dataset.locale;
            const isActive  = locale === this.activeValue;
            const available = this._isAvailable(locale);

            node.classList.toggle('is-active',      isActive);
            node.classList.toggle('is-unavailable', !available);

            // Ajouter/retirer le badge "bientôt" dynamiquement
            let badge = node.querySelector('.lang-switcher__soon');
            if (!available && !badge) {
                badge = document.createElement('span');
                badge.className     = 'lang-switcher__soon';
                badge.textContent   = 'bientôt';
                badge.setAttribute('aria-hidden', 'true');
                node.appendChild(badge);
            } else if (available && badge) {
                badge.remove();
            }
        });
    }

    _showNotice(locale) {
        if (!this.hasNoticeTarget) return;
        const name = LOCALES[locale] || locale.toUpperCase();
        this.noticeTarget.textContent = `${name} — traduction bientôt disponible.`;
        this.noticeTarget.classList.add('is-visible');
        clearTimeout(this._noticeTimer);
        this._noticeTimer = setTimeout(() => this._hideNotice(), 4500);
    }

    _hideNotice() {
        if (!this.hasNoticeTarget) return;
        this.noticeTarget.classList.remove('is-visible');
    }

    _onDocClick(event) {
        if (!this.element.contains(event.target)) this.close();
    }

    _onKeydown(event) {
        if (event.key === 'Escape') this.close();
    }

    _readCookie(name) {
        const m = document.cookie.match(new RegExp('(^|; )' + name + '=([^;]*)'));
        return m ? decodeURIComponent(m[2]) : null;
    }

    _writeCookie(name, value) {
        document.cookie = `${name}=${encodeURIComponent(value)}; path=/; max-age=${60 * 60 * 24 * 365}; SameSite=Lax`;
    }
}
