<?php
session_start();

// Simular exatamente o que acontece quando o formulário é enviado
echo "<h1>Simulação Exata do Formulário de Edição</h1>";

// Fazer login
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';

if (!Auth::check()) {
    Auth::login('admin@engenhario.com', 'admin123');
}

// Simular dados do formulário
$_POST = [
    '_method' => 'PUT',
    'name' => 'Projeto Editado Teste',
    'description' => 'Esta é uma descrição teste editada para verificar o funcionamento.',
    'status' => 'em_andamento',
    'priority' => 'alta',
    'deadline' => '2025-12-31',
    'notes' => 'Notas de teste'
];

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/projects/1';

echo "<h2>Estado Simulado:</h2>";
echo "<p><strong>Método HTTP:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>_method:</strong> " . $_POST['_method'] . "</p>";

// Incluir o ProjectController e tentar executar diretamente
try {
    require_once 'src/Controllers/ProjectController.php';
    require_once 'src/Models/Project.php';
    require_once 'src/Models/User.php';
    require_once 'src/Core/Controller.php';
    
    echo "<h2>Testando ProjectController Diretamente:</h2>";
    
    $controller = new \App\Controllers\ProjectController();
    
    echo "<p>✅ Controller criado com sucesso</p>";
    
    // Capturar saída
    ob_start();
    
    try {
        // Simular o método update
        $result = $controller->update(1);
        $output = ob_get_clean();
        
        echo "<p>✅ Método update executado</p>";
        if ($output) {
            echo "<h3>Saída do Controller:</h3>";
            echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px;'>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
            echo "</div>";
        }
        
        // Verificar redirecionamentos
        $headers = headers_list();
        if ($headers) {
            echo "<h3>Headers Definidos:</h3>";
            echo "<ul>";
            foreach ($headers as $header) {
                echo "<li>" . htmlspecialchars($header) . "</li>";
            }
            echo "</ul>";
        }
        
        // Verificar mensagens de sessão
        if (Session::has('success')) {
            echo "<p style='color: green;'>✅ Mensagem de sucesso: " . Session::get('success') . "</p>";
        }
        
        if (Session::has('error')) {
            echo "<p style='color: red;'>❌ Mensagem de erro: " . Session::get('error') . "</p>";
        }
        
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo "<p style='color: red;'>❌ Erro no método update: " . $e->getMessage() . "</p>";
        echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
        echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
        
        if ($output) {
            echo "<h3>Saída antes do erro:</h3>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao criar controller: " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
}

// Verificar se o projeto existe
echo "<h2>Verificando Projeto:</h2>";
try {
    require_once 'src/Models/Project.php';
    $projectModel = new \App\Models\Project();
    $project = $projectModel->find(1);
    
    if ($project) {
        echo "<p>✅ Projeto 1 encontrado:</p>";
        echo "<ul>";
        echo "<li><strong>Nome:</strong> " . htmlspecialchars($project['name']) . "</li>";
        echo "<li><strong>Status:</strong> " . $project['status'] . "</li>";
        echo "<li><strong>Descrição:</strong> " . htmlspecialchars(substr($project['description'], 0, 100)) . "...</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Projeto 1 não encontrado!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao verificar projeto: " . $e->getMessage() . "</p>";
}

echo "<h2>Dados do Formulário Simulado:</h2>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

?>

<style>
.info-box {
    background: #e7f3ff;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
    border-left: 4px solid #007bff;
}
</style>

<div class="info-box">
    <h3>💡 O que este teste faz:</h3>
    <ul>
        <li>Simula exatamente os dados que o formulário envia</li>
        <li>Executa o método update do ProjectController diretamente</li>
        <li>Mostra qualquer erro ou sucesso</li>
        <li>Verifica se o projeto existe no banco de dados</li>
    </ul>
</div>
