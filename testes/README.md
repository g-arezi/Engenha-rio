# 🏗️ Engenha Rio - Sistema de Gestão de Projetos de Arquitetura

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4.svg?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.0-7952B3.svg?logo=bootstrap)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Um sistema completo e moderno para gestão de documentos e projetos de arquitetura e engenharia, desenvolvido em PHP com uma interface moderna e responsiva.

## 📋 Índice

- [Visão Geral](#-visão-geral)
- [Características](#-características)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso](#-uso)
- [Funcionalidades](#-funcionalidades)
- [Screenshots](#-screenshots)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

## 🎯 Visão Geral

O **Engenha Rio** é uma solução completa para escritórios de arquitetura e engenharia que precisam gerenciar projetos, documentos e equipes de forma eficiente. O sistema oferece uma interface moderna e intuitiva, com funcionalidades específicas para diferentes tipos de usuários.

### Principais Objetivos

- ✅ Centralizar o gerenciamento de projetos arquitetônicos
- ✅ Organizar documentos técnicos de forma eficiente
- ✅ Facilitar a colaboração entre equipes
- ✅ Controlar permissões e acessos por função
- ✅ Acompanhar o progresso dos projetos em tempo real

## ⚡ Características

### 🔐 Sistema de Autenticação Robusto
- Login seguro com hash de senha
- Controle de sessões
- Diferentes níveis de permissão (Admin, Analista, Cliente)
- Bloqueio automático após tentativas falhadas

### 📊 Dashboard Interativo
- Visão geral dos projetos
- Estatísticas em tempo real
- Gráficos de status e progresso
- Histórico de atividades

### 📁 Gestão de Documentos
- Upload e organização de arquivos
- Versionamento de documentos
- Controle de acesso por projeto
- Suporte a múltiplos formatos

### 🏗️ Gerenciamento de Projetos
- Criação e edição de projetos
- Acompanhamento de status
- Associação com clientes
- Timeline de atividades

### 👥 Administração de Usuários
- Criação e edição de usuários
- Controle de permissões
- Aprovação de novos cadastros
- Histórico de ações

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 8.0+** - Linguagem de programação
- **Composer** - Gerenciador de dependências
- **PSR-4** - Autoloading de classes
- **PHPMailer** - Envio de emails
- **JSON** - Armazenamento de dados

### Frontend
- **Bootstrap 5.3.0** - Framework CSS
- **Font Awesome 6.4.0** - Ícones
- **JavaScript ES6+** - Interatividade
- **CSS3** - Estilização customizada

### Ferramentas de Desenvolvimento
- **PHPUnit** - Testes unitários
- **Servidor PHP Embutido** - Desenvolvimento local
- **Git** - Controle de versão

## 🏗️ Estrutura do Projeto

```
Engenha Rio/
├── 📁 config/                  # Configurações do sistema
│   ├── app.php                # Configurações principais
│   ├── security.php           # Configurações de segurança
│   └── settings.json          # Configurações JSON
├── 📁 data/                   # Dados do sistema (JSON)
│   ├── users.json            # Dados dos usuários
│   ├── projects.json         # Dados dos projetos
│   └── documents.json        # Metadados dos documentos
├── 📁 public/                 # Arquivos públicos
│   └── assets/               # CSS, JS, imagens
├── 📁 src/                    # Código fonte (PSR-4)
│   ├── Controllers/          # Controladores MVC
│   ├── Core/                 # Classes principais do sistema
│   ├── Middleware/           # Middlewares de autenticação
│   ├── Models/               # Modelos de dados
│   ├── Services/             # Serviços do sistema
│   └── Utils/                # Utilitários
├── 📁 views/                  # Templates e layouts
│   ├── layouts/              # Layouts principais
│   ├── auth/                 # Páginas de autenticação
│   ├── dashboard/            # Dashboard
│   ├── projects/             # Gestão de projetos
│   ├── documents/            # Gestão de documentos
│   └── admin/                # Painel administrativo
├── 📁 uploads/                # Arquivos enviados
├── 📁 logs/                   # Logs do sistema
├── 📁 cache/                  # Cache temporário
├── composer.json             # Dependências do Composer
├── index.php                 # Ponto de entrada
└── router.php                # Roteador para desenvolvimento
```

## 🚀 Instalação

### Pré-requisitos

- PHP 8.0 ou superior
- Composer
- Servidor web (Apache/Nginx) ou servidor PHP embutido

### Passo a Passo

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/engenha-rio.git
   cd engenha-rio
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Configure as permissões** (Linux/Mac)
   ```bash
   chmod -R 755 uploads/
   chmod -R 755 logs/
   chmod -R 755 cache/
   chmod -R 755 data/
   ```

4. **Inicie o servidor de desenvolvimento**
   ```bash
   # Usando servidor PHP embutido
   php -S localhost:8000 router.php
   
   # Ou use os scripts de inicialização (Windows)
   start-system.bat
   ```

5. **Acesse o sistema**
   ```
   http://localhost:8000
   ```

## ⚙️ Configuração

### Configuração Inicial

Edite o arquivo `config/app.php` para configurar:

```php
return [
    'app' => [
        'name' => 'Engenha Rio',
        'url' => 'http://localhost:8000',
        'timezone' => 'America/Sao_Paulo',
        'debug' => true // false em produção
    ],
    // ... outras configurações
];
```

### Usuário Administrador Padrão

O sistema vem com um usuário administrador pré-configurado:

- **Email:** `admin@sistema.com`
- **Senha:** `admin123`

> ⚠️ **Importante:** Altere a senha padrão após o primeiro login!

### Configurações de Segurança

Edite `config/security.php` para configurar:

- Headers de segurança
- Configurações de sessão
- Políticas de senha
- Timeout de sessão

## 📖 Uso

### 1. **Login no Sistema**
- Acesse a página inicial
- Faça login com suas credenciais
- Será redirecionado para o dashboard

### 2. **Dashboard**
- Visualize estatísticas dos projetos
- Acesse projetos recentes
- Monitore documentos pendentes

### 3. **Gestão de Projetos**
- Crie novos projetos
- Edite informações existentes
- Acompanhe o status e progresso
- Associe documentos aos projetos

### 4. **Gestão de Documentos**
- Faça upload de arquivos
- Organize por categorias
- Controle versões
- Compartilhe com a equipe

### 5. **Administração** (Admin/Analista)
- Gerencie usuários
- Configure permissões
- Visualize relatórios
- Acesse logs do sistema

## 🔧 Funcionalidades

### 👤 Gestão de Usuários
- [x] Cadastro de novos usuários
- [x] Edição de perfis
- [x] Controle de permissões por função
- [x] Aprovação de cadastros
- [x] Bloqueio/desbloqueio de contas

### 🏗️ Gestão de Projetos
- [x] Criação de projetos
- [x] Edição de informações
- [x] Status de acompanhamento
- [x] Associação com clientes
- [x] Timeline de atividades
- [x] Filtros e busca

### 📁 Gestão de Documentos
- [x] Upload de múltiplos arquivos
- [x] Organização por projetos
- [x] Controle de versões
- [x] Preview de documentos
- [x] Download seguro
- [x] Metadados detalhados

### 📊 Relatórios e Análises
- [x] Dashboard com métricas
- [x] Relatórios de projetos
- [x] Histórico de atividades
- [x] Estatísticas de usuários
- [x] Logs de sistema

### 🔐 Segurança
- [x] Autenticação robusta
- [x] Hash de senhas
- [x] Controle de sessões
- [x] Proteção CSRF
- [x] Headers de segurança
- [x] Logs de auditoria

## 💼 Tipos de Usuário

### 🔑 Administrador
- **Acesso total** ao sistema
- **Gerenciar usuários**: Criar, editar, aprovar
- **Controle de projetos** e documentos
- **Relatórios** e configurações do sistema
- **Logs** e monitoramento

### 📊 Analista
- **Painel administrativo** com permissões limitadas
- **Aprovar documentos** submetidos
- **Visualizar relatórios** de projetos
- **Gerenciar projetos** (conforme permissões)
- **Histórico** de atividades

### 👤 Cliente
- **Visualizar projetos** próprios ou associados
- **Upload de documentos** para projetos
- **Acompanhar status** de aprovação
- **Histórico** de atividades próprias
- **Perfil** pessoal

## 📊 Sistema de Status

### Status dos Projetos
- 🟡 **Aguardando**: Projeto criado, aguardando início
- 🔵 **Em Andamento**: Desenvolvimento ativo
- 🟠 **Pendente**: Aguardando informações/aprovação
- 🔴 **Atrasado**: Fora do prazo estipulado
- 🟢 **Concluído**: Projeto finalizado

### Status dos Documentos
- 📋 **Pendente**: Aguardando análise
- ✅ **Aprovado**: Documento aprovado
- ❌ **Rejeitado**: Documento rejeitado
- 🔄 **Revisão**: Em processo de revisão

## 📁 Sistema de Arquivos

### Tipos de Arquivo Suportados
- **Documentos**: PDF, DOC, DOCX, TXT
- **Planilhas**: XLS, XLSX, CSV
- **Imagens**: JPG, PNG, GIF, WEBP
- **Compactados**: ZIP, RAR
- **CAD**: DWG, DXF (visualização limitada)

### Organização
- **Por projeto**: Documentos agrupados por projeto
- **Por tipo**: Memorial, plantas, especificações, etc.
- **Por data**: Cronologia de uploads
- **Versionamento**: Controle de versões de documentos

## 🔒 Segurança

### Autenticação
- **Hash de senhas**: `password_hash()` com BCRYPT
- **Sessões seguras**: Regeneração de ID, timeout
- **Tentativas de login**: Bloqueio após 5 tentativas
- **Logout automático**: Por inatividade

### Autorização
- **Middleware**: Verificação de permissões por rota
- **Controle de acesso**: Baseado em roles (admin, analista, cliente)
- **Proteção de recursos**: Verificação de propriedade
- **Logs de auditoria**: Registro de ações sensíveis

### Proteções
- **Validação de entrada**: Sanitização de dados
- **Headers de segurança**: HSTS, CSP, X-Frame-Options
- **Upload seguro**: Validação rigorosa de arquivos
- **Proteção CSRF**: Tokens em formulários

## 📧 Sistema de Notificações

### Email Automático
- **Novos documentos**: Notificação para aprovadores
- **Mudança de status**: Atualizações de projeto
- **Documentos aprovados/rejeitados**: Feedback ao usuário
- **Lembretes**: Documentos pendentes há muito tempo

### Configuração SMTP
Configure em `config/app.php`:
```php
'mail' => [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'seu-email@gmail.com',
        'password' => 'sua-senha-app',
        'encryption' => 'tls'
    ]
]
```

## 📊 Relatórios

### Relatórios Disponíveis
- **Projetos por período**: Criados, concluídos, em andamento
- **Performance de usuários**: Atividade e produtividade
- **Documentos por tipo**: Estatísticas de upload
- **Status geral**: Visão macro do sistema

### Exportação
- **PDF**: Relatórios formatados para impressão
- **Excel**: Dados para análise externa
- **CSV**: Formato padrão para importação

## 🛠️ Manutenção

### Backup de Dados
- **Manual**: Cópia da pasta `/data/`
- **Automatizado**: Script de backup incluído
- **Restauração**: Procedimento documentado
- **Versionamento**: Múltiplas versões de backup

### Logs do Sistema
- **Localização**: `/logs/`
- **Rotação**: Por tamanho e data
- **Limpeza**: Script de manutenção automática
- **Análise**: Ferramentas para debugging

## 🌐 Deploy em Produção

### Servidor Compartilhado
1. **Upload**: Via FTP/SFTP
2. **Dependências**: `composer install --no-dev`
3. **Permissões**: Configurar pastas writable
4. **Virtual Host**: Apontar para `/public/`

### VPS/Servidor Dedicado
1. **Clone**: Git clone do repositório
2. **Webserver**: Nginx ou Apache
3. **SSL**: Let's Encrypt recomendado
4. **Backup**: Configurar backup automático
5. **Monitoring**: Configurar monitoramento

### Configurações de Produção
```php
// config/app.php - Produção
'app' => [
    'debug' => false,
    'url' => 'https://seudominio.com'
],
'security' => [
    'https_only' => true,
    'strict_transport' => true
]
```

## 🐛 Troubleshooting

### Problemas Comuns

**Erro 500 - Internal Server Error**
- Verificar logs do servidor (`/logs/error.log`)
- Verificar permissões de arquivo
- Verificar configurações PHP

**Upload não funciona**
- Verificar `upload_max_filesize` no php.ini
- Verificar `post_max_size` no php.ini
- Verificar permissões da pasta `/uploads/`

**Login não funciona**
- Verificar se as sessões estão habilitadas
- Verificar permissões da pasta de sessões
- Limpar cache de sessões

**Emails não são enviados**
- Verificar configurações SMTP
- Verificar logs de email (`/logs/mail.log`)
- Testar conectividade SMTP

### Debug Mode
Ativar debug no `config/app.php`:
```php
'app' => [
    'debug' => true
]
```

## 🧪 Testes

### Testes Implementados
- **Unitários**: Models e Services
- **Integração**: Controllers e APIs
- **Funcionais**: Fluxos completos do usuário
- **Segurança**: Autenticação e autorização

### Executar Testes
```bash
# Instalar dependências de dev
composer install

# Executar todos os testes
./vendor/bin/phpunit

# Testes específicos
./vendor/bin/phpunit tests/Unit/
./vendor/bin/phpunit tests/Feature/
```

## 📝 Roadmap

### Versão 1.1
- [ ] Sistema de notificações em tempo real
- [ ] API RESTful
- [ ] Integração com Google Drive/OneDrive
- [ ] App mobile (React Native)

### Versão 1.2
- [ ] Chat interno entre usuários
- [ ] Workflow de aprovação de documentos
- [ ] Integração com sistemas CAD
- [ ] Relatórios avançados com gráficos

### Versão 2.0
- [ ] Migração para banco de dados SQL
- [ ] Sistema de backup automático
- [ ] Multi-empresa (SaaS)
- [ ] Dashboard customizável

## 📱 Screenshots

*Em breve serão adicionadas capturas de tela do sistema...*

## 🤝 Contribuição

Contribuições são sempre bem-vindas! Para contribuir:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Diretrizes de Contribuição

- Siga os padrões PSR-4 para PHP
- Mantenha a documentação atualizada
- Adicione testes para novas funcionalidades
- Use commits semânticos

## 🐛 Relatando Bugs

Se encontrar algum bug, por favor:

1. Verifique se já não foi reportado nas [Issues](https://github.com/seu-usuario/engenha-rio/issues)
2. Crie uma nova issue com:
   - Descrição detalhada do problema
   - Passos para reproduzir
   - Ambiente (SO, versão PHP, navegador)
   - Screenshots se aplicável

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👨‍💻 Desenvolvedor

Desenvolvido com ❤️ por **[Seu Nome]**

- GitHub: [@seu-usuario](https://github.com/seu-usuario)
- Email: seuemail@exemplo.com
- LinkedIn: [Seu LinkedIn](https://linkedin.com/in/seu-perfil)

## 📞 Suporte

Para suporte técnico:

- 📧 Email: suporte@engenhario.com
- 📱 WhatsApp: +55 (21) 99999-9999
- 🌐 Website: [www.engenhario.com](https://www.engenhario.com)

## 🎓 Treinamento

### Para Administradores
1. **Configuração inicial**: Setup do sistema
2. **Gerenciamento de usuários**: Criar, editar, aprovar
3. **Configurações avançadas**: Email, segurança
4. **Relatórios**: Gerar e interpretar dados
5. **Manutenção**: Backup, logs, atualizações

### Para Analistas
1. **Interface do sistema**: Navegação básica
2. **Gestão de projetos**: Criar e acompanhar
3. **Aprovação de documentos**: Workflow
4. **Relatórios**: Gerar relatórios de projetos

### Para Clientes
1. **Acesso ao sistema**: Login e navegação
2. **Visualizar projetos**: Acompanhar status
3. **Upload de documentos**: Enviar arquivos
4. **Perfil**: Editar dados pessoais

## 💡 Dicas e Boas Práticas

### Para Desenvolvedores
- **Versionamento**: Use Git flow
- **Documentação**: Comente o código
- **Testes**: Escreva testes para novas features
- **Segurança**: Valide sempre inputs

### Para Usuários
- **Senhas**: Use senhas fortes
- **Arquivos**: Organize bem os documentos
- **Backup**: Faça backup regular dos dados
- **Atualizações**: Mantenha o sistema atualizado

### Para Administradores
- **Monitoramento**: Verifique logs regularmente
- **Backup**: Configure backup automático
- **Segurança**: Mantenha SSL atualizado
- **Performance**: Monitore uso de recursos

## 🎯 Conclusão

O **Engenha Rio** representa uma solução moderna e completa para a gestão de projetos de arquitetura e engenharia. Com sua arquitetura robusta, interface intuitiva e funcionalidades abrangentes, o sistema oferece tudo que um escritório de arquitetura precisa para organizar, gerenciar e acompanhar seus projetos de forma eficiente.

### Benefícios Principais

✅ **Organização Centralizada**: Todos os projetos e documentos em um só lugar  
✅ **Colaboração Eficiente**: Diferentes níveis de acesso para cada tipo de usuário  
✅ **Controle Total**: Acompanhamento detalhado de status e progresso  
✅ **Segurança Robusta**: Proteção de dados com as melhores práticas  
✅ **Interface Moderna**: Experiência de usuário otimizada e responsiva  
✅ **Escalabilidade**: Preparado para crescer junto com seu negócio  

### Próximos Passos Recomendados

1. **📥 Instalação**: Siga o guia de instalação detalhado
2. **⚙️ Configuração**: Personalize conforme suas necessidades
3. **👥 Treinamento**: Capacite sua equipe no uso do sistema
4. **📊 Monitoramento**: Acompanhe métricas e otimize o uso
5. **🔄 Feedback**: Contribua com sugestões e melhorias

---

<div align="center">
  <p>⭐ Se este projeto foi útil para você, considere dar uma estrela!</p>
  <p>🚀 <strong>Engenha Rio</strong> - Transformando a gestão de projetos de arquitetura</p>
  <p>Desenvolvido com ❤️ e muito ☕ pela comunidade</p>
</div>
