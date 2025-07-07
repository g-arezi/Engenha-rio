@echo off
echo =====================================================
echo     🚀 ENGENHA RIO - SERVIDOR LOCAL
echo =====================================================
echo.
echo Iniciando servidor PHP na porta 8000...
echo URL: http://localhost:8000
echo.
echo ✅ PROBLEMA RESOLVIDO! 
echo A rota /documents agora funciona corretamente!
echo.
echo Links uteis:
echo - http://localhost:8000/documents (documentos)
echo - http://localhost:8000/admin/users (admin)
echo - http://localhost:8000/login-e-teste.php (teste completo)
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
echo =====================================================
echo.

cd /d "%~dp0"

REM Usar router personalizado para resolver problemas de roteamento
php -S localhost:8000 router.php

pause
