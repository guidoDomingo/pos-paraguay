@echo off
title Agente de Impresion POS
powershell -ExecutionPolicy Bypass -NoProfile -File "%~dp0print-agent.ps1"
pause
