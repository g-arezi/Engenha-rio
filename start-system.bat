@echo off
echo ======================================
echo    SISTEMA ENGENHA-RIO - INICIANDO
echo ======================================
echo.
echo Verificando dependencias...

REM Verificar se o PHP esta instalado
php --version >nul 2>&1
if errorlevel 1 (
    echo ERRO: PHP nao encontrado. Instale o PHP primeiro.
    pause
    exit /b 1
)

echo PHP: OK
echo.

REM Verificar se o composer esta instalado
composer --version >nul 2>&1
if errorlevel 1 (
    echo AVISO: Composer nao encontrado. Algumas funcionalidades podem nao funcionar.
) else (
    echo Composer: OK
    echo Instalando dependencias...
    composer install --quiet
)

echo.
echo ======================================
echo    INICIANDO SERVIDOR DE DESENVOLVIMENTO
echo ======================================
echo.
echo URL do sistema: http://localhost:8080
echo.
echo Credenciais de teste:
echo - Admin: admin@sistema.com
echo - Analista: teste@user.com  
echo - Cliente: cliente@user.com
echo.
echo Funcionalidades implementadas:
echo [✓] Controle de acesso por perfil
echo [✓] Vinculacao obrigatoria cliente-projeto
echo [✓] Upload seguro de documentos
echo [✓] Validacao avancada de dados
echo.
echo Pressione Ctrl+C para parar o servidor
echo ======================================
echo.

REM Iniciar servidor PHP
php -S localhost:8080 index.php
