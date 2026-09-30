@echo off
title BIND-Tech TMS Server
echo ========================================================
echo       Starting BIND-Tech Tool Management System...
echo ========================================================
echo Opening: http://127.0.0.1:8001
echo.
timeout /t 2 >nul
start http://127.0.0.1:8001
php artisan serve --port=8001
