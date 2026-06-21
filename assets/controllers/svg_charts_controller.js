import { Controller } from '@hotwired/stimulus';

/**
 * Graphiques économiques SVG — 4 figures du rapport économique.
 * Chart.js chargé en CDN UMD → window.Chart disponible.
 *
 * Fig. 3 — Plafond SVG + seuil d'écrêtement par composition de ménage (France 2026)
 * Fig. 4 — Mécanique de dégressivité : franchise → écrêtement (vs RSA actuel)
 * Fig. 5 — Coût net SVG France · décomposition waterfall (Md€/an)
 * Fig. 6 — Taux de pauvreté avant/après SVG · Eurostat SILC 2023 + projection
 */

// ── Constantes de calibration France ─────────────────────────────────────────
const LW0_FR   = 1350;   // LW₀ France zone médiane (Eurostat SILC 2023)
const LW0_EU   = 1650;   // LW₀ référence UE (Eurostat MW médiane)
const FRANCHISE = 300;   // Franchise par adulte actif (€/mois)
const RSA_2024  = 635.71;// RSA socle personne seule (Caisse nationale 2024)

// ── Palette ──────────────────────────────────────────────────────────────────
const C = {
    navy:    '#003399',
    navy2:   '#4266b8',
    navyA:   'rgba(0,51,153,0.70)',
    navyB:   'rgba(0,51,153,0.12)',
    gold:    '#ffcc00',
    goldA:   'rgba(255,204,0,0.20)',
    green:   '#1a7a5e',
    greenA:  'rgba(26,122,94,0.70)',
    red:     '#c0392b',
    redA:    'rgba(192,57,43,0.70)',
    grey:    '#9faee5',
    greyA:   'rgba(159,174,229,0.20)',
    white:   '#ffffff',
};

// ── Fig. 3 — Plafond SVG par ménage ──────────────────────────────────────────
// Echelle OCDE modifiée : 1er adulte = 1.0, adulte supp. = +0.5, enfant = +0.3
// Seuil d'écrêtement = LW₀×Eq + FRANCHISE×n_actifs
const HOUSEHOLDS = [
    { label: 'Personne seule',            eq: 1.0, nActifs: 1 },
    { label: 'Couple sans enfant',        eq: 1.5, nActifs: 2 },
    { label: 'Couple + 1 enfant',         eq: 1.8, nActifs: 2 },
    { label: 'Couple + 2 enfants',        eq: 2.1, nActifs: 2 },
    { label: 'Couple + 3 enfants',        eq: 2.4, nActifs: 2 },
    { label: 'Parent isolé + 1 enfant',   eq: 1.3, nActifs: 1 },
    { label: 'Parent isolé + 2 enfants',  eq: 1.6, nActifs: 1 },
];

// ── Fig. 5 — Coût net SVG France · waterfall (Md€/an) ───────────────────────
// Source : modélisation Civitalisme + Drees 2024 + DSS PLF 2025
// Hypothèses : 14,8 M bénéficiaires, versement moyen 607 €/mois
const WATERFALL = [
    //  label                          start   end    color
    { label: 'Coût brut\n(distribution CDC)', s: 0,   e: 108, fill: C.navyA },
    { label: 'TVA collectée\n(~20 %)',         s: 108, e:  86, fill: C.greenA },
    { label: 'Éco. aides\nexistantes*',        s: 86,  e:  61, fill: C.greenA },
    { label: 'Retours fiscaux\ninduits',        s: 61,  e:  51, fill: C.greenA },
    { label: 'Coût net\nrésiduel',             s:  0,  e:  51, fill: C.gold   },
];
// * RSA + APL + AAH partiellement subsumés (Drees 2024)

// ── Fig. 6 — Taux AROP (At-Risk-Of-Poverty) · Eurostat SILC 2023 ────────────
// Source : Eurostat ilc_li02 — seuil 60 % revenu médian équivalent
// Projection "après SVG" : modélisation hypothèse couverture universelle
// Réduction estimée 55–65 % d'après : Straubhaar 2017, Kangas et al. 2019 (Kela)
const POVERTY = {
    countries: ['France', 'Allemagne', 'Italie', 'Espagne', 'Pologne', 'Roumanie'],
    before:    [14.4,     16.1,        20.1,     20.9,      14.2,      32.2],
    after:     [ 5.6,      6.3,         8.3,      8.8,       5.7,      14.1],
    // Réduction ≈ 60 % — cohérente avec pilote finlandais Kela 2017-2018
};

// ─────────────────────────────────────────────────────────────────────────────

export default class extends Controller {
    static targets = ['fig3', 'fig4', 'fig5', 'fig6'];

    connect() {
        const Chart = window.Chart;
        if (!Chart) {
            console.warn('[svg-charts] Chart.js non disponible — fallback Canvas.');
            this._initFallbacks();
            return;
        }
        this._initAll(Chart);
    }

