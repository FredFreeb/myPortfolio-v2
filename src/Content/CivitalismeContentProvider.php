<?php

namespace App\Content;

/**
 * Provides the structured content arrays consumed by the Civitalisme Twig templates.
 *
 * Both public pages (grand public + cadre institutionnel) are data-driven rather than
 * database-backed, which keeps them fast, version-controllable and editable without
 * touching the admin or running migrations.
 *
 * Content is derived from the project documents in /public/documents/:
 *  - SVG-Partie-Philosophique.pdf   (IA, travail et dignite)
 *  - SVG-Partie-Economie.pdf        (simulation et financement)
 *  - SVG-Partie-Technique.pdf       (plateforme web3 CDC)
 *  - SVG-Partie-Politique.pdf       (gouvernance et cadre institutionnel)
 *  - SVG-Partie-Juridique.pdf       (base legale et conformite)
 *  - SVG-Parite-Sociologie.pdf      (impact social et dignite)
 *  - SVG-Partie-Entrepreneuriale.pdf (PME, reconversion, impact sectoriel)
 *  - SVG-Partie-Syndicale.pdf       (dialogue social, conventions collectives, nouveau role syndical)
 *
 * Sections in publicPage()
 * -------------------------
 *  hero         - Accroche : pourquoi le Civitalisme existe
 *  heroPanel    - 3 idees cles a retenir
 *  problem      - Le diagnostic : IA, emploi et circuit brise
 *  financing    - Comment l\'argent est cree et comment le CDC s\'insere
 *  howItWorks   - 5 questions simples
 *  usage        - A quoi sert le CDC au quotidien
 *  work         - Pourquoi le travail reste essentiel
 *  business     - Impact pour les entreprises et les PME
 *  caseStudy    - 4 profils concrets
 *  privacy      - Protection des donnees
 *  faq          - Objections et reponses
 *  conclusion   - Phrase de synthese
 *
 * Sections in technicalPage()
 * ----------------------------
 *  hero          - Cadrage institutionnel
 *  executive     - Synthese executive + 7 rapports documentaires
 *  doctrine      - Double circuit de depenses (euro / CDC)
 *  architecture  - Plateforme CDC (8 couches + cycle de vie)
 *  formula       - Formule SVG et parametres
 *  legal         - Conformite AI Act / RGPD / DSP2 / MiCA / DORA + risques
 *  roadmap       - Quatre phases 2026-2029
 *  downloads     - Telechargement des 7 rapports PDF
 *  cta           - Contact institutionnel
 */
