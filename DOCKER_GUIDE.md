# 🐳 Docker Setup & Production Testing Guide

This project includes a complete, production-ready **Docker** setup featuring multi-container orchestration with **Laravel 12** and **MySQL 8.0**.

---

## 📁 Included Docker Files

1. [**`Dockerfile`**](file:///d:/GENERAL%20COURSE/DevOps_Preseth/Laravel/demo-app/Dockerfile): PHP 8.2 CLI container with required extensions (`pdo_mysql`, `pdo_sqlite`, `gd`, `bcmath`, `zip`, etc.) and Composer.
2. [**`docker-compose.yml`**](file:///d:/GENERAL%20COURSE/DevOps_Preseth/Laravel/demo-app/docker-compose.yml): Configures container services:
   - **`app`**: Laravel 12 application running on `http://localhost:8000`
   - **`db`**: MySQL 8.0 server listening on host port `3307` (to avoid conflicts with local XAMPP/MySQL)
3. [**`docker-entrypoint.sh`**](file:///d:/GENERAL%20COURSE/DevOps_Preseth/Laravel/demo-app/docker-entrypoint.sh): Automated startup script that waits for MySQL, sets permissions, runs `php artisan migrate --force`, seeds initial book records, and starts the server.
4. [**`.dockerignore`**](file:///d:/GENERAL%20COURSE/DevOps_Preseth/Laravel/demo-app/.dockerignore): Prevents bloated context builds by excluding `vendor`, `node_modules`, and temporary storage files.
5. [**`deploy-docker.bat`**](file:///d:/GENERAL%20COURSE/DevOps_Preseth/Laravel/demo-app/deploy-docker.bat): 1-click deployment script for Windows.

---

## 🚀 Quick Start Commands

### 1. Make sure Docker Desktop is open
Ensure **Docker Desktop** is running on your computer.

### 2. Build and Start Containers
Run the following command in your project terminal:
```bash
docker compose up -d --build
```

### 3. Check Container Status
```bash
docker compose ps
```

### 4. Access Your Application & API
- **Web UI Dashboard**: [`http://localhost:8000`](http://localhost:8000)
- **Mobile REST API**: [`http://localhost:8000/api/v1/books`](http://localhost:8000/api/v1/books)
- **MySQL Container Port**: `localhost:3307` (Database: `demo_app`, User: `root`, Password: `root`)

---

## 🛠️ Handy Docker Commands for Learning

### View Container Logs
```bash
docker compose logs -f app
```

### Run Artisan Commands inside Container
```bash
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan test
```

### Stop Containers
```bash
docker compose down
```

### Stop and Wipe Database Volume
```bash
docker compose down -v
```
