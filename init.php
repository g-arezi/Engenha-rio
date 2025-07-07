<?php
// Arquivo para inicializar o sistema com dados seguros
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Models\User;
use App\Models\Project;
use App\Models\Document;

echo "=== INICIALIZANDO SISTEMA ENGENHA RIO ===\n\n";

// Carregar configurações
Config::load();

// Verificar se os dados já existem
$userModel = new User();
$projectModel = new Project();
$documentModel = new Document();

$users = $userModel->all();
$projects = $projectModel->all();
$documents = $documentModel->all();

echo "Status atual:\n";
echo "- Usuários: " . count($users) . "\n";
echo "- Projetos: " . count($projects) . "\n";
echo "- Documentos: " . count($documents) . "\n\n";

// Verificar se precisamos criar usuários padrão
if (empty($users)) {
    echo "Criando usuários padrão...\n";
    
    // Admin
    $userModel->create([
        'id' => 'admin_001',
        'name' => 'Administrador',
        'email' => 'admin@engenhario.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'role' => 'admin',
        'active' => true
    ]);
    
    // Analista
    $userModel->create([
        'id' => 'analyst_001',
        'name' => 'João Silva',
        'email' => 'analista@engenhario.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'role' => 'analista',
        'active' => true
    ]);
    
    // Cliente
    $userModel->create([
        'id' => 'client_001',
        'name' => 'Rafael Edinaldo',
        'email' => 'cliente@engenhario.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'role' => 'cliente',
        'active' => true
    ]);
    
    echo "✓ Usuários padrão criados\n";
}

// Verificar se precisamos criar projetos padrão
if (empty($projects)) {
    echo "Criando projetos padrão...\n";
    
    $projectModel->create([
        'id' => 'project_001',
        'name' => 'Edifício Residencial Vista Mar',
        'description' => 'Projeto arquitetônico para edifício residencial de 15 andares com vista para o mar',
        'status' => 'aguardando',
        'priority' => 'alta',
        'user_id' => 'client_001',
        'analyst_id' => 'analyst_001',
        'deadline' => '2025-08-15',
        'created_at' => '2025-07-01 10:00:00',
        'updated_at' => '2025-07-01 10:00:00'
    ]);
    
    $projectModel->create([
        'id' => 'project_002',
        'name' => 'Casa Unifamiliar Moderna',
        'description' => 'Projeto de casa unifamiliar com conceito moderno e sustentável',
        'status' => 'pendente',
        'priority' => 'normal',
        'user_id' => 'client_001',
        'analyst_id' => 'analyst_001',
        'deadline' => '2025-09-30',
        'created_at' => '2025-06-15 14:30:00',
        'updated_at' => '2025-07-02 09:15:00'
    ]);
    
    $projectModel->create([
        'id' => 'project_003',
        'name' => 'Reforma Comercial Centro',
        'description' => 'Reforma completa de prédio comercial no centro da cidade',
        'status' => 'aprovado',
        'priority' => 'normal',
        'user_id' => 'client_001',
        'analyst_id' => 'analyst_001',
        'deadline' => '2025-07-20',
        'created_at' => '2025-05-10 11:45:00',
        'updated_at' => '2025-07-03 16:22:00'
    ]);
    
    $projectModel->create([
        'id' => 'project_004',
        'name' => 'Complexo Industrial',
        'description' => 'Projeto de complexo industrial para empresa de logística',
        'status' => 'atrasado',
        'priority' => 'alta',
        'user_id' => 'client_001',
        'analyst_id' => 'analyst_001',
        'deadline' => '2025-06-30',
        'created_at' => '2025-04-20 08:20:00',
        'updated_at' => '2025-07-01 12:10:00'
    ]);
    
    echo "✓ Projetos padrão criados\n";
}

// Verificar se precisamos criar documentos padrão
if (empty($documents)) {
    echo "Criando documentos padrão...\n";
    
    $documentModel->create([
        'id' => 'doc_001',
        'name' => 'Planta Baixa - Edifício Vista Mar.pdf',
        'description' => 'Planta baixa do pavimento tipo do edifício residencial',
        'type' => 'planta',
        'file_path' => 'documents/planta_baixa_edificio.pdf',
        'size' => 2048576,
        'mime_type' => 'application/pdf',
        'user_id' => 'client_001',
        'project_id' => 'project_001',
        'created_at' => '2025-07-01 11:00:00',
        'updated_at' => '2025-07-01 11:00:00'
    ]);
    
    $documentModel->create([
        'id' => 'doc_002',
        'name' => 'Memorial Descritivo - Casa Moderna.docx',
        'description' => 'Memorial descritivo do projeto da casa unifamiliar',
        'type' => 'memorial',
        'file_path' => 'documents/memorial_casa_moderna.docx',
        'size' => 1024000,
        'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'user_id' => 'client_001',
        'project_id' => 'project_002',
        'created_at' => '2025-06-16 09:30:00',
        'updated_at' => '2025-06-16 09:30:00'
    ]);
    
    $documentModel->create([
        'id' => 'doc_003',
        'name' => 'Projeto Estrutural - Reforma Comercial.dwg',
        'description' => 'Projeto estrutural da reforma do prédio comercial',
        'type' => 'estrutural',
        'file_path' => 'documents/projeto_estrutural_comercial.dwg',
        'size' => 5120000,
        'mime_type' => 'application/acad',
        'user_id' => 'client_001',
        'project_id' => 'project_003',
        'created_at' => '2025-05-12 14:15:00',
        'updated_at' => '2025-05-12 14:15:00'
    ]);
    
    echo "✓ Documentos padrão criados\n";
}

echo "\n=== SISTEMA INICIALIZADO COM SUCESSO ===\n";
echo "Você pode iniciar o servidor com: php -S localhost:8000\n";
echo "Ou executar o arquivo start.bat no Windows\n\n";

echo "USUÁRIOS DE TESTE:\n";
echo "- Admin: admin@engenhario.com / password\n";
echo "- Analista: analista@engenhario.com / password\n";
echo "- Cliente: cliente@engenhario.com / password\n";
?>