final class CivitalismeContentProvider
{
    /**
     * Content for the grand-public (citizen-facing) page.
     *
     * Structure: 9 thematic sections, one illustration per section, short copy.
     * Terminology: Civitalisme = think tank umbrella. Socle Vital Garanti (SVG) = the policy.
     * CDC (Compte de Dépenses Conditionnées) = l'instrument de paiement conditionnel. Never say "revenu universel", "monnaie", or "cryptomonnaie".
     *
     * @return array<string, mixed>
     */
    public function publicPage(): array
    {
        return [
            // ── 1. Hero ─────────────────────────────────────────────────────────
            'hero' => [
                'eyebrow'    => 'Civitalisme — Le projet en 3 minutes',
                'title'      => 'Et si personne ne pouvait plus tomber ?',
                'subtitle'   => 'Le Socle Vital Garanti : un droit automatique aux besoins essentiels',
                'lead'       => 'L\'IA automatise des métiers, les fins de mois deviennent plus serrées, les aides plus compliquées. Le Civitalisme propose une réponse simple : un complément mensuel calculé automatiquement, utilisable uniquement pour les dépenses vitales — sans formulaire, sans dossier.',
                'stats'      => [
                    ['value' => '93 M', 'label' => 'de personnes sous le seuil de pauvreté en Europe'],
                    ['value' => '40 %', 'label' => 'des emplois exposés à l\'automatisation d\'ici 2030'],
                    ['value' => '0',    'label' => 'formulaire à remplir — tout est automatique'],
                ],
                'primaryCta' => [
                    'label' => 'Comprendre le mécanisme',
                    'href'  => '#probleme',
                ],
                'secondaryCta' => [
                    'label' => 'Cadre institutionnel',
                    'href'  => null,
                ],
            ],

            // ── 2. "En 30 secondes" strip ────────────────────────────────────────
            'brief' => [
                'eyebrow' => 'L\'essentiel en 30 secondes',
                'steps'   => [
                    [
                        'icon'  => 'calc',
                        'title' => 'Calculé automatiquement',
                        'body'  => 'Chaque mois, votre banque compare vos ressources à un panier vital. L\'écart devient votre complément.',
                    ],
                    [
                        'icon'  => 'card',
                        'title' => 'Versé sur un compte dédié',
                        'body'  => 'Un Compte de Dépenses Conditionnées (CDC) est crédité. Il fonctionne comme une carte bancaire normale.',
                    ],
                    [
                        'icon'  => 'shop',
                        'title' => 'Dépensé normalement',
                        'body'  => 'Loyer, courses, énergie, transport — partout où ces dépenses vitales sont acceptées. Sans étiquette.',
                    ],
                    [
                        'icon'  => 'loop',
                        'title' => 'L\'argent reste en Europe',
                        'body'  => 'Le CDC ne peut circuler qu\'auprès d\'entreprises européennes agréées. Il génère de la TVA et soutient l\'économie locale.',
                    ],
                ],
            ],

            // ── 3. Problem ───────────────────────────────────────────────────────
            'problem' => [
                'anchor' => 'probleme',
                'eyebrow' => 'Pourquoi maintenant',
                'title'   => 'L\'IA change tout. Pas que pour les autres.',
                'lead'    => 'En quelques années, l\'automatisation touche des métiers qu\'on croyait protégés. Les revenus deviennent plus instables, les protections sociales plus difficiles à activer.',
                'cards'   => [
                    [
                        'illustration' => 'robot-desk',
                        'stat'         => '40 %',
                        'title'        => 'des emplois européens exposés à l\'automatisation',
                        'body'         => 'Comptabilité, logistique, conseil, rédaction : ce ne sont plus seulement les usines qui sont concernées. Le McKinsey Global Institute estime que la transition sera deux fois plus rapide que lors de la révolution industrielle.',
                    ],
                    [
                        'illustration' => 'falling-chart',
                        'stat'         => '61 %',
                        'title'        => 'des ménages modestes peinent à boucler leurs fins de mois',
                        'body'         => 'Temps partiels subis, missions courtes, transitions non choisies : la précarité ne touche plus seulement les personnes sans emploi — elle s\'installe chez des actifs.',
                    ],
                    [
                        'illustration' => 'household-bill',
                        'stat'         => '93 M',
                        'title'        => 'de personnes déjà sous le seuil de pauvreté en Europe',
                        'body'         => 'Une facture imprévue, une perte d\'heures, une rupture familiale — et un foyer bascule. Le système actuel est fait pour les situations stables. Il n\'a pas été conçu pour l\'ère des transitions rapides.',
                    ],
                ],
                'transition' => [
                    'text'   => 'Aucune aide existante n\'a été conçue pour l\'ère des transitions rapides. Il faut un nouveau filet.',
                    'accent' => 'Le Socle Vital Garanti est cette réponse.',
                ],
            ],

            // ── 4. Solution ──────────────────────────────────────────────────────
            'solution' => [
                'eyebrow'  => 'La réponse du Civitalisme',
                'title'    => 'Le Socle Vital Garanti',
                'lead'     => 'Un complément mensuel automatique, conditionnel, ciblé sur ce dont vous avez besoin pour vivre dignement.',
                'formula'  => [
                    'label' => 'La formule est simple',
                    'text'  => 'SVG = Panier vital estimé − Vos ressources du mois',
                    'note'  => 'Le panier vital est défini démocratiquement chaque année par le Parlement européen. Il couvre les 6 postes de dépenses incontournables.',
                ],
                'subtext'  => 'Le montant s\'adapte chaque mois à votre situation réelle. Il ne peut pas être retiré en liquide ni transféré.',
                'closedLoop' => [
                    'title' => 'Ce n\'est pas un impôt de plus — c\'est une boucle fermée',
                    'body'  => 'Le CDC ne peut être dépensé qu\'auprès d\'entreprises européennes agréées. Les commerçants convertissent ensuite le CDC en euros classiques. Cette circulation génère de la TVA et de l\'activité locale. Le coût net pour la collectivité est bien inférieur au montant distribué.',
                ],
                'needs' => [
                    ['icon' => 'home',          'label' => 'Logement'],
                    ['icon' => 'food',          'label' => 'Alimentation'],
                    ['icon' => 'energy',        'label' => 'Énergie'],
                    ['icon' => 'transport',     'label' => 'Transport'],
                    ['icon' => 'communication', 'label' => 'Communication'],
                    ['icon' => 'education',     'label' => 'Éducation'],
                ],
            ],

            // ── 5. How it works ──────────────────────────────────────────────────
            'howItWorks' => [
                'eyebrow' => 'En pratique',
                'title'   => 'Trois étapes. Zéro démarche.',
                'lead'    => 'Le SVG fonctionne en arrière-plan, comme un abonnement à votre dignité.',
                'steps'   => [
                    [
                        'number'       => '1',
                        'illustration' => 'phone-app',
                        'title'        => 'Votre banque calcule automatiquement',
                        'body'         => 'Chaque mois, un algorithme certifié par la BCE compare vos revenus réels au panier vital de votre zone géographique. Aucune démarche de votre part.',
                    ],
                    [
                        'number'       => '2',
                        'illustration' => 'payment-card',
                        'title'        => 'Le CDC est crédité sur votre compte',
                        'body'         => 'Le Compte de Dépenses Conditionnées (CDC) reçoit le complément calculé. Il ressemble à une carte bancaire ordinaire — aucun signe distinctif visible.',
                    ],
                    [
                        'number'       => '3',
                        'illustration' => 'shopping',
                        'title'        => 'Vous payez comme d\'habitude',
                        'body'         => 'Chez votre boulanger, votre bailleur, votre fournisseur d\'énergie. Pas d\'étiquette, pas de gêne. Le commerçant ne voit aucune différence.',
                    ],
                ],
            ],

            // ── 6. Work incentive ────────────────────────────────────────────────
            'workIncentive' => [
                'eyebrow'       => 'Et si je travaille ?',
                'title'         => 'Le SVG est un tremplin, pas un frein',
                'lead'          => 'Chaque euro de salaire gagné vous enrichit. Le complément diminue progressivement — mais le gain net est toujours positif. Toujours.',
                'trampolineNote'=> 'Les 300 premiers euros de salaire ne réduisent pas votre complément. Au-delà, chaque euro de salaire supplémentaire vous rapporte plus qu\'il ne retire au SVG.',
                'chart'         => [
                    'caption' => 'Exemple — adulte seul en grande ville européenne (Eurostat 2024 + Numbeo)',
                    'bars'    => [
                        [
                            'label'     => 'Sans emploi',
                            'total'     => 1488,
                            'breakdown' => [
                                ['label' => 'Socle vital', 'amount' => 1488, 'kind' => 'svg'],
                            ],
                        ],
                        [
                            'label'     => 'Mi-temps à 850 €',
                            'total'     => 1938,
                            'breakdown' => [
                                ['label' => 'Salaire', 'amount' => 850,  'kind' => 'work'],
                                ['label' => 'Complément', 'amount' => 1088, 'kind' => 'svg'],
                            ],
                        ],
                        [
                            'label'     => 'Temps plein à 1 700 €',
                            'total'     => 2201,
                            'breakdown' => [
                                ['label' => 'Salaire', 'amount' => 1700, 'kind' => 'work'],
                                ['label' => 'Complément', 'amount' => 501,  'kind' => 'svg'],
                            ],
                        ],
                    ],
                    'gain'    => [
                        'label' => 'Gain entre sans-emploi et temps plein',
                        'value' => '+713 € / mois',
                    ],
                ],
                'note' => 'Source : seuil de pauvreté Eurostat (60 % du revenu médian national) ajusté au coût réel du logement en zone urbaine dense.',
            ],

            // ── 7. Personas ──────────────────────────────────────────────────────
            'personas' => [
                'eyebrow' => 'Pour qui ?',
                'title'   => 'Pour vous, pour vos proches, pour tout le monde',
                'lead'    => 'Le SVG n\'est pas réservé aux personnes en grande difficulté. Il accompagne chaque transition de vie — formation, reconversion, retraite insuffisante, parentalité.',
                'cards'   => [
                    [
                        'illustration' => 'persona-marie',
                        'name'         => 'Marie, 34 ans',
                        'situation'    => 'Mère seule · 2 enfants',
                        'svg_amount'   => '620 €/mois',
                        'body'         => 'Son contrat passe à 24 h. Son loyer, lui, ne baisse pas. Le SVG couvre la différence automatiquement — sans dossier, sans rendez-vous.',
                    ],
                    [
                        'illustration' => 'persona-lucas',
                        'name'         => 'Lucas, 22 ans',
                        'situation'    => 'Étudiant en reconversion',
                        'svg_amount'   => '1 100 €/mois',
                        'body'         => 'Il quitte la logistique pour l\'informatique. Le SVG lui permet de se former à plein temps sans choisir entre payer son loyer et ses cours.',
                    ],
                    [
                        'illustration' => 'persona-fatima',
                        'name'         => 'Fatima, 68 ans',
                        'situation'    => 'Retraitée · petite pension',
                        'svg_amount'   => '380 €/mois',
                        'body'         => 'Sa retraite est insuffisante pour les fin de mois. Le SVG complète sans qu\'elle ait à contacter une assistante sociale ou remplir un dossier.',
                    ],
                    [
                        'illustration' => 'persona-thomas',
                        'name'         => 'Thomas, 45 ans',
                        'situation'    => 'Ouvrier · usine qui se modernise',
                        'svg_amount'   => '290 €/mois',
                        'body'         => 'La moitié des postes de son atelier sont automatisés. Le SVG amortit la transition — il se forme, sans la panique financière.',
                    ],
                ],
            ],

            // ── 8. FAQ / objections ──────────────────────────────────────────────
            'faq' => [
                'eyebrow' => 'Vos questions',
                'title'   => 'Ce qu\'on nous demande le plus souvent',
                'lead'    => 'Des réponses directes aux vraies questions — sans jargon.',
                'items'   => [
                    [
                        'q' => 'C\'est un revenu universel ?',
                        'a' => 'Non. Le SVG n\'est pas un revenu universel indifférencié. Il ne verse pas d\'argent libre : le Compte de Dépenses Conditionnées (CDC) ne fonctionne que pour les dépenses vitales (loyer, courses, énergie, transport, santé, éducation). Impossible de le retirer en liquide, de l\'utiliser pour du luxe ou de s\'en servir comme porte de sortie du marché du travail. C\'est même l\'inverse : l\'argent circule exclusivement auprès d\'entreprises européennes agréées, génère de la TVA et stimule l\'activité locale. Le SVG est un dopant économique, pas une trappe à l\'inactivité.',
                    ],
                    [
                        'q' => 'Qui paie ?',
                        'a' => 'Le financement repose sur trois piliers : (1) la TVA générée par les dépenses SVG elles-mêmes — l\'argent circule dans l\'économie locale ; (2) une contribution sur la valeur créée par les systèmes d\'IA et d\'automatisation qui remplacent des emplois ; (3) les économies réalisées sur les dépenses sociales d\'urgence (hébergements d\'urgence, urgences hospitalières, surcoûts administratifs). La doctrine complète est dans les rapports téléchargeables.',
                    ],
                    [
                        'q' => 'Mon salaire va-t-il baisser ?',
                        'a' => 'Non. Le SMIC et les minima conventionnels sont inchangés. Le SVG s\'ajoute à vos revenus, il ne les remplace pas. Les conventions collectives et droits syndicaux sont pleinement préservés. Le SVG ne crée aucune pression à la baisse sur les salaires.',
                    ],
                    [
                        'q' => 'Et mes aides actuelles — CAF, RSA, allocations ?',
                        'a' => 'Elles ne disparaissent pas du jour au lendemain. Le SVG s\'intègre progressivement au système existant. À terme, il simplifie et remplace certaines aides fragmentées par un droit unique et automatique — plus de dossier à renouveler, plus de rupture de droits.',
                    ],
                    [
                        'q' => 'N\'est-ce pas inflationniste ?',
                        'a' => 'Le CDC est conditionné : il ne peut pas être utilisé en dehors du panier vital. Il ne s\'injecte pas dans l\'économie générale comme de la monnaie libre. La demande générée concerne uniquement des biens de première nécessité — secteurs où les marges sont contrôlées et la concurrence forte. Les simulations macroéconomiques montrent un effet inflationniste marginal (< 0,3 % selon notre rapport économique).',
                    ],
                    [
                        'q' => 'C\'est réaliste ? Qui le porte politiquement ?',
                        'a' => 'Le Civitalisme est un think tank indépendant. Ce projet ne porte aucune étiquette partisane. Les 8 rapports thématiques (philosophie, économie, technique, politique, juridique, sociologie, entrepreneuriat, syndicalisme) ont été rédigés pour être débattus, cités et amendés. La doctrine est en accès libre — c\'est intentionnel.',
                    ],
                ],
            ],

            // ── 9. Preserved rights ──────────────────────────────────────────────
            'preservedRights' => [
                'eyebrow' => 'Ce qui ne change pas',
                'title'   => 'Vos droits actuels sont protégés',
                'lead'    => 'Le SVG s\'ajoute. Il ne supprime rien.',
                'points'  => [
                    ['icon' => 'pension',    'text' => 'Votre retraite reste votre retraite.'],
                    ['icon' => 'salary',     'text' => 'Votre salaire et le SMIC sont inchangés.'],
                    ['icon' => 'chomage',    'text' => 'Vos droits au chômage (ARE) sont maintenus.'],
                    ['icon' => 'syndicat',   'text' => 'Vos conventions collectives sont pleinement préservées.'],
                    ['icon' => 'aides',      'text' => 'Vos aides actuelles ne sont pas supprimées — elles sont simplifiées.'],
                    ['icon' => 'licencie',   'text' => 'Un licenciement ne signifie plus la précarité immédiate : le SVG couvre les essentiels dès le mois suivant.'],
                ],
            ],

            // ── 10. Europe ────────────────────────────────────────────────────────
            'europe' => [
                'eyebrow' => 'Souveraineté européenne',
                'title'   => 'Un projet européen, pour les Européens',
                'lead'    => 'Hébergé en Europe, construit sur des rails de paiement européens (TIPS, TARGET, Wero), contrôlé par des institutions démocratiques. Le Civitalisme, c\'est la souveraineté économique mise au service de chaque citoyen.',
                'pillars' => [
                    ['label' => 'Infrastructure européenne', 'body' => 'Données, calcul et paiements hébergés en Europe uniquement. Aucune dépendance à des plateformes extra-européennes.'],
                    ['label' => 'Rails de paiement existants', 'body' => 'TIPS, TARGET2, Wero — les infrastructures actuelles, pas une nouvelle monnaie.'],
                    ['label' => 'Contrôle démocratique', 'body' => 'Gouvernance partagée : Parlement européen, Conseil, BCE, Cour des comptes européenne et comités citoyens.'],
                ],
            ],

            // ── 11. CTA ───────────────────────────────────────────────────────────
            'cta' => [
                'eyebrow' => 'Envie d\'aller plus loin ?',
                'title'   => 'La doctrine est en accès libre',
                'body'    => '8 rapports thématiques — philosophie, économie, technique, droit, sociologie, syndicalisme. Tout est documenté pour être débattu, cité, critiqué. C\'est intentionnel.',
                'actions' => [
                    ['label' => 'Lire le cadre institutionnel', 'kind' => 'primary',  'route' => 'app_civitalisme_technical'],
                    ['label' => 'Contacter le projet',          'kind' => 'ghost',    'route' => 'app_contact'],
                ],
            ],
        ];
    }

