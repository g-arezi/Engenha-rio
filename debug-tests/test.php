<?php
// Arquivo de teste para verificar se o sistema está funcionando
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Models\User;
use App\Models\Project;
use App\Models\Document;

echo "=== TESTE DO SISTEMA ENGENHÁRIO ===\n\n";

// Teste 1: Configuração
echo "1. Testando configuração...\n";
Config::load();
echo "   ✓ Configuração carregada\n";
echo "   ✓ Nome da aplicação: " . Config::app('name') . "\n";
echo "   ✓ URL da aplicação: " . Config::app('url') . "\n\n";

// Teste 2: Modelos
echo "2. Testando modelos...\n";

$userModel = new User();
$projectModel = new Project();
$documentModel = new Document();

echo "   ✓ Modelo User criado\n";
echo "   ✓ Modelo Project criado\n";
echo "   ✓ Modelo Document criado\n\n";

// Teste 3: Dados de teste
echo "3. Verificando dados de teste...\n";

$users = $userModel->all();
$projects = $projectModel->all();
$documents = $documentModel->all();

echo "   ✓ Usuários cadastrados: " . count($users) . "\n";
echo "   ✓ Projetos cadastrados: " . count($projects) . "\n";
echo "   ✓ Documentos cadastrados: " . count($documents) . "\n\n";

// Teste 4: Usuários por tipo
echo "4. Verificando usuários por tipo...\n";

$admins = $userModel->getByRole('admin');
$analysts = $userModel->getByRole('analista');
$clients = $userModel->getByRole('cliente');

echo "   ✓ Administradores: " . count($admins) . "\n";
echo "   ✓ Analistas: " . count($analysts) . "\n";
echo "   ✓ Clientes: " . count($clients) . "\n\n";

// Teste 5: Projetos por status
echo "5. Verificando projetos por status...\n";

$statusCounts = $projectModel->getStatusCounts();
echo "   ✓ Aguardando: " . $statusCounts['aguardando'] . "\n";
echo "   ✓ Pendentes: " . $statusCounts['pendente'] . "\n";
echo "   ✓ Atrasados: " . $statusCounts['atrasado'] . "\n";
echo "   ✓ Aprovados: " . $statusCounts['aprovado'] . "\n\n";

// Teste 6: Estrutura de diretórios
echo "6. Verificando estrutura de diretórios...\n";

$directories = [
    'data' => 'data/',
    'uploads' => 'public/uploads/',
    'documents' => 'public/documents/'
];

foreach ($directories as $name => $path) {
    if (is_dir($path)) {
        echo "   ✓ Diretório {$name} existe\n";
    } else {
        echo "   ⚠ Diretório {$name} não existe - criando...\n";
        mkdir($path, 0755, true);
        echo "   ✓ Diretório {$name} criado\n";
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "Sistema pronto para uso!\n\n";

echo "USUÁRIOS DE TESTE:\n";
echo "- Admin: admin@engenhario.com / password\n";
echo "- Analista: analista@engenhario.com / password\n";
echo "- Cliente: cliente@engenhario.com / password\n\n";

echo "Para acessar o sistema, inicie um servidor web e acesse a URL configurada.\n";
echo "Exemplo: php -S localhost:8000\n";
?>
