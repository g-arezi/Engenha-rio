@echo off
echo ========================================
echo    SISTEMA DE DOCUMENTOS - TESTE
echo ========================================
echo.
echo Verificando PHP...
php -v
if errorlevel 1 (
    echo ERRO: PHP nao encontrado no PATH
    pause
    exit /b 1
)
echo.
echo Verificando arquivos...
if not exist "vendor\autoload.php" (
    echo ERRO: Autoloader nao encontrado
    echo Execute: composer install
    pause
    exit /b 1
)
echo.
echo Opcoes de teste:
echo.
echo 1. Iniciar servidor completo (localhost:8000)
echo 2. Testar documentos diretamente (localhost:8000/documents-direct.php)
echo 3. Teste rapido via PHP CLI
echo.
set /p opcao="Escolha uma opcao (1-3): "

if "%opcao%"=="1" goto servidor
if "%opcao%"=="2" goto direto
if "%opcao%"=="3" goto cli

:servidor
echo.
echo Iniciando servidor PHP completo...
echo.
echo URLs para testar:
echo - http://localhost:8000/documents
echo - http://localhost:8000/documents-direct.php
echo - http://localhost:8000/teste-documents-final.php
echo.
echo Pressione Ctrl+C para parar
echo.
php -S localhost:8000 router.php
goto fim

:direto
echo.
echo Iniciando servidor para acesso direto...
echo.
echo URL principal:
echo - http://localhost:8000/documents-direct.php
echo.
echo Pressione Ctrl+C para parar
echo.
php -S localhost:8000
goto fim

:cli
echo.
echo Executando teste via CLI...
echo.
php documents-direct.php
echo.
pause
goto fim

:fim
echo.
echo Teste finalizado.
pause
