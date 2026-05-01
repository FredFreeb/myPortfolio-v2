# Deploiement VPS Infomaniak

Ce projet est prevu pour vivre dans `/srv/sites/portfolio` sur le VPS, avec un reverse proxy Caddy commun a tous les futurs sites.

## 1. Connexion SSH

Depuis ton poste local, connecte-toi avec l IPv4 ou l IPv6 fournie par Infomaniak :

```bash
ssh utilisateur@IPV4_DU_VPS
ssh utilisateur@IPV6_DU_VPS
```

Si tu utilises une cle dediee :

```bash
ssh -i ~/.ssh/cle_infomaniak utilisateur@IPV4_DU_VPS
```

## 2. DNS des domaines

Dans la zone DNS de `fribel.com` et `fribel.eu`, pointe les domaines vers le VPS :

```text
fribel.com.  A     IPV4_DU_VPS
fribel.com.  AAAA  IPV6_DU_VPS
fribel.eu.   A     IPV4_DU_VPS
fribel.eu.   AAAA  IPV6_DU_VPS
```

Attends la propagation DNS avant de demander les certificats HTTPS a Caddy.

## 3. Arborescence VPS

```bash
sudo mkdir -p /srv/sites
sudo chown -R "$USER":"$USER" /srv/sites
cd /srv/sites
git clone URL_DU_DEPOT portfolio
cd portfolio
```

Les futurs sites pourront etre ajoutes a cote :

```text
/srv/sites/portfolio
/srv/sites/soundofmemories
/srv/sites/civitalisme
/srv/sites/blog
```

## 4. Variables de production

Sur le VPS uniquement :

```bash
cp .env.example .env.prod.local
chmod 600 .env.prod.local
```

Exemple minimal :

```dotenv
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=secret_unique_a_generer
APP_DOMAIN=fribel.com
APP_ADDITIONAL_DOMAINS=fribel.eu
DEFAULT_URI=https://fribel.com

DATABASE_URL="postgresql://app:motdepassefort@database:5432/portfolio?serverVersion=16&charset=utf8"
POSTGRES_DB=portfolio
POSTGRES_USER=app
POSTGRES_PASSWORD=motdepassefort
POSTGRES_VERSION=16

MAILER_DSN=smtp://user:password@smtp.example.com:587

STRIPE_PUBLIC_KEY=pk_live_xxx
STRIPE_SECRET_KEY=sk_live_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
MOLLIE_API_KEY=live_xxx
```

Le portfolio garde PostgreSQL car les migrations existantes sont ecrites pour PostgreSQL. Migrer vers MariaDB demanderait une migration SQL dediee.

## 5. Reverse proxy Caddy

Une seule instance Caddy sert tous les sites.

```bash
docker network create web
cd /srv/sites/portfolio/deploy/caddy
cp .env.example .env
chmod 600 .env
```

Dans `/srv/sites/portfolio/deploy/caddy/.env` :

```dotenv
CADDY_EMAIL=admin@fribel.com
PORTFOLIO_DOMAINS=fribel.com, fribel.eu
```

Puis :

```bash
docker compose up -d
```

## 6. Deployer le portfolio

```bash
cd /srv/sites/portfolio
./deploy.sh
```

Ou manuellement :

```bash
docker network create web
docker compose --env-file .env.prod.local -f compose.prod.yaml up -d --build
docker compose --env-file .env.prod.local -f compose.prod.yaml exec -T php php bin/console doctrine:migrations:migrate --no-interaction --env=prod
docker compose --env-file .env.prod.local -f compose.prod.yaml exec -T php php bin/console cache:clear --env=prod --no-debug
```

## 7. Commandes locales

```bash
docker compose up -d
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

Le site local est disponible sur `http://localhost:8080`.

## 8. Points de vigilance

- Ne versionne jamais `.env.local`, `.env.prod.local`, `deploy/caddy/.env` ni aucun fichier de secrets.
- Le DNS `A` et `AAAA` doit pointer vers le VPS avant le premier lancement Caddy.
- Ouvre seulement les ports publics `22`, `80` et `443`.
- Ne publie jamais le port PostgreSQL sur Internet.
- En production, garde `APP_ENV=prod` et `APP_DEBUG=0`.
- Le document root public reste uniquement `/app/public`.
