@echo off
echo ===================================================
echo   Hotel Booking Website - Local Development Server
echo ===================================================
echo.

where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set PHP_BIN="C:\xampp\php\php.exe"
    ) else (
        echo [!] PHP is not found in PATH or C:\xampp\php.
        echo [!] Please install XAMPP or PHP and add it to PATH.
        pause
        exit /b 1
    )
) else (
    set PHP_BIN=php
)

echo [*] Starting PHP Web Server at http://localhost:8000 ...
echo [*] Customer Portal: http://localhost:8000/
echo [*] Admin Portal:    http://localhost:8000/admin/
echo.
echo Press Ctrl+C to stop the server.
echo.

%PHP_BIN% -S localhost:8000 -t "%~dp0hotelbooking"