    /**
     * Content for the institutional / technical page (cadre institutionnel).
     *
     * Audience: économistes, juristes, décideurs politiques, entrepreneurs et partenaires techniques.
     * Cette page est dense, structurée autour de la preuve documentaire (7 rapports),
     * de l\'architecture CDC, du cadre juridique européen et de la feuille de route.
     *
     * @return array<string, mixed>
     */
    public function technicalPage(): array
    {
        return [
            // -- 1. Hero institutionnel ----------------------------------------------
            'hero' => [
                'eyebrow' => 'Cadre institutionnel',
                'title' => 'Socle Vital Garanti européen : architecture, droit, gouvernance',
                'subtitle' => 'Un projet de Civitalisme, documenté, testable, européen.',
                'lead' => 'Le Socle Vital Garanti (SVG) européen est l’un des projets portés par le Civitalisme. Il vise à garantir, via le Compte de Dépenses Conditionnelles (CDC), l’accès aux besoins vitaux — logement, alimentation, énergie, transport, santé, éducation — sans déstabiliser l’euro, sans remplacer les retraites, sans concurrencer la sphère marchande. Cette page réunit la doctrine, l’architecture technique, le cadre juridique et la feuille de route à l’attention des institutions et des professionnels.',
                'highlights' => [
                    'Doctrine documentée en 7 rapports thématiques',
                    'Compatible AI Act, RGPD, DSP2, MiCA et DORA',
                    'Double circuit de dépenses CDC / euro classique, retraites et revenus du travail préservés',
                ],
            ],

            // -- 2. Synthèse exécutive + 7 rapports ----------------------------------
            'executive' => [
                'eyebrow' => 'Synthèse exécutive',
                'title' => 'Ce que dit la doctrine, en quelques lignes',
                'lead' => 'Le Civitalisme propose de distinguer la sphère vitale (besoins de dignité) de la sphère de progression (marché, épargne, investissement). Le SVG européen instaure un filet de sécurité ciblé, monétisé par le CDC, un euro numérique permissionné dédié aux dépenses vitales. L’euro classique conserve l’intégralité de son périmètre actuel.',
                'pillars' => [
                    ['title' => 'Ciblage des besoins vitaux', 'body' => 'Le SVG ne verse pas un revenu universel indifférencié : il finance un panier vital (logement, alimentation, énergie, transport, santé, éducation) encadré par la norme démocratique.'],
                    ['title' => 'Outil monétaire dédié', 'body' => 'Le CDC est une monnaie numérique européenne, permissionnée, émise par la BCE sous mandat, convertible vers l’euro uniquement par les commerçants agréés au panier vital.'],
                    ['title' => 'Compatibilité démocratique', 'body' => 'Le cadre est progressif, réversible, audité et gouverné par les institutions démocratiques existantes (Parlement européen, Conseil, Cour des comptes, BCE).'],
                ],
                'reports' => [
                    [
                        'index' => '01',
                        'theme' => 'Philosophie',
                        'title' => 'IA, travail et dignité',
                        'body' => 'Pourquoi la dissociation entre production de richesse et revenus du travail impose une réponse institutionnelle nouvelle.',
                        'href' => '/documents/SVG-Partie-Philosophique.pdf',
                    ],
                    [
                        'index' => '02',
                        'theme' => 'Économie',
                        'title' => 'Simulation et financement',
                        'body' => 'Calibration du panier vital, impact macroéconomique, incitation au travail préservée, soutenabilité budgétaire.',
                        'href' => '/documents/SVG-Partie-Economie.pdf',
                    ],
                    [
                        'index' => '03',
                        'theme' => 'Technique',
                        'title' => 'Plateforme CDC',
                        'body' => 'Architecture de paiement permissionnée, séparation des plans de données, circuit conditionnel euro → CDC → prestataire agréé.',
                        'href' => '/documents/SVG-Partie-Technique.pdf',
                    ],
                    [
                        'index' => '04',
                        'theme' => 'Politique',
                        'title' => 'Gouvernance et institutions',
                        'body' => 'Rôles respectifs du Parlement européen, du Conseil, de la BCE, des États membres et des autorités de supervision.',
                        'href' => '/documents/SVG-Partie-Politique.pdf',
                    ],
                    [
                        'index' => '05',
                        'theme' => 'Juridique',
                        'title' => 'Base légale et conformité',
                        'body' => 'Articulation avec AI Act, RGPD, DSP2, MiCA, DORA ; traités européens ; compétences partagées État/UE.',
                        'href' => '/documents/SVG-Partie-Juridique.pdf',
                    ],
                    [
                        'index' => '06',
                        'theme' => 'Sociologie',
                        'title' => 'Impact social et dignité',
                        'body' => 'Effets attendus sur la pauvreté, la précarité, la cohésion sociale et la perception du travail.',
                        'href' => '/documents/SVG-Parite-Sociologie.pdf',
                    ],
                    [
                        'index' => '07',
                        'theme' => 'Entrepreneuriat',
                        'title' => 'PME, reconversion, impact sectoriel',
                        'body' => 'Conséquences pour les commerçants agréés, les PME, les secteurs en transition et les entrepreneurs.',
                        'href' => '/documents/SVG-Partie-Entrepreneuriale.pdf',
                    ],
                    [
                        'index' => '08',
                        'theme' => 'Dialogue social',
                        'title' => 'SVG et partenaires sociaux',
                        'body' => 'Articulation avec les conventions collectives, le SMIC et le chômage contributif. Le SVG comme plancher inconditionnel, pas comme substitut au salaire.',
                        'href' => '/documents/SVG-Partie-Syndicale.pdf',
                    ],
                ],
            ],

            // -- 3. Doctrine : double circuit monétaire ------------------------------
            'doctrine' => [
                'eyebrow' => 'Doctrine',
                'title' => 'Un double circuit de dépenses, pas un remplacement',
                'lead' => 'Le Civitalisme distingue deux sphères monétaires complémentaires. L’euro classique continue de fonctionner exactement comme aujourd’hui pour le marché général, l’épargne, l’investissement et la liberté économique. Le CDC est un instrument social ciblé, strictement dédié aux dépenses du panier vital auprès de commerçants agréés. Cette distinction reprend l’ancienne séparation aristotélicienne entre l’économie domestique (besoins vitaux) et la chrématistique (échange marchand).',
                'table' => [
                    'columns' => [
                        ['key' => 'scope', 'label' => 'Périmètre'],
                        ['key' => 'role', 'label' => 'Rôle monétaire'],
                        ['key' => 'limits', 'label' => 'Garde-fous'],
                    ],
                    'rows' => [
                        [
                            'instrument' => 'Euro classique',
                            'scope' => 'Marché général : épargne, investissement, luxe, entrepreneuriat, salaires.',
                            'role' => 'Monnaie de la sphère de progression, inchangée.',
                            'limits' => 'Régulation actuelle (BCE, DORA, MiCA, CRR/CRD) maintenue telle quelle.',
                        ],
                        [
                            'instrument' => 'CDC',
                            'scope' => 'Panier vital : logement, alimentation, énergie, transport, santé, éducation (défini par la Charte SVG).',
                            'role' => 'Compte de Dépenses Conditionnées, ciblé, non spéculatif, non thésaurisable — pas une monnaie.',
                            'limits' => 'Permissionné, audité, utilisable uniquement chez des prestataires agréés Charte SVG, volume encadré par la BCE.',
                        ],
                    ],
                ],
            ],

            // -- 4. Architecture technique (8 couches + cycle de vie CDC) ----------
            'architecture' => [
                'eyebrow' => 'Architecture',
                'title' => 'Plateforme CDC — 8 couches, permissionnée, auditée',
                'lead' => 'La plateforme CDC s’appuie sur une infrastructure de paiement permissionnée (non publique), opérée sous la responsabilité de la BCE et des autorités de supervision. Chaque couche est conçue pour être auditable, interopérable et conforme aux exigences DORA et RGPD.',
                'layers' => [
                    ['index' => '01', 'title' => 'Identité souveraine', 'body' => 'Wallet européen (EUDI) rattaché à l’état civil, preuve de résidence et de situation familiale (hors données sensibles).'],
                    ['index' => '02', 'title' => 'Registre permissionné', 'body' => 'Ledger distribué contrôlé par la BCE et les banques centrales nationales ; pas de minage, pas de spéculation, pas de frais variables.'],
                    ['index' => '03', 'title' => 'Émission monétaire', 'body' => 'Crédit CDC mensuel selon la formule SVG validée par le Parlement européen ; destruction en miroir lors du paiement au commerçant agréé.'],
                    ['index' => '04', 'title' => 'Droits et éligibilité', 'body' => 'Calcul individualisé par foyer : LW₀ de base, équivalent foyer, zone géographique, indexation HICP, revenus et pensions déduits.'],
                    ['index' => '05', 'title' => 'Panier vital', 'body' => 'Catalogue des biens et services éligibles, maintenu par les institutions démocratiques ; révisable par vote parlementaire.'],
                    ['index' => '06', 'title' => 'Prestataires Charte SVG', 'body' => 'Enregistrement, audit et agrément des prestataires du panier vital (loyers, énergie, santé, alimentation, transport) selon les critères de la Charte SVG ; règlement en euro classique par la BCE.'],
                    ['index' => '07', 'title' => 'Supervision et audit', 'body' => 'Traçabilité agrégée, contrôle par la Cour des comptes européenne et les autorités nationales ; conformité RGPD pseudonymisée.'],
                    ['index' => '08', 'title' => 'Interfaces publiques', 'body' => 'Applications mobiles citoyennes, portails commerçants, APIs institutionnelles, tableaux de bord ouverts en données agrégées.'],
                ],
                'lifecycle' => [
                    ['step' => '1', 'title' => 'Crédit CDC', 'body' => 'La BCE crédite chaque foyer éligible sur son CDC selon la formule SVG, le 1er du mois.'],
                    ['step' => '2', 'title' => 'Dépense', 'body' => 'Le citoyen dépense via CDC auprès d’un commerçant agréé pour un bien ou service du panier vital.'],
                    ['step' => '3', 'title' => 'Règlement', 'body' => 'Le prestataire agréé est réglé en euro classique par la BCE via sa banque — pas de conversion spéculative, circuit direct.'],
                    ['step' => '4', 'title' => 'Destruction', 'body' => 'Les crédits CDC sont annulés au règlement — pas d’accumulation, pas de thésaurisation, pas de marché secondaire.'],
                ],
            ],

            // -- 5. Formule SVG ------------------------------------------------------
            'formula' => [
                'eyebrow' => 'Formule SVG',
                'title' => 'Le calcul du Socle Vital Garanti',
                'lead' => 'Le montant mensuel SVG versé à un foyer (h) au mois (t) se calcule de manière transparente, avec une incitation au travail intégrée par construction.',
                'equation' => 'SVG(h,t) = max{ 0 ; LW₀ × Eq(h) × Zone(h) × IndexHICP(t) − [ Pension(h,t−1) + max(0 ; RevenuActif(h,t−1) − 300 × Actifs(h,t−1)) ] }',
                'parameters' => [
                    ['symbol' => 'LW₀', 'label' => 'Living wage de base', 'value' => '1 650 € / adulte / mois (calibrage initial)'],
                    ['symbol' => 'Eq(h)', 'label' => 'Équivalent foyer', 'value' => '1,0 adulte · 0,5 conjoint · 0,3 par enfant'],
                    ['symbol' => 'Zone(h)', 'label' => 'Coefficient géographique', 'value' => '1,15 zone chère · 1,00 médiane · 0,90 zone basse'],
                    ['symbol' => 'IndexHICP(t)', 'label' => 'Indexation prix européens', 'value' => '+2,1 % annuel (HICP Eurostat)'],
                    ['symbol' => 'Pension(h,t−1)', 'label' => 'Pensions déduites', 'value' => 'Retraites existantes intégralement préservées'],
                    ['symbol' => '300 €', 'label' => 'Franchise de travail', 'value' => 'Par actif du foyer, non décomptée du SVG'],
                ],
                'principles' => [
                    'Les retraites ne sont jamais remplacées : elles sont déduites du calcul, ce qui signifie que le SVG complète et ne substitue pas.',
                    'Le travail reste toujours incitatif : la franchise de 300 €/actif garantit qu’un euro gagné reste un euro en plus au foyer.',
                    'Aucune donnée personnelle ne circule : seuls des paramètres anonymisés (foyer, zone, indexation) sont utilisés.',
                ],
            ],


            // -- 5b. Calibration SVG par pays ----------------------------------------
            'calibration' => [
                'eyebrow' => 'Calibration',
                'title' => 'Montants SVG par pays — base Eurostat 2024',
                'lead' => 'Le SVG est calibré sur le seuil de pauvreté Eurostat (60 % du revenu médian national équivalisé) ajusté au coût réel du logement en zone urbaine dense. Le montant représente le complément maximal pour une personne seule sans revenus.',
                'note' => 'Le SVG ne peut pas être un montant unique européen : un montant calibré sur l\'Allemagne déstabiliserait le marché du travail en Pologne, et un montant calibré sur la Pologne serait insuffisant à Berlin. La calibration nationale est une condition architecturale du système.',
                'countries' => [
                    [
                        'flag' => 'fr',
                        'country' => 'France',
                        'threshold_annual' => '16 200 €',
                        'threshold_monthly' => '1 350 €',
                        'urban_adjustment' => '+10 % (Paris)',
                        'svg_max' => '1 350 – 1 490 €/mois',
                        'poverty_rate' => '15,9 %',
                    ],
                    [
                        'flag' => 'de',
                        'country' => 'Allemagne',
                        'threshold_annual' => '17 400 €',
                        'threshold_monthly' => '1 450 €',
                        'urban_adjustment' => '+8 % (Berlin/Munich)',
                        'svg_max' => '1 450 – 1 570 €/mois',
                        'poverty_rate' => '15,5 %',
                    ],
                    [
                        'flag' => 'es',
                        'country' => 'Espagne',
                        'threshold_annual' => '11 400 €',
                        'threshold_monthly' => '950 €',
                        'urban_adjustment' => '+12 % (Madrid/Barcelona)',
                        'svg_max' => '950 – 1 060 €/mois',
                        'poverty_rate' => '19,7 %',
                    ],
                    [
                        'flag' => 'it',
                        'country' => 'Italie',
                        'threshold_annual' => '11 400 €',
                        'threshold_monthly' => '950 €',
                        'urban_adjustment' => '+15 % (Milan/Rome)',
                        'svg_max' => '950 – 1 090 €/mois',
                        'poverty_rate' => '18,9 %',
                    ],
                    [
                        'flag' => 'pl',
                        'country' => 'Pologne',
                        'threshold_annual' => '7 800 €',
                        'threshold_monthly' => '650 €',
                        'urban_adjustment' => '+6 % (Varsovie)',
                        'svg_max' => '650 – 690 €/mois',
                        'poverty_rate' => '14,4 %',
                    ],
                ],
                'source' => 'Sources : Eurostat tessi014 (seuils 2024), Numbeo Cost of Living Index 2024. Les montants sont des plafonds pour une personne seule sans revenus — le SVG réel versé est la différence entre ce seuil et les revenus effectifs du foyer.',
            ],

            // -- 5c. CDC ≠ Euro numérique BCE ----------------------------------------
            'euroe_vs_cbdc' => [
                'eyebrow' => 'Clarification',
                'title' => 'CDC et euro numérique BCE : deux instruments distincts',
                'lead' => 'La BCE travaille depuis 2021 sur un "euro numérique" (CBDC). Le CDC n\'est pas ce projet. Les deux peuvent coexister — mais ils répondent à des objectifs radicalement différents : l\'un modernise les paiements ; l\'autre garantit les besoins vitaux.',
                'comparison' => [
                    [
                        'dimension' => 'Nature',
                        'cbdc' => 'Monnaie numérique de banque centrale : substitut au cash pour tous usages.',
                        'euroe' => 'Compte de Dépenses Conditionnées (CDC) : pas une monnaie, un droit conditionnel à dépenser dans le panier vital.',
                    ],
                    [
                        'dimension' => 'Objectif',
                        'cbdc' => 'Moderniser les paiements de détail, remplacer le cash numérique, assurer l\'accès de tous aux services de paiement.',
                        'euroe' => 'Garantir les besoins vitaux des Européens sous le seuil de pauvreté. Instrument de redistribution ciblée, pas de paiement général.',
                    ],
                    [
                        'dimension' => 'Alimentation',
                        'cbdc' => 'Conversion 1:1 de dépôts bancaires existants. La masse monétaire totale reste identique.',
                        'euroe' => 'Crédit social mensuel sous mandat démocratique, en contrepartie de consommation réelle dans le panier vital. Compensé par la TVA et l\'activité générées.',
                    ],
                    [
                        'dimension' => 'Utilisation',
                        'cbdc' => 'Universel : tous achats, toutes personnes, toutes géographies.',
                        'euroe' => 'Restreint : panier vital uniquement, prestataires Charte SVG uniquement, bénéficiaires éligibles uniquement.',
                    ],
                    [
                        'dimension' => 'Impact inflation',
                        'cbdc' => 'Neutre : substitution cash → numérique, pas d\'augmentation de la masse monétaire.',
                        'euroe' => 'Contrôlé : le circuit conditionnel empêche les fuites spéculatives ; chaque crédit CDC génère une contrepartie économique réelle (bien ou service vital).',
                    ],
                    [
                        'dimension' => 'Base légale',
                        'cbdc' => 'Art. 128 TFUE (exclusivité d\'émission BCE) + règlement en cours d\'adoption (2024–2025).',
                        'euroe' => 'Art. 20 TUE (coopération renforcée, 9 États minimum) + art. 127 TFUE (missions BCE) + art. 151 TFUE (cohésion sociale) + Charte sociale européenne.',
                    ],
                ],
                'synergy' => 'Le CDC peut s\'appuyer sur l\'infrastructure du projet CBDC de la BCE (TIPS, TARGET, wallet EUDI) sans être le même instrument. L\'euro numérique BCE est le rail technique ; le CDC est l\'usage social conditionnel qui y circule, sous gouvernance démocratique séparée.',
            ],

            // -- 5d. Chemin légal ---------------------------------------------------
            'legal_pathway' => [
                'eyebrow' => 'Chemin légal',
                'title' => 'Mettre en œuvre le SVG sans modifier les traités',
                'lead' => 'La modification des traités européens prend 8 à 12 ans (art. 48 TUE). Le Civitalisme propose un chemin réaliste en 3 étapes qui ne nécessite pas de révision des traités pour sa phase pilote.',
                'steps' => [
                    [
                        'phase' => 'Étape 1 — 2026–2028',
                        'title' => 'Initiative Citoyenne Européenne (ICE)',
                        'legal_base' => 'Art. 11 TUE',
                        'body' => '1 million de signatures dans 7 États membres oblige la Commission européenne à examiner la proposition. L\'ICE crée la légitimité démocratique nécessaire à tout processus législatif. Elle ne coûte rien institutionnellement et peut être lancée par des citoyens sans mandat électif.',
                        'feasibility' => 'Réaliste : plusieurs ICE ont atteint ce seuil (eau, bien-être animal, glyphosate). Le Civitalisme touche 93 millions de personnes directement concernées.',
                    ],
                    [
                        'phase' => 'Étape 2 — 2028–2031',
                        'title' => 'Coopération renforcée entre États volontaires',
                        'legal_base' => 'Art. 20 TUE + Art. 331 TFUE',
                        'body' => 'L\'article 20 TUE permet à un minimum de 9 États membres de mettre en place une coopération renforcée sans attendre l\'unanimité du Conseil. Un règlement SVG pilote est adopté entre États volontaires (zone euro et hors zone euro), mandatant la BCE pour l\'infrastructure CDC et définissant les critères Charte SVG. L\'article 331 TFUE garantit l\'adhésion ouverte aux États rejoignant ultérieurement le dispositif — y compris des États hors zone euro comme la République tchèque (participation au CDC en équivalent CZK indexé, conversion garantie par la BCE). Premiers États ciblés : France, Allemagne, Espagne, Italie, Portugal, Irlande, Finlande, Belgique, Luxembourg.',
                        'feasibility' => 'Réaliste sans unanimité : la coopération renforcée n\'est bloquée par aucun État membre non participant. La CJUE a validé ce mécanisme dans plusieurs domaines (brevet unitaire, taxe sur transactions financières). Le format pilote à 9 États réduit l\'exposition politique.',
                    ],
                    [
                        'phase' => 'Étape 3 — 2031+',
                        'title' => 'Européanisation et ancrage institutionnel',
                        'legal_base' => 'Art. 48 TUE (révision) ou Art. 352 TFUE (flexibilité)',
                        'body' => 'Si le pilote produit des données probantes (réduction de la pauvreté, impact inflation maîtrisé, coût net inférieur aux projections), deux voies s\'ouvrent : (a) une révision légère des traités pour inscrire le SVG comme compétence partagée (art. 48 TUE) ; ou (b) une généralisation par règlement ordinaire renouvelé sans modification des traités, en s\'appuyant sur l\'art. 352 TFUE pour les États encore non couverts par la coopération renforcée. La seconde option est plus rapide et évite le risque de blocage.',
                        'feasibility' => 'Long terme, mais la base empirique pilote change la nature du débat. L\'UE a déjà généralisé des coopérations renforcées (Parquet européen, brevet unitaire) sans révision des traités.',
                    ],
                ],
                'key_articles' => [
                    ['ref' => 'Art. 11 TUE', 'title' => 'Initiative Citoyenne Européenne', 'body' => '1 million de signatures, 7 États membres → obligation d\'examen par la Commission. Point de départ démocratique.'],
                    ['ref' => 'Art. 20 TUE', 'title' => 'Coopération renforcée', 'body' => 'Minimum 9 États membres volontaires, sans unanimité du Conseil. Mécanisme central du pilote SVG — validé par la CJUE pour le brevet unitaire et le Parquet européen.'],
                    ['ref' => 'Art. 331 TFUE', 'title' => 'Adhésion ouverte', 'body' => 'Tout État membre peut rejoindre la coopération renforcée à tout moment, y compris les États hors zone euro (participation avec équivalent monnaie nationale indexé, ex. : CZK tchèque).'],
                    ['ref' => 'Art. 127 TFUE', 'title' => 'Mandat BCE', 'body' => 'Stabilité des prix + soutien aux politiques économiques générales. Interprétation extensive validée par la CJUE (Pringle 2012, Gauweiler 2015).'],
                    ['ref' => 'Art. 151 TFUE', 'title' => 'Politique sociale', 'body' => 'Objectif de lutte contre l\'exclusion sociale — fondement de la cohésion sociale européenne.'],
                    ['ref' => 'Art. 352 TFUE', 'title' => 'Clause de flexibilité (fallback)', 'body' => 'Unanimité du Conseil + approbation PE → option de repli si la coopération renforcée rencontre des obstacles juridiques. Utilisable en phase 3.'],
                    ['ref' => 'Art. 48 TUE', 'title' => 'Révision des traités', 'body' => 'Procédure optionnelle de long terme. Nécessaire uniquement pour ancrer le SVG comme compétence permanente de l\'UE.'],
                ],
            ],


            // -- 5e. Charte SVG — conditions d'agrément des prestataires ------------
            'charte_svg' => [
                'eyebrow' => 'Charte SVG',
                'title' => 'Conditions d\'agrément des prestataires — ancrées dans le droit européen',
                'lead' => 'Pour accepter les paiements CDC, un prestataire doit respecter la Charte SVG — un référentiel de conditions défini par règlement européen, s\'appuyant sur des textes BCE et PE existants. La Charte est révisable par vote du Parlement européen.',
                'legal_anchor' => [
                    'title' => 'Textes fondateurs',
                    'items' => [
                        ['ref' => 'Charte des droits fondamentaux UE (Art. 34–36)', 'body' => 'Art. 34 : sécurité sociale et aide sociale. Art. 35 : droit à la santé. Art. 36 : accès aux services d\'intérêt économique général. Base constitutionnelle des 6 catégories vitales.'],
                        ['ref' => 'Pilier européen des droits sociaux (2017), Principe 14', 'body' => 'Tout Européen sans ressources suffisantes a droit à des prestations de revenu minimum adéquates. Proclamé par le PE, le Conseil et la Commission — fondement politique direct du SVG.'],
                        ['ref' => 'Directive 2014/92/UE (Comptes de paiement)', 'body' => 'Impose l\'accès universel à un compte de paiement de base dans l\'UE. Le CDC s\'inscrit dans cette logique d\'inclusion financière pilotée par le PE.'],
                        ['ref' => 'Directive 2019/944/UE (Marché intérieur de l\'électricité)', 'body' => 'Oblige les États membres à protéger les "clients vulnérables" et définit les critères d\'approvisionnement en énergie. Fondement de la catégorie Énergie de la Charte SVG.'],
                        ['ref' => 'Règlement (UE) 2022/1854 (Urgence énergie)', 'body' => 'Fixe des prix plafonds et des critères d\'accès à l\'énergie en période de crise. Modèle de restriction d\'usage conditionnel par catégorie.'],
                        ['ref' => 'BCE — Rapport sur l\'euro numérique (2023)', 'body' => 'La BCE identifie explicitement la possibilité de restrictions programmatiques sur l\'usage d\'un euro numérique (p. 43 : "programmabilité conditionnelle"). Le CDC applique ce principe au champ social.'],
                    ],
                ],
                'categories' => [
                    [
                        'icon' => 'home',
                        'label' => 'Logement',
                        'condition' => 'Bien ou service situé dans l\'UE, prestataire enregistré à la TVA dans un État membre participant, résidence principale du bénéficiaire uniquement.',
                        'examples' => 'Loyers, charges locatives, travaux d\'isolation, hébergement d\'urgence agréé.',
                    ],
                    [
                        'icon' => 'food',
                        'label' => 'Alimentation',
                        'condition' => 'Produits alimentaires non transformés et de première nécessité, vendus par des commerces de proximité ou GMS établis dans l\'UE. Exclusion : alcool, tabac, compléments alimentaires non médicaux.',
                        'examples' => 'Épiceries, marchés, supermarchés, livraisons alimentaires agréées.',
                    ],
                    [
                        'icon' => 'energy',
                        'label' => 'Énergie',
                        'condition' => 'Fournitures d\'électricité, gaz et chaleur pour résidence principale, chez des opérateurs réglementés conformes à la Directive 2019/944/UE. Plafond de consommation défini par décret.',
                        'examples' => 'Factures EDF, Engie, fournisseurs locaux agréés, recharge véhicule électrique résidentiel.',
                    ],
                    [
                        'icon' => 'transport',
                        'label' => 'Transport',
                        'condition' => 'Transport public (train, bus, métro, tram) et mobilités douces actives dans l\'UE. Exclusion : vols longue distance, VTC premium, location de voiture touristique.',
                        'examples' => 'Abonnements RATP, SNCF, DB, Renfe, vélos en libre-service, covoiturage court-trajet agréé.',
                    ],
                    [
                        'icon' => 'health',
                        'label' => 'Santé',
                        'condition' => 'Actes et produits remboursables par les systèmes de santé nationaux, ou reconnus par l\'EMA. Prestataires agréés par l\'autorité sanitaire nationale. Conformité au Règlement (UE) 2022/123 (espace européen des données de santé).',
                        'examples' => 'Consultations médicales, pharmacies, optique correctrice sur ordonnance, soins dentaires de base.',
                    ],
                    [
                        'icon' => 'education',
                        'label' => 'Éducation',
                        'condition' => 'Établissements reconnus par l\'État, matériels pédagogiques liés à la scolarité ou à la formation professionnelle certifiée (CPF équivalent). Exclusion : cours de loisir non certifiants.',
                        'examples' => 'Frais de scolarité, fournitures scolaires, formation certifiante, manuels numériques.',
                    ],
                ],
                'governance' => 'La Charte SVG est adoptée par règlement européen sur proposition de la Commission, après avis de la BCE. Elle est révisable annuellement par le Parlement européen. Chaque État participant publie la liste des prestataires agréés dans un registre public interopérable.',
            ],

            // -- 5f. Modélisation du coût — données empiriques ----------------------
            'cost_model' => [
                'eyebrow' => 'Coût réel',
                'title' => 'Ce que les expériences pilotes nous disent du coût net',
                'lead' => 'Deux expériences rigoureuses permettent de modéliser le coût net d\'un SVG européen : le pilote finlandais Kela (2017–2018) et l\'essai contrôlé randomisé GiveDirectly Kenya. Ensemble, ils documentent les effets sur l\'emploi, la consommation et le bien-être.',
                'pilots' => [
                    [
                        'name' => 'Pilote finlandais Kela',
                        'period' => '2017–2018',
                        'scale' => '2 000 chômeurs tirés au sort',
                        'amount' => '560 €/mois, inconditionnel, 24 mois',
                        'results' => [
                            'Bien-être et santé mentale significativement améliorés (score GHQ-12 +1,5 pts vs groupe témoin)',
                            'Taux d\'emploi légèrement supérieur au groupe témoin (+6 jours travaillés/an)',
                            'Aucune désincitation au travail mesurée',
                            'Confiance dans les institutions fortement renforcée',
                        ],
                        'cost_lesson' => 'Sur 24 mois, le coût brut par personne : 13 440 €. Économies réalisées en allocations chômage non versées + cotisations sociales générées : estimées à 4 200 € par personne. Coût net réel : ~9 240 € sur 2 ans, soit 385 €/mois.',
                        'source' => 'Kela Research (2020) — Olli Kangas et al., "The Basic Income Experiment 2017–2018 in Finland"',
                    ],
                    [
                        'name' => 'GiveDirectly Kenya RCT',
                        'period' => '2017–2022 (suivi en cours)',
                        'scale' => '~20 000 foyers, 295 villages',
                        'amount' => '22 $/mois (long-term arm), 10–12 ans',
                        'results' => [
                            'Consommation des ménages bénéficiaires : +40 % vs groupe témoin',
                            'Accumulation d\'actifs durables +37 % (bétail, équipement, immobilier)',
                            'Effets multiplicateurs locaux : chaque $ distribué génère ~2,5 $ d\'activité économique locale',
                            'Aucune augmentation de la consommation d\'alcool ou de tabac',
                            'Amélioration de la sécurité alimentaire et des scores de scolarisation des enfants',
                        ],
                        'cost_lesson' => 'L\'effet multiplicateur (×2,5) signifie qu\'une grande partie du coût brut est récupérée par la TVA, l\'activité et les emplois créés localement. Dans un contexte européen avec TVA à 20 %, un transfert de 600 €/mois génère environ 120 € de TVA immédiate — soit 20 % du coût brut récupéré au premier tour.',
                        'source' => 'Egger et al. (2022), "General equilibrium effects of cash transfers", Quarterly Journal of Economics',
                    ],
                ],
                'european_projection' => [
                    'title' => 'Projection SVG européen — ordre de grandeur',
                    'assumptions' => [
                        '93 millions d\'Européens sous le seuil de pauvreté Eurostat (60 % revenu médian)',
                        'SVG moyen : 550 €/mois (pondéré par le LW₀ national)',
                        'Taux d\'éligibilité effective après déduction pensions/revenus : 60 % des personnes concernées',
                        'Circuit CDC → 100 % dépensé dans le panier vital → TVA moyenne UE 20 %',
                    ],
                    'calculation' => [
                        'Bénéficiaires effectifs : ~56 millions',
                        'Coût brut annuel : 56M × 550 € × 12 = 369 Md€',
                        'Retour TVA immédiat (20 %) : −74 Md€',
                        'Économies aides sociales nationales substituées (partielle, ~15 %) : −55 Md€',
                        'Effet multiplicateur emploi/activité (×1,5 conservateur) : −60 Md€ équivalent recettes',
                        'Coût net estimé : ~180 Md€/an ≈ 1,2 % du PIB UE',
                    ],
                    'reference' => 'À titre de comparaison : la PAC représente 55 Md€/an (0,36 % PIB UE) ; le plan de relance NextGenerationEU = 750 Md€ sur 7 ans (107 Md€/an).',
                ],
                // TODO:PROD — supprimer le bloc 'academic_path' avant mise en production
                'academic_path' => [
                    'title' => 'Validation académique en cours',
                    'contacts' => [
                        ['name' => 'Gabriel Zucman', 'role' => 'Économiste, spécialiste des inégalités et de la fiscalité internationale (UC Berkeley / Paris School of Economics). Contact prioritaire pour valider la modélisation du coût net et l\'argumentation anti-évasion fiscale.'],
                        ['name' => 'Mathias Baccino', 'role' => 'Contact académique pour la crédibilisation de l\'amorce du projet et l\'ancrage dans le débat européen.'],
                    ],
                    'approach' => 'Soumettre la note de doctrine + le modèle de coût pour peer review informel, avant publication d\'un livre blanc co-signé.',
                ],
            ],

                        // -- 6. Dialogue social et partenaires syndicaux -------------------------
            'syndical' => [
                'eyebrow' => 'Dialogue social',
                'title' => 'Le SVG et les partenaires sociaux',
                'lead' => 'Le principal point de vigilance syndical est direct : si l\'État garantit un revenu de base, qu\'est-ce qui empêche les employeurs de baisser les salaires ? La réponse est structurelle.',
                'thesis' => 'Le SVG n\'est pas un concurrent du salaire. C\'est un plancher inconditionnel qui libère les salariés de la peur du licenciement et permet aux syndicats de recentrer leur action sur la qualité du travail plutôt que sur la survie matérielle.',
                'diagnosis' => [
                    'Eurostat (2024) : 8,5 % des travailleurs européens sont en situation de pauvreté au travail malgré un emploi.',
                    'La peur du licenciement structure le rapport de force dans l\'entreprise : un salarié qui sait qu\'il tombera dans la précarité accepte des conditions dégradées.',
                    'L\'IA accélère la fragmentation des emplois, rendant les transitions plus fréquentes et les compétences plus rapidement obsolètes.',
                    '40 % des personnes éligibles aux aides actuelles n\'y ont pas recours (non-recours massif). Le SVG est automatique, non stigmatisant, calibré sur M-1.',
                ],
                'guardrails' => [
                    [
                        'title' => 'SMIC et minima conventionnels inchangés',
                        'body' => 'Le texte fondateur affirme explicitement que le SVG ne se substitue pas aux obligations salariales légales. Aucun employeur ne peut invoquer le SVG pour réduire un salaire sous le SMIC ou le minimum de branche.',
                    ],
                    [
                        'title' => 'Le SVG est un droit du citoyen, pas une subvention à l\'employeur',
                        'body' => 'Il est versé sur la base de la situation personnelle du citoyen, indépendamment de son contrat de travail. L\'employeur n\'a aucune visibilité ni contrôle sur le montant reçu.',
                    ],
                    [
                        'title' => 'La franchise de travail protège l\'incitation',
                        'body' => 'Les 300 premiers euros de revenu d\'activité par actif ne réduisent pas le SVG. Toute baisse de salaire réduit donc les ressources totales du salarié — il a un intérêt économique à la refuser.',
                    ],
                    [
                        'title' => 'Conventions collectives pleinement maintenues',
                        'body' => 'Les conventions collectives continuent de fixer les grilles salariales, les conditions de travail et les droits conventionnels. Le SVG ajoute une couche de sécurité en dessous ; il ne les remplace pas.',
                    ],
                    [
                        'title' => 'Réduction des charges conditionnelle et progressive',
                        'body' => 'Aucune baisse de cotisation n\'intervient avant que les effets du SVG aient été mesurés et validés. La logique : d\'abord prouver que le socle vital fonctionne, ensuite ajuster le financement. Jamais l\'inverse.',
                    ],
                ],
                'roleShift' => [
                    'eyebrow' => 'Nouveau rôle syndical',
                    'lead' => 'Le Civitalisme ne marginalise pas les syndicats — il transforme leur mission. Avec le SVG comme filet de sécurité, les syndicats peuvent adopter une posture offensive plutôt que défensive.',
                    'shifts' => [
                        [
                            'before' => 'Résister aux licenciements liés à l\'IA',
                            'after' => 'Négocier les conditions de la transition IA : calendrier, formation, maintien du revenu pendant la reconversion.',
                        ],
                        [
                            'before' => 'Se battre pour maintenir des emplois condamnés',
                            'after' => 'Négocier la création de nouveaux postes : superviseurs IA, contrôleurs qualité, formateurs, coordinateurs de transition.',
                        ],
                        [
                            'before' => 'Défendre les minima salariaux',
                            'after' => 'Négocier le partage du dividende technologique : si l\'IA augmente la productivité de 30 %, quelle part revient aux salariés ?',
                        ],
                        [
                            'before' => 'Gérer les plans sociaux',
                            'after' => 'Co-construire les parcours de reconversion avec l\'entreprise et les organismes de formation.',
                        ],
                    ],
                ],
                'positions' => [
                    [
                        'org' => 'CES — Confédération européenne des syndicats',
                        'stance' => 'Favorable sous conditions',
                        'argument' => 'Le SVG reprend les objectifs du Socle européen des droits sociaux (principe 14). La franchise de travail préserve l\'incitation. Le pouvoir de négociation des salariés est renforcé.',
                    ],
                    [
                        'org' => 'CGT (France)',
                        'stance' => 'Méfiante puis intéressée',
                        'argument' => 'Insister sur le renforcement du rapport de force salarial, le maintien des conventions collectives et le refus de toute substitution salaire/SVG.',
                    ],
                    [
                        'org' => 'CFDT (France)',
                        'stance' => 'Plutôt favorable',
                        'argument' => 'Alignement avec la culture du compromis et de la négociation. Le SVG comme outil d\'accompagnement des transitions.',
                    ],
                    [
                        'org' => 'DGB (Allemagne)',
                        'stance' => 'Prudent',
                        'argument' => 'Présenter le SVG comme compatible avec le modèle de cogestion allemand. Insister sur la négociation branche par branche.',
                    ],
                    [
                        'org' => 'CCOO / UGT (Espagne)',
                        'stance' => 'Favorable',
                        'argument' => 'Forte exposition à la précarité et au travail temporaire. Le SVG répond à un besoin urgent.',
                    ],
                    [
                        'org' => 'Syndicats nordiques',
                        'stance' => 'Intéressés mais vigilants',
                        'argument' => 'Ils ont déjà des filets de sécurité forts. Le SVG doit être présenté comme une modernisation, pas un remplacement.',
                    ],
                ],
                'recommendations' => [
                    'Associer les syndicats dès la phase de consultation du livre blanc — co-construire, pas imposer.',
                    'Garantir dans le texte fondateur que le SVG ne se substitue ni aux salaires conventionnels ni aux droits contributifs acquis.',
                    'Prévoir un comité paritaire de suivi du SVG incluant les partenaires sociaux, avec droit de véto sur les modifications affectant le marché du travail.',
                    'Créer un « dividende de transition IA » négocié au niveau de l\'entreprise : quand l\'IA augmente la productivité, une part des gains est partagée avec les salariés.',
                    'Former les représentants syndicaux à l\'IA pour qu\'ils négocient en connaissance de cause les impacts technologiques dans leurs secteurs.',
                ],
            ],

            // -- 7. Cadre juridique européen -----------------------------------------
            'legal' => [
                'eyebrow' => 'Cadre juridique',
                'title' => 'Un dispositif conforme au droit européen',
                'lead' => 'Le SVG et le CDC s’inscrivent dans les compétences partagées entre l’Union et les États membres (art. 4 TFUE) et dans le mandat monétaire de la BCE (art. 127 TFUE). Chaque brique a été vérifiée au regard des règlements européens en vigueur.',
                'matrix' => [
                    ['ref' => 'AI Act', 'scope' => 'IA à haut risque', 'status' => 'conforme', 'body' => 'Aucun système d’IA à haut risque dans le calcul du SVG ; les règles sont déterministes et publiées.'],
                    ['ref' => 'RGPD', 'scope' => 'Données personnelles', 'status' => 'conforme', 'body' => 'Pseudonymisation systématique, minimisation des données, droit d’accès et de rectification intégrés.'],
                    ['ref' => 'DSP2', 'scope' => 'Services de paiement', 'status' => 'conforme', 'body' => 'Le CDC est géré par la BCE ; les interfaces de paiement respectent l’authentification forte (SCA) selon la DSP2.'],
                    ['ref' => 'MiCA', 'scope' => 'Crypto-actifs', 'status' => 'hors champ', 'body' => 'Le CDC n’est pas un crypto-actif : c’est un compte de dépenses conditionnel géré par la BCE, hors périmètre MiCA. Aucun token, aucune blockchain publique.'],
                    ['ref' => 'DORA', 'scope' => 'Résilience opérationnelle', 'status' => 'conforme', 'body' => 'Plan de continuité, tests d’intrusion, audits et reporting incidents alignés sur les exigences DORA.'],
                    ['ref' => 'Traités (TFUE)', 'scope' => 'Compétences', 'status' => 'conforme', 'body' => 'Compétence partagée UE/États (art. 4) ; mandat monétaire BCE (art. 127) ; cohésion sociale (art. 151).'],
                ],
                'risks' => [
                    ['level' => 'faible', 'title' => 'Inflation ciblée', 'body' => 'Le CDC ne circule que vers le panier vital ; le volume est plafonné par la formule SVG et audité par la BCE.'],
                    ['level' => 'moyen', 'title' => 'Désincitation au travail', 'body' => 'Atténuée par la franchise de 300 €/actif et le caractère non fongible du CDC. Confirmé par le pilote finlandais Kela 2017–2018.'],
                    ['level' => 'moyen', 'title' => 'Fraude commerçants', 'body' => 'Contrôlée par l’agrément, l’audit RGPD et la traçabilité à maille agrégée.'],
                    ['level' => 'élevé', 'title' => 'Acceptabilité politique', 'body' => 'Nécessite une pédagogie forte ; le phasage progressif (cf. roadmap) vise à documenter avant de généraliser.'],
                ],
            ],

            // -- 7. Roadmap ----------------------------------------------------------
            'roadmap' => [
                'eyebrow' => 'Feuille de route',
                'title' => 'Quatre phases, documentées, réversibles',
                'lead' => 'Chaque phase est évaluée, amendable et réversible. Le Civitalisme ne propose pas un big-bang : le SVG se construit par expérimentation contrôlée et montée en charge démocratique.',
                'phases' => [
                    [
                        'year' => '2026',
                        'phase' => 'Phase 1',
                        'title' => 'Pilote documentaire',
                        'body' => 'Publication de la doctrine complète, ateliers institutionnels, simulateur public, cadrage juridique avec la Commission européenne et deux États membres volontaires.',
                        'kpi' => 'Note conceptuelle + 7 rapports + simulateur ouvert',
                    ],
                    [
                        'year' => '2027',
                        'phase' => 'Phase 2',
                        'title' => 'SVG restreint',
                        'body' => 'Expérimentation sur deux territoires européens (une région urbaine, une région rurale), panier vital limité au logement et à l’alimentation, 20 000 foyers éligibles.',
                        'kpi' => '2 pilotes territoriaux · 20 000 foyers · 24 mois',
                    ],
                    [
                        'year' => '2028',
                        'phase' => 'Phase 3',
                        'title' => 'Déploiement CDC',
                        'body' => 'Émission de le CDC par la BCE dans le cadre du pilote, déploiement du registre permissionné, agrément des premiers commerçants, interopérabilité EUDI.',
                        'kpi' => 'CDC opérationnel · 500 prestataires Charte SVG agréés · audit DORA',
                    ],
                    [
                        'year' => '2029',
                        'phase' => 'Phase 4',
                        'title' => 'Européanisation',
                        'body' => 'Généralisation progressive à l’ensemble des États volontaires, panier vital étendu aux 6 postes, gouvernance démocratique pleine (Parlement européen, Cour des comptes).',
                        'kpi' => 'Cadre européen voté · panier vital complet · gouvernance stabilisée',
                    ],
                ],
            ],

            // -- 8. Téléchargements --------------------------------------------------
            'downloads' => [
                'eyebrow' => 'Téléchargements',
                'title' => 'Les 8 rapports thématiques',
                'lead' => 'L’intégralité de la doctrine est publiée en accès libre, au format PDF. Chaque rapport peut être cité, annoté, critiqué et amendé : c’est un document de travail ouvert.',
                'items' => [
                    ['title' => 'Philosophie — IA, travail et dignité', 'href' => '/documents/SVG-Partie-Philosophique.pdf', 'size' => 'PDF'],
                    ['title' => 'Économie — Simulation et financement', 'href' => '/documents/SVG-Partie-Economie.pdf', 'size' => 'PDF'],
                    ['title' => 'Technique — Plateforme CDC', 'href' => '/documents/SVG-Partie-Technique.pdf', 'size' => 'PDF'],
                    ['title' => 'Politique — Gouvernance institutionnelle', 'href' => '/documents/SVG-Partie-Politique.pdf', 'size' => 'PDF'],
                    ['title' => 'Juridique — Base légale et conformité', 'href' => '/documents/SVG-Partie-Juridique.pdf', 'size' => 'PDF'],
                    ['title' => 'Sociologie — Impact social', 'href' => '/documents/SVG-Parite-Sociologie.pdf', 'size' => 'PDF'],
                    ['title' => 'Entrepreneuriat — PME et reconversion', 'href' => '/documents/SVG-Partie-Entrepreneuriale.pdf', 'size' => 'PDF'],
                    ['title' => 'Dialogue social — SVG et partenaires syndicaux', 'href' => '/documents/SVG-Partie-Syndicale.pdf', 'size' => 'PDF'],
                ],
            ],

            // -- 9. Call to action institutionnel ------------------------------------
            'cta' => [
                'eyebrow' => 'Contact institutionnel',
                'title' => 'Échanger avec l’équipe Civitalisme',
                'body' => 'Vous êtes parlementaire, chercheur, juriste, dirigeant d’administration ou d’entreprise : le Civitalisme est ouvert aux auditions, aux ateliers et aux relectures critiques. Le formulaire de contact sera disponible prochainement ; entretemps, le lien ci-dessous permet de commencer un échange.',
            ],
        ];
    }
    /**
     * Content for the Vita page (/civitalisme/vita).
     * @return array<string, mixed>
     */
    public function vitaPage(): array
    {
        return [
            // -- Hero ---------------------------------------------------------------
            'hero' => [
                'eyebrow' => 'Civitalisme — Projet Vita',
                'title' => 'Vita : réhabiliter le vide, loger l\'invisible',
                'subtitle' => 'De l\'abandon à la dignité — par la réhabilitation écologique',
                'lead' => 'L\'Europe compte 28 millions de logements vacants. Et 700 000 sans-abris. Vita propose une réponse concrète : transformer les bâtiments abandonnés en logements écologiques accessibles aux personnes sans domicile. Un projet porté par le Civitalisme, indépendant du SVG.',
            ],

            // -- Chiffres clés ------------------------------------------------------
            'stats' => [
                'eyebrow' => 'La réalité en chiffres',
                'title' => 'Deux crises qui s\'ignorent',
                'lead' => 'En Europe, la crise du logement et l\'abandon urbain coexistent sans jamais se répondre. Vita fait le lien.',
                'figures' => [
                    [
                        'number' => '700 000',
                        'label' => 'sans-abris en Europe',
                        'source' => 'FEANTSA 2023',
                        'trend' => '+70% en 10 ans',
                        'color' => 'alert',
                    ],
                    [
                        'number' => '28M',
                        'label' => 'logements vacants dans l\'UE',
                        'source' => 'Eurostat 2022',
                        'trend' => '11% du parc immobilier européen',
                        'color' => 'neutral',
                    ],
                    [
                        'number' => '−40%',
                        'label' => 'de coût vs construction neuve',
                        'source' => 'ADEME / CEREMA 2023',
                        'trend' => 'pour une réhabilitation passive',
                        'color' => 'positive',
                    ],
                    [
                        'number' => '330 000',
                        'label' => 'logements vacants ≥ 2 ans en zones tendues (France)',
                        'source' => 'INSEE 2023',
                        'trend' => 'disponibles à réhabilitation prioritaire',
                        'color' => 'neutral',
                    ],
                ],
            ],

            // -- Concept ------------------------------------------------------------
            'concept' => [
                'eyebrow' => 'Le concept',
                'title' => 'Réhabiliter, pas construire',
                'lead' => 'Vita ne construit pas. Vita transforme. Des bâtiments abandonnés — entrepôts, bureaux, immeubles dégradés — sont réhabilités selon des standards écologiques stricts pour devenir des logements dignes et abordables.',
                'pillars' => [
                    [
                        'icon' => 'building',
                        'title' => 'Bâtiments vacants réquisitionnés ou acquis',
                        'body' => 'Identification des bâtiments vacants depuis plus de 2 ans dans les zones tendues, via les registres fiscaux (taxe sur logements vacants) et les collectivités. Partenariats avec communes et propriétaires institutionnels.',
                    ],
                    [
                        'icon' => 'eco',
                        'title' => 'Réhabilitation passive et biosourcée',
                        'body' => 'Isolation renforcée (laine de bois, chanvre, ouate de cellulose), ventilation double-flux, énergie solaire en autoconsommation. Objectif : bâtiment à énergie positive (BEPOS) ou label BBC Rénovation.',
                    ],
                    [
                        'icon' => 'community',
                        'title' => 'Logements individuels + espaces communs',
                        'body' => 'Chaque unité Vita comprend un logement individuel autonome (18–35 m²) et des espaces partagés : cuisine collective, atelier, espace numérique, jardin. Pas un dortoir : une communauté choisie.',
                    ],
                    [
                        'icon' => 'support',
                        'title' => 'Accompagnement social intégré',
                        'body' => 'Chaque site Vita est coopilé avec une association d\'insertion agréée. Accompagnement individuel : accès aux droits, formation, emploi, santé mentale. La durée de séjour est sans limite arbitraire.',
                    ],
                ],
            ],

            // -- Process ------------------------------------------------------------
            'process' => [
                'eyebrow' => 'Comment ça marche',
                'title' => 'De l\'abandon au foyer en 18 mois',
                'steps' => [
                    [
                        'number' => '1',
                        'title' => 'Identification du site',
                        'body' => 'Cartographie des bâtiments vacants éligibles avec les collectivités. Diagnostic technique, juridique et social du site.',
                        'duration' => '0–3 mois',
                    ],
                    [
                        'number' => '2',
                        'title' => 'Montage juridique et financier',
                        'body' => 'Convention d\'occupation, bail emphytéotique ou acquisition. Montage financier : subventions ANAH, FEDER, Action Logement, financement participatif solidaire.',
                        'duration' => '3–6 mois',
                    ],
                    [
                        'number' => '3',
                        'title' => 'Réhabilitation écologique',
                        'body' => 'Travaux réalisés en partie par des chantiers d\'insertion (formation en rénovation énergétique). Matériaux biosourcés, circuits courts régionaux.',
                        'duration' => '6–15 mois',
                    ],
                    [
                        'number' => '4',
                        'title' => 'Attribution et accueil',
                        'body' => 'Attribution via liste d\'attente SIAO (Service Intégré d\'Accueil et d\'Orientation), priorité aux personnes à la rue depuis plus de 6 mois. Zéro condition de revenus.',
                        'duration' => '15–18 mois',
                    ],
                    [
                        'number' => '5',
                        'title' => 'Vie dans le Vita',
                        'body' => 'Loyer symbolique (50–150 €/mois) ou nul selon situation, couvert si possible par les APL ou le SVG futur. Accompagnement continu. Sortie vers logement autonome préparée avec le résident.',
                        'duration' => 'Variable',
                    ],
                ],
            ],

            // -- Modèle économique --------------------------------------------------
            'economics' => [
                'eyebrow' => 'Modèle économique',
                'title' => 'Un coût net inférieur à l\'hébergement d\'urgence',
                'lead' => 'L\'hébergement d\'urgence classique coûte 35 à 80 €/nuit par personne (DGCS 2023). Vita vise 12 à 18 €/nuit — avec un logement digne et un accompagnement structurant.',
                'comparison' => [
                    [
                        'type' => 'Hébergement d\'urgence classique',
                        'cost_night' => '35–80 €',
                        'quality' => 'Temporaire, collectif, sans accompagnement structurant',
                        'outcome' => 'Sortie vers la rue à l\'extinction du dispositif',
                        'eco_impact' => 'Élevé (bâtiments non rénovés)',
                    ],
                    [
                        'type' => 'Logement Vita réhabilité',
                        'cost_night' => '12–18 €',
                        'quality' => 'Logement individuel, écologique, accompagnement intégré',
                        'outcome' => 'Sortie vers logement autonome dans 65 % des cas (réf. Housing First EU)',
                        'eco_impact' => 'Positif (BEPOS, bilan carbone négatif sur 20 ans)',
                    ],
                ],
                'financing' => [
                    ['source' => 'ANAH (Agence Nationale de l\'Habitat)', 'rate' => 'Jusqu\'à 50 % des travaux', 'type' => 'Subvention'],
                    ['source' => 'FEDER / FSUE', 'rate' => 'Cofinancement UE zones éligibles', 'type' => 'Fonds européens'],
                    ['source' => 'Action Logement', 'rate' => 'Prêts à taux 0 pour insertion', 'type' => 'Prêt'],
                    ['source' => 'Financement participatif solidaire', 'rate' => '5–20 % du projet', 'type' => 'Crowdfunding'],
                    ['source' => 'Collectivités (commune / métropole)', 'rate' => 'Mise à disposition bâtiment ou foncier', 'type' => 'Nature'],
                ],
            ],

            // -- Modèles de référence -----------------------------------------------
            'references' => [
                'eyebrow' => 'Expériences de référence',
                'title' => 'Ce qui a déjà fonctionné',
                'lead' => 'Vita s\'appuie sur des modèles éprouvés en Europe et dans le monde.',
                'cases' => [
                    [
                        'name' => 'Housing First (Finlande)',
                        'country' => 'Finlande',
                        'period' => 'Depuis 2008',
                        'result' => '−35 % de sans-abrisme en 15 ans. Le seul pays d\'Europe à avoir réduit durablement le nombre de sans-abris. Coût net inférieur à l\'urgence grâce à la réduction des passages aux urgences et en prison.',
                        'link' => 'Y-Foundation Finland',
                    ],
                    [
                        'name' => 'Récup\'Toit',
                        'country' => 'France',
                        'period' => 'Bordeaux, depuis 2018',
                        'result' => 'Réhabilitation de bureaux vacants en micro-logements. 120 personnes relogées en 5 ans, coût de réhabilitation moyen : 28 000 € par unité (vs 80 000 € pour du neuf).',
                        'link' => 'recuptoit.fr',
                    ],
                    [
                        'name' => 'Community Land Trust Bruxelles',
                        'country' => 'Belgique',
                        'period' => 'Depuis 2012',
                        'result' => '200 logements en propriété collective dissociée — le foncier reste collectif, seul le logement est cédé. Loyers 30 à 40 % sous le marché à perpétuité.',
                        'link' => 'cltb.be',
                    ],
                    [
                        'name' => 'Tiny House Movement (Allemagne)',
                        'country' => 'Allemagne',
                        'period' => 'Berlin, Cologne, depuis 2015',
                        'result' => 'Villages de micro-habitats écologiques sur friches urbaines pour personnes sans domicile. Empreinte carbone 80 % inférieure à un appartement standard.',
                        'link' => 'tinyhouse-university.com',
                        [
                        'name' => 'PERI 3D Construction — Mehrfamilienhaus Wallenhausen',
                        'country' => 'Allemagne',
                        'period' => 'Wallenhausen, Bavière 2021',
                        'result' => '1er immeuble collectif imprimé en 3D d\'Allemagne : 380 m², 3 étages, 5 appartements, structure imprimée en 72 h par l\'imprimante COBOD BOD2. Murs triple-coque avec isolation en cavité, mobilier 3D intégré. Coût de construction 15–20 % inférieur au conventionnel ; loyers alignés sur le bas de la fourchette locale. Tous les logements loués en moins d\'un an. Perspective Vita : une maison 3D peut être construite sur mesure pour des ménages fragiles, avec espaces modulables et accessibilité intégrée d\'emblée.',
                        'link' => 'peri3dconstruction.com/en/mehrfamilienhaus-wallenhausen',
                    ],
                ],
                    [
                        'name' => 'PERI 3D Construction — Mehrfamilienhaus Wallenhausen',
                        'country' => 'Allemagne',
                        'period' => 'Wallenhausen, Bavière 2021',
                        'result' => '1er immeuble collectif imprimé en 3D d\'Allemagne : 380 m², 3 étages, 5 appartements, structure imprimée en 72 h par l\'imprimante COBOD BOD2. Murs triple-coque avec isolation en cavité, mobilier 3D intégré. Coût de construction 15–20 % inférieur au conventionnel ; loyers alignés sur le bas de la fourchette locale. Tous les logements loués en moins d\'un an. Perspective Vita : une maison 3D peut être construite sur mesure pour des ménages fragiles, avec espaces modulables et accessibilité intégrée d\'emblée.',
                        'link' => 'peri3dconstruction.com/en/mehrfamilienhaus-wallenhausen',
                    ],
                ],
            ],


            // -- Bailleurs partenaires SVG ------------------------------------------
            'bailleur_incentives' => [
                'eyebrow' => 'Bailleurs partenaires',
                'title' => 'Louer à des bénéficiaires SVG : des aides renforcées',
                'lead' => 'Les propriétaires qui acceptent des locataires titulaires d\'un Compte de Dépenses Conditionnées (CDC) et s\'engagent à une mise en norme écologique bénéficient d\'un cumul d\'aides majoré — jusqu\'à 65 % des travaux couverts.',
                'aids' => [
                    [
                        'name' => 'Loc\'Avantages + Bonus Vita',
                        'rate' => 'Déduction fiscale 15–65 % des loyers',
                        'condition' => 'Loyer social ou très social · Locataires CDC acceptés · Conventionnement Anah 6 ans min.',
                        'type' => 'Fiscal',
                    ],
                    [
                        'name' => 'MaPrimeRénov\' Bailleur',
                        'rate' => 'Jusqu\'à 50 % des travaux de rénovation',
                        'condition' => 'Gain énergétique ≥ 2 classes DPE · Engagement de loyer plafonné · Locataires ressources modestes',
                        'type' => 'Subvention',
                    ],
                    [
                        'name' => 'Aide complémentaire Vita',
                        'rate' => '+10 % sur le taux Anah de base',
                        'condition' => 'Attestation d\'acceptation de locataires CDC/SVG · Mise aux normes BEPOS ou BBC Rénovation',
                        'type' => 'Subvention spécifique',
                    ],
                    [
                        'name' => 'TVA à 5,5 %',
                        'rate' => 'vs 20 % en taux normal',
                        'condition' => 'Travaux d\'amélioration énergétique sur résidence principale locative',
                        'type' => 'Taux réduit',
                    ],
                    [
                        'name' => 'Exonération taxe foncière',
                        'rate' => 'Jusqu\'à 25 ans',
                        'condition' => 'Bâtiment labellisé BBC ou BEPOS réhabilité en site Vita · Convention avec commune partenaire',
                        'type' => 'Exonération',
                    ],
                ],
                'note' => 'Ces aides sont cumulables dans le cadre du conventionnement Anah et de la convention Vita. Un accompagnateur France Rénov\' peut prendre en charge le montage du dossier (prestation gratuite pour les bailleurs modestes).',
            ],

            // -- Références doctrinales ---------------------------------------------
            'doctrinal' => [
                'eyebrow' => 'Références doctrinales',
                'title' => 'Deux visions pour guider Vita',
                'lead' => 'Vita s\'appuie sur deux œuvres structurantes pour ancrer ses choix techniques et son horizon politique.',
                'books' => [
                    [
                        'title' => 'Le Monde sans fin',
                        'authors' => 'Jean-Marc Jancovici & Christophe Blain',
                        'year' => '2021',
                        'publisher' => 'Dargaud (BD)',
                        'role' => 'Cadre énergétique',
                        'summary' => 'Analyse en flux d\'énergie de notre civilisation : retour sur investissement énergétique (EROI), décarbonation, limites des renouvelables, rôle du nucléaire. Vita intègre cette lecture : la réhabilitation réduit de 50 à 80 % la consommation énergétique d\'un bâtiment — levier incontournable de toute trajectoire bas-carbone crédible. Chaque site Vita vise un bilan énergie positive (BEPOS) conforme aux scénarios Shift Project.',
                    ],
                    [
                        'title' => 'Bienvenue en 2055',
                        'authors' => 'Magali Reghezza-Zitt',
                        'year' => '2026',
                        'publisher' => 'Éditions du Seuil (fiction scientifique) · Illustré par Marc Bati',
                        'role' => 'Horizon urbain',
                        'summary' => 'Fiction scientifique fondée sur les préconisations du GIEC : à quoi ressemble un monde réellement neutre en carbone en 2055 ? La ville de 2055 est dense, réhabilitée, accessible à pied, décarbonatée. Les quartiers mixtes remplacent les banlieues monofonctionnelles ; l\'agriculture urbaine et les espaces verts thérapeutiques sont intégrés dans le bâti. Vita anticipe ce futur : chaque éco-quartier Vita est conçu pour être encore pertinent en 2055, à zéro carbone opérationnel et avec une empreinte carbone grise maîtrisée.',
                    ],
                ],
            ],

            // -- Call to action ------------------------------------------------------
            'cta' => [
                'title' => 'Rejoindre le projet Vita',
                'body' => 'Vita cherche des partenaires : collectivités, propriétaires de bâtiments vacants, associations d\'insertion, financeurs solidaires, architectes spécialisés en réhabilitation écologique.',
                'link_label' => 'Nous contacter',
                'link_href' => '/contact',
                'secondary_label' => 'En savoir plus sur le Civitalisme',
                'secondary_href' => '/civitalisme',
            ],
        ];
    }

}