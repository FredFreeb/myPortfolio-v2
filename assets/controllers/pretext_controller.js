import { Controller } from '@hotwired/stimulus';
import { layoutWithLines, prepareWithSegments } from '@chenglou/pretext';

export default class extends Controller {
    static values = {
        lineHeight: Number,
    };

    connect() {
        this.sourceText = (this.element.dataset.text || this.element.textContent || '').trim().replace(/\s+/g, ' ');
        this.element.setAttribute('aria-label', this.sourceText);
        this.lastWidth = 0;
        this.lastFont = '';
        this.prepared = null;

        this.render = this.render.bind(this);
        this.resizeObserver = new ResizeObserver(() => this.render());
        this.resizeObserver.observe(this.element);

        if (document.fonts?.ready) {
            document.fonts.ready.then(() => this.render());
        } else {
            this.render();
        }
    }

    disconnect() {
        this.resizeObserver?.disconnect();
    }

    render() {
        const width = Math.max(this.element.clientWidth, 220);
        const styles = window.getComputedStyle(this.element);
        const font = `${styles.fontWeight} ${styles.fontSize} ${styles.fontFamily}`;
        const computedLineHeight = parseFloat(styles.lineHeight);
        const lineHeight = this.hasLineHeightValue
            ? this.lineHeightValue
            : (Number.isNaN(computedLineHeight) ? parseFloat(styles.fontSize) * 1.16 : computedLineHeight);

        if (!this.prepared || this.lastFont !== font) {
            this.prepared = prepareWithSegments(this.sourceText, font);
            this.lastFont = font;
        }

        if (width === this.lastWidth) {
            return;
        }

        this.lastWidth = width;

        const { lines } = layoutWithLines(this.prepared, width, lineHeight);

        this.element.innerHTML = lines
            .map((line, index) => `<span class="pretext-line" style="--line-index:${index}">${this.escapeHtml(line.text)}</span>`)
            .join('');

        this.element.classList.add('is-pretext-ready');
    }

    escapeHtml(value) {
        return value
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
}
