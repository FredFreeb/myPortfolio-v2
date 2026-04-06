import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['button', 'panel', 'backdrop'];

    connect() {
        if (this.element.dataset.navFallbackInitialized === 'true') {
            return;
        }

        this.mediaQuery = window.matchMedia('(max-width: 860px)');
        this.open = false;
        this.element.dataset.navReady = 'true';
        this.sync = this.sync.bind(this);
        if (typeof this.mediaQuery.addEventListener === 'function') {
            this.mediaQuery.addEventListener('change', this.sync);
        } else if (typeof this.mediaQuery.addListener === 'function') {
            this.mediaQuery.addListener(this.sync);
        }
        this.sync();
    }

    disconnect() {
        if (this.element.dataset.navFallbackInitialized === 'true') {
            return;
        }

        if (typeof this.mediaQuery.removeEventListener === 'function') {
            this.mediaQuery.removeEventListener('change', this.sync);
        } else if (typeof this.mediaQuery.removeListener === 'function') {
            this.mediaQuery.removeListener(this.sync);
        }

        document.body.classList.remove('has-mobile-nav');
        document.body.classList.remove('mobile-nav-ready');
        delete this.element.dataset.navReady;
    }

    toggle() {
        if (this.element.dataset.navFallbackInitialized === 'true') {
            return;
        }

        if (!this.isMobile()) {
            return;
        }

        this.open = !this.open;
        this.render();
    }

    close() {
        if (this.element.dataset.navFallbackInitialized === 'true') {
            return;
        }

        if (!this.isMobile()) {
            return;
        }

        this.open = false;
        this.render();
    }

    sync() {
        if (this.element.dataset.navFallbackInitialized === 'true') {
            return;
        }

        if (!this.isMobile()) {
            this.open = false;
        }

        this.render();
    }

    isMobile() {
        return this.mediaQuery.matches;
    }

    render() {
        const expanded = this.isMobile() && this.open;
        const mobile = this.isMobile();

        if (this.hasButtonTarget) {
            this.buttonTarget.setAttribute('aria-expanded', String(expanded));
            this.buttonTarget.setAttribute('aria-label', expanded ? 'Fermer la navigation' : 'Ouvrir la navigation');
        }

        if (this.hasPanelTarget) {
            if (expanded) {
                this.panelTarget.setAttribute('data-open', 'true');
                this.panelTarget.hidden = false;
            } else {
                this.panelTarget.removeAttribute('data-open');
                this.panelTarget.hidden = mobile;
            }
        }

        if (this.hasBackdropTarget) {
            this.backdropTarget.hidden = !expanded;
            this.backdropTarget.toggleAttribute('data-open', expanded);
        }

        this.element.classList.toggle('is-open', expanded);
        document.body.classList.toggle('has-mobile-nav', expanded);
        document.body.classList.toggle('mobile-nav-ready', mobile);
    }
}
