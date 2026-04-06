import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        speed: {
            type: Number,
            default: 34,
        },
        startDelay: {
            type: Number,
            default: 180,
        },
        cursor: {
            type: String,
            default: '|',
        },
    };

    connect() {
        this.fullText = (this.element.dataset.text || this.element.textContent || '')
            .replace(/\s+/g, ' ')
            .trim();
        this.element.setAttribute('aria-label', this.fullText);
        this.element.textContent = '';

        this.destroyed = false;
        this.index = 0;
        this.timeout = null;

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.element.textContent = this.fullText;
            return;
        }

        this.textNode = document.createElement('span');
        this.textNode.className = 'typewriter-text';
        this.cursorNode = document.createElement('span');
        this.cursorNode.className = 'typewriter-cursor';
        this.cursorNode.textContent = this.cursorValue;

        this.element.append(this.textNode, this.cursorNode);
        this.timeout = window.setTimeout(() => this.tick(), this.startDelayValue);
    }

    disconnect() {
        this.destroyed = true;
        if (this.timeout) {
            window.clearTimeout(this.timeout);
        }
    }

    tick() {
        if (this.destroyed) {
            return;
        }

        if (this.index < this.fullText.length) {
            this.index += 1;
            this.textNode.textContent = this.fullText.slice(0, this.index);

            const currentChar = this.fullText.charAt(this.index - 1);
            const variance = Math.random() * 16;
            const delay = currentChar === ' '
                ? Math.max(this.speedValue * 0.45, 10)
                : this.speedValue + variance;

            this.timeout = window.setTimeout(() => this.tick(), delay);
            return;
        }

        this.cursorNode.classList.add('is-done');
    }
}