    disconnect() {
        this._charts?.forEach(c => c.destroy());
        this._charts = [];
        if (this._resizeHandler) window.removeEventListener('resize', this._resizeHandler);
    }

    _initAll(Chart) {
        this._charts = [];
        if (this.hasFig3Target) this._charts.push(this._buildFig3(Chart));
        if (this.hasFig4Target) this._charts.push(this._buildFig4(Chart));
        if (this.hasFig5Target) this._charts.push(this._buildFig5(Chart));
        if (this.hasFig6Target) this._charts.push(this._buildFig6(Chart));
    }

    // ── Helpers ────────────────────────────────────────────────────────────────
    _baseTitle(text) {
        return {
            display: true,
            text,
            color: C.navy,
            font: { size: 13, weight: '600', family: 'Inter, sans-serif' },
            padding: { bottom: 14 },
        };
    }

    _baseTooltip(fmtFn) {
        return { callbacks: { label: ctx => ` ${fmtFn(ctx)}` } };
    }

    // ── Fig. 3 ─────────────────────────────────────────────────────────────────
    _buildFig3(Chart) {
        const labels  = HOUSEHOLDS.map(h => h.label);
        const plafond = HOUSEHOLDS.map(h => Math.round(LW0_FR * h.eq));
        const seuil   = HOUSEHOLDS.map(h => Math.round(LW0_FR * h.eq) + FRANCHISE * h.nActifs);

        return new Chart(this.fig3Target, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Plafond SVG (€/mois)',
                        data: plafond,
                        backgroundColor: C.navyA,
                        borderColor: C.navy,
                        borderWidth: 1.5,
                        borderRadius: 5,
                        borderSkipped: false,
                    },
                    {
                        label: 'Seuil d\'écrêtement (€/mois)',
                        data: seuil,
                        backgroundColor: C.goldA,
                        borderColor: C.gold,
                        borderWidth: 1.5,
                        borderRadius: 5,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: C.navy, font: { size: 11 }, padding: 12 },
                    },
                    tooltip: this._baseTooltip(ctx => `${ctx.raw.toLocaleString('fr-FR')} €/mois`),
                    title: this._baseTitle([
                        'Entre 1 350 € et 3 960 €/mois selon la composition du foyer',
                        'Plafond SVG avant déductions · France 2026 · Échelle OCDE modifiée · Eurostat SILC 2023',
                    ]),
                },
                scales: {
                    x: {
                        grid: { color: C.greyA },
                        ticks: { color: C.navy2, callback: v => `${(v/1000).toFixed(1)}k €` },
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: C.navy, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ── Fig. 4 ─────────────────────────────────────────────────────────────────
    _buildFig4(Chart) {
        // Personne seule, zone médiane France, n=1 actif
        // SVG = max(0, LW₀ - max(0, Revenu - FRANCHISE))
        // Phase 1 [0-300€] : franchise active → SVG = LW₀_FR = 1350€
        // Phase 2 [300-1650€] : dégressivité 1 pour 1 → SVG = 1650 - Revenu
        // Phase 3 [>1650€] : SVG = 0
        const incomes = Array.from({ length: 22 }, (_, i) => i * 100); // 0…2100
        const svgVals = incomes.map(rev => {
            const deduit = Math.max(0, rev - FRANCHISE);
            return Math.max(0, LW0_FR - deduit);
        });
        const totalVals = incomes.map((rev, i) => rev + svgVals[i]);
        const rsaLine   = incomes.map(() => RSA_2024);

        return new Chart(this.fig4Target, {
            type: 'line',
            data: {
                labels: incomes.map(v => `${v}`),
                datasets: [
                    {
                        label: `SVG France (€/mois)`,
                        data: svgVals,
                        borderColor: C.navy,
                        backgroundColor: 'rgba(0,51,153,0.08)',
                        fill: true,
                        tension: 0,
                        pointRadius: 0,
                        borderWidth: 2.5,
                    },
                    {
                        label: 'Revenu total net (salaire + SVG)',
                        data: totalVals,
                        borderColor: C.gold,
                        backgroundColor: 'rgba(255,204,0,0.10)',
                        fill: true,
                        tension: 0,
                        pointRadius: 0,
                        borderWidth: 2.5,
                        borderDash: [],
                    },
                    {
                        label: `RSA socle 2024 (${RSA_2024.toFixed(0)} €/mois)`,
                        data: rsaLine,
                        borderColor: C.red,
                        borderDash: [5, 4],
                        pointRadius: 0,
                        borderWidth: 1.8,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: C.navy, font: { size: 11 }, padding: 12 },
                    },
                    tooltip: this._baseTooltip(ctx => `${ctx.dataset.label} : ${ctx.raw.toLocaleString('fr-FR')} €`),
                    title: this._baseTitle([
                        'Chaque euro gagné augmente toujours le revenu total',
                        '1 adulte seul · France · SVG dégressif avec franchise 300 €/actif · RSA actuel = 635 €/mois (Caisse nationale 2024)',
                    ]),
                },
                scales: {
                    x: {
                        grid: { color: C.greyA },
                        ticks: {
                            color: C.navy2,
                            maxRotation: 0,
                            maxTicksLimit: 11,
                            font: { size: 11 },
                            callback: v => `${v} €`,
                        },
                        title: { display: true, text: 'Revenu actif (€/mois)', color: C.navy2, font: { size: 11 } },
                    },
                    y: {
                        grid: { color: C.greyA },
                        max: 2100,
                        ticks: { color: C.navy, callback: v => `${v} €` },
                        title: { display: true, text: '€/mois', color: C.navy2, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ── Fig. 5 ─────────────────────────────────────────────────────────────────
    // Waterfall coût brut → coût net SVG France (Md€/an)
    // Chart.js floating bars : data = [start, end]
    _buildFig5(Chart) {
        const labels = WATERFALL.map(w => w.label);
        const data   = WATERFALL.map(w => [w.s, w.e]);
        const fills  = WATERFALL.map(w => w.fill);

        return new Chart(this.fig5Target, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Md€/an',
                    data,
                    backgroundColor: fills,
                    borderColor: fills.map(f => f.replace(/[\d.]+\)$/, '1)')),
                    borderWidth: 1.5,
                    borderRadius: 5,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const [s, e] = ctx.raw;
                                const val = Math.abs(e - s);
                                const sign = e < s ? '−' : '';
                                return ` ${sign}${val} Md€/an`;
                            },
                        },
                    },
                    title: this._baseTitle([
                        'Coût net : 51 Md€/an pour la France — soit 1,8 % du PIB',
                        'Après TVA collectée, économies sur aides existantes et retours fiscaux · Drees 2024, DSS PLF 2025',
                    ]),
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: C.navy, font: { size: 11 }, maxRotation: 0 },
                    },
                    y: {
                        grid: { color: C.greyA },
                        min: 0,
                        max: 120,
                        ticks: { color: C.navy2, callback: v => `${v} Md€` },
                        title: { display: true, text: 'Milliards €/an', color: C.navy2, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ── Fig. 6 ─────────────────────────────────────────────────────────────────
    _buildFig6(Chart) {
        return new Chart(this.fig6Target, {
            type: 'bar',
            data: {
                labels: POVERTY.countries,
                datasets: [
                    {
                        label: 'Avant SVG — Eurostat SILC 2023 (%)',
                        data:  POVERTY.before,
                        backgroundColor: C.navyA,
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Après SVG — projection modélisée (%)',
                        data:  POVERTY.after,
                        backgroundColor: C.gold,
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
                        labels: { color: C.navy, font: { size: 11 }, padding: 12 },
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label} : ${ctx.raw.toFixed(1)} %`,
                            afterBody: () => ['(Réduction estimée ≈ 60 % · Hypothèse couverture universelle)'],
                        },
                    },
                    title: this._baseTitle([
                        'La pauvreté divisée par 2,5 dans les pays les plus exposés',
                        'Avant = Eurostat SILC 2023 (ilc_li02) · Après = projection modélisée · hypothèse couverture universelle (Kangas et al. 2019)',
                    ]),
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: C.navy, font: { size: 12 } },
                    },
                    y: {
                        grid: { color: C.greyA },
                        max: 40,
                        ticks: { color: C.navy2, callback: v => `${v} %` },
                        title: { display: true, text: 'Taux AROP (% pop.)', color: C.navy2, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ── Fallbacks Canvas (sans Chart.js) ────────────────────────────────────────
    _initFallbacks() {
        this._drawFallbacks();
        this._resizeHandler = () => {
            clearTimeout(this._resizeTimer);
            this._resizeTimer = setTimeout(() => this._drawFallbacks(), 120);
        };
        window.addEventListener('resize', this._resizeHandler);
    }

    _drawFallbacks() {
        if (this.hasFig3Target) this._fallbackBars(this.fig3Target, 'Fig. 3 — Plafond SVG par ménage (France 2026)',
            HOUSEHOLDS.map(h => h.label),
            HOUSEHOLDS.map(h => Math.round(LW0_FR * h.eq)),
            v => `${v.toLocaleString('fr-FR')} €`);
        if (this.hasFig4Target) this._fallbackLine(this.fig4Target);
        if (this.hasFig5Target) this._fallbackWaterfall(this.fig5Target);
        if (this.hasFig6Target) this._fallbackBars(this.fig6Target, 'Fig. 6 — Taux AROP avant/après SVG',
            POVERTY.countries,
            POVERTY.before,
            v => `${v.toFixed(1)} %`);
    }

    _prepareCanvas(canvas) {
        const wrap   = canvas.parentElement;
        const width  = Math.max(320, wrap?.clientWidth ?? 640);
        const height = Math.max(260, wrap?.clientHeight ?? 320);
        const dpr    = window.devicePixelRatio || 1;
        canvas.width  = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        canvas.style.width  = `${width}px`;
        canvas.style.height = `${height}px`;
        const ctx = canvas.getContext('2d');
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);
        return { ctx, w: width, h: height };
    }

    _fallbackBars(canvas, title, labels, values, fmt) {
        const { ctx, w, h } = this._prepareCanvas(canvas);
        const left = w < 500 ? 120 : 170;
        const top = 50; const row = Math.max(22, (h - top - 20) / labels.length);
        const max = Math.max(...values);
        ctx.fillStyle = C.navy; ctx.font = '600 12px Inter,sans-serif';
        ctx.textAlign = 'center'; ctx.fillText(title, w / 2, 22);
        labels.forEach((lbl, i) => {
            const y = top + i * row;
            const bw = ((w - left - 20) * values[i]) / max;
            ctx.fillStyle = C.greyA; ctx.fillRect(left, y + 6, w - left - 20, 14);
            ctx.fillStyle = i % 2 ? C.navy2 : C.navy; ctx.fillRect(left, y + 6, bw, 14);
            ctx.fillStyle = C.navy; ctx.textAlign = 'right'; ctx.font = '11px Inter,sans-serif';
            ctx.fillText(lbl, left - 8, y + 13);
            ctx.textAlign = 'left'; ctx.fillText(fmt(values[i]), left + bw + 6, y + 13);
        });
    }

    _fallbackLine(canvas) {
        const { ctx, w, h } = this._prepareCanvas(canvas);
        const incomes = Array.from({ length: 22 }, (_, i) => i * 100);
        const svgVals = incomes.map(r => Math.max(0, LW0_FR - Math.max(0, r - FRANCHISE)));
        const totals  = incomes.map((r, i) => r + svgVals[i]);
        const left = 44; const right = 20; const top = 46; const bot = 40;
        const cW = w - left - right; const cH = h - top - bot;
        const maxV = Math.max(...totals);
        const X = i => left + (i / (incomes.length - 1)) * cW;
        const Y = v => top + cH - (v / maxV) * cH;
        ctx.fillStyle = C.navy; ctx.font = '600 12px Inter,sans-serif'; ctx.textAlign = 'center';
        ctx.fillText('Fig. 4 — Travailler reste toujours plus rentable', w / 2, 22);
        [[svgVals, C.navy], [totals, '#b38f00']].forEach(([vals, col]) => {
            ctx.strokeStyle = col; ctx.lineWidth = 2.5; ctx.beginPath();
            vals.forEach((v, i) => i ? ctx.lineTo(X(i), Y(v)) : ctx.moveTo(X(i), Y(v)));
            ctx.stroke();
        });
        // RSA line
        ctx.strokeStyle = C.red; ctx.setLineDash([5,4]); ctx.lineWidth = 1.8; ctx.beginPath();
        ctx.moveTo(left, Y(RSA_2024)); ctx.lineTo(w - right, Y(RSA_2024)); ctx.stroke();
        ctx.setLineDash([]);
    }

    _fallbackWaterfall(canvas) {
        const { ctx, w, h } = this._prepareCanvas(canvas);
        const left = 20; const right = 20; const top = 46; const bot = 60;
        const cW = w - left - right; const cH = h - top - bot;
        const barW = cW / WATERFALL.length;
        const maxV = 120;
        ctx.fillStyle = C.navy; ctx.font = '600 12px Inter,sans-serif'; ctx.textAlign = 'center';
        ctx.fillText('Fig. 5 — Coût net SVG France · Md€/an', w / 2, 22);
        WATERFALL.forEach(({ label, s, e, fill }, i) => {
            const x = left + i * barW + barW * 0.15;
            const bW2 = barW * 0.7;
            const yStart = top + cH - (Math.max(s,e) / maxV) * cH;
            const bH = (Math.abs(e - s) / maxV) * cH;
            ctx.fillStyle = fill; ctx.fillRect(x, yStart, bW2, bH);
            ctx.fillStyle = C.navy; ctx.font = '10px Inter,sans-serif'; ctx.textAlign = 'center';
            ctx.fillText(`${Math.abs(e - s)} Md€`, x + bW2 / 2, yStart - 6);
            ctx.fillText(label.replace('\n', ' '), x + bW2 / 2, top + cH + 14 + (i % 2) * 14);
        });
    }
}
