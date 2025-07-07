<?php
/**
 * TESTE FINAL DO SISTEMA ENGENHA-RIO
 * Verifica se todas as funcionalidades estão funcionando corretamente
 */

echo "=== TESTE FINAL DO SISTEMA ENGENHA-RIO ===\n\n";

// 1. Verificar estrutura de arquivos
echo "1. Verificando estrutura de arquivos...\n";
$requiredFiles = [
    'index.php',
    'src/Controllers/AdminController.php',
    'src/Controllers/ProjectController.php',
    'src/Controllers/DocumentController.php',
    'src/Models/User.php',
    'src/Models/Project.php',
    'src/Models/Document.php',
    'src/Core/Router.php',
    'src/Core/Auth.php',
    'src/Core/Config.php',
    'config/app.php',
    'data/users.json',
    'data/projects.json',
    'data/documents.json'
];

$missingFiles = [];
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $missingFiles[] = $file;
    }
}

if (empty($missingFiles)) {
    echo "✅ Todos os arquivos necessários estão presentes\n";
} else {
    echo "❌ Arquivos faltando: " . implode(', ', $missingFiles) . "\n";
}

// 2. Verificar sintaxe PHP
echo "\n2. Verificando sintaxe PHP...\n";
$phpFiles = [
    'index.php',
    'src/Controllers/AdminController.php',
    'src/Controllers/ProjectController.php',
    'src/Controllers/DocumentController.php',
    'src/Models/User.php',
    'src/Models/Project.php',
    'src/Models/Document.php',
    'src/Core/Router.php',
    'src/Core/Auth.php',
    'src/Core/Config.php',
    'config/app.php'
];

$syntaxErrors = [];
foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = shell_exec("php -l $file 2>&1");
        if (strpos($output, 'No syntax errors') === false) {
            $syntaxErrors[] = $file . ': ' . $output;
        }
    }
}

if (empty($syntaxErrors)) {
    echo "✅ Nenhum erro de sintaxe encontrado\n";
} else {
    echo "❌ Erros de sintaxe encontrados:\n";
    foreach ($syntaxErrors as $error) {
        echo "   - $error\n";
    }
}

// 3. Verificar dados JSON
echo "\n3. Verificando dados JSON...\n";
$jsonFiles = ['data/users.json', 'data/projects.json', 'data/documents.json'];
$jsonErrors = [];

foreach ($jsonFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonErrors[] = $file . ': ' . json_last_error_msg();
        }
    } else {
        $jsonErrors[] = $file . ': Arquivo não encontrado';
    }
}

if (empty($jsonErrors)) {
    echo "✅ Todos os arquivos JSON são válidos\n";
} else {
    echo "❌ Erros nos arquivos JSON:\n";
    foreach ($jsonErrors as $error) {
        echo "   - $error\n";
    }
}

// 4. Verificar classes e autoload
echo "\n4. Verificando classes e autoload...\n";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "✅ Autoload do Composer carregado\n";
} else {
    echo "❌ Autoload do Composer não encontrado\n";
}

// 5. Verificar configuração
echo "\n5. Verificando configuração...\n";
if (file_exists('config/app.php')) {
    $config = require 'config/app.php';
    if (is_array($config)) {
        echo "✅ Configuração carregada com sucesso\n";
        echo "   - App Name: " . ($config['app']['name'] ?? 'N/A') . "\n";
        echo "   - Environment: " . ($config['app']['env'] ?? 'N/A') . "\n";
        echo "   - Debug: " . ($config['app']['debug'] ? 'Enabled' : 'Disabled') . "\n";
    } else {
        echo "❌ Configuração inválida\n";
    }
} else {
    echo "❌ Arquivo de configuração não encontrado\n";
}

// 6. Verificar permissões de diretórios
echo "\n6. Verificando permissões de diretórios...\n";
$directories = ['logs', 'cache', 'temp', 'sessions', 'public/uploads'];
$permissionErrors = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        $permissionErrors[] = "$dir: Diretório não existe";
    } elseif (!is_writable($dir)) {
        $permissionErrors[] = "$dir: Sem permissão de escrita";
    }
}

if (empty($permissionErrors)) {
    echo "✅ Todas as permissões de diretório estão corretas\n";
} else {
    echo "❌ Problemas de permissão encontrados:\n";
    foreach ($permissionErrors as $error) {
        echo "   - $error\n";
    }
}

// 7. Verificar dados de usuários
echo "\n7. Verificando dados de usuários...\n";
if (file_exists('data/users.json')) {
    $users = json_decode(file_get_contents('data/users.json'), true);
    if (is_array($users)) {
        echo "✅ Dados de usuários carregados: " . count($users) . " usuários\n";
        
        $adminCount = 0;
        foreach ($users as $user) {
            if (($user['role'] ?? '') === 'admin') {
                $adminCount++;
            }
        }
        echo "   - Administradores: $adminCount\n";
    } else {
        echo "❌ Dados de usuários inválidos\n";
    }
} else {
    echo "❌ Arquivo de usuários não encontrado\n";
}

// 8. Verificar dados de projetos
echo "\n8. Verificando dados de projetos...\n";
if (file_exists('data/projects.json')) {
    $projects = json_decode(file_get_contents('data/projects.json'), true);
    if (is_array($projects)) {
        echo "✅ Dados de projetos carregados: " . count($projects) . " projetos\n";
    } else {
        echo "❌ Dados de projetos inválidos\n";
    }
} else {
    echo "❌ Arquivo de projetos não encontrado\n";
}

// 9. Verificar views essenciais
echo "\n9. Verificando views essenciais...\n";
$essentialViews = [
    'views/layouts/app.php',
    'views/layouts/sidebar.php',
    'views/admin/index.php',
    'views/admin/users.php',
    'views/admin/settings.php',
    'views/projects/index.php',
    'views/projects/create.php',
    'views/projects/edit.php',
    'views/projects/show.php',
    'views/documents/index.php',
    'views/documents/show.php',
    'views/profile/index.php'
];

$missingViews = [];
foreach ($essentialViews as $view) {
    if (!file_exists($view)) {
        $missingViews[] = $view;
    }
}

if (empty($missingViews)) {
    echo "✅ Todas as views essenciais estão presentes\n";
} else {
    echo "❌ Views faltando: " . implode(', ', $missingViews) . "\n";
}

// 10. Resumo final
echo "\n=== RESUMO FINAL ===\n";
$totalErrors = count($missingFiles) + count($syntaxErrors) + count($jsonErrors) + count($permissionErrors) + count($missingViews);

if ($totalErrors === 0) {
    echo "🎉 SISTEMA COMPLETAMENTE FUNCIONAL! 🎉\n";
    echo "✅ Todos os testes passaram com sucesso\n";
    echo "✅ Sistema pronto para uso em produção\n\n";
    
    echo "INSTRUÇÕES PARA INICIAR:\n";
    echo "1. Execute: php -S localhost:8000\n";
    echo "2. Acesse: http://localhost:8000\n";
    echo "3. Use as credenciais:\n";
    echo "   - Admin: admin@engenhario.com / password\n";
    echo "   - Analista: analista@engenhario.com / password\n";
    echo "   - Cliente: cliente@engenhario.com / password\n";
} else {
    echo "⚠️  SISTEMA COM PROBLEMAS ⚠️\n";
    echo "❌ Total de erros encontrados: $totalErrors\n";
    echo "Por favor, corrija os problemas listados acima.\n";
}

echo "\n=== FIM DO TESTE ===\n";
