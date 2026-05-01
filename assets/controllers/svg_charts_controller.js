import { Controller } from '@hotwired/stimulus';

/**
 * Graphiques économiques du Socle Vital Garanti — 4 figures du rapport économique.
 *
 * Dépend de Chart.js (chargé en CDN via <script> dans le bloc extra_head du template).
 * Utilise window.Chart, disponible grâce au script UMD chargé avant le bundle ES.
 *
 * Fig. 3 — SVG maximal par composition de ménage (barres horizontales)
 * Fig. 4 — Incitation au travail : SVG + revenu net en fonction du revenu actif (lignes)
 * Fig. 5 — Sources de financement hypothétiques (anneau / doughnut)
 * Fig. 6 — Réduction simulée de la pauvreté, avant/après SVG (barres groupées)
 */

const LW0       = 1650;
const HICP      = 1.0;  // Année 2026 = index de référence

const EU_BLUE   = '#003399';
const EU_YELLOW = '#ffcc00';
const EU_LIGHT  = '#9faee5';
const EU_BLUE2  = '#4266b8';
const EU_SOFT   = 'rgba(0,51,153,0.12)';

// ---------- Données Fig. 3 — SVG par type de foyer --------------------------
const HOUSEHOLD_TYPES = [
    { label: 'Personne seule',          eq: 1.0 },
    { label: 'Couple sans enfant',      eq: 1.5 },
    { label: 'Couple + 1 enfant',       eq: 1.8 },
    { label: 'Couple + 2 enfants',      eq: 2.1 },
    { label: 'Couple + 3 enfants',      eq: 2.4 },
    { label: 'Parent isolé + 1 enfant', eq: 1.3 },
    { label: 'Parent isolé + 2 enfants', eq: 1.6 },
];

// ---------- Données Fig. 4 — Incitation au travail --------------------------
// Foyer : 1 adulte, zone médiane, pas de pension. Franchise = 300€/1 actif.
function buildIncentiveData() {
    const incomes = Array.from({ length: 21 }, (_, i) => i * 100); // 0 … 2000
    const svgAmounts = incomes.map(income => {
        const franchise = Math.max(0, income - 300);
        return Math.max(0, LW0 - franchise);
    });
    const totalNet = incomes.map((income, i) => income + svgAmounts[i]);
    return { incomes, svgAmounts, totalNet };
}

// ---------- Données Fig. 5 — Financement ------------------------------------ (hypothétique)
const FINANCING = {
    labels: ['Cotisation IA / automatisation', 'TVA sociale', 'Dividende numérique', 'Contribution publique', 'Coopération franco-allemande'],
    data:   [35, 25, 20, 15, 5],
    colors: [EU_BLUE, EU_BLUE2, EU_LIGHT, '#6a8ed4', '#b8c8f0'],
};

// ---------- Données Fig. 6 — Réduction de la pauvreté -----------------------
const POVERTY = {
    countries: ['France', 'Allemagne', 'Italie', 'Espagne', 'Pologne', 'Roumanie'],
    before:    [14.1,      16.3,        20.1,    21.0,       15.4,     33.9],
    after:     [ 4.2,       5.1,         8.0,     8.8,        4.9,     12.1],
};

export default class extends Controller {
    static targets = ['fig3', 'fig4', 'fig5', 'fig6'];

    connect() {
        // Chart.js est chargé via CDN (UMD) — disponible sur window.Chart
        const Chart = window.Chart;
        if (!Chart) {
            console.warn('[svg-charts] Chart.js non disponible. Rendu Canvas natif utilisé.');
            this._initFallbacks();
            return;
        }
        this._initAll();
    }

    disconnect() {
        this._charts?.forEach(c => c.destroy());
        this._charts = [];
        if (this._fallbackResizeHandler) {
            window.removeEventListener('resize', this._fallbackResizeHandler);
        }
    }

    _initAll() {
        this._charts = [];
        if (this.hasFig3Target) this._charts.push(this._buildFig3());
        if (this.hasFig4Target) this._charts.push(this._buildFig4());
        if (this.hasFig5Target) this._charts.push(this._buildFig5());
        if (this.hasFig6Target) this._charts.push(this._buildFig6());
    }

    _initFallbacks() {
        this._drawFallbacks();
        this._fallbackResizeHandler = () => {
            window.clearTimeout(this._fallbackResizeTimer);
            this._fallbackResizeTimer = window.setTimeout(() => this._drawFallbacks(), 120);
        };
        window.addEventListener('resize', this._fallbackResizeHandler);
    }

