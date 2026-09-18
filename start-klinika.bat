@echo off
chcp 65001 >nul
title Klinika - Open Server
echo ========================================
echo  Klinika.local ishga tushirish
echo ========================================
echo.

if not exist "C:\OSPanel\bin\ospanel.exe" (
  echo [X] OSPanel topilmadi: C:\OSPanel
  pause
  exit /b 1
)

echo [1/4] Open Server Panel...
tasklist /FI "IMAGENAME eq ospanel.exe" 2>nul | find /I "ospanel.exe" >nul
if errorlevel 1 (
  start "" "C:\OSPanel\bin\ospanel.exe"
  timeout /t 8 /nobreak >nul
)

echo [2/4] Modullar: Apache, MySQL-8.0, PHP-8.2, Bind...
call "C:\OSPanel\bin\osp.bat" on Apache >nul 2>&1
call "C:\OSPanel\bin\osp.bat" on MySQL-8.0 >nul 2>&1
call "C:\OSPanel\bin\osp.bat" on PHP-8.2 >nul 2>&1
call "C:\OSPanel\bin\osp.bat" on Bind >nul 2>&1

echo [3/4] Bazani tekshirish...
set "MYSQL=C:\OSPanel\modules\MySQL-8.0\bin\mysql.exe"
"%MYSQL%" --host=127.0.1.30 --port=3306 --protocol=TCP -uroot -e "CREATE DATABASE IF NOT EXISTS klinika CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >nul 2>&1

echo [4/4] Sayt ochilmoqda...
timeout /t 1 /nobreak >nul
start "" "http://klinika.local"
start "" "http://klinika.local/wp-admin"

echo.
echo Tayyor:
echo   Sayt:  http://klinika.local
echo   Admin: http://klinika.local/wp-admin
echo   Tema:  C:\OSPanel\home\klinika.local\wp-content\themes\klinika-theme
echo          (junction -^> d:\maket\клиника\klinika-theme)
echo.
echo Agar ochilmasa: hosts da "127.0.1.11 klinika.local" borligini tekshiring.
echo.
pause
