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
 *  - SVG-Partie-Technique.pdf       (plateforme web3 EuroE)
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
 *  financing    - Comment l'argent est cree et comment l'EuroE s'insere
 *  howItWorks   - 5 questions simples
 *  usage        - A quoi sert l'EuroE au quotidien
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
 *  doctrine      - Double circuit monetaire (euro / EuroE)
 *  architecture  - Plateforme EuroE (8 couches + cycle de vie)
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
     * EuroE = the monetary tool. Never say "revenu universel" or "cryptomonnaie".
     *
     * @return array<string, mixed>
     */
    public function publicPage(): array
    {
        return [
            // -- 1. Hero -------------------------------------------------------------
            'hero' => [
                'eyebrow' => 'Civitalisme — Comprendre en 2 minutes',
                'title' => 'Et si personne ne pouvait plus tomber ?',
                'subtitle' => 'Le Socle Vital Garanti, un filet de sécurité européen',
                'lead' => 'Le Civitalisme porte un projet simple : faire en sorte que chaque Européen puisse vivre dignement — qu’il travaille, se forme, ou traverse un moment difficile.',
                'primaryCta' => [
                    'label' => 'Comprendre en 2 minutes',
                    'href' => '#probleme',
                ],
                'secondaryCta' => [
                    'label' => 'Voir le cadre institutionnel',
                    'href' => null, // set in template via path()
                ],
            ],

            // -- 2. Problem ----------------------------------------------------------
            'problem' => [
                'anchor' => 'probleme',
                'eyebrow' => 'Pourquoi maintenant',
                'title' => 'L’IA change tout. Pas que pour les autres.',
                'lead' => 'En quelques années, l’automatisation transforme des métiers qu’on croyait protégés. Les revenus deviennent plus instables, les fins de mois plus dures.',
                'cards' => [
                    [
                        'illustration' => 'robot-desk',
                        'title' => 'L’IA remplace des métiers qu’on croyait protégés',
                        'body' => 'Comptabilité, conseil, rédaction, logistique : plus de 40 % des emplois européens sont exposés à l’automatisation dans les dix prochaines années.',
                    ],
                    [
                        'illustration' => 'falling-chart',
                        'title' => 'Les revenus deviennent instables pour des millions d’Européens',
                        'body' => 'Temps partiels subis, missions courtes, transitions forcées : près de 61 % des ménages modestes ont du mal à boucler leurs fins de mois.',
                    ],
                    [
                        'illustration' => 'household-bill',
                        'title' => 'Loyer, énergie, alimentation : tout devient un choix impossible',
                        'body' => '93 millions de personnes vivent déjà sous le seuil de pauvreté en Europe. Une mauvaise surprise suffit à faire basculer un foyer.',
                    ],
                ],
                'transition' => 'Le système actuel n’a pas été conçu pour ça. Il faut un nouveau filet.',
            ],

            // -- 3. Solution ---------------------------------------------------------
            'solution' => [
                'eyebrow' => 'La réponse du Civitalisme',
                'title' => 'Le Socle Vital Garanti',
                'lead' => 'Chaque Européen reçoit automatiquement un complément qui couvre ses besoins essentiels. Pas une aide à demander. Un droit.',
                'subtext' => 'Le montant s’adapte chaque mois à votre situation réelle.',
                'needs' => [
                    ['icon' => 'home', 'label' => 'Logement'],
                    ['icon' => 'food', 'label' => 'Alimentation'],
                    ['icon' => 'energy', 'label' => 'Énergie'],
                    ['icon' => 'transport', 'label' => 'Transport'],
                    ['icon' => 'communication', 'label' => 'Communication'],
                    ['icon' => 'education', 'label' => 'Éducation'],
                ],
            ],

            // -- 4. How it works -----------------------------------------------------
            'howItWorks' => [
                'eyebrow' => 'En pratique',
                'title' => 'Simple comme 1, 2, 3',
                'lead' => 'Trois étapes, pas de formulaire à remplir, pas d’aide à demander.',
                'steps' => [
                    [
                        'number' => '1',
                        'illustration' => 'phone-app',
                        'title' => 'Votre banque calcule votre complément',
                        'body' => 'Chaque mois, elle regarde automatiquement vos ressources et calcule le montant dont vous avez besoin.',
                    ],
                    [
                        'number' => '2',
                        'illustration' => 'payment-card',
                        'title' => 'Vous recevez des EuroE',
                        'body' => 'Ils valent 1 € chacun et s’utilisent partout en Europe pour vos dépenses essentielles.',
                    ],
                    [
                        'number' => '3',
                        'illustration' => 'shopping',
                        'title' => 'Vous payez normalement',
                        'body' => 'Sans étiquette, sans gêne, sans formulaire. Le commerçant ne voit pas de différence.',
                    ],
                ],
            ],

            // -- 5. Work incentive ---------------------------------------------------
            'workIncentive' => [
                'eyebrow' => 'Et si je travaille ?',
                'title' => 'Travailler rapporte toujours plus',
                'lead' => 'Le Socle Vital Garanti ne remplace pas le travail. Il vous protège pendant les transitions et récompense chaque euro gagné.',
                'chart' => [
                    'caption' => 'Exemple pour un jeune actif en grande ville européenne',
                    'bars' => [
                        [
                            'label' => 'Sans emploi',
                            'total' => 1488,
                            'breakdown' => [
                                ['label' => 'Socle vital', 'amount' => 1488, 'kind' => 'svg'],
                            ],
                        ],
                        [
                            'label' => 'Avec un emploi à 1 700 €',
                            'total' => 2201,
                            'breakdown' => [
                                ['label' => 'Salaire', 'amount' => 1700, 'kind' => 'work'],
                                ['label' => 'Complément', 'amount' => 501, 'kind' => 'svg'],
                            ],
                        ],
                    ],
                    'gain' => [
                        'label' => 'Gain net',
                        'value' => '+713 € / mois',
                    ],
                ],
                'note' => 'Les 300 premiers euros de salaire ne réduisent pas votre complément.',
            ],

            // -- 6. Personas ---------------------------------------------------------
            'personas' => [
                'eyebrow' => 'Pour qui ?',
                'title' => 'Pour vous, pour vos proches, pour tout le monde',
                'lead' => 'Quatre histoires parmi des millions. Le Socle Vital Garanti accompagne chaque étape de vie.',
                'cards' => [
                    [
                        'illustration' => 'persona-marie',
                        'name' => 'Marie, 34 ans',
                        'situation' => 'Mère seule',
                        'body' => 'Son loyer est sécurisé même quand ses heures de travail baissent.',
                    ],
                    [
                        'illustration' => 'persona-lucas',
                        'name' => 'Lucas, 22 ans',
                        'situation' => 'Étudiant',
                        'body' => 'Il peut se former à l’IA sans choisir entre manger et apprendre.',
                    ],
                    [
                        'illustration' => 'persona-fatima',
                        'name' => 'Fatima, 68 ans',
                        'situation' => 'Retraitée',
                        'body' => 'Sa petite retraite est complétée pour vivre dignement.',
                    ],
                    [
                        'illustration' => 'persona-thomas',
                        'name' => 'Thomas, 45 ans',
                        'situation' => 'Ouvrier',
                        'body' => 'Son usine se modernise. Il se forme sans panique, à son rythme.',
                    ],
                ],
            ],

            // -- 7. Preserved rights -------------------------------------------------
            'preservedRights' => [
                'eyebrow' => 'Ce qui ne change pas',
                'title' => 'Vos droits actuels sont protégés',
                'lead' => 'Le Socle Vital Garanti s’ajoute au système existant. Il ne le remplace pas.',
                'points' => [
                    'Votre retraite reste votre retraite.',
                    'Votre salaire reste votre salaire — le SMIC et les minima conventionnels sont inchangés.',
                    'Vos droits au chômage (ARE) sont maintenus comme droits liés à l\'emploi.',
                    'Vos conventions collectives et droits syndicaux sont pleinement préservés.',
                    'Vos aides actuelles ne sont pas supprimées — elles sont simplifiées.',
                    'Un licenciement ne signifie plus la précarité immédiate : le SVG couvre les besoins essentiels dès le mois suivant.',
                ],
            ],

            // -- 8. European project -------------------------------------------------
            'europe' => [
                'eyebrow' => 'Souveraineté européenne',
                'title' => 'Un projet européen, pour les Européens',
                'lead' => 'Hébergé en Europe, construit sur des rails de paiement européens, contrôlé par des institutions démocratiques. Le Civitalisme, c’est la souveraineté économique au service de chacun.',
                'pillars' => [
                    ['label' => 'Hébergement européen', 'body' => 'Données et infrastructure en Europe uniquement.'],
                    ['label' => 'Rails de paiement européens', 'body' => 'TIPS, TARGET, Wero — les infrastructures existantes.'],
                    ['label' => 'Contrôle démocratique', 'body' => 'Gouvernance partagée entre institutions élues et citoyens.'],
                ],
            ],

            // -- 9. Call to action ---------------------------------------------------
            'cta' => [
                'eyebrow' => 'Envie d’aller plus loin ?',
                'title' => 'Découvrez le projet en détail',
                'body' => 'Rapports complets, simulateur, feuille de route et architecture technique : tout est documenté pour les institutions, les chercheurs et les curieux.',
            ],
        ];
    }

    /**
     * Content for the institutional / technical page (cadre institutionnel).
     *
     * Audience: économistes, juristes, décideurs politiques, entrepreneurs et partenaires techniques.
     * Cette page est dense, structurée autour de la preuve documentaire (7 rapports),
     * de l'architecture EuroE, du cadre juridique européen et de la feuille de route.
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
                'lead' => 'Le Socle Vital Garanti (SVG) européen est l’un des projets portés par le Civitalisme. Il vise à garantir, via l’outil monétaire EuroE, l’accès aux besoins vitaux — logement, alimentation, énergie, transport, santé, éducation — sans déstabiliser l’euro, sans remplacer les retraites, sans concurrencer la sphère marchande. Cette page réunit la doctrine, l’architecture technique, le cadre juridique et la feuille de route à l’attention des institutions et des professionnels.',
                'highlights' => [
                    'Doctrine documentée en 7 rapports thématiques',
                    'Compatible AI Act, RGPD, DSP2, MiCA et DORA',
                    'Double circuit monétaire, retraites et revenus du travail préservés',
                ],
            ],

            // -- 2. Synthèse exécutive + 7 rapports ----------------------------------
            'executive' => [
                'eyebrow' => 'Synthèse exécutive',
                'title' => 'Ce que dit la doctrine, en quelques lignes',
                'lead' => 'Le Civitalisme propose de distinguer la sphère vitale (besoins de dignité) de la sphère de progression (marché, épargne, investissement). Le SVG européen instaure un filet de sécurité ciblé, monétisé par l’EuroE, un euro numérique permissionné dédié aux dépenses vitales. L’euro classique conserve l’intégralité de son périmètre actuel.',
                'pillars' => [
                    ['title' => 'Ciblage des besoins vitaux', 'body' => 'Le SVG ne verse pas un revenu universel indifférencié : il finance un panier vital (logement, alimentation, énergie, transport, santé, éducation) encadré par la norme démocratique.'],
                    ['title' => 'Outil monétaire dédié', 'body' => 'L’EuroE est une monnaie numérique européenne, permissionnée, émise par la BCE sous mandat, convertible vers l’euro uniquement par les commerçants agréés au panier vital.'],
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
                        'title' => 'Plateforme EuroE',
                        'body' => 'Architecture web3 permissionnée, séparation des plans de données, conversion contrôlée euro ⇄ EuroE.',
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
                'title' => 'Un double circuit monétaire, pas un remplacement',
                'lead' => 'Le Civitalisme distingue deux sphères monétaires complémentaires. L’euro classique continue de fonctionner exactement comme aujourd’hui pour le marché général, l’épargne, l’investissement et la liberté économique. L’EuroE est un instrument social ciblé, strictement dédié aux dépenses du panier vital auprès de commerçants agréés. Cette distinction reprend l’ancienne séparation aristotélicienne entre l’économie domestique (besoins vitaux) et la chrématistique (échange marchand).',
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
                            'instrument' => 'EuroE',
                            'scope' => 'Panier vital : logement, alimentation, énergie, transport, santé, éducation.',
                            'role' => 'Monnaie de la sphère vitale, ciblée, non spéculative, non thésaurisable.',
                            'limits' => 'Permissionnée, auditée, convertible uniquement par commerçants agréés, volume encadré par la BCE.',
                        ],
                    ],
                ],
            ],

            // -- 4. Architecture technique (8 couches + cycle de vie EuroE) ----------
            'architecture' => [
                'eyebrow' => 'Architecture',
                'title' => 'Plateforme EuroE — 8 couches, permissionnée, auditée',
                'lead' => 'L’EuroE s’appuie sur une infrastructure web3 permissionnée (non publique), opérée sous la responsabilité de la BCE et des autorités de supervision. Chaque couche est conçue pour être auditable, interopérable et conforme aux exigences DORA et RGPD.',
                'layers' => [
                    ['index' => '01', 'title' => 'Identité souveraine', 'body' => 'Wallet européen (EUDI) rattaché à l’état civil, preuve de résidence et de situation familiale (hors données sensibles).'],
                    ['index' => '02', 'title' => 'Registre permissionné', 'body' => 'Ledger distribué contrôlé par la BCE et les banques centrales nationales ; pas de minage, pas de spéculation, pas de frais variables.'],
                    ['index' => '03', 'title' => 'Émission monétaire', 'body' => 'Création d’EuroE mensuelle selon la formule SVG validée par le Parlement européen ; destruction en miroir lors du paiement au commerçant agréé.'],
                    ['index' => '04', 'title' => 'Droits et éligibilité', 'body' => 'Calcul individualisé par foyer : LW₀ de base, équivalent foyer, zone géographique, indexation HICP, revenus et pensions déduits.'],
                    ['index' => '05', 'title' => 'Panier vital', 'body' => 'Catalogue des biens et services éligibles, maintenu par les institutions démocratiques ; révisable par vote parlementaire.'],
                    ['index' => '06', 'title' => 'Commerçants agréés', 'body' => 'Enregistrement, audit et conversion EuroE → euro classique pour les vendeurs du panier vital (loyers, énergie, santé, alimentation, etc.).'],
                    ['index' => '07', 'title' => 'Supervision et audit', 'body' => 'Traçabilité agrégée, contrôle par la Cour des comptes européenne et les autorités nationales ; conformité RGPD pseudonymisée.'],
                    ['index' => '08', 'title' => 'Interfaces publiques', 'body' => 'Applications mobiles citoyennes, portails commerçants, APIs institutionnelles, tableaux de bord ouverts en données agrégées.'],
                ],
                'lifecycle' => [
                    ['step' => '1', 'title' => 'Émission', 'body' => 'La BCE crédite chaque foyer éligible en EuroE selon la formule SVG, le 1er du mois.'],
                    ['step' => '2', 'title' => 'Dépense', 'body' => 'Le citoyen dépense en EuroE auprès d’un commerçant agréé pour un bien ou service du panier vital.'],
                    ['step' => '3', 'title' => 'Conversion', 'body' => 'Le commerçant agréé convertit ses EuroE en euro classique auprès de sa banque, qui les présente à la BCE.'],
                    ['step' => '4', 'title' => 'Destruction', 'body' => 'Les EuroE sont détruits à la conversion — pas d’accumulation, pas de thésaurisation, pas de marché secondaire.'],
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


            // -- 6. Dialogue social et partenaires syndicaux -------------------------
            'syndical' => [
                'eyebrow' => 'Dialogue social',
                'title' => 'Le SVG et les partenaires sociaux',
                'lead' => 'Le principal point de vigilance syndical est direct : si l\'Etat garantit un revenu de base, qu\'est-ce qui empeche les employeurs de baisser les salaires ? La reponse est structurelle.',
                'thesis' => 'Le SVG n\'est pas un concurrent du salaire. C\'est un plancher inconditionnel qui lib\xc3\xa8re les salari\xc3\xa9s de la peur du licenciement et permet aux syndicats de recentrer leur action sur la qualit\xc3\xa9 du travail plut\xc3\xb4t que sur la survie mat\xc3\xa9rielle.',
                'diagnosis' => [
                    'Eurostat (2024) : 8,5 % des travailleurs europ\xc3\xa9ens sont en situation de pauvret\xc3\xa9 au travail malgr\xc3\xa9 un emploi.',
                    'La peur du licenciement structure le rapport de force dans l\'entreprise : un salari\xc3\xa9 qui sait qu\'il tombera dans la pr\xc3\xa9carit\xc3\xa9 accepte des conditions d\xc3\xa9grad\xc3\xa9es.',
                    'L\'IA acc\xc3\xa9l\xc3\xa8re la fragmentation des emplois, rendant les transitions plus fr\xc3\xa9quentes et les comp\xc3\xa9tences plus rapidement obsoletes.',
                    '40 % des personnes \xc3\xa9ligibles aux aides actuelles n\'y ont pas recours (non-recours massif). Le SVG est automatique, non stigmatisant, calibr\xc3\xa9 sur M-1.',
                ],
                'guardrails' => [
                    [
                        'title' => 'SMIC et minima conventionnels inchang\xc3\xa9s',
                        'body' => 'Le texte fondateur affirme explicitement que le SVG ne se substitue pas aux obligations salariales l\xc3\xa9gales. Aucun employeur ne peut invoquer le SVG pour r\xc3\xa9duire un salaire sous le SMIC ou le minimum de branche.',
                    ],
                    [
                        'title' => 'Le SVG est un droit du citoyen, pas une subvention \xc3\xa0 l\'employeur',
                        'body' => 'Il est vers\xc3\xa9 sur la base de la situation personnelle du citoyen, ind\xc3\xa9pendamment de son contrat de travail. L\'employeur n\'a aucune visibilit\xc3\xa9 ni contr\xc3\xb4le sur le montant re\xc3\xa7u.',
                    ],
                    [
                        'title' => 'La franchise de travail prot\xc3\xa8ge l\'incitation',
                        'body' => 'Les 300 premiers euros de revenu d\'activit\xc3\xa9 par actif ne r\xc3\xa9duisent pas le SVG. Toute baisse de salaire r\xc3\xa9duit donc les ressources totales du salari\xc3\xa9 \xe2\x80\x94 il a un int\xc3\xa9r\xc3\xaat \xc3\xa9conomique \xc3\xa0 la refuser.',
                    ],
                    [
                        'title' => 'Conventions collectives pleinement maintenues',
                        'body' => 'Les conventions collectives continuent de fixer les grilles salariales, les conditions de travail et les droits conventionnels. Le SVG ajoute une couche de s\xc3\xa9curit\xc3\xa9 en dessous ; il ne les remplace pas.',
                    ],
                    [
                        'title' => 'R\xc3\xa9duction des charges conditionnelle et progressive',
                        'body' => 'Aucune baisse de cotisation n\'intervient avant que les effets du SVG aient \xc3\xa9t\xc3\xa9 mesur\xc3\xa9s et valid\xc3\xa9s. La logique : d\'abord prouver que le socle vital fonctionne, ensuite ajuster le financement. Jamais l\'inverse.',
                    ],
                ],
                'roleShift' => [
                    'eyebrow' => 'Nouveau r\xc3\xb4le syndical',
                    'lead' => 'Le Civitalisme ne marginalise pas les syndicats \xe2\x80\x94 il transforme leur mission. Avec le SVG comme filet de s\xc3\xa9curit\xc3\xa9, les syndicats peuvent adopter une posture offensive plut\xc3\xb4t que d\xc3\xa9fensive.',
                    'shifts' => [
                        [
                            'before' => 'R\xc3\xa9sister aux licenciements li\xc3\xa9s \xc3\xa0 l\'IA',
                            'after' => 'N\xc3\xa9gocier les conditions de la transition IA : calendrier, formation, maintien du revenu pendant la reconversion.',
                        ],
                        [
                            'before' => 'Se battre pour maintenir des emplois condamn\xc3\xa9s',
                            'after' => 'N\xc3\xa9gocier la cr\xc3\xa9ation de nouveaux postes : superviseurs IA, contr\xc3\xb4leurs qualit\xc3\xa9, formateurs, coordinateurs de transition.',
                        ],
                        [
                            'before' => 'D\xc3\xa9fendre les minima salariaux',
                            'after' => 'N\xc3\xa9gocier le partage du dividende technologique : si l\'IA augmente la productivit\xc3\xa9 de 30 %, quelle part revient aux salari\xc3\xa9s ?',
                        ],
                        [
                            'before' => 'G\xc3\xa9rer les plans sociaux',
                            'after' => 'Co-construire les parcours de reconversion avec l\'entreprise et les organismes de formation.',
                        ],
                    ],
                ],
                'positions' => [
                    [
                        'org' => 'CES \xe2\x80\x94 Conf\xc3\xa9d\xc3\xa9ration europ\xc3\xa9enne des syndicats',
                        'stance' => 'Favorable sous conditions',
                        'argument' => 'Le SVG reprend les objectifs du Socle europ\xc3\xa9en des droits sociaux (principe 14). La franchise de travail pr\xc3\xa9serve l\'incitation. Le pouvoir de n\xc3\xa9gociation des salari\xc3\xa9s est renforc\xc3\xa9.',
                    ],
                    [
                        'org' => 'CGT (France)',
                        'stance' => 'M\xc3\xa9fiante puis int\xc3\xa9ress\xc3\xa9e',
                        'argument' => 'Insister sur le renforcement du rapport de force salarial, le maintien des conventions collectives et le refus de toute substitution salaire/SVG.',
                    ],
                    [
                        'org' => 'CFDT (France)',
                        'stance' => 'Plut\xc3\xb4t favorable',
                        'argument' => 'Alignement avec la culture du compromis et de la n\xc3\xa9gociation. Le SVG comme outil d\'accompagnement des transitions.',
                    ],
                    [
                        'org' => 'DGB (Allemagne)',
                        'stance' => 'Prudent',
                        'argument' => 'Pr\xc3\xa9senter le SVG comme compatible avec le mod\xc3\xa8le de cogestion allemand. Insister sur la n\xc3\xa9gociation branche par branche.',
                    ],
                    [
                        'org' => 'CCOO / UGT (Espagne)',
                        'stance' => 'Favorable',
                        'argument' => 'Forte exposition \xc3\xa0 la pr\xc3\xa9carit\xc3\xa9 et au travail temporaire. Le SVG r\xc3\xa9pond \xc3\xa0 un besoin urgent.',
                    ],
                    [
                        'org' => 'Syndicats nordiques',
                        'stance' => 'Int\xc3\xa9ress\xc3\xa9s mais vigilants',
                        'argument' => 'Ils ont d\xc3\xa9j\xc3\xa0 des filets de s\xc3\xa9curit\xc3\xa9 forts. Le SVG doit \xc3\xaatre pr\xc3\xa9sent\xc3\xa9 comme une modernisation, pas un remplacement.',
                    ],
                ],
                'recommendations' => [
                    'Associer les syndicats d\xc3\xa8s la phase de consultation du livre blanc \xe2\x80\x94 co-construire, pas imposer.',
                    'Garantir dans le texte fondateur que le SVG ne se substitue ni aux salaires conventionnels ni aux droits contributifs acquis.',
                    'Pr\xc3\xa9voir un comit\xc3\xa9 paritaire de suivi du SVG incluant les partenaires sociaux, avec droit de v\xc3\xa9to sur les modifications affectant le march\xc3\xa9 du travail.',
                    'Cr\xc3\xa9er un \xc2\xab dividende de transition IA \xc2\xbb n\xc3\xa9goci\xc3\xa9 au niveau de l\'entreprise : quand l\'IA augmente la productivit\xc3\xa9, une part des gains est partag\xc3\xa9e avec les salari\xc3\xa9s.',
                    'Former les repr\xc3\xa9sentants syndicaux \xc3\xa0 l\'IA pour qu\'ils n\xc3\xa9gocient en connaissance de cause les impacts technologiques dans leurs secteurs.',
                ],
            ],

            // -- 7. Cadre juridique européen -----------------------------------------
            'legal' => [
                'eyebrow' => 'Cadre juridique',
                'title' => 'Un dispositif conforme au droit européen',
                'lead' => 'Le SVG et l’EuroE s’inscrivent dans les compétences partagées entre l’Union et les États membres (art. 4 TFUE) et dans le mandat monétaire de la BCE (art. 127 TFUE). Chaque brique a été vérifiée au regard des règlements européens en vigueur.',
                'matrix' => [
                    ['ref' => 'AI Act', 'scope' => 'IA à haut risque', 'status' => 'conforme', 'body' => 'Aucun système d’IA à haut risque dans le calcul du SVG ; les règles sont déterministes et publiées.'],
                    ['ref' => 'RGPD', 'scope' => 'Données personnelles', 'status' => 'conforme', 'body' => 'Pseudonymisation systématique, minimisation des données, droit d’accès et de rectification intégrés.'],
                    ['ref' => 'DSP2', 'scope' => 'Services de paiement', 'status' => 'conforme', 'body' => 'L’EuroE est émis par la BCE ; les interfaces de paiement respectent l’authentification forte (SCA).'],
                    ['ref' => 'MiCA', 'scope' => 'Crypto-actifs', 'status' => 'hors champ', 'body' => 'L’EuroE n’est pas un crypto-actif : il s’agit d’une monnaie centrale numérique permissionnée, hors périmètre MiCA.'],
                    ['ref' => 'DORA', 'scope' => 'Résilience opérationnelle', 'status' => 'conforme', 'body' => 'Plan de continuité, tests d’intrusion, audits et reporting incidents alignés sur les exigences DORA.'],
                    ['ref' => 'Traités (TFUE)', 'scope' => 'Compétences', 'status' => 'conforme', 'body' => 'Compétence partagée UE/États (art. 4) ; mandat monétaire BCE (art. 127) ; cohésion sociale (art. 151).'],
                ],
                'risks' => [
                    ['level' => 'faible', 'title' => 'Inflation ciblée', 'body' => 'L’EuroE ne circule que vers le panier vital ; la conversion est plafonnée et auditée.'],
                    ['level' => 'moyen', 'title' => 'Désincitation au travail', 'body' => 'Atténuée par la franchise de 300 €/actif et le caractère non fongible de l’EuroE.'],
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
                        'title' => 'Intégration EuroE',
                        'body' => 'Émission de l’EuroE par la BCE dans le cadre du pilote, déploiement du registre permissionné, agrément des premiers commerçants, interopérabilité EUDI.',
                        'kpi' => 'EuroE opérationnel · 500 commerçants agréés · audit DORA',
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
                    ['title' => 'Technique — Plateforme EuroE', 'href' => '/documents/SVG-Partie-Technique.pdf', 'size' => 'PDF'],
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
}
