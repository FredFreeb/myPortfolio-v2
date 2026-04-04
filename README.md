# Portfolio + Civitalisme

Site portfolio refondu sous Symfony 8 avec Docker, un back-office EasyAdmin et une structure pensée pour rester simple à maintenir.

## Ce que contient le site

- `Portfolio` : page d’accueil avec travaux, positionnement et aperçus du projet Civitalisme.
- `About me` : parcours, environnement de travail, passions et lien d’album configurable.
- `Civitalisme` : page centrale + deux sous-pages `grand public` et `institutionnel`.
- `Contact` : formulaire protégé contre le spam, stockage minimal en base et lecture dans l’admin.

## Stack

- Symfony 8
- Twig + Asset Mapper
- Doctrine ORM + Migrations
- EasyAdmin
- PostgreSQL
- Docker / Docker Compose
- Pretext de Cheng Lou pour la mise en scène typographique

## Démarrage local

1. Lancer les conteneurs :

```bash
docker compose up -d --build
```

2. Appliquer la base :

```bash
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

3. Charger les contenus de départ :

```bash
docker compose exec -T php php bin/console doctrine:fixtures:load --no-interaction
```

4. Ouvrir le site :

- Front : `http://localhost:8080`
- Mailpit : `http://localhost:8025`

## Admin par défaut

Les valeurs par défaut sont dans `.env` et doivent être changées avant toute mise en production :

- e-mail : `admin@portfolio.local`
- mot de passe : `ChangeMePlease!123`

Pour recréer ou mettre à jour le compte admin :

```bash
docker compose exec -T php php bin/console app:admin:create admin@portfolio.local "mot-de-passe-fort"
```

## Variables utiles

- `APP_SECRET` : secret Symfony, à changer absolument en prod.
- `APP_ALBUM_URL` : lien vers l’album affiché sur la page `About me`.
- `ADMIN_EMAIL` / `ADMIN_PASSWORD` : bootstrap du compte admin dans les fixtures.
- `MAILER_DSN` : DSN de mail réel si le formulaire doit envoyer des mails plus tard.

## Données et sécurité

- Le formulaire de contact stocke uniquement : nom, e-mail, structure éventuelle, objet et message.
- Limitation des envois via `rate_limiter`.
- Auth admin Symfony avec hachage natif des mots de passe et protection CSRF.
- Cookies de session en `httponly` et `SameSite=Lax`.

## Déploiement VPS

Pour un VPS simple :

1. Cloner le dépôt sur le serveur.
2. Renseigner les variables d’environnement réelles.
3. Construire et démarrer :

```bash
docker compose -f compose.yaml up -d --build
```

4. Lancer les migrations :

```bash
docker compose -f compose.yaml exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

5. Créer ou mettre à jour l’admin :

```bash
docker compose -f compose.yaml exec -T php php bin/console app:admin:create "email@domaine.tld" "mot-de-passe-fort"
```

Le `compose.override.yaml` est prévu pour le confort local : ports exposés, montage du code et persistance séparée de `vendor/` et `var/`.
