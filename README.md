# 🏗️ Engenha Rio - Sistema de Gestão de Projetos de Engenharia

**⚠️ AVISO IMPORTANTE: Este projeto é de propriedade exclusiva da Engenha Rio. Qualquer uso não autorizado, cópia, distribuição ou modificação será tratado judicialmente conforme a legislação brasileira de direitos autorais.**

Sistema web completo para gestão de projetos de engenharia, desenvolvido em PHP puro com arquitetura MVC personalizada.

## 🔒 Direitos Autorais e Propriedade Intelectual

Este sistema é **propriedade intelectual exclusiva da Engenha Rio**. Todos os direitos são reservados conforme:

- **Lei nº 9.610/98** (Lei de Direitos Autorais)
- **Lei nº 9.279/96** (Lei de Propriedade Industrial)
- **Marco Civil da Internet** (Lei nº 12.965/14)

### 📋 Uso Autorizado

O acesso a este repositório é restrito a:
- ✅ Funcionários autorizados da Engenha Rio
- ✅ Consultores contratados com NDA assinado
- ✅ Parceiros com acordo de confidencialidade

### ⚖️ Uso Não Autorizado

Qualquer das seguintes ações será considerada **violação de direitos autorais**:
- ❌ Cópia total ou parcial do código-fonte
- ❌ Distribuição ou compartilhamento não autorizado
- ❌ Uso comercial sem licença expressa
- ❌ Modificação ou adaptação sem autorização
- ❌ Engenharia reversa ou descompilação

**Violações serão processadas judicialmente com pedido de indenização por danos materiais e morais.**

## 📋 Sobre o Projeto

O **Engenha Rio** é uma plataforma robusta de gestão de projetos voltada especificamente para empresas de engenharia. O sistema oferece controle completo sobre projetos, documentos, usuários e templates, com diferentes níveis de acesso e funcionalidades especializadas.

## ✨ Funcionalidades Principais

### 👥 **Gestão de Usuários**
- **Administradores**: Controle total do sistema
- **Analistas**: Gestão de projetos e avaliação técnica
- **Clientes**: Visualização de projetos e upload de documentos
- Sistema de autenticação e autorização robusto
- Perfis personalizáveis com diferentes permissões

### 📊 **Gestão de Projetos**
- Criação e edição de projetos com status dinâmicos
- Associação de clientes e analistas responsáveis
- Controle de prazos e prioridades
- Dashboard interativo com métricas em tempo real
- Timeline de atividades e histórico completo

### 📄 **Sistema de Documentos Inteligente**
- **Templates Personalizáveis**: Criação de templates com documentos obrigatórios e opcionais
- **Upload Categorizado**: Clientes especificam o tipo de documento ao enviar
- **Validação Automática**: Verificação de formatos e tamanhos de arquivo
- **Status Visual**: Interface intuitiva mostrando documentos pendentes/enviados
- **Download Seguro**: Sistema de download com controle de acesso

### 🎯 **Templates de Documentos**
- Criação de templates reutilizáveis por tipo de projeto
- Documentos obrigatórios e opcionais configuráveis
- Documentos customizados específicos por projeto
- Descrições detalhadas e formatos aceitos
- Associação automática com projetos

### 🎨 **Interface Moderna**
- Design responsivo com Bootstrap 5
- Interface intuitiva e acessível
- Dashboards interativos com gráficos
- Notificações em tempo real
- Tema consistente com identidade visual

## 🛠️ Tecnologias Utilizadas

### **Backend**
- **PHP 8.0+**: Linguagem principal
- **Arquitetura MVC**: Estrutura organizada e escalável
- **Router Personalizado**: Sistema de roteamento customizado
- **Sistema de Auth**: Autenticação e autorização próprios
- **JSON Database**: Armazenamento em arquivos JSON (facilmente migrável)

### **Frontend**
- **HTML5**: Estrutura semântica
- **CSS3**: Estilização avançada
- **JavaScript ES6+**: Interações dinâmicas
- **Bootstrap 5.1.3**: Framework CSS responsivo
- **Font Awesome**: Ícones vetoriais

### **Ferramentas**
- **Composer**: Gerenciamento de dependências
- **Git**: Controle de versão
- **PHP Built-in Server**: Servidor de desenvolvimento

## 📁 Estrutura do Projeto

