## About Project

Accountant test task using:

- PHP 8.3
- Laravel 11
- Mysql 8
- Redis for cache, session and queue
- Docker

## Installation
For start project, you need to install docker and docker-compose, after that you can run this command :

```bash
docker compose up -d
```
```bash
docker exec myapp composer install
```
```bash
docker exec myapp php artisan migrate --seed
```
```bash
docker exec myapp php artisan key:generate
```

```bash
docker exec myapp php artisan jwt:secret
```

## API Documentation

All api endpoint available on folder request.
