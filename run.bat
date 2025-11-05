@echo off
title Laravel Local Runner
color 0A

cd /d "%~dp0"

echo ============================================
echo Menjalankan Laravel Local Server
echo ============================================

start /B php artisan serve

timeout /t 3 >nul

start "" http://127.0.0.1:8000

echo ============================================
echo Laravel server sudah berjalan di http://127.0.0.1:8000
echo Tekan CTRL + C untuk menghentikan server (jika dijalankan manual)
echo ============================================
pause
