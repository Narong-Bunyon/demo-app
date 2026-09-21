@echo off
echo ========================================================
echo   🚀 DEMO BOOK VAULT - DOCKER DEPLOYMENT SCRIPT
echo ========================================================
echo.

echo 1. Checking Docker engine status...
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker is not running!
    echo Please start Docker Desktop application on your Windows PC and try again.
    pause
    exit /b 1
)

echo ✅ Docker is active!
echo.
echo 2. Building and starting containers (Laravel App & MySQL)...
docker compose up -d --build

echo.
echo 3. Checking container status...
docker compose ps

echo.
echo ========================================================
echo 🎉 Docker Deployment Complete!
echo.
echo 🌐 Web Dashboard: http://localhost:8000
echo 📱 Mobile API:    http://localhost:8000/api/v1/books
echo 🗄️ MySQL Port:    localhost:3307 (User: root / Pass: root)
echo ========================================================
pause
