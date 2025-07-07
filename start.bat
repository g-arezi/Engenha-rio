@echo off
echo ====================================
echo    ENGENHARIO - Sistema de Arquitetura
echo ====================================
echo.

echo Iniciando servidor de desenvolvimento...
echo.
echo Acesse: http://localhost:8000
echo.
echo Usuarios de teste:
echo - Admin: admin@engenhario.com / password
echo - Analista: analista@engenhario.com / password  
echo - Cliente: cliente@engenhario.com / password
echo.
echo Pressione Ctrl+C para parar o servidor
echo.

cd /d "%~dp0"
php -S localhost:8000