```
engenha-rio/
├── 📂 config/              # Configurações do sistema
│   ├── app.php            # Configurações gerais
│   ├── environment.php    # Variáveis de ambiente
│   ├── logger.php         # Configuração de logs
│   └── security.php       # Configurações de segurança
├── 📂 src/                # Código fonte principal
│   ├── 📂 Controllers/    # Controladores MVC
│   ├── 📂 Models/         # Modelos de dados
│   ├── 📂 Core/           # Classes fundamentais
│   ├── 📂 Middleware/     # Middlewares de aplicação
│   ├── 📂 Services/       # Serviços especializados
│   └── 📂 Utils/          # Utilitários e helpers
├── 📂 views/              # Templates e views
│   ├── 📂 layouts/        # Layouts base
│   ├── 📂 projects/       # Views de projetos
│   ├── 📂 documents/      # Views de documentos
│   └── 📂 auth/           # Views de autenticação
├── 📂 public/             # Arquivos públicos
│   ├── 📂 assets/         # CSS, JS, imagens
│   └── 📂 documents/      # Uploads de documentos
├── 📂 data/               # Banco de dados JSON
│   ├── users.json         # Usuários do sistema
│   ├── projects.json      # Projetos
│   ├── documents.json     # Documentos
│   └── document_templates.json # Templates
├── 📂 logs/               # Logs do sistema
└── 📂 vendor/             # Dependências Composer
```

## 🚀 Instalação e Configuração

### **Pré-requisitos**
- PHP 8.0 ou superior
- Composer
- Git

