# 🛠 Translation Service - Setup & Migration

This guide will help you set up the Laravel Translation Service project using Docker, configure the database, MinIO CDN, run migrations, and seed the translation tables.

---

## **1. Prerequisites**

Make sure you have installed:

- [Docker](https://docs.docker.com/get-docker/) & [Docker Compose](https://docs.docker.com/compose/install/)
- Git

---

## **2. Project Structure**

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

## **3. Docker Compose Services**

| Service | Port | Description |
|---------|------|-------------|
| **app** | - | PHP-FPM Laravel Application |
| **nginx** | 8080:80 | Nginx Web Server |
| **db** | 3308:3306 | MySQL 9 Database |
| **cdn** | 9000, 9001 | MinIO (S3-Compatible Local CDN) |

Volumes:

- `./storage` → `/var/www/html/storage` (persistent Laravel storage)
- `mysql_data` → `/var/lib/mysql`
- `minio_data` → `/data`

Network: `translation_network` (bridge) with fixed IPs.

---

## **4. Environment Configuration**

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
````

2. Update database configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db
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
AWS_ENDPOINT=http://cdn:9000
AWS_USE_PATH_STYLE_ENDPOINT=true
```

> Use the internal Docker hostname `cdn` for MinIO access inside the container.

---

## **5. Start Docker Containers**

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

## **6. Install Dependencies**

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

## **7. Run Migrations**

Run the translation tables migration:

```bash
php artisan migrate
```

Tables created:

* `translation`
* `tag`
* `translation_tag`

---

## **8. Run Seeder**

Seed sample translations for testing:

```bash
php artisan db:seed --class=TranslationSeeder
```

Example `TranslationSeeder.php`:

```php
use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        Translation::factory()->count(100000)->create(); // generates 100k translations
    }
}
```

---

## **9. Verify Database**

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

## **10. Accessing Files**

* All translation JSON files are stored in `/storage/app/i18n` inside the container.
* Use `Storage::disk('s3')` in Laravel to upload/export files to MinIO.
* Example exported file path:

```
/var/www/html/storage/app/i18n/en/web.json
```

* Access via S3 URL:

```
http://localhost:9000/translations/i18n/en/web.json
```

---

## **11. Common Commands**

| Command                                | Description                              |
| -------------------------------------- | ---------------------------------------- |
| `docker-compose up -d`                 | Start all services in detached mode      |
| `docker-compose down`                  | Stop all containers                      |
| `docker exec -it translation_app bash` | Enter app container                      |
| `php artisan migrate`                  | Run database migrations                  |
| `php artisan db:seed`                  | Seed database                            |
| `php artisan tinker`                   | Interactively test Laravel models & code |

---
