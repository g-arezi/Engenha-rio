<?php
session_start();

echo "<h1>🔧 Correção do Sistema de Edição</h1>";

// Incluir dependências
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';

// Fazer login automático
if (!Auth::check()) {
    if (Auth::login('admin@engenhario.com', 'admin123')) {
        echo "<p style='color: green;'>✅ Login automático realizado</p>";
    }
}

echo "<h2>1. Verificando Estado do Sistema</h2>";

// Verificar se o projeto existe
require_once 'src/Models/Project.php';
$projectModel = new \App\Models\Project();

// Verificar todos os projetos
$projects = $projectModel->getAll();
echo "<p><strong>Total de projetos:</strong> " . count($projects) . "</p>";

if (empty($projects)) {
    echo "<h3>🚨 Problema: Nenhum projeto encontrado!</h3>";
    echo "<p>Vamos criar um projeto teste:</p>";
    
    $testProject = [
        'id' => 1,
        'name' => 'Projeto Teste 1',
        'description' => 'Este é um projeto teste criado para verificar o funcionamento do sistema de edição.',
        'status' => 'em_andamento',
        'priority' => 'normal',
        'client_id' => 1,
        'user_id' => 1,
        'analyst_id' => 1,
        'deadline' => '2025-12-31',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    if ($projectModel->create($testProject)) {
        echo "<p style='color: green;'>✅ Projeto teste criado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>❌ Erro ao criar projeto teste</p>";
    }
    
    $projects = $projectModel->getAll();
}

// Mostrar projetos disponíveis
echo "<h3>Projetos Disponíveis:</h3>";
echo "<ul>";
foreach ($projects as $project) {
    echo "<li><strong>ID " . $project['id'] . ":</strong> " . htmlspecialchars($project['name']) . " (Status: " . $project['status'] . ")</li>";
}
echo "</ul>";

echo "<h2>2. Teste de Edição Funcional</h2>";

if (isset($_POST['test_edit'])) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>📝 Processando Edição...</h3>";
    
    try {
        require_once 'src/Controllers/ProjectController.php';
        require_once 'src/Models/User.php';
        require_once 'src/Core/Controller.php';
        
        $controller = new \App\Controllers\ProjectController();
        
        // Capturar qualquer saída
        ob_start();
        
        // Simular dados do formulário
        $_POST['name'] = $_POST['edit_name'];
        $_POST['description'] = $_POST['edit_description'];
        $_POST['status'] = $_POST['edit_status'];
        $_POST['priority'] = $_POST['edit_priority'];
        $_POST['_method'] = 'PUT';
        
        // Executar update
        $controller->update($_POST['project_id']);
        
        $output = ob_get_clean();
        
        echo "<p style='color: green;'>✅ Edição processada com sucesso!</p>";
        
        if ($output) {
            echo "<p><strong>Saída:</strong></p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
        
        // Verificar mensagens de sessão
        if (Session::has('success')) {
            echo "<p style='color: green;'><strong>Sucesso:</strong> " . Session::get('success') . "</p>";
            Session::remove('success');
        }
        
        if (Session::has('error')) {
            echo "<p style='color: red;'><strong>Erro:</strong> " . Session::get('error') . "</p>";
            Session::remove('error');
        }
        
        // Verificar se o projeto foi realmente atualizado
        $updatedProject = $projectModel->find($_POST['project_id']);
        if ($updatedProject) {
            echo "<h4>Projeto Após Edição:</h4>";
            echo "<ul>";
            echo "<li><strong>Nome:</strong> " . htmlspecialchars($updatedProject['name']) . "</li>";
            echo "<li><strong>Status:</strong> " . $updatedProject['status'] . "</li>";
            echo "<li><strong>Prioridade:</strong> " . $updatedProject['priority'] . "</li>";
            echo "<li><strong>Última atualização:</strong> " . $updatedProject['updated_at'] . "</li>";
            echo "</ul>";
        }
        
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
        echo "<p><strong>Arquivo:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>";
    }
    
    echo "</div>";
}

?>

<style>
.btn {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}
.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }
.test-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    border: 1px solid #dee2e6;
}
input, select, textarea {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 5px;
}
</style>

<div class="test-form">
    <h3>🧪 Formulário de Teste de Edição</h3>
    <form method="POST">
        <input type="hidden" name="test_edit" value="1">
        
        <p><strong>Projeto a Editar:</strong></p>
        <select name="project_id" required>
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?> (ID: <?= $project['id'] ?>)</option>
            <?php endforeach; ?>
        </select>
        
        <p><strong>Novo Nome:</strong></p>
        <input type="text" name="edit_name" value="Projeto Editado via Teste" style="width: 300px;" required>
        
        <p><strong>Nova Descrição:</strong></p>
        <textarea name="edit_description" style="width: 400px; height: 100px;" required>Esta é uma descrição editada via sistema de teste para verificar o funcionamento correto da edição de projetos.</textarea>
        
        <p><strong>Novo Status:</strong></p>
        <select name="edit_status">
            <option value="aguardando">Aguardando</option>
            <option value="em_andamento" selected>Em Andamento</option>
            <option value="aprovado">Aprovado</option>
            <option value="concluido">Concluído</option>
        </select>
        
        <p><strong>Nova Prioridade:</strong></p>
        <select name="edit_priority">
            <option value="baixa">Baixa</option>
            <option value="normal">Normal</option>
            <option value="alta" selected>Alta</option>
            <option value="urgente">Urgente</option>
        </select>
        
        <br><br>
        <button type="submit" class="btn btn-success">🚀 Testar Edição</button>
    </form>
</div>

<h2>3. Links para Testar Interface Real</h2>
<a href="/projects" class="btn btn-primary" target="_blank">📋 Ver Lista de Projetos</a>
<?php if (!empty($projects)): ?>
    <a href="/projects/<?= $projects[0]['id'] ?>" class="btn btn-primary" target="_blank">👁️ Ver Primeiro Projeto</a>
    <a href="/projects/<?= $projects[0]['id'] ?>/edit" class="btn btn-success" target="_blank">✏️ Editar Primeiro Projeto</a>
<?php endif; ?>

<h2>4. Diagnóstico Completo</h2>
<details>
    <summary>Ver detalhes técnicos</summary>
    <h3>Estado da Autenticação:</h3>
    <p><strong>Logado:</strong> <?= Auth::check() ? 'Sim' : 'Não' ?></p>
    <?php if (Auth::check()): ?>
        <p><strong>Usuário:</strong> <?= Auth::user()['name'] ?> (<?= Auth::user()['role'] ?>)</p>
    <?php endif; ?>
    
    <h3>Configuração do Servidor:</h3>
    <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
    <p><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' ?></p>
    <p><strong>Current URI:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
</details>
