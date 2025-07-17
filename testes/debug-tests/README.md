# Pasta Debug-Tests

Esta pasta contém todos os arquivos de debug e teste que foram criados durante o desenvolvimento do sistema Engenha-rio.

## Estrutura dos Arquivos

### Arquivos de Debug (debug-*)
- **debug-admin*.php** - Testes de funcionalidades administrativas
- **debug-auth.php** - Testes de autenticação
- **debug-browser.php** - Testes de compatibilidade do navegador
- **debug-edit-user.php** - Testes de edição de usuários
- **debug-login-step-by-step.php** - Debug detalhado do processo de login
- **debug-logo.php** - Testes de logo/imagem
- **debug-permissions.php** - Testes de permissões
- **debug-session.php** - Testes de sessão
- **debug-sidebar-auth.php** - Testes de sidebar e autenticação
- **debug-*.html** - Outputs de debug em HTML

### Arquivos de Teste (test-*)
- **test-admin-*.php** - Testes de funcionalidades administrativas
- **test-auth-*.php** - Testes de autenticação
- **test-create-user*.php** - Testes de criação de usuários
- **test-delete*.php** - Testes de exclusão
- **test-edit*.php** - Testes de edição
- **test-final*.php** - Testes finais do sistema
- **test-history*.php** - Testes de histórico
- **test-login*.php** - Testes de login
- **test-settings*.php** - Testes de configurações
- **test-system*.php** - Testes gerais do sistema
- **test-user*.php** - Testes de usuários
- **test-validation*.php** - Testes de validação
- **test-project-client-link.php** - Testa a vinculação obrigatória cliente-projeto

### Arquivos de Suporte
- **add-admin-user.php** - Script para adicionar usuário admin
- **admin-users-edit.php** - Script de edição de usuários admin
- **auto-login.php** - Script de login automático
- **convert-logo.php** - Script de conversão de logo
- **cookies.txt** - Arquivo de cookies de teste
- **diagnostico-final.php** - Diagnóstico final do sistema
- **fix-password.php** - Script de correção de senha
- **force-login.php** - Script de login forçado
- **generate-hash.php** - Script para gerar hashes
- **login-test.html/php** - Testes de login
- **logo*.*** - Arquivos de teste de logo
- **logout-reset.php** - Script de reset de logout
- **setup-session.php** - Script de configuração de sessão
- **startup.html** - Página de startup

## Observações

- Estes arquivos foram criados para depuração e teste durante o desenvolvimento
- Podem ser removidos em produção
- Contêm códigos experimentais e de diagnóstico
- Alguns arquivos podem ter dependências com o sistema principal

## Uso

Para usar qualquer um destes arquivos:

1. Certifique-se de que o sistema principal está funcionando
2. Execute os arquivos PHP através do servidor web
3. Alguns arquivos podem precisar de ajustes de caminho

## Histórico

- Criado durante o desenvolvimento do sistema Engenha-rio
- Organizado em: 04/07/2025
- Contém arquivos de debug e teste criados para resolver problemas específicos

## Manutenção

- Revise periodicamente para remover arquivos obsoletos
- Mantenha apenas arquivos úteis para debugging futuro
- Considere compactar arquivos antigos em um arquivo ZIP
