# INSTRUÇÕES PARA RESOLVER ERRO 403 FORBIDDEN

## 🔧 PASSOS PARA CORREÇÃO NA HOSPEDAGEM

### 1. Arquivos Atualizados:
- ✅ `.htaccess` - Versão simplificada e compatível
- ✅ `config/security.php` - Configurações de produção
- ✅ `diagnostico.php` - Para testar o servidor
- ✅ `teste.php` - Teste básico

### 2. Teste Inicial:
Acesse: `https://seu-dominio.com/teste.php`
- Se funcionar: ✅ Servidor PHP OK
- Se não funcionar: ❌ Problema de permissões

### 3. Diagnóstico Completo:
Acesse: `https://seu-dominio.com/diagnostico.php`
- Verificará permissões, módulos Apache, etc.

### 4. Se ainda tiver erro 403:

#### Opção A - .htaccess Mínimo:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Opção B - Sem .htaccess:
1. Renomeie `.htaccess` para `.htaccess-backup`
2. Acesse: `https://seu-dominio.com/index.php`

#### Opção C - Verificar Permissões:
- Arquivos PHP: 644
- Diretórios: 755
- index.php: 644

### 5. Comandos para o cPanel/Hospedagem:
```bash
chmod 644 *.php
chmod 755 */
chmod 644 .htaccess
```

### 6. Estrutura Mínima Necessária:
```
/
├── index.php (644)
├── .htaccess (644)
├── composer.json (644)
├── vendor/ (755)
├── src/ (755)
├── views/ (755)
├── data/ (755)
└── config/ (755)
```

### 7. Se Composer não estiver instalado:
1. Faça upload da pasta `vendor/` completa
2. Ou execute: `composer install` no terminal da hospedagem

### 8. Teste Final:
Após as correções, acesse:
- `https://seu-dominio.com/` - Deve redirecionar para login
- `https://seu-dominio.com/login` - Página de login
- `https://seu-dominio.com/documents` - Deve pedir autenticação

## ⚠️ PROBLEMAS COMUNS:

1. **mod_rewrite desabilitado**: Use URLs diretas como `/index.php/login`
2. **Permissões**: Execute `chmod` nos arquivos
3. **Composer**: Certifique-se que `vendor/` existe
4. **PHP Version**: Mínimo PHP 7.4+

## 📞 SUPORTE:
Se nenhuma solução funcionar, entre em contato com o suporte da hospedagem mencionando:
- Erro 403 Forbidden
- Necessidade do mod_rewrite
- PHP 7.4+ required
