import { Controller } from '@hotwired/stimulus';

/**
 * Simulateur interactif du Socle Vital Garanti (SVG) européen.
 *
 * Formule officielle Civitalisme :
 *   SVG(h,t) = max{ 0 ; LW₀ × Eq(h) × Zone(h) × IndexHICP(t)
 *                   − [ Pension(h,t−1) + max(0 ; RevenuActif(h,t−1) − 300 × Actifs(h,t−1)) ] }
 *
 * Terminologie :
 *  - Civitalisme = le think tank / umbrella
 *  - SVG (Socle Vital Garanti) = la politique
 *  - EuroE = l'outil monétaire
 */

const CONFIG = {
    LW0: 1650,
    EQ: {
        adult:  1.00,   // Premier adulte du foyer
        spouse: 0.50,   // Conjoint (foyer de 2+ adultes)
        child:  0.30,   // Par enfant à charge
    },
    ZONE: {
        expensive: 1.15,
        median:    1.00,
        cheap:     0.90,
    },
    HICP_ANNUAL: 0.021,
    FRANCHISE:   300,
    BASE_YEAR:   2026,
};

export default class extends Controller {
    static targets = [
        'adults', 'children', 'zone', 'pension',
        'activeIncome', 'activeWorkers', 'year',
        'result', 'totalResult', 'breakdown', 'bar',
        'pensionOutput', 'activeIncomeOutput',
    ];

    connect() {
        this.compute();
    }

    compute() {
        const adults        = Math.max(1, parseInt(this.adultsTarget.value,        10) || 1);
        const children      = Math.max(0, parseInt(this.childrenTarget.value,      10) || 0);
        const zone          = this.zoneTarget.value || 'median';
        const pension       = Math.max(0, parseFloat(this.pensionTarget.value)     || 0);
        const activeIncome  = Math.max(0, parseFloat(this.activeIncomeTarget.value) || 0);
        const activeWorkers = Math.max(0, parseInt(this.activeWorkersTarget.value, 10) || 0);
        const year          = parseInt(this.yearTarget.value, 10) || CONFIG.BASE_YEAR;

        const eq         = this._computeEquivalent(adults, children);
        const zoneFactor = CONFIG.ZONE[zone] ?? 1.0;
        const hicp       = this._computeHICP(year);
        const base       = CONFIG.LW0 * eq * zoneFactor * hicp;
        // Franchise = 300 € × nombre d'actifs déclarés (0 actif → pas de franchise)
        const franchiseCap = CONFIG.FRANCHISE * activeWorkers;
        const franchise    = Math.max(0, activeIncome - franchiseCap);
        const deduction    = pension + franchise;
        const svg          = Math.max(0, base - deduction);
        const total        = activeIncome + svg;

        // Mettre à jour les affichages des sliders
        this._syncSliderOutput('pensionOutput',      pension);
        this._syncSliderOutput('activeIncomeOutput', activeIncome);

        this._renderResult(svg, total, {
            base, pension, franchise, franchiseCap, deduction,
            eq, zoneFactor, hicp,
            activeIncome, activeWorkers, adults, children,
        });
    }

    // -------------------------------------------------------------------------
    _computeEquivalent(adults, children) {
        if (adults < 1) return 0;
        // 1er adulte : 1.00 | chaque adulte supplémentaire : +0.50 | chaque enfant : +0.30
        return CONFIG.EQ.adult + (adults - 1) * CONFIG.EQ.spouse + children * CONFIG.EQ.child;
    }

    _computeHICP(year) {
        const years = Math.max(0, year - CONFIG.BASE_YEAR);
        return Math.pow(1 + CONFIG.HICP_ANNUAL, years);
    }

