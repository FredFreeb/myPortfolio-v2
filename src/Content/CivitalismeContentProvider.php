<?php

namespace App\Content;

final class CivitalismeContentProvider
{
    /**
     * @return array<string, mixed>
     */
    public function publicPage(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Version grand public • Lecture en 3 minutes',
                'title' => 'Civitalisme',
                'subtitle' => 'Sécuriser le vital dans une économie qui accélère',
                'lead' => 'Quand un revenu chute, les dépenses vitales restent là. Le Civitalisme propose un complément européen simple pour éviter les bascules brutales.',
                'highlights' => [
                    'Le Civitalisme ne remplace pas le système actuel. Il le complète.',
                    'L’euro classique reste la monnaie principale.',
                    'L’EuroE protège le vital.',
                ],
            ],
            'heroPanel' => [
                'label' => 'En 30 secondes',
                'title' => '3 repères clés',
                'body' => 'Tu peux lire cette page sans bagage économique. Le but est de comprendre vite.',
                'points' => [
                    'Complément, pas remplacement.',
                    'L’euro classique reste central.',
                    'Le travail reste essentiel.',
                    'Le projet avance par étapes.',
                ],
            ],
            'problem' => [
                'eyebrow' => 'Le problème',
                'title' => 'Une économie plus puissante, mais des vies plus fragiles',
                'lead' => 'La technologie progresse vite. La sécurité du quotidien, elle, peut décrocher vite.',
                'narrativeTitle' => 'Ce que vivent les foyers',
                'narrative' => [
                    'Un emploi peut disparaître en quelques semaines.',
                    'Le loyer, l’énergie et l’alimentation, eux, ne s’arrêtent jamais.',
                ],
                'points' => [
                    [
                        'title' => '💥 Revenu',
                        'body' => 'Perte de mission, baisse d’activité, contrat arrêté : le choc est rapide.',
                    ],
                    [
                        'title' => '🏠 Dépenses',
                        'body' => 'Logement, énergie, transport, alimentation : ces coûts restent fixes.',
                    ],
                    [
                        'title' => '⚠️ Risque',
                        'body' => 'Sans amortisseur, la précarité arrive vite et l’économie locale ralentit.',
                    ],
                ],
            ],
            'financing' => [
                'eyebrow' => 'Infographie',
                'title' => 'Comment l’argent est créé aujourd’hui',
                'lead' => 'Version ultra simple, sans jargon.',
                'narrativeTitle' => 'En 30 secondes',
                'narrative' => [
                    'Quand une banque accorde un crédit, elle crée de l’argent.',
                    'Cet argent circule dans l’économie.',
                    'Quand le crédit est remboursé, cet argent est détruit.',
                    'La BCE crée billets et pièces. Les banques, elles, ont des règles et des limites.',
                    'Aujourd’hui, la majorité de l’argent est créée par la dette.',
                    'Le Civitalisme ne remplace pas ce système. Il le complète.',
                ],
                'points' => [
                    [
                        'title' => '💳 Crédit',
                        'body' => 'Le crédit crée de l’argent pour financer des projets et des achats.',
                    ],
                    [
                        'title' => '🔁 Remboursement',
                        'body' => 'Le remboursement détruit progressivement cet argent.',
                    ],
                    [
                        'title' => '🏦 BCE',
                        'body' => 'La BCE émet les billets et pièces, et sécurise l’infrastructure monétaire.',
                    ],
                    [
                        'title' => '⚖️ Règles',
                        'body' => 'Les banques ne créent pas sans limite : elles doivent respecter un cadre strict.',
                    ],
                ],
                'infography' => [
                    'title' => 'Aujourd’hui vs Civitalisme',
                    'caption' => 'Deux logiques qui coexistent dans un même cadre européen.',
                    'rowLabel' => 'Logique',
                    'columns' => [
                        ['key' => 'flow', 'label' => 'Mécanisme'],
                        ['key' => 'purpose', 'label' => 'Objectif'],
                    ],
                    'rows' => [
                        [
                            'label' => 'Aujourd’hui',
                            'flow' => 'Crédit bancaire → dette',
                            'purpose' => 'Financer l’économie générale',
                        ],
                        [
                            'label' => 'Civitalisme',
                            'flow' => 'EuroE complémentaire',
                            'purpose' => 'Sécuriser les besoins essentiels',
                        ],
                    ],
                    'quote' => 'Le Civitalisme ne remplace pas le système actuel. Il le complète.',
                ],
            ],
            'howItWorks' => [
                'eyebrow' => 'Les 5 questions',
                'title' => 'Qui, quoi, pourquoi, comment, quand ?',
                'lead' => 'Le projet peut se résumer en cinq réponses simples.',
                'points' => [
                    [
                        'title' => 'Qui ?',
                        'body' => 'Les foyers exposés aux chocs de revenu : jeunes actifs, familles, parents isolés, retraités.',
                    ],
                    [
                        'title' => 'Quoi ?',
                        'body' => 'Un euro complémentaire appelé EuroE, dédié au vital.',
                    ],
                    [
                        'title' => 'Pourquoi ?',
                        'body' => 'Éviter qu’une baisse de revenu fasse basculer un foyer.',
                    ],
                    [
                        'title' => 'Comment ?',
                        'body' => 'Double circuit monétaire : euro classique pour le marché, EuroE pour la continuité de vie.',
                    ],
                    [
                        'title' => 'Quand ?',
                        'body' => 'Par étapes : pilotes, ajustements, extension progressive.',
                    ],
                ],
            ],
            'usage' => [
                'eyebrow' => 'Qui + usages',
                'title' => 'À quoi sert l’EuroE ?',
                'lead' => 'L’EuroE est un euro d’usage social. Il protège les besoins de base.',
                'needs' => [
                    '🏠 Logement',
                    '⚡ Énergie',
                    '🍞 Alimentation',
                    '🚇 Transport essentiel',
                    '📱 Communication de base',
                ],
                'points' => [
                    [
                        'title' => 'Pour les particuliers',
                        'body' => 'L’EuroE sert à tenir le vital. Il n’est pas conçu pour spéculer.',
                    ],
                    [
                        'title' => 'Pour les professionnels agréés',
                        'body' => 'Ils peuvent l’accepter et le convertir en euro classique dans un cadre contrôlé.',
                    ],
                    [
                        'title' => 'Cadre général',
                        'body' => 'Dans une grande ville européenne, le principe reste le même : euro principal + EuroE pour le vital.',
                    ],
                ],
            ],
            'work' => [
                'eyebrow' => 'Le travail',
                'title' => 'Pourquoi cela ne remplace pas le travail',
                'lead' => 'Le socle vital protège la base. Le travail reste la voie du projet de vie.',
                'quote' => 'Le travail reste essentiel.',
                'benefits' => [
                    'Plus de confort',
                    'Plus de liberté',
                    'Plus d’épargne',
                    'Plus de projets',
                    'Plus de mobilité',
                ],
                'points' => [
                    [
                        'title' => 'Base protégée',
                        'body' => 'Le socle évite la chute, il ne remplace pas un parcours professionnel.',
                    ],
                    [
                        'title' => 'Progression personnelle',
                        'body' => 'Compétences, responsabilités et salaires gardent leur rôle central.',
                    ],
                    [
                        'title' => 'Transitions plus stables',
                        'body' => 'Reconversion, formation, changement de poste : le passage est moins brutal.',
                    ],
                ],
            ],
            'business' => [
                'eyebrow' => 'Les entreprises',
                'title' => 'Pourquoi les entreprises peuvent aussi y gagner',
                'lead' => 'Un foyer plus stable, c’est aussi une économie plus stable.',
                'points' => [
                    [
                        'title' => 'Demande locale plus régulière',
                        'body' => 'Les commerces subissent moins de chutes brutales en période de choc.',
                    ],
                    [
                        'title' => 'Moins d’impayés',
                        'body' => 'Logement, énergie, alimentation : le risque d’impayés recule.',
                    ],
                    [
                        'title' => 'Changements plus soutenables',
                        'body' => 'Les transitions technologiques deviennent moins violentes pour les équipes.',
                    ],
                    [
                        'title' => 'Cap européen',
                        'body' => 'Le marché intérieur peut gagner en stabilité et en souveraineté des paiements.',
                    ],
                ],
            ],
            'caseStudy' => [
                'eyebrow' => 'Cas concrets',
                'title' => 'Quatre profils dans une grande ville européenne',
                'summary' => 'Le principe reste le même : quand le revenu baisse, l’EuroE amortit le choc vital.',
                'table' => [
                    'title' => 'Exemples simplifiés',
                    'caption' => 'Lecture rapide pour comprendre la logique.',
                    'rowLabel' => 'Profil',
                    'columns' => [
                        ['key' => 'before', 'label' => 'Avant'],
                        ['key' => 'after', 'label' => 'Après le choc'],
                        ['key' => 'response', 'label' => 'Réponse EuroE'],
                        ['key' => 'impact', 'label' => 'Ce que ça change'],
                    ],
                    'rows' => [
                        [
                            'label' => 'Famille',
                            'before' => '5 527,5 €',
                            'after' => '4 732,5 €',
                            'response' => 'l’EuroE augmente',
                            'impact' => 'évite l’effondrement',
                        ],
                        [
                            'label' => 'Jeune actif',
                            'before' => '1 487,6 €',
                            'after' => '2 200,8 € avec emploi',
                            'response' => 'l’EuroE baisse mais reste utile',
                            'impact' => 'travailler paie',
                        ],
                        [
                            'label' => 'Retraité',
                            'before' => '1 200 €',
                            'after' => '1 487,6 € avec EuroE',
                            'response' => 'complément stable',
                            'impact' => 'couvre mieux le vital',
                        ],
                        [
                            'label' => 'Parent isolé',
                            'before' => '4 021,4 €',
                            'after' => '2 771 €',
                            'response' => 'l’EuroE monte fortement',
                            'impact' => 'protège l’enfant et le foyer',
                        ],
                    ],
                ],
                'effects' => [
                    'L’EuroE n’efface pas les difficultés. Il réduit la violence du choc.',
                    'Son rôle est de protéger le vital, pendant que le revenu classique continue de faire vivre le reste de l’économie.',
                ],
            ],
            'privacy' => [
                'eyebrow' => 'Protéger sans surveiller',
                'title' => 'Une Europe protectrice, sans société de surveillance',
                'lead' => 'Sécurité oui. Surveillance permanente non.',
                'points' => [
                    [
                        'title' => 'Données minimales',
                        'body' => 'Le système ne garde que ce qui est utile au service.',
                    ],
                    [
                        'title' => 'Traçage limité',
                        'body' => 'Audit possible, sans suivi permanent de la vie privée.',
                    ],
                    [
                        'title' => 'Contrôle démocratique',
                        'body' => 'Les règles sont publiques et contrôlées par des institutions démocratiques.',
                    ],
                ],
            ],
            'faq' => [
                [
                    'question' => 'Est-ce une crypto ?',
                    'answer' => 'Non. L’EuroE est un euro complémentaire d’usage social, pas un actif spéculatif.',
                ],
                [
                    'question' => 'Est-ce que cela remplace l’euro ?',
                    'answer' => 'Non. L’euro classique reste la monnaie principale.',
                ],
                [
                    'question' => 'À quoi sert l’EuroE ?',
                    'answer' => 'L’EuroE protège le vital : logement, énergie, alimentation, transport essentiel, communication de base.',
                ],
                [
                    'question' => 'Pourquoi continuer à travailler ?',
                    'answer' => 'Parce que le travail reste essentiel pour progresser, épargner et construire ses projets.',
                ],
                [
                    'question' => 'Le projet est-il immédiat ?',
                    'answer' => 'Non. Le projet avance par étapes, avec tests et ajustements.',
                ],
                [
                    'question' => 'Faut-il surveiller tout le monde ?',
                    'answer' => 'Non. Le principe est clair : protéger sans surveiller.',
                ],
            ],
            'conclusion' => [
                'title' => 'Le résumé en une phrase',
                'body' => 'Le Civitalisme complète le système actuel pour sécuriser le vital, garder le travail central et avancer de façon progressive. Pour les règles détaillées, passe à la version technique.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function technicalPage(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Cadre technique et institutionnel',
                'title' => 'Cadre institutionnel du Civitalisme',
                'subtitle' => 'Doctrine, architecture monétaire, gouvernance et mise en œuvre',
                'lead' => 'Le Civitalisme est présenté ici comme une hypothèse de doctrine européenne pour l’ère de l’automatisation. Son point de départ est simple : si la production dépend de moins en moins du travail humain direct, l’Europe doit inventer un nouveau mode de distribution de la sécurité économique.',
                'highlights' => [
                    'Architecture monétaire publique',
                    'Gouvernance démocratique et cadre européen',
                    'Double circuit monétaire adossé à la sécurité économique',
                ],
            ],
            'heroPanel' => [
                'label' => 'Principes',
                'title' => 'Un projet articulé, pas un slogan',
                'body' => 'Le Civitalisme ne prétend ni remplacer immédiatement l’euro, ni supprimer les impôts, ni confier tout le pouvoir à la BCE. Il propose un cadre institutionnel progressif pour redistribuer une partie du dividende technologique.',
                'points' => [
                    'Euro classique pour le marché général ; EuroE pour le Socle vital européen.',
                    'Rôle monétaire de sécurité pour la BCE et rôle normatif pour les institutions démocratiques.',
                    'Cadre compatible avec souveraineté européenne des paiements, sécurité sociale et transition écologique.',
                ],
            ],
            'introduction' => [
                'eyebrow' => 'Introduction',
                'title' => 'Une doctrine pour l’ère de l’automatisation',
                'lead' => 'L’Europe cherche simultanément à rester compétitive, réussir sa transition écologique, renforcer sa souveraineté numérique et préserver sa cohésion sociale. Le risque est celui d’une dissociation durable entre productivité et revenus distribués.',
                'metrics' => [
                    ['label' => 'Compétitivité', 'value' => 'Rester productive sans désagréger le contrat social'],
                    ['label' => 'Transition écologique', 'value' => 'Réorienter la production sans fragiliser les ménages'],
                    ['label' => 'Souveraineté numérique', 'value' => 'Maîtriser les rails de paiement et les données critiques'],
                    ['label' => 'Cohésion sociale', 'value' => 'Prévenir les ruptures de revenus et la tension démocratique'],
                ],
            ],
            'diagnostic' => [
                'eyebrow' => 'Diagnostic',
                'title' => 'Une tension structurelle à traiter',
                'lead' => 'L’automatisation accroît le risque d’une dissociation entre productivité et revenus distribués. Le résultat peut être une fragilisation de la demande intérieure, une montée des inégalités et une tension démocratique croissante.',
                'points' => [
                    ['title' => 'Demande intérieure', 'body' => 'Une production plus efficace peut coexister avec une demande plus fragile si les revenus ne suivent plus.'],
                    ['title' => 'Inégalités', 'body' => 'Les gains se concentrent plus vite lorsque le capital technique remplace une partie du travail humain direct.'],
                    ['title' => 'Transition', 'body' => 'Les chocs de reconversion deviennent plus fréquents et plus rapides, notamment dans les classes moyennes.'],
                    ['title' => 'Démocratie', 'body' => 'Quand la sécurité économique recule, la confiance dans les institutions et la capacité de projection collective se dégradent.'],
                ],
            ],
            'doctrine' => [
                'eyebrow' => 'Doctrine',
                'title' => 'Ni spéculation, ni planification totale',
                'lead' => 'Le Civitalisme n’est ni une crypto spéculative, ni un rejet de l’économie de marché, ni un retour à la planification centralisée. C’est une doctrine de double circuit monétaire : euro classique pour le marché général, EuroE pour la sécurité économique vitale.',
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
                            'limits' => 'Conserve son rôle général sans être remplacé par l’EuroE.',
                        ],
                        [
                            'label' => 'EuroE',
                            'function' => 'Euro social numérique complémentaire',
                            'scope' => 'Socle vital européen, continuité matérielle et circulation essentielle agréée.',
                            'limits' => 'Non spéculatif, non patrimonial pour les particuliers et encadré pour les professionnels.',
                        ],
                    ],
                ],
                'points' => [
                    ['title' => 'Sécurité économique', 'body' => 'La doctrine traite la distribution du revenu comme une condition de stabilité productive et démocratique.'],
                    ['title' => 'Marché conservé', 'body' => 'Le marché, l’initiative privée et l’investissement restent actifs dans le circuit de l’euro classique.'],
                    ['title' => 'Capacité publique', 'body' => 'L’architecture monétaire publique garantit les usages essentiels sans absorber toute l’économie.'],
                ],
            ],
            'architecture' => [
                'eyebrow' => 'Architecture de l’EuroE',
                'title' => 'Des comptes individuels, des règles collectives',
                'lead' => 'L’EuroE repose sur des comptes individuels dès la naissance, un calcul du droit au niveau du foyer, des comptes enfants protégés, des comptes professionnels agréés et une distinction nette entre particuliers et professionnels.',
                'points' => [
                    ['title' => 'Comptes individuels', 'body' => 'Chaque citoyen dispose d’un compte personnel afin de garantir la continuité des droits et l’interopérabilité européenne.'],
                    ['title' => 'Calcul foyer', 'body' => 'Le droit n’est pas attribué au hasard individuel mais ajusté selon la composition réelle du foyer.'],
                    ['title' => 'Enfance protégée', 'body' => 'Les comptes enfants sont sécurisés afin d’éviter captation, surendettement ou marchandisation de droits vitaux.'],
                    ['title' => 'Professionnels agréés', 'body' => 'Les commerces et prestataires essentiels utilisent l’EuroE dans un cadre progressif, agréé et auditable.'],
                    ['title' => 'Interopérabilité', 'body' => 'La BCE et l’Eurosystème assurent rail, sécurité, stabilité et intégrité technique du système.'],
                    ['title' => 'Distinction claire', 'body' => 'Les particuliers utilisent l’EuroE comme compte d’usage vital ; les professionnels comme circuit régulé de continuité économique.'],
                ],
            ],
            'socle' => [
                'eyebrow' => 'Socle vital européen',
                'title' => 'Le panier vital prioritaire',
                'lead' => 'Le Socle vital européen couvre prioritairement logement, eau et chauffage, communication de base, transport essentiel et alimentation. Il vise à empêcher une chute brutale sous le seuil matériel de dignité.',
                'needs' => [
                    'Logement',
                    'Eau et chauffage',
                    'Communication de base',
                    'Transport essentiel',
                    'Alimentation',
                ],
            ],
            'calculation' => [
                'eyebrow' => 'Règle de calcul',
                'title' => 'Une formule lisible et paramétrable',
                'lead' => 'Le modèle repose sur une base adulte équivalent, une taille équivalente du foyer, un coefficient territorial, d’éventuelles majorations de vulnérabilité, une franchise d’activité et un retrait progressif de l’EuroE à mesure que les revenus remontent.',
                'equations' => [
                    'Socle vital du foyer = base × taille équivalente × coefficient territorial + majorations',
                    'EuroE du foyer = socle vital - retrait progressif appliqué au revenu ajusté',
                ],
                'parameters' => [
                    'Base adulte équivalent : 1 500 €',
                    'Premier adulte = 1,0',
                    'Autre personne de 14 ans ou plus = 0,5',
                    'Enfant de moins de 14 ans = 0,3',
                    'Franchise d’activité : 300 € par actif',
                    'Taux de retrait : 0,7',
                ],
            ],
            'caseStudy' => [
                'eyebrow' => 'Cas type',
                'title' => 'Famille à Prague',
                'summary' => '2 adultes actifs, 1 jeune de 17 ans, 1 enfant de 8 ans. Revenu net mensuel du foyer avant choc : 3 800 €. Revenu net mensuel après perte d’un emploi : 1 850 €.',
                'household' => [
                    'Base adulte équivalent : 1 500 €',
                    'Taille équivalente du foyer : 2,3',
                    'Coefficient territorial Prague : 1,15',
                    'Franchise d’activité : 300 € par actif',
                    'Taux de retrait : 0,7',
                ],
                'formulas' => [
                    'Socle vital du foyer : 1 500 × 2,3 × 1,15 = 3 967,5 €',
                ],
                'before' => [
                    'Revenu ajusté = 3 800 - 600 = 3 200 €',
                    'EuroE du foyer = 3 967,5 - (0,7 × 3 200) = 1 727,5 €',
                    'Total foyer = 5 527,5 €',
                ],
                'after' => [
                    'Revenu ajusté = 1 850 - 300 = 1 550 €',
                    'EuroE du foyer = 3 967,5 - (0,7 × 1 550) = 2 882,5 €',
                    'Total foyer = 4 732,5 €',
                ],
                'effects' => [
                    'Maintien de la consommation locale.',
                    'Limitation de l’effondrement de la demande.',
                    'Soutien indirect à l’activité économique.',
                    'Réduction du risque d’impayés logement, énergie et alimentation.',
                    'Rôle de stabilisateur automatique pendant le choc.',
                ],
                'conclusion' => 'Le coût brut ne suffit pas comme lecture. L’analyse doit intégrer les retombées positives potentielles sur la demande, l’activité, la stabilité des foyers et les finances publiques secondaires.',
            ],
            'stakeholders' => [
                'eyebrow' => 'Ce qu’attend chaque acteur',
                'title' => 'Les questions à traiter explicitement',
                'lead' => 'Un cadre institutionnel crédible doit parler à la fois aux économistes, aux décideurs, aux juristes, aux sociologues et aux institutions européennes.',
                'points' => [
                    [
                        'title' => 'Ce qu’attend un économiste',
                        'points' => [
                            'Coût brut et coût net.',
                            'Effet sur la consommation et la demande.',
                            'Effet sur l’inflation et le déficit.',
                            'Logique de stabilisateur automatique.',
                            'Impact sur l’offre de travail.',
                            'Doctrine de conversion du dividende technologique.',
                        ],
                        'documents' => [
                            [
                                'label' => 'Lire la partie Économie (PDF)',
                                'href' => '/documents/SVG-Partie-Economie.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Technique (PDF)',
                                'href' => '/documents/SVG-Partie-Technique.pdf',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Ce qu’attend un responsable politique',
                        'points' => [
                            'Qui bénéficie en priorité.',
                            'Comment commencer sans choc systémique.',
                            'Quels pilotes lancer.',
                            'Quels bénéfices visibles rapidement.',
                            'Quelle trajectoire sur 5 à 10 ans.',
                        ],
                        'documents' => [
                            [
                                'label' => 'Lire la partie Politique (PDF)',
                                'href' => '/documents/SVG-Partie-Politique.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Économie (PDF)',
                                'href' => '/documents/SVG-Partie-Economie.pdf',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Ce qu’attend un sociologue',
                        'points' => [
                            'Non-stigmatisation.',
                            'Protection des enfants.',
                            'Équilibre entre autonomie et contrôle.',
                            'Place du travail dans une économie automatisée.',
                            'Effet sur la dignité et la cohésion sociale.',
                        ],
                        'documents' => [
                            [
                                'label' => 'Lire la partie Sociologie (PDF)',
                                'href' => '/documents/SVG-Parite-Sociologie.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Politique (PDF)',
                                'href' => '/documents/SVG-Partie-Politique.pdf',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Ce qu’attend un juriste ou régulateur',
                        'points' => [
                            'Rôle réel de la BCE.',
                            'Articulation avec le digital euro.',
                            'Protection des données.',
                            'Critères d’agrément objectifs.',
                            'Compatibilité avec les principes européens.',
                        ],
                        'documents' => [
                            [
                                'label' => 'Lire la partie Juridique (PDF)',
                                'href' => '/documents/SVG-Partie-Juridique.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Technique (PDF)',
                                'href' => '/documents/SVG-Partie-Technique.pdf',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Ce qu’attendrait le Parlement européen',
                        'points' => [
                            'Base juridique claire.',
                            'Subsidiarité et proportionnalité.',
                            'Étude d’impact.',
                            'Gouvernance démocratique.',
                            'Feuille de route et contrôle continu.',
                        ],
                        'documents' => [
                            [
                                'label' => 'Lire la partie Politique (PDF)',
                                'href' => '/documents/SVG-Partie-Politique.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Juridique (PDF)',
                                'href' => '/documents/SVG-Partie-Juridique.pdf',
                            ],
                            [
                                'label' => 'Lire la partie Technique (PDF)',
                                'href' => '/documents/SVG-Partie-Technique.pdf',
                            ],
                        ],
                    ],
                ],
            ],
            'governance' => [
                'eyebrow' => 'Gouvernance',
                'title' => 'Qui fait quoi dans le système',
                'lead' => 'La crédibilité du Civitalisme repose sur une répartition nette des rôles entre institutions monétaires, institutions démocratiques, opérateurs techniques et citoyens.',
                'actors' => [
                    [
                        'title' => 'BCE / Eurosystème',
                        'body' => 'Rail monétaire public, sécurité, interopérabilité, stabilité et intégrité du système.',
                    ],
                    [
                        'title' => 'Institutions démocratiques',
                        'body' => 'Définition des règles sociales, du panier vital, des règles budgétaires et des équilibres du système.',
                    ],
                    [
                        'title' => 'Agence d’agrément / audit',
                        'body' => 'Contrôle des opérateurs, audit des usages, surveillance des agréments et redevabilité technique.',
                    ],
                    [
                        'title' => 'États membres',
                        'body' => 'Exécution sociale, articulation avec la justice, protection de l’enfance et recours de proximité.',
                    ],
                    [
                        'title' => 'Banques / PSP',
                        'body' => 'Wallets, paiements, support opérationnel et expérience utilisateur conforme aux règles publiques.',
                    ],
                    [
                        'title' => 'Citoyens',
                        'body' => 'Information claire, capacité de recours, participation démocratique et contrôle du respect de leurs droits.',
                    ],
                ],
            ],
            'risks' => [
                'eyebrow' => 'Risques et garde-fous',
                'title' => 'Les objections doivent être traitées dès la conception',
                'items' => [
                    ['title' => 'Inflation sur les biens vitaux', 'body' => 'Encadrement du réseau agréé, observation de prix, clauses anti-captation et ajustement des agréments.'],
                    ['title' => 'Désincitation au travail', 'body' => 'Franchise d’activité, retrait progressif et maintien d’un écart clair entre socle vital et revenus de progression.'],
                    ['title' => 'Cybersécurité', 'body' => 'Architecture résiliente, supervision européenne, segmentation des accès et protocoles de continuité.'],
                    ['title' => 'Fraude et usurpation', 'body' => 'Identité robuste, audits, plafonds d’usage et détection ciblée des anomalies.'],
                    ['title' => 'Surendettement', 'body' => 'Séparation des usages vitaux, impossibilité d’adosser simplement l’EuroE à une logique de dette patrimoniale.'],
                    ['title' => 'Captation des droits enfants', 'body' => 'Comptes protégés, restrictions d’usage et mécanismes de contrôle spécifiques à la protection de l’enfance.'],
                    ['title' => 'Surveillance excessive', 'body' => 'Minimisation des données, traçabilité proportionnée et gouvernance démocratique des accès.'],
                    ['title' => 'Concurrence et aides d’État', 'body' => 'Critères d’agrément objectifs, cadre européen commun et justification claire de l’intérêt général.'],
                    ['title' => 'Greenwashing', 'body' => 'Évaluation sérieuse des effets écologiques et articulation explicite avec les infrastructures essentielles.'],
                ],
            ],
            'roadmap' => [
                'eyebrow' => 'Roadmap',
                'title' => 'Une montée en charge progressive',
                'phases' => [
                    [
                        'phase' => 'Phase 0',
                        'title' => 'Doctrine et coalition',
                        'body' => 'Structurer la doctrine, produire la base juridique, agréger une coalition d’économistes, juristes, responsables publics et acteurs techniques.',
                    ],
                    [
                        'phase' => 'Phase 1',
                        'title' => 'Pilotes sociaux',
                        'body' => 'Tester le Socle vital européen sur des cas ciblés, territoires volontaires et foyers diversifiés.',
                    ],
                    [
                        'phase' => 'Phase 2',
                        'title' => 'Réseau agréé essentiel',
                        'body' => 'Constituer le réseau de professionnels et de prestataires essentiels capable de recevoir et traiter l’EuroE.',
                    ],
                    [
                        'phase' => 'Phase 3',
                        'title' => 'Montée en charge européenne',
                        'body' => 'Étendre l’interopérabilité, harmoniser les règles et installer la souveraineté européenne des paiements sur les usages vitaux.',
                    ],
                    [
                        'phase' => 'Phase 4',
                        'title' => 'Dividende technologique mature',
                        'body' => 'Stabiliser le modèle de financement, l’articulation budgétaire et la redistribution progressive d’une partie des gains d’automatisation.',
                    ],
                ],
            ],
            'conclusion' => [
                'title' => 'Une doctrine en construction',
                'body' => 'Le Civitalisme n’est pas présenté ici comme une solution finalisée. Il est conçu comme une doctrine prospective pour répondre à une question historique : comment organiser la sécurité économique dans une économie où les machines produisent de plus en plus à la place des humains ?',
            ],
        ];
    }
}
