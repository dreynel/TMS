@echo off
title BIND-Tech TMS Setup
echo ========================================================
echo       BIND-Tech Tool Management System (TMS)
echo             Automatic Database & App Setup
echo ========================================================
echo.

REM 1. Copy .env if missing
if not exist .env (
    echo [*] Creating .env from .env.example...
    copy .env.example .env
    echo [*] Generating APP_KEY...
    php artisan key:generate
)

REM 2. Create Storage Link
echo [*] Linking public storage directory...
php artisan storage:link >nul 2>&1

REM 3. Run Migrations & Seeders
echo.
echo [*] Building database tables and seeding sample records...
echo (If Laravel asks: "The database 'tms_db' does not exist. Would you like to create it?", answer yes)
php artisan migrate --seed --force

echo.
echo ========================================================
echo   [SUCCESS] Database built and system initialized!
echo   Open your browser and run: php artisan serve
echo   Or double-click 'run.bat' to launch immediately!
echo ========================================================
echo.
pause