    _drawFallbacks() {
        if (this.hasFig3Target) this._drawFig3Fallback(this.fig3Target);
        if (this.hasFig4Target) this._drawFig4Fallback(this.fig4Target);
        if (this.hasFig5Target) this._drawFig5Fallback(this.fig5Target);
        if (this.hasFig6Target) this._drawFig6Fallback(this.fig6Target);
    }

    _prepareCanvas(canvas) {
        const wrapper = canvas.parentElement;
        const width = Math.max(320, Math.floor(wrapper?.clientWidth ?? 640));
        const height = Math.max(260, Math.floor(wrapper?.clientHeight ?? 320));
        const ratio = window.devicePixelRatio || 1;

        canvas.width = Math.floor(width * ratio);
        canvas.height = Math.floor(height * ratio);
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;

        const ctx = canvas.getContext('2d');
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);
        ctx.font = '12px Inter, system-ui, sans-serif';
        ctx.textBaseline = 'middle';

        return { ctx, width, height };
    }

    _drawTitle(ctx, text, width) {
        ctx.fillStyle = EU_BLUE;
        ctx.font = '600 13px Inter, system-ui, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(text, width / 2, 22);
    }

    _drawFig3Fallback(canvas) {
        const { ctx, width, height } = this._prepareCanvas(canvas);
        const data = HOUSEHOLD_TYPES.map(h => ({ label: h.label, value: Math.round(LW0 * h.eq * HICP) }));
        const max = Math.max(...data.map(item => item.value));
        const left = width < 520 ? 118 : 170;
        const right = 24;
        const top = 52;
        const row = Math.max(24, (height - top - 22) / data.length);

        this._drawTitle(ctx, 'Fig. 3 — SVG maximal avant déductions', width);
        data.forEach((item, index) => {
            const y = top + index * row;
            const barWidth = ((width - left - right) * item.value) / max;
            ctx.fillStyle = EU_SOFT;
            ctx.fillRect(left, y + 7, width - left - right, 12);
            ctx.fillStyle = index % 2 === 0 ? EU_BLUE : EU_BLUE2;
            ctx.fillRect(left, y + 7, barWidth, 12);
            ctx.fillStyle = EU_BLUE;
            ctx.font = '12px Inter, system-ui, sans-serif';
            ctx.textAlign = 'right';
            ctx.fillText(item.label, left - 10, y + 13);
            ctx.textAlign = 'left';
            ctx.fillText(`${item.value.toLocaleString('fr-FR')} €`, left + barWidth + 6, y + 13);
        });
    }

    _drawFig4Fallback(canvas) {
        const { ctx, width, height } = this._prepareCanvas(canvas);
        const { incomes, svgAmounts, totalNet } = buildIncentiveData();
        const left = 46;
        const right = 22;
        const top = 52;
        const bottom = 44;
        const chartW = width - left - right;
        const chartH = height - top - bottom;
        const max = Math.max(...totalNet);
        const x = index => left + (index / (incomes.length - 1)) * chartW;
        const y = value => top + chartH - (value / max) * chartH;

        this._drawTitle(ctx, 'Fig. 4 — Incitation au travail', width);
        ctx.strokeStyle = EU_SOFT;
        ctx.lineWidth = 1;
        for (let i = 0; i <= 4; i++) {
            const gy = top + (chartH / 4) * i;
            ctx.beginPath();
            ctx.moveTo(left, gy);
            ctx.lineTo(width - right, gy);
            ctx.stroke();
        }

        const drawLine = (values, color) => {
            ctx.strokeStyle = color;
            ctx.lineWidth = 2.4;
            ctx.beginPath();
            values.forEach((value, index) => {
                if (index === 0) ctx.moveTo(x(index), y(value));
                else ctx.lineTo(x(index), y(value));
            });
            ctx.stroke();
        };

        drawLine(svgAmounts, EU_BLUE);
        drawLine(totalNet, EU_YELLOW);
        ctx.fillStyle = EU_BLUE;
        ctx.font = '12px Inter, system-ui, sans-serif';
        ctx.textAlign = 'left';
        ctx.fillText('SVG', left, height - 22);
        ctx.fillStyle = '#b38f00';
        ctx.fillText('Revenu total', left + 54, height - 22);
    }

    _drawFig5Fallback(canvas) {
        const { ctx, width, height } = this._prepareCanvas(canvas);
        const cx = width / 2;
        const cy = Math.max(130, height * 0.43);
        const radius = Math.min(width, height) * 0.24;
        let start = -Math.PI / 2;

        this._drawTitle(ctx, 'Fig. 5 — Sources de financement hypothétiques', width);
        FINANCING.data.forEach((value, index) => {
            const angle = (value / 100) * Math.PI * 2;
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, radius, start, start + angle);
            ctx.closePath();
            ctx.fillStyle = FINANCING.colors[index];
            ctx.fill();
            start += angle;
        });
        ctx.beginPath();
        ctx.arc(cx, cy, radius * 0.58, 0, Math.PI * 2);
        ctx.fillStyle = '#ffffff';
        ctx.fill();

        ctx.font = '11px Inter, system-ui, sans-serif';
        ctx.textAlign = 'left';
        FINANCING.labels.forEach((label, index) => {
            const x = 24 + (index % 2) * Math.max(140, width * 0.45);
            const y = height - 74 + Math.floor(index / 2) * 20;
            ctx.fillStyle = FINANCING.colors[index];
            ctx.fillRect(x, y - 5, 10, 10);
            ctx.fillStyle = EU_BLUE;
            ctx.fillText(`${label} · ${FINANCING.data[index]} %`, x + 16, y);
        });
    }

    _drawFig6Fallback(canvas) {
        const { ctx, width, height } = this._prepareCanvas(canvas);
        const left = 42;
        const right = 24;
        const top = 52;
        const bottom = 46;
        const chartW = width - left - right;
        const chartH = height - top - bottom;
        const groupW = chartW / POVERTY.countries.length;
        const max = 40;

        this._drawTitle(ctx, 'Fig. 6 — Réduction de la pauvreté', width);
        ctx.strokeStyle = EU_SOFT;
        ctx.lineWidth = 1;
        for (let i = 0; i <= 4; i++) {
            const y = top + (chartH / 4) * i;
            ctx.beginPath();
            ctx.moveTo(left, y);
            ctx.lineTo(width - right, y);
            ctx.stroke();
        }

        POVERTY.countries.forEach((country, index) => {
            const baseX = left + index * groupW + groupW * 0.22;
            const barW = Math.max(8, groupW * 0.2);
            const beforeH = (POVERTY.before[index] / max) * chartH;
            const afterH = (POVERTY.after[index] / max) * chartH;
            ctx.fillStyle = 'rgba(0,51,153,0.72)';
            ctx.fillRect(baseX, top + chartH - beforeH, barW, beforeH);
            ctx.fillStyle = EU_YELLOW;
            ctx.fillRect(baseX + barW + 4, top + chartH - afterH, barW, afterH);
            ctx.fillStyle = EU_BLUE;
            ctx.font = '10px Inter, system-ui, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(country.slice(0, 3), baseX + barW, height - 22);
        });
    }

    // -------------------------------------------------------------------------
    // Fig. 3 — SVG maximal par type de ménage (zone médiane, 2026, sans déductions)
    // -------------------------------------------------------------------------
    _buildFig3() {
        const Chart = window.Chart;
        const labels = HOUSEHOLD_TYPES.map(h => h.label);
        const data   = HOUSEHOLD_TYPES.map(h => Math.round(LW0 * h.eq * HICP));

        return new Chart(this.fig3Target, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'SVG maximal (€/mois)',
                    data,
                    backgroundColor: data.map((_, i) => i % 2 === 0 ? EU_BLUE : EU_BLUE2),
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.raw.toLocaleString('fr-FR')} €/mois (avant déductions)`,
                        },
                    },
                    title: {
                        display: true,
                        text: 'Fig. 3 — SVG maximal avant déductions (zone médiane · 2026)',
                        color: EU_BLUE,
                        font: { size: 13, weight: '600', family: 'Inter, sans-serif' },
                        padding: { bottom: 16 },
                    },
                },
                scales: {
                    x: {
                        grid:   { color: EU_SOFT },
                        ticks: {
                            color: EU_BLUE2,
                            callback: v => `${(v / 1000).toFixed(1)}k €`,
                        },
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: EU_BLUE, font: { size: 12 } },
                    },
                },
            },
        });
    }

    // -------------------------------------------------------------------------
    // Fig. 4 — Incitation au travail (1 adulte, zone médiane, franchise 300 €)
    // -------------------------------------------------------------------------
    _buildFig4() {
        const Chart = window.Chart;
        const { incomes, svgAmounts, totalNet } = buildIncentiveData();

        return new Chart(this.fig4Target, {
            type: 'line',
            data: {
                labels: incomes.map(v => `${v} €`),
                datasets: [
                    {
                        label: 'SVG (EuroE/mois)',
                        data: svgAmounts,
                        borderColor: EU_BLUE,
                        backgroundColor: 'rgba(0,51,153,0.10)',
                        fill: true,
                        tension: 0.1,
                        pointRadius: 4,
                        pointBackgroundColor: EU_BLUE,
                    },
                    {
                        label: 'Revenu total (salaire + SVG)',
                        data: totalNet,
                        borderColor: EU_YELLOW,
                        backgroundColor: 'rgba(255,204,0,0.12)',
                        fill: true,
                        tension: 0.1,
                        pointRadius: 4,
                        pointBackgroundColor: '#e0b300',
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: EU_BLUE, font: { size: 12 } },
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label} : ${ctx.raw.toLocaleString('fr-FR')} €`,
                        },
                    },
                    title: {
                        display: true,
                        text: 'Fig. 4 — Incitation au travail : le revenu total ne baisse jamais',
                        color: EU_BLUE,
                        font: { size: 13, weight: '600', family: 'Inter, sans-serif' },
                        padding: { bottom: 16 },
                    },
                },
                scales: {
                    x: {
                        grid:  { color: EU_SOFT },
                        ticks: { color: EU_BLUE2, maxRotation: 0, font: { size: 11 } },
                        title: { display: true, text: 'Revenu actif (€/mois)', color: EU_BLUE2 },
                    },
                    y: {
                        grid:  { color: EU_SOFT },
                        ticks: { color: EU_BLUE, callback: v => `${v} €` },
                        title: { display: true, text: '€/mois', color: EU_BLUE2 },
                    },
                },
            },
        });
    }

    // -------------------------------------------------------------------------
    // Fig. 5 — Sources de financement hypothétiques
    // -------------------------------------------------------------------------
    _buildFig5() {
        const Chart = window.Chart;
        return new Chart(this.fig5Target, {
            type: 'doughnut',
            data: {
                labels:   FINANCING.labels,
                datasets: [{
                    data:            FINANCING.data,
                    backgroundColor: FINANCING.colors,
                    borderColor:     '#ffffff',
                    borderWidth:     3,
                    hoverOffset:     10,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: EU_BLUE, font: { size: 12 }, padding: 14 },
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label} : ${ctx.raw} %`,
                        },
                    },
                    title: {
                        display: true,
                        text: 'Fig. 5 — Sources de financement hypothétiques du SVG',
                        color: EU_BLUE,
                        font: { size: 13, weight: '600', family: 'Inter, sans-serif' },
                        padding: { bottom: 16 },
                    },
                },
            },
        });
    }

    // -------------------------------------------------------------------------
    // Fig. 6 — Taux de pauvreté avant / après SVG (simulation)
    // -------------------------------------------------------------------------
    _buildFig6() {
        const Chart = window.Chart;
        return new Chart(this.fig6Target, {
            type: 'bar',
            data: {
                labels: POVERTY.countries,
                datasets: [
                    {
                        label: 'Avant SVG (%)',
                        data:  POVERTY.before,
                        backgroundColor: 'rgba(0,51,153,0.70)',
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Après SVG — simulation (%)',
                        data:  POVERTY.after,
                        backgroundColor: EU_YELLOW,
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: EU_BLUE, font: { size: 12 } },
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label} : ${ctx.raw.toFixed(1)} %`,
                        },
                    },
                    title: {
                        display: true,
                        text: 'Fig. 6 — Réduction de la pauvreté : simulation SVG (données hypothétiques)',
                        color: EU_BLUE,
                        font: { size: 13, weight: '600', family: 'Inter, sans-serif' },
                        padding: { bottom: 16 },
                    },
                },
                scales: {
                    x: {
                        grid:  { display: false },
                        ticks: { color: EU_BLUE, font: { size: 12 } },
                    },
                    y: {
                        grid:  { color: EU_SOFT },
                        max:   40,
                        ticks: { color: EU_BLUE2, callback: v => `${v} %` },
                        title: { display: true, text: 'Taux de pauvreté (%)', color: EU_BLUE2 },
                    },
                },
            },
        });
    }
}
