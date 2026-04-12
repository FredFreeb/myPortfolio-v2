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
 *  hero / heroPanel  - Cadrage institutionnel
 *  introduction      - Doctrine pour l'ere de l'automatisation
 *  diagnostic        - Tension structurelle
 *  doctrine          - Double circuit monetaire
 *  architecture      - Plateforme web3 permissionnee
 *  socle             - Panier vital prioritaire
 *  calculation       - Formule de calcul
 *  caseStudy         - Cas Prague
 *  stakeholders      - Ce qu'attend chaque acteur (7 profils)
 *  governance        - Repartition des roles
 *  risks             - Objections et garde-fous
 *  roadmap           - Montee en charge
 *  conclusion        - Synthese
 */
final class CivitalismeContentProvider
{
    /**
     * Content for the grand-public (citizen-facing) page.
     *
     * @return array<string, mixed>
     */
    public function publicPage(): array
    {
        return [
            // -- Hero ----------------------------------------------------------------
            'hero' => [
                'eyebrow' => 'Version grand public - Lecture en 5 minutes',
                'title' => 'Civitalisme',
                'subtitle' => 'Protéger le vital a l\'ère de l\'intelligence artificielle',
                'lead' => 'L\'IA et l\'automatisation transforment l\'économie plus vite que les emplois ne se renouvellent. Le Civitalisme propose un socle vital en euro pour que personne ne perde l\'accès au logement, a l\'énérgie ou a l\'alimentation pendant cette transition.',
                'highlights' => [
                    'Ni crypto, ni utopie : un complément social en euro.',
                    'L\'euro classique reste la monnaie principale.',
                    'L\'EuroE protège le vital pendant la transition.',
                ],
            ],

            // -- Hero aside panel ----------------------------------------------------
            'heroPanel' => [
                'label' => 'A retenir',
                'title' => '3 idees simples',
                'body' => 'Le projet est fait pour etre compris vite, meme sans bagage economique.',
                'points' => [
                    'Le Civitalisme complete le systeme actuel. Il ne le remplace pas.',
                    'Le travail reste essentiel pour progresser.',
                    'Le projet avance par etapes, avec des pilotes et des ajustements.',
                ],
            ],

            // -- Problem statement ---------------------------------------------------
            'problem' => [
                'eyebrow' => 'Pourquoi c\'est urgent',
                'title' => 'L\'IA change la donne, et vite',
                'lead' => 'Pour la première fois, une technologie concurrence non seulement les bras, mais aussi le cerveau. Les estimations de l\'OCDE et du FMI convergent : entre 40 % et 60 % des emplois dans les economies avancées sont exposées à une forme d\'automatisation par l\'IA dans les dix prochaines annees.',
                'narrativeTitle' => 'Ce que cela change dans la vraie vie',
                'narrative' => [
                    'Les revolutions technologiques precedentes se deployaient sur 60 a 80 ans. La revolution IA transforme les modes de production en 3 a 5 ans. Cette acceleration ne laisse pas le temps aux mécanismes naturels du marche du travail de fonctionner.',
                    'Un travailleur dont le poste est supprimé par l\'IA en 2026 ne peut pas se reconvertir du jour au lendemain. La formation prend du temps, de l\'argent et de la sécurité materielle. Sans socle vital, la reconversion est un luxe reserve a ceux qui ont déjà des ressources.',
                ],
                'points' => [
                    [
                        'title' => 'Le circuit se brise',
                        'body' => 'L\'economie fonctionne en boucle : travail, salaire, consommation, profit, investissement. Quand l\'IA remplace le travail sans creer d\'emplois equivalents, cette boucle se casse.',
                    ],
                    [
                        'title' => 'Plus de richesse, moins de partage',
                        'body' => 'L\'IA ne détruit pas la richesse, elle en crée massivement. Mais cette richesse se concentre dans les mains de ceux qui possedent le capital et les infrastructures d\'IA.',
                    ],
                    [
                        'title' => 'Un risque démocratique',
                        'body' => 'L\'histoire enseigne que les sociétés ou une majorité perd son accès a la dignité materielle deviennent instables. L\'IA risque d\'accelerer ce phénomène.',
                    ],
                ],
            ],

            // -- Financing -----------------------------------------------------------
            'financing' => [
                'eyebrow' => 'Repère essentiel',
                'title' => 'Comment l\'argent est crée aujourd\'hui',
                'lead' => 'Version simple en 30 secondes, sans jargon.',
                'narrativeTitle' => 'En 30 secondes',
                'narrative' => [
                    'La majorité de l\'argent est crée quand une banque accorde un crédit a un ménage ou a une entreprise.',
                    'Cet argent circule ensuite dans l\'économie.',
                    'Quand le prêt est remboursé, cette monnaie disparait progressivement.',
                    'Les banques ne peuvent pas créer sans limites : elles doivent respecter des régles, des resèrves et un cadre bancaire.',
                    'Aujourd\'hui, la majorité de l\'argent est crée par la dette.',
                ],
                'resourcesTitle' => 'Pour comprendre en video',
                'resources' => [
                    [
                        'label' => 'Dessine-moi l\'eco - La création monetaire (YouTube)',
                        'href' => 'https://www.youtube.com/watch?v=o2u7Xa57y8A',
                        'meta' => '3 min 30 - vulgarisation',
                    ],
                    [
                        'label' => 'Banque de France - Comment est créée la monnaie',
                        'href' => 'https://www.banque-france.fr/fr/publications-et-statistiques/publications/comment-est-creee-la-monnaie',
                        'meta' => 'video + infographies officielles',
                    ],
                    [
                        'label' => 'Citeco - La création monetaire (animation)',
                        'href' => 'https://www.citeco.fr/la-creation-monetaire',
                        'meta' => 'format pédagogique grand public',
                    ],
                ],
                'points' => [
                    [
                        'title' => 'Pourquoi ce n\'est pas une monnaie magique',
                        'body' => 'L\'EuroE n\'est pas un bouton illimité. Le projet repose sur un cadre, des régles et un suivi public. Sa valeur est ancrée à parité 1:1 avec l\'euro.',
                    ],
                    [
                        'title' => 'Le coût brut n\'est pas la bonne lecture',
                        'body' => 'Chaque euro d\'EuroE emis genere environ 2,30 EUR de chiffre d\'affaires interieur grace a la recirculation dans l\'économie locale.',
                    ],
                    [
                        'title' => 'Le vrai coût apparait a la sortie du circuit',
                        'body' => 'Avec un taux de recirculation de 75 %, seule une fraction de l\'EuroE est convertie en euro classique. C\'est la que le coût réel se materialise.',
                    ],
                    [
                        'title' => 'Le financement reste mixte',
                        'body' => 'Le projet combine plusieurs sources, de facon progressive.',
                        'points' => [
                            'Redeploiement d\'une partie de soutiens déjà existants.',
                            'Financement public.',
                            'Contribution liée aux conversions professionnelles.',
                            'A long terme, partage d\'une part du dividende technologique de l\'IA.',
                        ],
                    ],
                ],
                'infography' => [
                    'title' => 'Ce qui change avec ce projet',
                    'caption' => 'Le projet complète le systeme actuel, il ne le remplace pas.',
                    'rowLabel' => 'Etape',
                    'columns' => [
                        ['key' => 'mechanism', 'label' => 'Mécanisme'],
                        ['key' => 'meaning', 'label' => 'Pourquoi c\'est utile'],
                    ],
                    'rows' => [
                        [
                            'label' => 'Aujourd\'hui',
                            'mechanism' => 'Crédit bancaire -> dette',
                            'meaning' => 'Moteur principal de création monetaire.',
                        ],
                        [
                            'label' => 'Complement Civitalisme',
                            'mechanism' => 'EuroE d\'usage social, libellé en euro',
                            'meaning' => 'Sécuriser les besoins essentiels quand un revenu baisse.',
                        ],
                        [
                            'label' => 'Sortie du circuit',
                            'mechanism' => 'Conversion pro vers euro classique',
                            'meaning' => 'C\'est surtout ici que le coût en euro classique devient visible.',
                        ],
                    ],
                    'quote' => 'Le Civitalisme ne remplace pas ce système. Il le complète.',
                ],
                'euroe' => [
                    'title' => 'Comment l\'EuroE est généré et pourquoi son coût n\'est pas "automatique"',
                    'lead' => 'Chaque mois, le droit EuroE est calculé à partir des revenus du mois précédent du foyer, puis versé dans un wallet social rattaché à la banque principale.',
                    'steps' => [
                        [
                            'title' => '1. Calcul mensuel',
                            'body' => 'Le système regarde les revenus du mois précédent et ajuste la dotation EuroE. Le moteur d\'éligibilité est auditable et conforme à l\'AI Act.',
                        ],
                        [
                            'title' => '2. Versement wallet',
                            'body' => 'L\'EuroE est versé sur un wallet bancaire sécurisé pour payer les besoins essentiels. Les banques gérent les wallets comme elles gérent les comptes.',
                        ],
                        [
                            'title' => '3. Circulation utile',
                            'body' => 'Tant qu\'il circule entre citoyens, commerces et services européens, il soutient l\'activité intérieure. Les commercants l\'acceptent via les terminaux existants (NFC, QR code).',
                        ],
                        [
                            'title' => '4. Cout visible a la sortie',
                            'body' => 'Le coût en euro classique apparaît surtout lors des conversions vers l\'exterieur du circuit EuroE, via le mecanisme de reverse waterfall.',
                        ],
                    ],
                    'table' => [
                        'title' => 'Lecture simple du cout EuroE',
                        'caption' => 'Le coût visible en euro classique dépend surtout des sorties du circuit.',
                        'rowLabel' => 'Moment',
                        'columns' => [
                            ['key' => 'flow', 'label' => 'Ce qui se passe'],
                            ['key' => 'cost', 'label' => 'Effet coût'],
                        ],
                        'rows' => [
                            [
                                'label' => 'Entree du droit',
                                'flow' => 'Dotation EuroE versee sur wallet social',
                                'cost' => 'Pas de coût de conversion immediat',
                            ],
                            [
                                'label' => 'Circulation intérieure',
                                'flow' => 'Paiements dans le réseau de biens et services essentiels',
                                'cost' => 'Soutien de la demande locale (multiplicateur x2,3)',
                            ],
                            [
                                'label' => 'Sortie / conversion',
                                'flow' => 'Conversion professionnelle EuroE -> euro classique',
                                'cost' => 'Le coût principal se materialise ici (environ 25 % du total)',
                            ],
                        ],
                    ],
                    'noteLabel' => 'Repere cout :',
                    'note' => 'Le vrai coût se voit surtout quand l\'argent sort du circuit europeen, pas seulement quand il est crée.',
                ],
                'noteLabel' => 'Idee cle :',
                'note' => 'Plus l\'EuroE reste dans l\'economie réelle européenne, plus il soutient l\'activité interieure.',
            ],

            // -- How it works --------------------------------------------------------
            'howItWorks' => [
                'eyebrow' => 'Les 5 questions',
                'title' => 'Qui, quoi, pourquoi, comment, quand ?',
                'lead' => 'La version grand public tient en cinq réponses courtes.',
                'points' => [
                    [
                        'title' => 'Qui ?',
                        'body' => 'Tous les citoyens européens. Le socle est universel mais dégressif : plus vos revenus sont élevés, moins l\'EuroE est important. Ceux qui en ont le plus besoin : familles, jeunes actifs, personnes agées, parents isolés.',
                    ],
                    [
                        'title' => 'Quoi ?',
                        'body' => 'Un euro complémentaire d\'usage social : l\'EuroE. Parité 1:1 avec l\'euro, non speculatif, utilisable uniquement pour les besoins essentiels.',
                    ],
                    [
                        'title' => 'Pourquoi ?',
                        'body' => 'Parce que l\'IA transforme l\'économie plus vite que les gens ne peuvent se reconvertir. Le socle vital donne le temps de s\'adapter sans tomber dans la precaritée.',
                    ],
                    [
                        'title' => 'Comment ?',
                        'body' => 'Via un wallet bancaire securisé, distribué par les banques existantes. Les paiements se font comme aujourd\'hui : carte, NFC, QR code. Tout tourne sur des rails européens (TIPS, TARGET, Wero).',
                    ],
                    [
                        'title' => 'Quand ?',
                        'body' => 'Par étapes : d\'abord cadrage et coalition (phase 0), puis pilotes sociaux (phase 1), réseau agrée (phase 2), montée en charge européenne (phase 3).',
                    ],
                ],
            ],

            // -- Usage ---------------------------------------------------------------
            'usage' => [
                'eyebrow' => 'Dans la vraie vie',
                'title' => 'A quoi sert l\'EuroE ?',
                'lead' => 'L\'EuroE est un euro complémentaire d\'usage social. Il couvre les besoins fondamentaux pour maintenir la continuitée de vie.',
                'needs' => [
                    'Logement',
                    'Energie',
                    'Alimentation',
                    'Transport essentiel',
                    'Communication de base',
                    'Sante',
                ],
                'points' => [
                    [
                        'title' => 'Qui est concerné ?',
                        'body' => 'Tous les citoyens européens. Le droit est universel et calcule au niveau du foyer : base de 1 500 EUR par adulte équivalent, ajustée selon la taille du foyer et le coefficient territorial.',
                    ],
                    [
                        'title' => 'Comment on l\'utilise ?',
                        'body' => 'Via un wallet géré par sa banque, avec les moyens de paiement européens existants. L\'experience est identique à un paiement classique : le commercant ne voit pas de différence.',
                    ],
                    [
                        'title' => 'Deux sphères, une liberté de choix',
                        'body' => 'Le Civitalisme distingue la sphère vitale (logement, énergie, alimentation - garantie en EuroE) et la sphère de progression (épargne, investissement, luxe - en euro classique, libre et concurrentielle).',
                    ],
                    [
                        'title' => 'Pas une crypto, pas une aide',
                        'body' => 'C\'est un droit, pas une aide. Comme l\'école publique ou la sécurité, le socle vital est une infrastructure commune. On ne dit pas qu\'un enfant scolarisé est "assisté" ; on dit qu\'il exerce un droit.',
                    ],
                ],
            ],

            // -- Work ----------------------------------------------------------------
            'work' => [
                'eyebrow' => 'Travail et dignité',
                'title' => 'La dignité comme fondement, le travail comme choix',
                'lead' => 'Dans le modele actuel, la dignité materielle est conditionnée au travail. Si le travail se rarefie sous l\'effet de l\'IA, conditionner la dignité au travail revient à condamner une partie croissante de la population.',
                'quote' => 'Le travail contraint ne donne pas de sens ; il donne un revenu. Le travail choisi donne du sens. Ce projet permet cette distinction.',
                'benefits' => [
                    'Plus de confort',
                    'Plus de liberte',
                    'Plus d\'épargne',
                    'Plus de projets',
                    'Plus de mobilité',
                    'Plus de temps pour se former',
                ],
                'points' => [
                    [
                        'title' => 'Base protegée, progression libre',
                        'body' => 'Le socle evite la chute. Le travail reste la clé pour progresser, gagner en confort et construire ses projets. La franchise de 300 EUR par actif garantit que travailler paie toujours.',
                    ],
                    [
                        'title' => 'Se reconvertir sans tomber',
                        'body' => 'L\'IA n\'est pas qu\'une menace, c\'est aussi le meilleur outil de formation jamais invente. Avec le socle vital, chacun peut prendre le temps de se former aux nouveaux metiers sans risquer la precarité.',
                    ],
                    [
                        'title' => 'Le temps comme ressource',
                        'body' => 'Le véritable luxe de la transition IA est le temps. Le temps de se former, de comprendre les nouveaux outils, d\'inventer de nouveaux metiers. Le socle vital achète ce temps.',
                    ],
                ],
            ],

            // -- Business impact -----------------------------------------------------
            'business' => [
                'eyebrow' => 'Economie reelle',
                'title' => 'Pourquoi les entreprises y gagnent aussi',
                'lead' => 'L\'EuroE crée un plancher de demande intérieure stable. Pour les PME, qui representent 65 % de l\'emploi européen, c\'est un filet de sécurité économique autant que social.',
                'points' => [
                    [
                        'title' => 'Un plancher de demande garanti',
                        'body' => 'En periode de recession ou de choc technologique, les ménages protéges par le socle vital continuent à consommer. Les commercants subissent moins les chutes brutales.',
                    ],
                    [
                        'title' => 'Reconversion financée',
                        'body' => 'Un salarié dont le poste est automatisé conserve sa dignité materielle pendant qu\'il se forme. Reconvertir coûte moins cher que de remplacer (50 a 200 % du salaire annuel).',
                    ],
                    [
                        'title' => 'Un réseau commercial européen',
                        'body' => 'Les commercants qui acceptent l\'EuroE accèdent à un ecosysteme fidèle et prévisible. C\'est comparable aux tickets-restaurant, mais à l\'echelle européenne.',
                    ],
                    [
                        'title' => 'Convertibilité garantie',
                        'body' => 'La parité 1:1 euroE/euro garantit aux entreprises que les euroE reçus ont une valeur réelle. Le mécanisme de reverse waterfall permet de rapatrier les fonds en euro classique.',
                    ],
                ],
            ],

            // -- Case study ----------------------------------------------------------
            'caseStudy' => [
                'eyebrow' => 'Cas concrets',
                'title' => 'Quatre profils dans une grande ville européenne',
                'summary' => 'Lecture rapide : avant le choc, après le choc, reponse EuroE, effet concret.',
                'metrics' => [
                    ['label' => 'Dotation EuroE totale (4 profils)', 'value' => '5 828 EUR / mois'],
                    ['label' => 'Sorties estimées vers euro classique', 'value' => '1 460 EUR / mois'],
                    ['label' => 'EuroE re-circule dans l\'économie locale', 'value' => '4 368 EUR / mois'],
                ],
                'table' => [
                    'title' => 'Montants et coûts (illustration pedagogique)',
                    'caption' => 'Montants mensuels indicatifs pour visualiser les variations individuelles et le coût global.',
                    'rowLabel' => 'Profil',
                    'columns' => [
                        ['key' => 'before', 'label' => 'Avant'],
                        ['key' => 'after', 'label' => 'Après le choc'],
                        ['key' => 'euroe', 'label' => 'EuroE après choc'],
                        ['key' => 'conversion', 'label' => 'Sortie estimée (coût visible)'],
                    ],
                    'rows' => [
                        [
                            'label' => 'Famille avec enfants',
                            'before' => '5 527,5 EUR',
                            'after' => '4 732,5 EUR',
                            'euroe' => '2 883 EUR',
                            'conversion' => '620 EUR',
                        ],
                        [
                            'label' => 'Jeune actif',
                            'before' => '1 487,6 EUR',
                            'after' => '2 200,8 EUR avec emploi',
                            'euroe' => '651 EUR',
                            'conversion' => '140 EUR',
                        ],
                        [
                            'label' => 'Personne âgée',
                            'before' => '1 200 EUR',
                            'after' => '1 487,6 EUR avec EuroE',
                            'euroe' => '288 EUR',
                            'conversion' => '60 EUR',
                        ],
                        [
                            'label' => 'Parent isolé',
                            'before' => '4 021,4 EUR',
                            'after' => '2 771 EUR',
                            'euroe' => '2 006 EUR',
                            'conversion' => '640 EUR',
                        ],
                    ],
                ],
                'profiles' => [
                    [
                        'title' => 'Famille avec enfants',
                        'before' => 'Deux revenus, budget serré mais stable.',
                        'after' => 'Perte d\'un salaire à cause de l\'automatisation. Dépenses vitales inchangées.',
                        'response' => 'L\'EuroE augmente pour couvrir le socle vital. Le foyer garde le temps de se réorganiser.',
                        'impact' => 'Le foyer évite l\'effondrement du niveau de vie et peut financer une formation IA.',
                    ],
                    [
                        'title' => 'Jeune actif',
                        'before' => 'Entrée sur le marché du travail, revenus irreguliers.',
                        'after' => 'Contrat interrompu par l\'automatisation ou transition vers une formation.',
                        'response' => 'L\'EuroE reste present le temps du rebond. L\'IA sert d\'outil de formation personnalise.',
                        'impact' => 'Travailler paie toujours, mais la transition est moins brutale. Reconversion en 6 a 18 mois.',
                    ],
                    [
                        'title' => 'Personne âgée',
                        'before' => 'Pension modeste, budget sensible aux hausses de prix.',
                        'after' => 'Hausse des dépenses vitales ou imprévu de sante.',
                        'response' => 'Complément EuroE ciblé sur les besoins essentiels.',
                        'impact' => 'Meilleure continuité de vie au quotidien, sans dépendre d\'aides fragmentées.',
                    ],
                    [
                        'title' => 'Parent isolé',
                        'before' => 'Un seul revenu pour tout le foyer.',
                        'after' => 'Baisse d\'activité liée à l\'IA, charges enfants inchangées.',
                        'response' => 'L\'EuroE monte plus fortement pendant le choc. Les comptes enfants sont protégés.',
                        'impact' => 'Protection renforcée de l\'enfant et du foyer. Le compte enfant empêche la captation.',
                    ],
                ],
                'effects' => [
                    'L\'EuroE n\'efface pas les difficultés ou les inconforts. Il reduit la violence du choc.',
                    'Son rôle est de protéger le vital pendant que le revenu classique continue de faire vivre le reste de l\'économie.',
                ],
            ],

            // -- Privacy -------------------------------------------------------------
            'privacy' => [
                'eyebrow' => 'Protéger sans surveiller',
                'title' => 'Une protection utile, sans suivi permanent des personnes',
                'lead' => 'Le but n\'est pas de surveiller les gens, mais de protéger la continuité de vie. L\'architecture repose sur trois plans de données strictement séparés.',
                'points' => [
                    [
                        'title' => 'Données d\'identité',
                        'body' => 'Nom, date de naissance, IBAN, donnees KYC. Accessibles uniquement par la banque ou le PSP, sous base légale. Jamais exposées sur la chaine.',
                    ],
                    [
                        'title' => 'Données de paiement',
                        'body' => 'Adresses pseudonymes dérivées, soldes et transferts. Accessibles au registre central et aux banques selon besoin. Aucun lien direct avec l\'identité civile.',
                    ],
                    [
                        'title' => 'Données de supervision',
                        'body' => 'Agrégats, alertes, indicateurs de risque. Accessibles uniquement à l\'opérateur central et aux autorités habilitées. Pas de profilage individuel.',
                    ],
                    [
                        'title' => 'Recours et transparence',
                        'body' => 'Les régles sont publiques. Les citoyens doivent pouvoir contester une décision. Conformité RGPD, droit à l\'oubli, minimisation des données.',
                    ],
                ],
            ],

            // -- FAQ -----------------------------------------------------------------
            'faq' => [
                [
                    'question' => 'Est-ce une crypto ou un bitcoin europeen ?',
                    'answer' => 'Non. L\'EuroE est un euro complementaire d\'usage social, a parite 1:1 avec l\'euro, non speculatif, distribue par les banques. Il tourne sur un registre web3 permissionne, pas sur une blockchain publique.',
                ],
                [
                    'question' => 'Est-ce que cela remplace l\'euro ?',
                    'answer' => 'Non. L\'euro classique reste la monnaie principale pour les salaires, l\'épargne, l\'investissement et les échanges internationaux. L\'EuroE couvre uniquement la sphère vitale.',
                ],
                [
                    'question' => 'Pourquoi continuer à travailler si un socle existe ?',
                    'answer' => 'Parce que le socle couvre le vital, pas le confort. La franchise de 300 EUR par actif garantit que travailler paie toujours. Et la motivation humaine ne se reduit pas à la peur de la faim : curiosité, reconnaissance, ambition restent des moteurs puissants.',
                ],
                [
                    'question' => 'C\'est de l\'assistanat généralisé ?',
                    'answer' => 'C\'est un droit, pas une aide. Comme l\'école publique ou la sécurité, le socle vital est une infrastructure commune. On ne dit pas qu\'un enfant scolarisé est "assisté" ; on dit qu\'il exerce un droit.',
                ],
                [
                    'question' => 'Qui va payer ?',
                    'answer' => 'Le cout réel n\'est pas l\'émission d\'EuroE mais la conversion en euro. Cette conversion est financée par un mix : redéploiement social, contribution de conversion, et à terme, dividende technologique. Et chaque euro d\'EuroE génère environ 2,30 EUR d\'activité intérieure.',
                ],
                [
                    'question' => 'Cela va créer de l\'inflation ?',
                    'answer' => 'L\'EuroE circule dans un périmètre contrôlé et ne remplace pas l\'euro. Le risque inflationniste est réel mais pilotable par des plafonds, des taux de fuite et une surveillance sectorielle.',
                ],
                [
                    'question' => 'Comment les entreprises y gagnent ?',
                    'answer' => 'L\'EuroE crée un plancher de demande stable pour les secteurs essentiels. Les PME qui acceptent l\'EuroE accèdent à un reseau commercial fidèle, avec une convertibilité garantie à parité 1:1.',
                ],
                [
                    'question' => 'Le projet arrive-t-il d\'un coup ?',
                    'answer' => 'Non. Le projet avance en 5 phases : cadrage, sandbox, pilote operationnel, extension, puis généralisation à l\'echelle de l\'UE. Chaque étape est testable et démocratiquement controlée.',
                ],
            ],

            // -- Conclusion ----------------------------------------------------------
            'conclusion' => [
                'title' => 'En une phrase',
                'body' => 'Le Civitalisme ajoute une couche de securite economique pour protéger le vital a l\'ere de l\'IA, sans remplacer l\'euro classique, le système bancaire ni le travail. L\'Europe a inventée l\'Etat-providence au XXe siecle pour répondre à l\'industrialisation sauvage. Ce projet propose d\'inventer le Socle vital au XXIe siècle pour répondre à l\'automatisation cognitive.',
            ],
        ];
    }