### **Instalação**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/[username]/engenha-rio.git
   cd engenha-rio
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   # Copie o arquivo de configuração
   cp config/environment.example.php config/environment.php
   
   # Edite as configurações conforme necessário
   nano config/environment.php
   ```

4. **Configure permissões**
   ```bash
   # Linux/Mac
   chmod 755 public/documents
   chmod 755 logs
   chmod 644 data/*.json
   
   # Windows - usar Properties > Security
   ```

5. **Inicie o servidor**
   ```bash
   php -S localhost:8080 -t public router.php
   ```

6. **Acesse o sistema**
   - URL: http://localhost:8080
   - Usuário padrão: admin@sistema.com
   - Senha padrão: [configurar na instalação]

## ⚙️ Configuração

### **Variáveis de Ambiente**

Edite `config/environment.php`:

```php
<?php
return [
    'app_name' => 'Engenha Rio',
    'app_env' => 'development', // development, production
    'app_debug' => true,
    
    // Configurações de upload
    'max_upload_size' => '50MB',
    'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'png'],
    
    // Configurações de segurança
    'session_timeout' => 7200, // 2 horas
    'password_min_length' => 8,
    
    // Logs
    'log_level' => 'info',
    'log_file' => 'logs/app.log'
];
```

### **Configuração de Produção**

Para ambiente de produção:

1. **Configure servidor web** (Apache/Nginx)
2. **Defina APP_ENV=production**
3. **Configure HTTPS**
4. **Desabilite debug** (APP_DEBUG=false)
5. **Configure backup automático**

## 👤 Sistema de Usuários

### **Tipos de Usuário**

| Tipo | Permissões |
|------|------------|
| **Admin** | Acesso completo ao sistema |
| **Analista** | Gestão de projetos, avaliação técnica |
| **Cliente** | Visualização de projetos próprios, upload de documentos |

### **Fluxo de Trabalho**

1. **Admin** cria projeto e associa cliente/analista
2. **Admin** define template de documentos necessários
3. **Cliente** visualiza projeto e documentos pendentes
4. **Cliente** faz upload dos documentos especificando tipo
5. **Analista** avalia projeto e documentos
6. **Admin** aprova/rejeita projeto

## 📄 Sistema de Templates

### **Criação de Templates**

```php
// Exemplo de template
{
    "name": "Projeto Residencial",
    "required_documents": [
        {
            "type": "cpf",
            "name": "CPF do Proprietário",
            "description": "Documento de identificação",
            "accept": ".pdf,.jpg,.png"
        }
    ],
    "optional_documents": [
        {
            "type": "memorial",
            "name": "Memorial Descritivo",
            "description": "Documento opcional"
        }
    ]
}
```

### **Documentos Customizados**

O sistema permite adicionar documentos específicos além dos padrões do template, oferecendo flexibilidade total para cada projeto.

## 🔐 Segurança

### **Recursos de Segurança**
- ✅ Autenticação por sessão
- ✅ Controle de acesso baseado em roles
- ✅ Validação de upload de arquivos
- ✅ Sanitização de dados de entrada
- ✅ Proteção contra CSRF
- ✅ Headers de segurança
- ✅ Logs de auditoria

### **Validações de Upload**
- Verificação de extensão de arquivo
- Validação de tipo MIME
- Limite de tamanho configurável
- Quarentena de arquivos suspeitos

## 📊 API e Integração

### **Endpoints Principais**

```
GET    /projects              # Listar projetos
POST   /projects              # Criar projeto
GET    /projects/{id}         # Visualizar projeto
PUT    /projects/{id}         # Atualizar projeto

POST   /documents/upload      # Upload de documento
GET    /documents/{id}        # Visualizar documento
DELETE /documents/{id}        # Excluir documento

GET    /document-templates    # Listar templates
POST   /document-templates    # Criar template
```

### **Formato de Resposta**

```json
{
    "success": true,
    "message": "Operação realizada com sucesso",
    "data": { ... }
}
```

## 🧪 Testes

### **Executar Testes**

```bash
# Testes unitários
vendor/bin/phpunit tests/

# Teste de upload
curl -X POST http://localhost:8080/documents/upload \
  -F "file=@teste.pdf" \
  -F "document_type=cpf" \
  -F "project_id=project_001"
```

## 📈 Monitoramento

### **Logs do Sistema**
- `logs/app.log` - Logs gerais da aplicação
- `logs/errors.log` - Erros do sistema
- `logs/access.log` - Logs de acesso
- `logs/documents.log` - Logs de documentos

### **Métricas Importantes**
- Tempo de resposta das páginas
- Taxa de upload de documentos
- Projetos por status
- Usuários ativos

## 🔄 Backup e Manutenção

### **Backup Automático**

```bash
#!/bin/bash
# Script de backup
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf backup_$DATE.tar.gz data/ public/documents/ logs/
```

### **Manutenção Regular**
- Limpeza de logs antigos
- Backup de dados
- Verificação de integridade de arquivos
- Atualização de dependências

## 🤝 Contribuição

### **Como Contribuir**

1. **Fork** o projeto
2. **Crie** uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. **Commit** suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. **Push** para a branch (`git push origin feature/nova-feature`)
5. **Abra** um Pull Request

### **Padrões de Código**
- PSR-12 para PHP
- ESLint para JavaScript
- Comentários em português
- Testes para novas funcionalidades


## 📜 Licença e Termos de Uso

### 🔐 **Propriedade Exclusiva**

Este software é **propriedade intelectual exclusiva da Engenha Rio** e está protegido por:

- **Direitos Autorais**: Todos os direitos reservados © 2025 Engenha Rio
- **Propriedade Industrial**: Marcas, patentes e know-how protegidos
- **Segredos Comerciais**: Algoritmos e processos proprietários

### ⚖️ **Termos Legais**

1. **Uso Restrito**: Permitido apenas para funcionários autorizados da Engenha Rio
2. **Confidencialidade**: Todas as informações são consideradas confidenciais
3. **Não Distribuição**: Proibida qualquer forma de distribuição
4. **Não Modificação**: Alterações somente com autorização expressa
5. **Responsabilidade**: Usuários são responsáveis pela segurança e integridade

### 🚨 **Consequências Legais**

**Qualquer violação destes termos resultará em:**
- Processo judicial por violação de direitos autorais
- Pedido de indenização por danos materiais e morais
- Medidas cautelares para cessação imediata do uso
- Busca e apreensão de materiais contrafeitos
- Responsabilização criminal quando aplicável

### 📞 **Contato Legal**

Para questões relacionadas a direitos autorais:
- 📧 **Email**: contato.gsaa@outlook.com

---

**⚠️ IMPORTANTE**: Este projeto NÃO é open source e NÃO possui licença MIT. A presença de arquivos de licença serve apenas para estrutura de desenvolvimento interno.

## 🔄 Changelog

### v2.1.0 (2025-07-17)
- ✨ Sistema de upload com especificação de tipo de documento
- ✨ Templates personalizáveis com documentos customizados
- ✨ Interface melhorada para documentos pendentes
- 🐛 Correção no sistema de roteamento
- 🔧 Melhorias no sistema de logs

### v2.0.0 (2025-07-01)
- ✨ Novo sistema de templates de documentos
- ✨ Dashboard interativo
- ✨ Sistema de notificações
- 🔧 Refatoração completa da arquitetura

### v1.0.0 (2025-06-01)
- 🎉 Lançamento inicial
- ✨ Sistema básico de projetos e documentos
- ✨ Autenticação e autorização
- ✨ Interface responsiva

---

<div align="center">


**⚠️ PROPRIEDADE INTELECTUAL PROTEGIDA ⚠️**

*Este sistema é de uso exclusivo da Engenha Rio. Qualquer uso não autorizado será processado judicialmente.*

**Desenvolvido com tecnologia proprietária para gestão especializada de projetos de engenharia**

🔒 **Confidencial** | ⚖️ **Protegido por Lei** | 🛡️ **Todos os Direitos Reservados**

</div>
