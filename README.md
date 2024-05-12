Shipment Backend Service
============

REQUIREMENTS
------------

- PHP 8.2
- MySQL 8.4
- Composer 2.0
- Redis 6.2
- Node.js 14.16.1

Installation
------------

> You need to have [docker](http://www.docker.com) (20.10.10) and
[docker-compose](https://docs.docker.com/compose/install/) (1.29.2) installed.

1. Clone app from [git repository](https://github.com/jgodstime/shipment-api-laravel.git)
2. Go to the root of the project and run following commands:

```sh 
cp .env.example .env
```

3. Create docker network

```sh
docker network create shipment
```

5. Build docker

```sh
docker-compose build
```

6. Run docker

```sh
docker-compose up -d
```

7. Enter to the php docker container

```sh
docker-compose exec php bash
```

8. Install composer dependencies, generate key, run migrations with seeds

```sh
composer install --prefer-dist
php artisan key:generate
php artisan migrate:fresh --seed
```

9. App will be available on http://shipment-backend.local:9662 url

Useful tips
===========

To connect to MySQL server use following credentials:

```
hostname: 127.0.0.1
port: 3324
username: docker
password: secret
```

To connect to your exiting MySQL database , edit your database credentials in `.env` but replace the host with the
following:

```
DB_HOST=host.docker.internal
```

To run test, run this command:

```
pat
```

Rebuild docker containers if they already exists
===========

This may be required if new dependencies have been added to the project or container. After the last changes were
pulled:

- if new dependencies were added both to the container and project:

```sh
docker-compose down
docker-compose build  (in rare cases with --no-cache)
```

Then run docker and enter to it

```sh
docker-compose up -d
docker-compose exec php bash
```

Install new project dependencies if they exist and update migrations (inside docker container)

```sh
composer install --prefer-dist
php artisan migrate
```

- if dependencies were added only to the project:

```sh
composer install --prefer-dist
php artisan migrate
```
