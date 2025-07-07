# Engenha Rio - Sistema de Gestão de Projetos de Arquitetura

Um sistema completo de gestão de documentos e projetos de arquitetura desenvolvido em PHP 8+ com Composer.

## 🚀 Funcionalidades

### 🏗️ Sistema de Usuários
- **3 tipos de usuários**: Administrador, Analista e Cliente
- **Autenticação**: Login e cadastro seguros
- **Controle de acesso**: Permissões diferenciadas por tipo

### 📋 Dashboard Interativo
- **Upload de documentos**: Interface moderna para envio de arquivos
- **Gestão de projetos**: Acompanhamento de status
- **Painel administrativo**: Controle completo para admins

### 📧 Sistema de Notificações
- **Email automático**: Notificações para pendências
- **Alertas**: Atualizações de projeto
- **Lembretes**: Documentos em atraso

## 🛠️ Tecnologias

- **PHP 8+** com Composer
- **Framework modular** próprio
- **Bootstrap 5** para UI responsiva
- **PHPMailer** para emails
- **Upload seguro** de arquivos
- **Armazenamento JSON** (sem banco de dados)

## 📦 Instalação

### Pré-requisitos

- PHP 8.0 ou superior
- Composer
- Servidor web (Apache/Nginx)

### Passos para instalação

1. **Clone o repositório**
```bash
git clone https://github.com/seu-usuario/engenhario.git
cd engenhario
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
```

4. **Edite o arquivo `.env`** com suas configurações:
```env
APP_NAME="Engenha Rio"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Configurações de email
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="Engenha Rio"
```

5. **Configure o servidor web**

Para Apache, certifique-se de que o módulo `mod_rewrite` está habilitado.

Para Nginx, adicione estas configurações ao seu bloco server:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

6. **Configure as permissões**
```bash
chmod 755 data/
chmod 755 public/uploads/
```

## 🔐 Usuários de Teste

O sistema vem com usuários pré-configurados para teste:

### Administrador
- **Email**: admin@engenhario.com
- **Senha**: password
- **Permissões**: Acesso total ao sistema

### Analista
- **Email**: analista@engenhario.com
- **Senha**: password
- **Permissões**: Gerenciar projetos e documentos

### Cliente
- **Email**: cliente@engenhario.com
- **Senha**: password
- **Permissões**: Visualizar seus projetos e documentos

## 📁 Estrutura do Projeto

```
engenhario/
├── src/
│   ├── Core/          # Classes do núcleo do sistema
│   ├── Controllers/   # Controladores
│   ├── Models/        # Modelos de dados
│   └── Services/      # Serviços auxiliares
├── views/
│   ├── layouts/       # Layouts base
│   ├── auth/          # Telas de autenticação
│   ├── dashboard/     # Dashboard
│   ├── projects/      # Gestão de projetos
│   └── documents/     # Gestão de documentos
├── data/              # Arquivos JSON (banco de dados)
├── public/            # Arquivos públicos
│   ├── uploads/       # Uploads de arquivos
│   └── assets/        # CSS, JS, imagens
├── vendor/            # Dependências do Composer
└── .htaccess          # Configurações do Apache
```

## 🎨 Interface

O sistema possui uma interface moderna e responsiva baseada em Bootstrap 5, com:

- **Sidebar fixa** para navegação
- **Dashboard interativo** com estatísticas
- **Cards coloridos** para status dos projetos
- **Design responsivo** para mobile e desktop

## 📊 Tipos de Status

- **🔵 Aguardando**: Projetos aguardando análise
- **🟡 Pendente**: Projetos em análise
- **🔴 Atrasado**: Projetos com prazo vencido
- **🟢 Aprovado**: Projetos aprovados

## 🚀 Uso

### Para Clientes
1. Faça login no sistema
2. Crie novos projetos
3. Faça upload de documentos
4. Acompanhe o status dos projetos

### Para Analistas
1. Visualize projetos atribuídos
2. Analise documentos
3. Atualize status dos projetos
4. Adicione observações

### Para Administradores
1. Gerencie todos os usuários
2. Visualize estatísticas gerais
3. Configure o sistema
4. Gerencie todos os projetos

## 🔧 Personalização

### Cores do Sistema
As cores podem ser personalizadas no arquivo CSS principal:
```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
}
```

### Tipos de Arquivo Permitidos
Configure os tipos de arquivo permitidos em `DocumentController.php`:
```php
$allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'dwg', 'dxf'];
```

## 📧 Sistema de Notificações

O sistema inclui notificações automáticas por email para:
- Novos projetos criados
- Mudanças de status
- Prazos próximos ao vencimento
- Documentos pendentes

## 🔒 Segurança

- Autenticação baseada em sessões
- Validação de entrada de dados
- Proteção contra XSS e CSRF
- Upload seguro de arquivos
- Controle de acesso por roles

## 📱 Responsividade

O sistema é totalmente responsivo e funciona perfeitamente em:
- Desktop
- Tablets
- Smartphones

## 🆘 Suporte

Para suporte técnico, entre em contato através do botão de chat no sistema ou envie um email para suporte@engenhario.com.

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

---

**Engenha Rio** - Gestão profissional de projetos de arquitetura 🏗️
