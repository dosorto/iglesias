@echo off
cd /d "%~dp0.."

set "PHP_BIN="

if exist "C:\laragon\bin\php" (
    for /d %%D in ("C:\laragon\bin\php\php-*") do set "PHP_BIN=%%D\php.exe"
)

if defined PHP_BIN (
    "%PHP_BIN%" artisan schedule:run
) else (
    php artisan schedule:run
)
