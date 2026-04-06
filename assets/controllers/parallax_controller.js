import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['layer'];

    static values = {
        intensity: {
            type: Number,
            default: 18,
        },
    };

    connect() {
        this.pointer = { x: 0, y: 0 };
        this.scrollOffset = 0;
        this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (this.prefersReducedMotion) {
            return;
        }

        this.handlePointerMove = this.handlePointerMove.bind(this);
        this.handlePointerLeave = this.handlePointerLeave.bind(this);
        this.handleScroll = this.handleScroll.bind(this);

        this.element.addEventListener('pointermove', this.handlePointerMove);
        this.element.addEventListener('pointerleave', this.handlePointerLeave);
        window.addEventListener('scroll', this.handleScroll, { passive: true });
        this.apply();
    }

    disconnect() {
        this.element.removeEventListener('pointermove', this.handlePointerMove);
        this.element.removeEventListener('pointerleave', this.handlePointerLeave);
        window.removeEventListener('scroll', this.handleScroll);
    }

    handlePointerMove(event) {
        const bounds = this.element.getBoundingClientRect();
        const x = (event.clientX - bounds.left) / bounds.width - 0.5;
        const y = (event.clientY - bounds.top) / bounds.height - 0.5;

        this.pointer = { x, y };
        this.apply();
    }

    handlePointerLeave() {
        this.pointer = { x: 0, y: 0 };
        this.apply();
    }

    handleScroll() {
        const bounds = this.element.getBoundingClientRect();
        this.scrollOffset = Math.max(Math.min(bounds.top * -0.02, 12), -12);
        this.apply();
    }

    apply() {
        this.layerTargets.forEach((layer) => {
            const depth = Number(layer.dataset.parallaxDepth || 1);
            const translateX = this.pointer.x * depth * this.intensityValue;
            const translateY = this.pointer.y * depth * this.intensityValue + (this.scrollOffset * depth * 0.35);

            layer.style.setProperty('--parallax-x', `${translateX.toFixed(2)}px`);
            layer.style.setProperty('--parallax-y', `${translateY.toFixed(2)}px`);
        });
    }
}
