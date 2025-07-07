@echo off
title Sistema Engenha-rio - Inicializacao Automatica
color 0A

echo.
echo ██████╗ ███████╗██╗██████╗ ████████╗ ██████╗ 
echo ██╔══██╗██╔════╝██║██╔══██╗╚══██╔══╝██╔═══██╗
echo ██████╔╝█████╗  ██║██████╔╝   ██║   ██║   ██║
echo ██╔══██╗██╔══╝  ██║██╔══██╗   ██║   ██║   ██║
echo ██║  ██║███████╗██║██║  ██║   ██║   ╚██████╔╝
echo ╚═╝  ╚═╝╚══════╝╚═╝╚═╝  ╚═╝   ╚═╝    ╚═════╝ 
echo.
echo ████████╗███████╗██╗  ██╗██████╗ 
echo ╚══██╔══╝██╔════╝██║  ██║██╔══██╗
echo    ██║   █████╗  ██║  ██║██║  ██║
echo    ██║   ██╔══╝  ██║  ██║██║  ██║
echo    ██║   ███████╗╚█████╔╝██████╔╝
echo    ╚═╝   ╚══════╝ ╚════╝ ╚═════╝ 
echo.
echo ====================================================
echo          SISTEMA ENGENHA-RIO v1.0
echo      Sistema de Gerenciamento de Projetos
echo ====================================================
echo.

echo [INFO] Verificando estrutura do sistema...
timeout /t 1 /nobreak > nul

if not exist "src" (
    echo [ERRO] Diretorio src nao encontrado!
    pause
    exit
)

if not exist "data" (
    echo [ERRO] Diretorio data nao encontrado!
    pause
    exit
)

if not exist "views" (
    echo [ERRO] Diretorio views nao encontrado!
    pause
    exit
)

echo [OK] Estrutura do sistema verificada!
echo.

echo [INFO] Verificando dependencias...
timeout /t 1 /nobreak > nul

if not exist "vendor" (
    echo [AVISO] Executando composer install...
    composer install
    if errorlevel 1 (
        echo [ERRO] Falha ao instalar dependencias!
        echo [INFO] Execute manualmente: composer install
        pause
        exit
    )
)

echo [OK] Dependencias verificadas!
echo.

echo [INFO] Verificando permissoes de diretorios...
timeout /t 1 /nobreak > nul

if not exist "logs" mkdir logs
if not exist "cache" mkdir cache
if not exist "temp" mkdir temp
if not exist "sessions" mkdir sessions
if not exist "public\uploads" mkdir public\uploads

echo [OK] Diretorios criados/verificados!
echo.

echo [INFO] Iniciando sistema...
echo.
echo ====================================================
echo    SISTEMA PRONTO PARA USO!
echo ====================================================
echo.
echo URL de Acesso: http://localhost:8000
echo.
echo CREDENCIAIS DE TESTE:
echo ┌─────────────────────────────────────────────────┐
echo │ Administrador:                                  │
echo │ Email: admin@engenhario.com                     │
echo │ Senha: password                                 │
echo │                                                 │
echo │ Analista:                                       │
echo │ Email: analista@engenhario.com                  │
echo │ Senha: password                                 │
echo │                                                 │
echo │ Cliente:                                        │
echo │ Email: cliente@engenhario.com                   │
echo │ Senha: password                                 │
echo └─────────────────────────────────────────────────┘
echo.
echo [INFO] Pressione Ctrl+C para parar o servidor
echo [INFO] O sistema sera aberto automaticamente no navegador
echo.

:: Aguardar 3 segundos e abrir o navegador
timeout /t 3 /nobreak > nul
start http://localhost:8000

:: Iniciar o servidor PHP
php -S localhost:8000

echo.
echo [INFO] Sistema finalizado!
pause
