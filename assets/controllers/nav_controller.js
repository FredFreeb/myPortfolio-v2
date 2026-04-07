import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['button'];

    connect() {
        this.mediaQuery = window.matchMedia('(max-width: 860px)');
        this.close = this.close.bind(this);
        this.toggle = this.toggle.bind(this);
        this.onViewportChange = this.onViewportChange.bind(this);
        this.handleDocumentKeydown = this.handleDocumentKeydown.bind(this);

        if (typeof this.mediaQuery.addEventListener === 'function') {
            this.mediaQuery.addEventListener('change', this.onViewportChange);
        } else if (typeof this.mediaQuery.addListener === 'function') {
            this.mediaQuery.addListener(this.onViewportChange);
        }
        document.addEventListener('keydown', this.handleDocumentKeydown);
        this.close();
    }

    disconnect() {
        if (typeof this.mediaQuery.removeEventListener === 'function') {
            this.mediaQuery.removeEventListener('change', this.onViewportChange);
        } else if (typeof this.mediaQuery.removeListener === 'function') {
            this.mediaQuery.removeListener(this.onViewportChange);
        }
        document.removeEventListener('keydown', this.handleDocumentKeydown);
        this.close();
    }

    toggle() {
        if (!this.isMobile()) {
            return;
        }
        if (this.element.classList.contains('is-open')) {
            this.close();
            return;
        }
        this.open();
    }

    open() {
        if (!this.isMobile()) {
            return;
        }
        this.element.classList.add('is-open');
        document.body.classList.add('has-mobile-nav');
        this.renderButton(true);
    }

    close() {
        this.element.classList.remove('is-open');
        document.body.classList.remove('has-mobile-nav');
        this.renderButton(false);
    }

    isMobile() {
        return this.mediaQuery.matches;
    }

    onViewportChange() {
        if (!this.isMobile()) {
            this.close();
        }
    }

    handleDocumentKeydown(event) {
        if (event.key === 'Escape') {
            this.close();
        }
    }

    renderButton(expanded) {
        if (!this.hasButtonTarget) {
            return;
        }
        this.buttonTarget.setAttribute('aria-expanded', String(expanded));
        this.buttonTarget.setAttribute('aria-label', expanded ? 'Fermer la navigation' : 'Ouvrir la navigation');
    }
}