    _fmt(n) {
        return n.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });
    }

    _syncSliderOutput(targetName, value) {
        const hasTarget = `has${targetName.charAt(0).toUpperCase()}${targetName.slice(1)}Target`;
        if (this[hasTarget]) {
            this[`${targetName}Target`].textContent = this._fmt(value);
        }
    }

    _renderResult(svg, total, { base, pension, franchise, franchiseCap, deduction, eq, zoneFactor, hicp, activeIncome, activeWorkers, adults, children }) {
        // -- Résultat principal (SVG)
        if (this.hasResultTarget) {
            this.resultTarget.textContent = this._fmt(svg);
            this.resultTarget.classList.toggle('is-zero', svg === 0);
        }

        // -- Revenu total (salaire + SVG)
        if (this.hasTotalResultTarget) {
            this.totalResultTarget.textContent = this._fmt(total);
        }

        // -- Décomposition
        if (this.hasBreakdownTarget) {
            const adultsLabel   = adults === 1 ? '1 adulte' : `${adults} adultes`;
            const childrenLabel = children === 0 ? 'sans enfant' : `${children} enfant${children > 1 ? 's' : ''}`;
            const foyer         = `${adultsLabel}, ${childrenLabel}`;

            // Lignes de la franchise (selon qu'elle soit totale, partielle, ou inexistante)
            const franchiseRows = [];
            if (activeWorkers > 0) {
                franchiseRows.push([
                    `✓ Franchise travail (${activeWorkers} actif${activeWorkers > 1 ? 's' : ''} × 300 €)`,
                    `${this._fmt(franchiseCap)} exonérés`,
                ]);
                if (franchise > 0) {
                    franchiseRows.push([
                        '− Revenu actif net (au-delà franchise)',
                        `− ${this._fmt(franchise)}`,
                    ]);
                }
            } else if (activeIncome > 0) {
                franchiseRows.push([
                    '− Revenu actif (0 actif déclaré → franchise nulle)',
                    `− ${this._fmt(activeIncome)}`,
                ]);
            }

            const rows = [
                ['LW₀ de base',                this._fmt(CONFIG.LW0)],
                [`Équivalent foyer (${foyer})`, `× ${eq.toFixed(2)}`],
                ['Zone géographique',           `× ${zoneFactor.toFixed(2)}`],
                ['Indexation HICP',             `× ${hicp.toFixed(4)}`],
                ['= Base brute SVG',            this._fmt(base)],
                ...(pension > 0 ? [['− Pensions du foyer', `− ${this._fmt(pension)}`]] : []),
                ...franchiseRows,
                ['= SVG mensuel (EuroE)',        this._fmt(svg)],
                ["+ Revenus d'activité",         `+ ${this._fmt(activeIncome)}`],
                ['= Revenu total du foyer',      this._fmt(total)],
            ];

            const svgRowIndex   = rows.findIndex(([l]) => l.startsWith('= SVG'));
            const totalRowIndex = rows.length - 1;
            this.breakdownTarget.innerHTML = rows.map(([label, value], i) => {
                const isTotal = i === totalRowIndex;
                const isSVG   = i === svgRowIndex;
                return `<div class="sim-breakdown__row${isTotal ? ' sim-breakdown__row--grand-total' : isSVG ? ' sim-breakdown__row--total' : ''}">
                    <span>${label}</span>
                    <strong>${value}</strong>
                </div>`;
            }).join('');
        }

        // -- Barre visuelle à 3 segments (SVG | franchise couverte | revenu actif net)
        if (this.hasBarTarget) {
            const maxRef      = CONFIG.LW0 * 2.4 * 1.15 * 1.1 + 4000;
            const franchInBar = Math.min(activeIncome, franchiseCap);   // part de revenu dans la franchise
            const workInBar   = Math.max(0, activeIncome - franchiseCap); // part au-delà franchise

            const pctSVG    = Math.min(100, (svg / maxRef) * 100);
            const pctFranch = Math.min(100 - pctSVG, (franchInBar / maxRef) * 100);
            const pctWork   = Math.min(100 - pctSVG - pctFranch, (workInBar / maxRef) * 100);

            const barSVG    = pctSVG    > 0 ? `<div class="sim-bar__fill sim-bar__fill--svg"    style="width:${pctSVG}%">${pctSVG > 8 ? `<span class="sim-bar__label sim-bar__label--svg">SVG ${this._fmt(svg)}</span>` : ''}</div>` : '';
            const barFranch = pctFranch > 0 ? `<div class="sim-bar__fill sim-bar__fill--franch" style="width:${pctFranch}%;left:${pctSVG}%">${pctFranch > 10 ? `<span class="sim-bar__label">Franchise ${this._fmt(franchInBar)}</span>` : ''}</div>` : '';
            const barWork   = pctWork   > 0 ? `<div class="sim-bar__fill sim-bar__fill--work"   style="width:${pctWork}%;left:${pctSVG + pctFranch}%">${pctWork > 8 ? `<span class="sim-bar__label">${this._fmt(workInBar)}</span>` : ''}</div>` : '';

            this.barTarget.innerHTML = barSVG + barFranch + barWork;
        }
    }
}
