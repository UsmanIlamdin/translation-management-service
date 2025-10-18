# 🛠 Translation Service - Setup & Migration

This guide will help you set up the **Laravel Translation Service** project using Docker, configure the database, MinIO CDN, run migrations, and seed the translation tables.  

The project allows you to **manage translations** in the database (CRUD), and export them as JSON files to a MinIO (S3-compatible) CDN. Exported translations are **segregated by locale and tag** and publicly accessible for frontend applications (e.g., Vue.js).

---

## 1. Prerequisites

Make sure you have installed:

- [Docker](https://docs.docker.com/get-docker/) & [Docker Compose](https://docs.docker.com/compose/install/)
- Git

---

## 2. Project Structure

```

project-root/
├─ docker/
│  ├─ app/
│  │  └─ Dockerfile
│  ├─ nginx/
│  │  └─ Dockerfile
│  └─ mysql/
│     └─ conf.d/
├─ storage/
├─ database/
│  ├─ migrations/
│  └─ seeders/
├─ app/
├─ config/
└─ docker-compose.yml

````

---

## 3. Docker Compose Services

| Service | Port        | Description                        |
|---------|------------|------------------------------------|
| **app**   | -          | PHP-FPM Laravel Application       |
| **nginx** | 8080:80    | Nginx Web Server                  |
| **db**    | 3308:3306  | MySQL 9 Database                  |
| **cdn**   | 9000, 9001 | MinIO (S3-Compatible Local CDN)  |

**Volumes:**

- `./storage` → `/var/www/html/storage` (persistent Laravel storage)
- `mysql_data` → `/var/lib/mysql`
- `minio_data` → `/data`

**Network:** `translation_network` (bridge) with static IPs.

---

## 4. Environment Configuration

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
````

2. Update database configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=172.29.0.2
DB_PORT=3306
DB_DATABASE=translation_service
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

3. Update MinIO configuration:

```dotenv
FILESYSTEM_DRIVER=s3

AWS_ACCESS_KEY_ID=minioadmin
AWS_SECRET_ACCESS_KEY=minioadmin
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=translations
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_ENDPOINT=http://172.29.0.12:9000
```

> Use the internal Docker hostname `172.29.0.12` for MinIO access **inside the container**.

---

## 5. Start Docker Containers

```bash
docker-compose up -d
```

Check running containers:

```bash
docker ps
```

Expected containers:

* `translation_app`
* `translation_nginx`
* `translation_db`
* `translation_cdn`

---

## 6. Install Dependencies

Enter the app container:

```bash
docker exec -it translation_app bash
```

Install PHP dependencies:

```bash
composer install
```

Generate application key:

```bash
php artisan key:generate
```

---

## 7. Run Migrations

Run the translation tables migration:

```bash
php artisan migrate
```

Tables created:

* `translation`
* `tag`
* `translation_tag`

---

## 8. Verify Database

Connect to MySQL container:

```bash
docker exec -it translation_db mysql -u laravel -plaravel translation_service
```

Check tables:

```sql
SHOW TABLES;
SELECT COUNT(*) FROM translation;
```

---

## 9. Create Bucket in MinIO Object Storage

1. Open MinIO console in browser: [http://localhost:9001](http://localhost:9001)
2. Log in with credentials from Step 4 (`minioadmin:minioadmin`)
3. Create a bucket named as in your `.env` file (`translations`)
4. Set bucket to **public** for anonymous access:

```bash
mc alias set localminio http://localhost:9000 minioadmin minioadmin
mc anonymous set public localminio/translations
```

---

## 10. Accessing Files

* Use `Storage::disk('s3')` in Laravel to upload/export files to MinIO.
* Example exported file path:

```
http://localhost:9000/translations/i18n/en/web.json
```

* The export endpoint returns public URLs of the uploaded files.

---

## 11. Common Commands

| Command                                | Description                              |
| -------------------------------------- | ---------------------------------------- |
| `docker-compose up -d`                 | Start all services in detached mode      |
| `docker-compose down`                  | Stop all containers                      |
| `docker exec -it translation_app bash` | Enter app container                      |
| `php artisan migrate`                  | Run database migrations                  |
| `php artisan db:seed`                  | Seed database                            |

---
