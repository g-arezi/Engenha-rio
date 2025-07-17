@echo off
echo Iniciando servidor PHP para teste do sistema de documentos...
echo.
echo Acesse no navegador:
echo - http://localhost:8000/documents
echo - http://localhost:8000/teste-documents-final.php
echo - http://localhost:8000/debug-documents.php
echo.
echo Pressione Ctrl+C para parar o servidor
echo.
php -S localhost:8000 router.php
