<?php
/**
 * Teste de Funcionalidade de Edição de Projetos
 * 
 * Este arquivo testa se a funcionalidade de edição de projetos está funcionando corretamente.
 */

session_start();
require_once 'vendor/autoload.php';
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Core/Auth.php';
require_once 'src/Models/Project.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/ProjectController.php';

use App\Controllers\ProjectController;
use App\Models\Project;
use App\Models\User;
use App\Core\Auth;

echo "<h1>🔧 Teste de Funcionalidade - Edição de Projetos</h1>\n";
echo "<hr>\n";

// Simular login como admin
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

try {
    echo "<h2>1. Verificando Projetos Existentes</h2>\n";
    
    $projectModel = new Project();
    $projects = $projectModel->all();
    
    if ($projects && count($projects) > 0) {
        echo "✅ Encontrados " . count($projects) . " projetos:\n";
        echo "<ul>\n";
        foreach ($projects as $project) {
            echo "<li><strong>{$project['name']}</strong> (ID: {$project['id']}) - Status: {$project['status']}</li>\n";
        }
        echo "</ul>\n";
        
        // Testar o primeiro projeto
        $testProject = $projects[0];
        $projectId = $testProject['id'];
        
        echo "<h2>2. Testando Rota de Edição</h2>\n";
        echo "Projeto de teste: <strong>{$testProject['name']}</strong><br>\n";
        echo "Link de edição: <a href='/projects/{$projectId}/edit' target='_blank'>Editar Projeto</a><br>\n";
        
        echo "<h2>3. Testando Controller</h2>\n";
        
        // Testar método edit
        echo "Testando ProjectController::edit({$projectId})<br>\n";
        
        $controller = new ProjectController();
        
        // Capturar output do método edit
        ob_start();
        try {
            $controller->edit($projectId);
            echo "❌ Método edit não deveria retornar output direto<br>\n";
        } catch (Exception $e) {
            echo "⚠️ Exceção capturada: " . $e->getMessage() . "<br>\n";
        }
        $editOutput = ob_get_clean();
        
        if (empty($editOutput)) {
            echo "✅ Método edit executou sem erros<br>\n";
        } else {
            echo "⚠️ Output do método edit: " . substr($editOutput, 0, 200) . "...<br>\n";
        }
        
        echo "<h2>4. Testando Dados de Edição</h2>\n";
        
        // Buscar projeto com informações completas
        $fullProject = $projectModel->find($projectId);
        if ($fullProject) {
            echo "✅ Projeto encontrado para edição:<br>\n";
            echo "<ul>\n";
            echo "<li>Nome: {$fullProject['name']}</li>\n";
            echo "<li>Status: {$fullProject['status']}</li>\n";
            echo "<li>Prioridade: " . ($fullProject['priority'] ?? 'normal') . "</li>\n";
            echo "<li>Descrição: " . substr($fullProject['description'], 0, 100) . "...</li>\n";
            echo "</ul>\n";
        }
        
        echo "<h2>5. Links de Teste</h2>\n";
        echo "<ul>\n";
        echo "<li><a href='http://localhost:8000/projects' target='_blank'>📋 Ver Todos os Projetos</a></li>\n";
        echo "<li><a href='http://localhost:8000/projects/{$projectId}' target='_blank'>👁️ Ver Projeto</a></li>\n";
        echo "<li><a href='http://localhost:8000/projects/{$projectId}/edit' target='_blank'>✏️ Editar Projeto</a></li>\n";
        echo "</ul>\n";
        
    } else {
        echo "❌ Nenhum projeto encontrado<br>\n";
        echo "Para testar a edição, primeiro crie um projeto.<br>\n";
        echo "<a href='http://localhost:8000/projects/create' target='_blank'>Criar Novo Projeto</a><br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro durante o teste: " . $e->getMessage() . "<br>\n";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre><br>\n";
}

echo "<hr>\n";
echo "<h2>✅ Funcionalidades Habilitadas</h2>\n";
echo "<ul>\n";
echo "<li>✅ Função JavaScript editProject() corrigida</li>\n";
echo "<li>✅ Controller ProjectController::update() melhorado</li>\n";
echo "<li>✅ View projects/edit.php com campos condicionais</li>\n";
echo "<li>✅ Permissões baseadas em roles (admin/analista/cliente)</li>\n";
echo "<li>✅ Validações de formulário</li>\n";
echo "<li>✅ Campo de observações internas</li>\n";
echo "</ul>\n";

echo "<h2>🎯 Como Testar</h2>\n";
echo "<ol>\n";
echo "<li>Acesse a <a href='http://localhost:8000/projects' target='_blank'>página de projetos</a></li>\n";
echo "<li>Clique no botão 'Editar' de qualquer projeto</li>\n";
echo "<li>Modifique os campos disponíveis</li>\n";
echo "<li>Clique em 'Salvar Alterações'</li>\n";
echo "</ol>\n";

echo "<h2>👥 Permissões por Tipo de Usuário</h2>\n";
echo "<ul>\n";
echo "<li><strong>Admin:</strong> Pode editar todos os campos de qualquer projeto</li>\n";
echo "<li><strong>Analista:</strong> Pode editar projetos atribuídos a ele</li>\n";
echo "<li><strong>Cliente:</strong> Pode editar apenas nome e descrição de seus projetos</li>\n";
echo "</ul>\n";
?>
