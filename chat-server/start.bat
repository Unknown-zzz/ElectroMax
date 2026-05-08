@echo off
REM ElectroMax Chat Server Startup Script for Windows
REM Usage: start.bat [port]

setlocal enabledelayedexpansion

set PORT=8181
if not "%1"=="" set PORT=%1

REM Check if composer dependencies are installed
if not exist "vendor\" (
    echo 📦 Installing dependencies...
    call composer install
    if errorlevel 1 (
        echo Error installing dependencies
        pause
        exit /b 1
    )
)

REM Start the server
echo 🚀 Starting ElectroMax Chat Server on port %PORT%...
set WS_PORT=%PORT%
php ratchet-server.php
pause