    /**
     * Content for the institutional / technical page (cadre institutionnel).
     *
     * Audience: economists, legal experts, policy makers, entrepreneurs and technical reviewers.
     *
     * @return array<string, mixed>
     */
    public function technicalPage(): array
    {
        return [
            // -- Hero ----------------------------------------------------------------
            'hero' => [
                'eyebrow' => 'Cadre technique et institutionnel',
                'title' => 'Cadre institutionnel du Civitalisme',
                'subtitle' => 'Doctrine, architecture monetaire, gouvernance et mise en oeuvre',
                'lead' => 'L\'intelligence artificielle generative provoque une dissociation structurelle entre production de richesse et distribution de revenus par le travail. Le capitalisme ne dispose d\'aucun mecanisme endogene pour redistribuer les gains de productivite lorsque le travail humain cesse d\'etre le vecteur principal de creation de valeur. Le Civitalisme propose de resoudre cette contradiction avant qu\'elle ne devienne une fracture sociale irreversible.',
                'highlights' => [
                    'Infrastructure web3 bancaire europeenne permissionnee',
                    'Gouvernance democratique et conformite AI Act',
                    'Double circuit monetaire adosse a la securite economique',
                ],
            ],
            'heroPanel' => [
                'label' => 'Principes',
                'title' => 'Un projet articule, pas un slogan',
                'body' => 'Le Civitalisme ne pretend ni remplacer l\'euro, ni supprimer les impots, ni confier tout le pouvoir a la BCE. Il propose un cadre institutionnel progressif pour redistribuer une partie du dividende technologique.',
                'points' => [
                    'Euro classique pour le marche general ; EuroE pour le Socle vital europeen.',
                    'Role monetaire de securite pour la BCE et role normatif pour les institutions democratiques.',
                    'Cadre compatible avec souverainete europeenne des paiements, conformite AI Act et DORA.',
                ],
            ],

            // -- Introduction --------------------------------------------------------
            'introduction' => [
                'eyebrow' => 'Introduction',
                'title' => 'Une doctrine pour l\'ère de l\'automatisation',
                'lead' => 'L\'IA générative n\'automatise plus seulement les taches répétitives : elle pénètre les metiers intellectuels, créatifs et decisionnels. Le modèle capitaliste classique repose sur un circuit fermé (travail -> salaire -> consommation -> profit -> investissement -> travail). Lorsque l\'IA remplace le travail sans créer d\'emplois équivalents, ce circuit se brise.',
                'metrics' => [
                    ['label' => 'Emplois exposés à l\'IA (OCDE/FMI)', 'value' => '40 a 60 % dans les economies avancées'],
                    ['label' => 'Duree de la transition IA', 'value' => '3 a 10 ans vs 60-80 ans (industrielle)'],
                    ['label' => 'PME dans l\'emploi européen', 'value' => '65 % du secteur prive'],
                    ['label' => 'Entreprises UE ayant integré l\'IA', 'value' => 'Environ 13 % (estimation 2025)'],
                ],
            ],

            // -- Diagnostic ----------------------------------------------------------
            'diagnostic' => [
                'eyebrow' => 'Diagnostic',
                'title' => 'Une tension structurelle sans précédent',
                'lead' => 'Pour la première fois, une technologie est capable de produire du texte, du code, des images, des analyses et des stratégies à un coût marginal proche de zero. Elle ne remplace pas seulement le bras du travailleur ; elle concurrence son cerveau.',
                'points' => [
                    ['title' => 'Circuit brise', 'body' => 'Si toutes les entreprises reduisent leurs effectifs simultanement grace a l\'IA, qui achete les produits ? Henry Ford avait compris ce paradoxe en 1914. L\'IA pose le même probleme à une echelle ou aucun entrepreneur individuel ne peut le resoudre seul.'],
                    ['title' => 'Prosperite sans distribution', 'body' => 'Le PIB mondial peut continuer a croitre, les entreprises technologiques affichent des profits records. Mais cette richesse se concentre dans les mains de ceux qui possedent le capital, les données et les infrastructures d\'IA.'],
                    ['title' => 'Vitesse de transition', 'body' => 'La revolution industrielle s\'est deployee sur 60 a 80 ans, la revolution numérique sur 20 ans. La révolution IA generative transforme les modes de production en 3 a 5 ans.'],
                    ['title' => 'Risque democratique', 'body' => 'Les sociétés où une majorité perd son accès à la dignité materielle deviennent instables. La montée des populismes en Europe est déjà un symptôme d\'une redistribution insuffisante.'],
                ],
            ],

            // -- Doctrine ------------------------------------------------------------
            'doctrine' => [
                'eyebrow' => 'Doctrine',
                'title' => 'Ni spéculation, ni planification totale : une troisième voie',
                'lead' => 'Le Civitalisme distingue deux sphères. La sphère vitale (logement, alimentation, énérgie, transport, santé, éducation) garantie par le socle vital en EuroE. La sphère de progression (épargne, investissement, luxe, entrepreneuriat) en euro classique, régie par la concurrence et la liberté économique. Cette distinction remonte à Aristote, qui séparait déjà la chrématistique (gestion) naturelle de la chrématistique commerciale.',
                'table' => [
                    'columns' => [
                        ['key' => 'function', 'label' => 'Fonction'],
                        ['key' => 'scope', 'label' => 'Périmètre'],
                        ['key' => 'limits', 'label' => 'Garde-fous'],
                    ],
                    'rows' => [
                        [
                            'label' => 'Euro classique',
                            'function' => 'Monnaie générale du marché',
                            'scope' => 'Salaires, investissement, épargne, prix libres et échanges internationaux.',
                            'limits' => 'Conserve son rôle général sans être remplacé par l\'EuroE.',
                        ],
                        [
                            'label' => 'EuroE',
                            'function' => 'Euro social numérique complémentaire',
                            'scope' => 'Socle vital européen, continuité materielle et circulation essentielle agréée.',
                            'limits' => 'Non spéculatif, non patrimonial, parité 1:1, périmètre d\'usage contrôlé.',
                        ],
                    ],
                ],
                'points' => [
                    ['title' => 'Sécurité économique', 'body' => 'La doctrine traite la distribution du revenu comme une condition de stabilite productive et democratique, pas comme une concession sociale.'],
                    ['title' => 'Marché conserve', 'body' => 'Le marché, l\'initiative privée et l\'investissement restent actifs dans le circuit de l\'euro classique. Le Civitalisme n\'abolit ni le marché ni la propriété privée.'],
                    ['title' => 'Infrastructure européenne', 'body' => 'L\'UE dispose deja d\'un cadre juridique, d\'une monnaie commune, d\'un marché unique et d\'une trajectoire vers l\'euro numérique. Le Civitalisme s\'adosse à ces acquis.'],
                ],
            ],

            // -- Architecture --------------------------------------------------------
            'architecture' => [
                'eyebrow' => 'Architecture technique',
                'title' => 'Plateforme web3 permissionnée',
                'lead' => 'L\'EuroE repose sur un registre web3 permissionné compatible EVM, à noeuds autorisés, operé sous gouvernance européenne et distribué via les banques et PSP (Prestataires de services de paiement). L\'architecture comprend 8 couches fonctionnelles, de la gouvernance a l\'observabilité.',
                'points' => [
                    ['title' => 'Registre permissionné', 'body' => 'Compatible EVM pour bénéficier d\'un écosystème d\'outils mature, tout en conservant une gouvernance fermée. L\'EBSI offre un précédent europeen utile.'],
                    ['title' => 'Wallets bancaires custodial', 'body' => 'Gérés par les banques et PSP autorisés. Identifiant client non exposé sur la chaine, adresses pseudonymes dérivées, module de signature securisé (HSM/secure element).'],
                    ['title' => 'Moteur d\'eligibilite conforme AI Act', 'body' => 'Calcul automatique des droits SVG, classifie haut risque. Transparence, explicabilite, intervention humaine, audit continu et documentation technique complète.'],
                    ['title' => 'Conversion a parite 1:1', 'body' => 'Waterfall (funding depuis le compte bancaire) et reverse waterfall (rapatriement par les commercants). Plafonds dynamiques en cas de stress. Adosse aux rails SCT Inst, TIPS, TARGET et EPI/Wero.'],
                    ['title' => 'APIs entreprises normalisees', 'body' => 'API de caisse (NFC, QR code, < 2s), module comptable (export standards, TVA integre), dashboard commercant (temps reel, alertes), API de conversion (reverse waterfall, délais garantis).'],
                    ['title' => 'Cybersecurite DORA', 'body' => 'Gestion des clés dans des HSM européens, authentification forte, ségmentation réseau stricte, journalisation inviolable, PRA/PCA multi-sites, tests de pénétration.'],
                ],
            ],

            // -- Socle vital ---------------------------------------------------------
            'socle' => [
                'eyebrow' => 'Socle vital européen',
                'title' => 'Le panier vital prioritaire',
                'lead' => 'Le Socle vital européen couvre prioritairement les besoins fondamentaux. Il vise à empecher une chute brutale sous le seuil matériel de dignité, tout en préservant l\'incitation au travail par une franchise d\'activité et un retrait progressif.',
                'needs' => [
                    'Logement',
                    'Eau et chauffage',
                    'Alimentation',
                    'Transport essentiel',
                    'Communication de base',
                    'Santé',
                    'Education et formation',
                ],
            ],

            // -- Calculation ---------------------------------------------------------
            'calculation' => [
                'eyebrow' => 'Règle de calcul',
                'title' => 'Une formule lisible et paramétrable',
                'lead' => 'Le modèle repose sur une base adulte équivalent, une taille équivalente du foyer, un coefficient territorial, d\'eventuelles majorations de vulnérabilité, une franchise d\'activité et un retrait progressif de l\'EuroE à mesure que les revenus remontent.',
                'equations' => [
                    'Socle vital du foyer = base x taille équivalente x coefficient territorial + majorations',
                    'EuroE du foyer = socle vital - retrait progressif appliqué au revenu ajusté',
                ],
                'parameters' => [
                    'Base adulte equivalent : 1 500 EUR',
                    'Premier adulte = 1,0',
                    'Autre personne de 14 ans ou plus = 0,5',
                    'Enfant de moins de 14 ans = 0,3',
                    'Franchise d\'activite : 300 EUR par actif',
                    'Taux de retrait : 0,7',
                    'Coût moyen de formation IA par salarié : 2 000 à 8 000 EUR',
                    'Délai moyen de reconversion : 6 à 18 mois selon le secteur',
                ],
            ],

            // -- Case study ----------------------------------------------------------
            'caseStudy' => [
                'eyebrow' => 'Cas type',
                'title' => 'Famille dans une grand ville européenne confrontée à un choc de revenu lié à l\'IA',
                'summary' => '2 adultes actifs, 1 jeune de 17 ans, 1 enfant de 8 ans. Revenu net mensuel du foyer avant choc : 3 800 EUR. Revenu net mensuel après perte d\'un emploi (automatisation IA) : 1 850 EUR.',
                'household' => [
                    'Base adulte equivalent : 1 500 EUR',
                    'Taille equivalente du foyer : 2,3',
                    'Coefficient territorial Capital européenne : 1,15',
                    'Franchise d\'activite : 300 EUR par actif',
                    'Taux de retrait : 0,7',
                ],
                'formulas' => [
                    'Socle vital du foyer : 1 500 x 2,3 x 1,15 = 3 967,5 EUR',
                ],
                'before' => [
                    'Revenu ajuste = 3 800 - 600 = 3 200 EUR',
                    'EuroE du foyer = 3 967,5 - (0,7 x 3 200) = 1 727,5 EUR',
                    'Total foyer = 5 527,5 EUR',
                ],
                'after' => [
                    'Revenu ajuste = 1 850 - 300 = 1 550 EUR',
                    'EuroE du foyer = 3 967,5 - (0,7 x 1 550) = 2 882,5 EUR',
                    'Total foyer = 4 732,5 EUR',
                ],
                'effects' => [
                    'Maintien de la consommation locale malgré la perte d\'emploi.',
                    'Limitation de l\'effondrement de la demande intérieure.',
                    'Soutien indirect a l\'activité économique : multiplicateur interne de 2,30 EUR par euro emis.',
                    'Réduction du risque d\'impayes logement, energie et alimentation.',
                    'Role de stabilisateur automatique pendant le choc, le temps de la reconversion IA (6 a 18 mois).',
                ],
                'conclusion' => 'Le cout brut ne suffit pas comme lecture. L\'analyse doit intégrer les retombées positives sur la demande, l\'activite, la stabilite des foyers et les finances publiques secondaires.',
            ],

            // -- Stakeholders --------------------------------------------------------
            'stakeholders' => [
                'eyebrow' => 'Ce qu\'attend chaque acteur',
                'title' => 'Les questions à traiter explicitement',
                'lead' => 'Un cadre institutionnel crédible doit parler a la fois aux economistes, aux decideurs, aux juristes, aux sociologues, aux entrepreneurs, aux philosophes et aux institutions europeennes.',
                'points' => [
                    [
                        'title' => 'Ce qu\'attend un economiste',
                        'body' => 'Des chiffres, des mecanismes et des garde-fous quantifies.',
                        'points' => [
                            'Cout brut et cout net : chaque euro d\'EuroE genere environ 2,30 EUR d\'activite interieure (recirculation 75 %).',
                            'Stabilisateur automatique : l\'EuroE augmente quand les revenus baissent, sans intervention legislative.',
                            'Impact sur l\'inflation : perimetre controle, plafonds dynamiques, surveillance sectorielle.',
                            'Financement mixte : redeploiement social, contribution de conversion, dividende technologique a terme.',
                            'Franchise d\'activite de 300 EUR : l\'incitation au travail est preservee.',
                            'Multiplicateur interne et effet sur la demande interieure europeenne.',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Economie (PDF)', 'href' => '/documents/SVG-Partie-Economie.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attend un responsable politique',
                        'body' => 'Un deploiement progressif, des benefices visibles et un calendrier tenable.',
                        'points' => [
                            'Qui beneficie en priorite : les foyers exposes aux chocs de revenu lies a l\'IA.',
                            'Demarrage sans choc : phase pilote sur des territoires volontaires et des foyers diversifies.',
                            'Benefices visibles : stabilisation de la demande locale, reduction des impayes, formation financee.',
                            'Trajectoire : 5 phases sur 10 ans, de la doctrine a la generalisation UE.',
                            'Souverainete : recours prioritaire aux rails europeens (TIPS, TARGET, Wero).',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Economie (PDF)', 'href' => '/documents/SVG-Partie-Economie.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attend un sociologue',
                        'body' => 'Dignité, non-stigmatisation et protection des plus vulnérables.',
                        'points' => [
                            'Non-stigmatisation : experience de paiement identique, discrétion d\'usage, droit universel.',
                            'Protection des enfants : comptes protégés, restrictions d\'usage, contrôles dédiés.',
                            'Equilibre autonomie/contrôle : données minimales, recours possibles, RGPD.',
                            'Place du travail : la dignité est un droit, le travail est un choix valorisé, pas une condition de survie.',
                            'Cohésion sociale : prévenir la fracture entre une société de detenteurs d\'IA et une majorité precarisée.',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Sociologie (PDF)', 'href' => '/documents/SVG-Parite-Sociologie.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attend un juriste ou régulateur',
                        'body' => 'Une base légale solide et une conformitée aux cadres européens existants.',
                        'points' => [
                            'Base juridique : l\'EuroE n\'est pas une monnaie souveraine concurrente mais un instrument social en euro.',
                            'Articulation digital euro : compatible avec la trajectoire BCE, wallets custodial cohérents.',
                            'Protection des données : 3 plans séparés (identité, paiement, supervision), conformité RGPD.',
                            'Conformité AI Act : moteur d\'éligibilité classifiée haut risque, transparence et intervention humaine.',
                            'Conformité DORA : socle cybersecurité d\'infrastructure financière critique.',
                            'Critères d\'agrement objectifs : conditions publiques, non discriminatoires, auditables.',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Juridique (PDF)', 'href' => '/documents/SVG-Partie-Juridique.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attend un entrepreneur ou un chef de PME',
                        'body' => 'Un avantage concurrentiel, pas une contrainte supplémentaire.',
                        'points' => [
                            'Plancher de demande : l\'EuroE injecte un flux prévisible dans les secteurs essentiels (alimentation, logement, énergie, transport, santé).',
                            'Reconversion financée : un salarié en formation IA conserve sa dignitée materielle. Reconvertir coûte moins cher que remplacer (50-200 % du salaire).',
                            'Reseau commercial fidèle : accepter l\'EuroE = accéder à un ecosystème européen structuré et prévisible.',
                            'Convertibilité garantie : parité 1:1, reverse waterfall, délais de conversion garantis, intégration aux logiciels de caisse existants.',
                            'Avantages fiscaux envisageables : crédit d\'impot recirculation, exoneration reconversion, label "Entreprise euroE".',
                            'Impact sectoriel : commerce, batiment, santé, transport, éducation, agriculture, services financiers, industrie.',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Entrepreneuriale (PDF)', 'href' => '/documents/SVG-Partie-Entrepreneuriale.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attend un philosophe, un modérateur ou un journaliste',
                        'body' => 'Une vision cohérente de la dignité, du travail et de la civilisation face à l\'IA.',
                        'points' => [
                            'Troisième voie : ni communisme, ni capitalisme debridé. Le Civitalisme ne propose pas d\'abolir le marché ni la propriété privée.',
                            'Dignité inconditionnelle : la dignité materielle est un droit, le travail est un choix valorisant. L\'inverse condamne une majorité croissante.',
                            'Continuité civilisationnelle : l\'EuroE est un pont temporel qui donne aux individus, entreprises et institutions le temps de s\'adapter.',
                            'Filiation intellectuelle : Aristote (chrématistique), Schumpeter (destruction créatrice), Keynes (possibilités economiques), Acemoglu (automatisation et nouvelles tâches).',
                            'Nouveau contrat social : sécurité vitale inconditionnelle, travail valorise mais non contraint, partage du dividende technologique, transition accompagnée.',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Philosophique (PDF)', 'href' => '/documents/SVG-Partie-Philosophique.pdf'],
                        ],
                    ],
                    [
                        'title' => 'Ce qu\'attendrait un Parlementaire européen',
                        'body' => 'Un cadre législatif clair, subsidiaire et progressif.',
                        'points' => [
                            'Base juridique claire : instrument social en euro, pas une monnaie souveraine concurrente.',
                            'Subsidiarité et proportionnalité : execution sociale par les Etats, cadre commun européen.',
                            'Etude d\'impact : simulations quantifiées (cf. rapport economique), cas types territorialises.',
                            'Gouvernance démocratique : séparation nette entre BCE (rail monétaire) et institutions démocratiques (régles sociales).',
                            'Feuille de route en 5 phases : cadrage, sandbox, pilote opérationnel, extension, généralisation.',
                            'Conformité régulatoire : AI Act, DORA, RGPD, MiCA (distinction EMT/token de paiement).',
                        ],
                        'documents' => [
                            ['label' => 'Lire la partie Politique (PDF)', 'href' => '/documents/SVG-Partie-Politique.pdf'],
                            ['label' => 'Lire la partie Juridique (PDF)', 'href' => '/documents/SVG-Partie-Juridique.pdf'],
                            ['label' => 'Lire la partie Technique (PDF)', 'href' => '/documents/SVG-Partie-Technique.pdf'],
                        ],
                    ],
                ],
            ],

            // -- Governance ----------------------------------------------------------
            'governance' => [
                'eyebrow' => 'Gouvernance',
                'title' => 'Qui fait quoi dans le système',
                'lead' => 'La credibilité dce p^rojet repose sur une répartition nette des rôles entre institutions monétaire, institutions démocratiques, opérateurs techniques et citoyens. Chaque acteur a un périmètre défini et des mécanismes de redevabilité.',
                'actors' => [
                    [
                        'title' => 'BCE / Eurosysteme',
                        'body' => 'Rail monétaire public, sécurité, interoperabilité, stabilité et intégrité du système. Opère le registre permissionné et les smart contracts de gouvernance.',
                    ],
                    [
                        'title' => 'Institutions démocratiques',
                        'body' => 'Définition des régles sociales, du panier vital, des régles budgetaires et des équilibres du système. Le Parlement européen vote le cadre, les Etats executent.',
                    ],
                    [
                        'title' => 'Agence d\'agrément / audit',
                        'body' => 'Contrôle des opérateurs, audit des usages, surveillance des agréments et redevabilité technique. Evaluation indépendante à chaque phase.',
                    ],
                    [
                        'title' => 'Etats membres',
                        'body' => 'Execution sociale, articulation avec la justice, protection de l\'enfance, recours de proximité et coefficients territoriaux.',
                    ],
                    [
                        'title' => 'Banques / PSP',
                        'body' => 'Wallets custodial, onboarding KYC/AML, paiements, support client, réclamations et expérience utilisateur conforme aux régles publiques.',
                    ],
                    [
                        'title' => 'Entreprises agréées',
                        'body' => 'Acceptation de l\'EuroE via APIs normalisées, reconciliation comptable, recirculation dans le circuit européen. Label "Entreprise euroE" pour les marches publics.',
                    ],
                    [
                        'title' => 'Citoyens',
                        'body' => 'Information claire, capacité de recours, participation démocratique et contrôle du respect de leurs droits. Droit a l\'oubli et minimisation des données.',
                    ],
                ],
            ],

            // -- Risks ---------------------------------------------------------------
            'risks' => [
                'eyebrow' => 'Risques et garde-fous',
                'title' => 'Les objections doivent être traitées dès la conception',
                'items' => [
                    ['title' => 'Inflation sur les biens vitaux', 'body' => 'Encadrement du réseau agréé, observation de prix, clauses anti-captation et ajustement des agréments. Le périmètre de l\'EuroE est restreint aux biens essentiels.'],
                    ['title' => 'Désincitation au travail', 'body' => 'Franchise d\'activité de 300 EUR par actif, retrait progressif (taux 0,7) et maintien d\'un écart clair entre socle vital et revenus de progression.'],
                    ['title' => 'Cyber sécurité', 'body' => 'Architecture conforme DORA : HSM européens, authentification forte, segmentation réseau, journalisation inviolable, PRA/PCA multi-sites, red team exercises.'],
                    ['title' => 'Fraude et usurpation', 'body' => 'Identité robuste, IA de détection des schémas atypiques (risque limite AI Act), plafonds d\'usage, taux de faux positifs contrôle, recours humain.'],
                    ['title' => 'Risque de liquidité entreprises', 'body' => 'Reverse waterfall, plafonds adaptés aux volumes d\'activité, délais de conversion garantis, file d\'attente transparente en cas de stress.'],
                    ['title' => 'Surprix commercants', 'body' => 'Interdiction du surprix dans les conditions d\'agrément, contrôle par les autorités de la consommation.'],
                    ['title' => 'Captation des droits enfants', 'body' => 'Comptes protégés, restrictions d\'usage et mécanismes de contrôle spécifiques à la protection de l\'enfance.'],
                    ['title' => 'Surveillance excessive', 'body' => 'Trois plans de données strictement séparés (identité, paiement, supervision). Minimisation des données, traçabilité proportionnée, gouvernance démocratique des accès.'],
                    ['title' => 'Concurrence et aides d\'Etat', 'body' => 'Critères d\'agrément objectifs, cadre européen commun, non discriminatoire. Justification claire de l\'intérêt général.'],
                    ['title' => 'Complexité administrative PME', 'body' => 'APIs bancaires normalisées, intégration aux logiciels de caisse existants, accompagnement technique, dashboard commercant temps réel.'],
                ],
            ],

            // -- Roadmap -------------------------------------------------------------
            'roadmap' => [
                'eyebrow' => 'Roadmap',
                'title' => 'Une montée en charge progressive',
                'phases' => [
                    [
                        'phase' => 'Phase 0 - Cadrage',
                        'title' => 'Doctrine et coalition',
                        'body' => 'Structurer la doctrine, produire la base juridique, agreger une coalition d\'économistes, juristes, résponsables publics et acteurs techniques. Blueprint, modele de gouvernance, exigences cyber, data model, specifications APIs entreprises.',
                    ],
                    [
                        'phase' => 'Phase 1 - Sandbox',
                        'title' => 'Tester les briques critiques',
                        'body' => 'PoC web3, APIs bancaires et entreprises, moteur d\'éligibilité conforme AI Act, SOC pilote. Diagnostic IA interne pour les entreprises partenaires.',
                    ],
                    [
                        'phase' => 'Phase 2 - Pilote operationnel',
                        'title' => 'Paiements réels et pilotes territoriaux',
                        'body' => 'Registre operationnel, dashboards, procédures d\'incident, audit externe, module commercant. Integration du paiement euroE, lancement des parcours de formation IA.',
                    ],
                    [
                        'phase' => 'Phase 3 - Extension',
                        'title' => 'Montee en charge européenne',
                        'body' => 'Scaling, interopérabilité, industrialisation des contrôles, module reconversion IA. Redeploiement des equipes, optimisation du mix humain/IA.',
                    ],
                    [
                        'phase' => 'Phase 4 - Généralisation',
                        'title' => 'Dividende technologique mature',
                        'body' => 'Cadre d\'exploitation permanent, certification, contrôles continus, IA integree. Standard europeen, label entreprise euroE, accès marchés publics.',
                    ],
                ],
            ],

            // -- Conclusion ----------------------------------------------------------
            'conclusion' => [
                'title' => 'Une doctrine en construction',
                'body' => 'Le Civitalisme n\'est pas une réaction de peur face à l\'IA. C\'est une reponse lucide a une transformation dont la vitesse et la profondeur n\'ont pas de précédent historique. L\'Europe a inventée l\'Etat-providence au XXe siecle pour répondre aux ravages de l\'industrialisation sauvage. Le Civitalisme propose d\'inventer le Socle vital au XXIe siecle pour repondre aux ravages potentiels de l\'automatisation cognitive. La question n\'est pas de savoir si cette transition est necessaire, mais si nous aurons le courage politique de la mettre en oeuvre avant que la fracture ne devienne irréparable.',
            ],
        ];
    }
}
