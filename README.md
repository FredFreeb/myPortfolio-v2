# Portfolio + Civitalisme

Site portfolio refondu sous Symfony 8 avec Docker, un back-office EasyAdmin et une structure pensée pour rester simple à maintenir.

## Ce que contient le site

- `Portfolio` : page d’accueil avec travaux, positionnement et aperçus du projet Civitalisme.
- `About me` : parcours, environnement de travail, passions et lien d’album configurable.
- `Civitalisme` : une page grand public et une page technique / institutionnelle reliées entre elles.
- `Contact` : formulaire protégé contre le spam, stockage minimal en base et lecture dans l’admin.

## Stack

- Symfony 8
- Twig + Asset Mapper
- Doctrine ORM + Migrations
- EasyAdmin
- Nginx + PHP-FPM
- PostgreSQL
- Docker / Docker Compose
- Pretext de Cheng Lou pour la mise en scène typographique

## Démarrage local

1. Lancer les conteneurs :

```bash
bash bin/docker-up-lite
```

2. Appliquer la base :

```bash
docker compose -f compose.yaml exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

3. Charger les contenus de départ :

```bash
docker compose -f compose.yaml exec -T php php bin/console doctrine:fixtures:load --no-interaction
```

4. Ouvrir le site :

- Front : `http://localhost:8080` via `nginx`
- Mailpit : `http://localhost:8025` uniquement si tu lances `bash bin/docker-up-lite --with-mailer`

## Mode léger Mac M1

Cette stack est pensée pour rester fluide quand plusieurs projets tournent en même temps :

- `php` limité à `1 CPU`, `768 MB`
- `nginx` limité à `0.25 CPU`, `128 MB`
- `database` limitée à `0.75 CPU`, `512 MB`
- `mailer` désactivé par défaut
- `php-fpm` volontairement bridé à peu de workers
- `memory_limit` PHP local ramené à `256M`

Commande recommandée :

```bash
bash bin/docker-up-lite
```

Si tu as besoin du Mailpit ponctuellement :

```bash
bash bin/docker-up-lite --with-mailer
```

## Admin par défaut

Les valeurs par défaut sont dans `.env` et doivent être changées avant toute mise en production :

- e-mail : `admin@portfolio.local`
- mot de passe : `ChangeMePlease!123`

Pour recréer ou mettre à jour le compte admin :

```bash
docker compose -f compose.yaml exec -T php php bin/console app:admin:create admin@portfolio.local "mot-de-passe-fort"
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
docker compose -f compose.yaml -f compose.override.yaml up -d --build php nginx database
```

4. Lancer les migrations :

```bash
docker compose -f compose.yaml exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

5. Créer ou mettre à jour l’admin :

```bash
docker compose -f compose.yaml exec -T php php bin/console app:admin:create "email@domaine.tld" "mot-de-passe-fort"
```

Le `compose.override.yaml` est prévu pour le confort local : ports exposés, montage du code, persistance séparée de `vendor/` et `var/`, et démarrage léger pour le développement.

## Architecture Docker

- `nginx` : point d’entrée HTTP et service de visualisation du site.
- `php` : exécution Symfony via `php-fpm`.
- `database` : PostgreSQL.
- `mailer` : Mailpit pour les tests locaux, lancé seulement à la demande via le profil `tools`.

Le port HTTP exposé est configurable via `APP_PORT` et vaut `8080` par défaut.
