@echo off
echo Starting Laravel Development Servers...
echo.
echo Port 8000: Subdomain Template (Public Pages)
echo Port 9000: Main Site (Admin Login and Dashboard)
echo.
echo Press Ctrl+C to stop both servers
echo.

start "Laravel Server - Port 8000 (Subdomain Template)" cmd /k "php artisan serve --port=8000"
timeout /t 2 /nobreak >nul
start "Laravel Server - Port 9000 (Main Site)" cmd /k "php artisan serve --port=9000"

echo.
echo Both servers are starting...
echo Subdomain Template: http://127.0.0.1:8000
echo Main Site (Admin): http://127.0.0.1:9000/admin/login
echo.
pause

