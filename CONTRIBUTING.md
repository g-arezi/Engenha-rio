# 🤝 Contribuindo para o Engenha Rio

Obrigado por seu interesse em contribuir com o Engenha Rio! Este documento fornece diretrizes para contribuições.

## 📋 Sumário

- [Como Contribuir](#como-contribuir)
- [Relatando Bugs](#relatando-bugs)
- [Sugerindo Melhorias](#sugerindo-melhorias)
- [Desenvolvimento Local](#desenvolvimento-local)
- [Padrões de Código](#padrões-de-código)
- [Processo de Pull Request](#processo-de-pull-request)
- [Testes](#testes)

## 🚀 Como Contribuir

### 1. **Fork do Repositório**
```bash
git clone https://github.com/[seu-usuario]/engenha-rio.git
cd engenha-rio
```

### 2. **Configurar Ambiente**
```bash
composer install
cp config/environment.example.php config/environment.php
# Configure as variáveis conforme necessário
```

### 3. **Criar Branch**
```bash
git checkout -b feature/minha-nova-feature
# ou
git checkout -b bugfix/correcao-bug
# ou
git checkout -b docs/atualizacao-documentacao
```

### 4. **Fazer Alterações**
- Implemente sua feature/correção
- Adicione testes se necessário
- Atualize documentação

### 5. **Commit e Push**
```bash
git add .
git commit -m "tipo: descrição clara da mudança"
git push origin feature/minha-nova-feature
```

### 6. **Abrir Pull Request**
- Descreva claramente as mudanças
- Referencie issues relacionadas
- Aguarde review

## 🐛 Relatando Bugs

### **Antes de Reportar**
- Verifique se já existe uma issue similar
- Teste na versão mais recente
- Colete informações do ambiente

### **Template de Bug Report**
```markdown
**Descrição do Bug**
Descrição clara e concisa do problema.

**Passos para Reproduzir**
1. Vá para '...'
2. Clique em '....'
3. Role para baixo até '....'
4. Veja o erro

**Comportamento Esperado**
O que deveria acontecer.

**Screenshots**
Se aplicável, adicione screenshots.

**Ambiente:**
- OS: [ex. Windows 10, Ubuntu 20.04]
- PHP: [ex. 8.0.30]
- Navegador: [ex. Chrome 91.0]
- Versão do Sistema: [ex. v2.1.0]

**Informações Adicionais**
Qualquer outro contexto sobre o problema.
```

## 💡 Sugerindo Melhorias

### **Template de Feature Request**
```markdown
**Sua sugestão está relacionada a um problema?**
Descrição clara do problema. Ex: "Sempre fico frustrado quando [...]"

**Descreva a solução que você gostaria**
Descrição clara e concisa do que você quer que aconteça.

**Descreva alternativas consideradas**
Descrição de outras soluções ou recursos considerados.

**Contexto adicional**
Adicione qualquer outro contexto ou screenshots sobre a solicitação.
```

## 🛠️ Desenvolvimento Local

### **Configuração do Ambiente**

1. **Dependências**
   ```bash
   # PHP 8.0+
   php --version
   
   # Composer
   composer --version
   
   # Git
   git --version
   ```

2. **Servidor Local**
   ```bash
   php -S localhost:8080 -t public router.php
   ```

3. **Banco de Dados**
   ```bash
   # Criar dados de teste
   php scripts/seed-database.php
   ```

### **Estrutura de Pastas**

Organize seus arquivos seguindo a estrutura existente:
```
src/
├── Controllers/     # Lógica de negócio
├── Models/         # Modelos de dados
├── Core/           # Classes fundamentais
├── Middleware/     # Middlewares
├── Services/       # Serviços especializados
└── Utils/          # Utilitários
```

## 📝 Padrões de Código

### **PHP (PSR-12)**

```php
<?php

namespace App\Controllers;

class ExampleController extends Controller
{
    private $model;
    
    public function __construct()
    {
        $this->model = new ExampleModel();
    }
    
    public function index(): void
    {
        $data = $this->model->getAll();
        $this->view('example.index', compact('data'));
    }
}
```

### **JavaScript (ES6+)**

```javascript
// Use const/let ao invés de var
const elements = document.querySelectorAll('.example');

// Funções arrow quando apropriado
const processData = (data) => {
    return data.map(item => item.processed);
};

// Async/await para operações assíncronas
async function fetchData() {
    try {
        const response = await fetch('/api/data');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erro:', error);
    }
}
```

### **CSS/SCSS**

```css
/* Use BEM para nomenclatura */
.project-card {
    display: flex;
    padding: 1rem;
}

.project-card__title {
    font-size: 1.2rem;
    font-weight: bold;
}

.project-card__title--highlighted {
    color: var(--primary-color);
}

/* Mobile-first */
@media (min-width: 768px) {
    .project-card {
        padding: 2rem;
    }
}
```

### **Convenções de Nomenclatura**

- **Classes PHP**: PascalCase (`UserController`)
- **Métodos/Variáveis**: camelCase (`getUserData()`)
- **Constantes**: SCREAMING_SNAKE_CASE (`MAX_UPLOAD_SIZE`)
- **Arquivos**: kebab-case (`user-profile.php`)
- **CSS Classes**: kebab-case (`user-profile-card`)

## 🔄 Processo de Pull Request

### **Checklist Antes do PR**

- [ ] Código segue os padrões estabelecidos
- [ ] Testes passam (`composer test`)
- [ ] Documentação atualizada se necessário
- [ ] Não há conflitos com a branch main
- [ ] Commits seguem o padrão de mensagens
- [ ] Removidas informações sensíveis/debug

### **Padrão de Mensagens de Commit**

```
tipo(escopo): descrição

Tipos válidos:
- feat: nova funcionalidade
- fix: correção de bug
- docs: documentação
- style: formatação, ponto e vírgula, etc
- refactor: refatoração de código
- test: adição de testes
- chore: tarefas de build, configurações

Exemplos:
feat(auth): adiciona autenticação por 2FA
fix(upload): corrige validação de arquivo
docs(readme): atualiza instruções de instalação
```

### **Review Process**

1. **Automated Checks**: Testes e linting automáticos
2. **Code Review**: Review por pelo menos um mantenedor
3. **Testing**: Teste manual se necessário
4. **Merge**: Squash and merge preferível

## 🧪 Testes

### **Executar Testes**

```bash
# Todos os testes
composer test

# Testes específicos
vendor/bin/phpunit tests/Unit/
vendor/bin/phpunit tests/Feature/

# Com coverage
composer test-coverage
```

### **Escrever Testes**

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->assertEquals('Test User', $user->getName());
        $this->assertEquals('test@example.com', $user->getEmail());
    }
}
```

## 📚 Documentação

### **Documentando Código**

```php
/**
 * Processa upload de documento
 *
 * @param array $file Dados do arquivo $_FILES
 * @param string $projectId ID do projeto
 * @param string $documentType Tipo do documento
 * @return array Resultado do processamento
 * @throws UploadException Se o upload falhar
 */
public function processUpload(array $file, string $projectId, string $documentType): array
{
    // Implementação...
}
```

### **Atualizando README**

Mantenha o README atualizado com:
- Novas funcionalidades
- Mudanças na instalação
- Novos requisitos
- Screenshots atualizados

## 🏷️ Versionamento

Seguimos [Semantic Versioning](https://semver.org/):

- **MAJOR** (x.0.0): Mudanças incompatíveis
- **MINOR** (x.y.0): Novas funcionalidades compatíveis
- **PATCH** (x.y.z): Correções de bug

## 🎉 Reconhecimentos

Contribuidores são listados em:
- [CONTRIBUTORS.md](CONTRIBUTORS.md)
- [GitHub Contributors](https://github.com/[repo]/contributors)

## 📞 Dúvidas?

- 💬 Discussões: [GitHub Discussions](https://github.com/[repo]/discussions)
- 📧 Email: [email protegido]
- 💭 Discord: [link do servidor]

---

**Obrigado por contribuir! 🚀**

Cada contribuição, seja grande ou pequena, é valiosa para o projeto.
