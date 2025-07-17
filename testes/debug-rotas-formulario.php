<?php
session_start();

echo "<h1>Debug de Rotas e Formulário</h1>";

// Incluir dependências
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';
require_once 'src/Core/Router.php';

// Login automático se não estiver logado
if (!Auth::check()) {
    Auth::login('admin@engenhario.com', 'admin123');
}

echo "<h2>1. Estado da Autenticação:</h2>";
echo "<p><strong>Logado:</strong> " . (Auth::check() ? 'Sim' : 'Não') . "</p>";
if (Auth::check()) {
    $user = Auth::user();
    echo "<p><strong>Usuário:</strong> " . $user['name'] . " (" . $user['role'] . ")</p>";
}

echo "<h2>2. Informações da Requisição:</h2>";
echo "<p><strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Dados POST:</h3>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    if (isset($_POST['_method'])) {
        echo "<p style='color: green;'><strong>✅ _method encontrado:</strong> " . $_POST['_method'] . "</p>";
    }
}

echo "<h2>3. Testando Router:</h2>";

try {
    // Criar uma instância do router
    $router = new Router();
    
    // Definir as rotas principais
    $router->get('/projects/{id}/edit', 'ProjectController@edit')->middleware('auth');
    $router->put('/projects/{id}', 'ProjectController@update')->middleware('auth');
    
    echo "<p>✅ Router criado e rotas definidas</p>";
    
    // Verificar se a rota existe
    $reflection = new ReflectionClass($router);
    $routesProperty = $reflection->getProperty('routes');
    $routesProperty->setAccessible(true);
    $routes = $routesProperty->getValue($router);
    
    echo "<h3>Rotas Registradas:</h3>";
    echo "<ul>";
    foreach ($routes as $route) {
        echo "<li><strong>" . $route['method'] . "</strong> " . $route['path'] . 
             " → " . $route['handler'] . 
             " (middleware: " . implode(', ', $route['middleware']) . ")</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao criar router: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Teste de Formulário:</h2>";

if (isset($_POST['test_submit'])) {
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>📝 Formulário Enviado!</h3>";
    echo "<p><strong>Método Original:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
    
    if (isset($_POST['_method'])) {
        echo "<p><strong>Método Override:</strong> " . $_POST['_method'] . "</p>";
        echo "<p style='color: green;'>✅ Override funcionando!</p>";
    } else {
        echo "<p style='color: red;'>❌ Campo _method não encontrado!</p>";
    }
    
    echo "<p><strong>Dados recebidos:</strong></p>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
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
.btn-warning { background: #ffc107; color: black; }
.test-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    border: 1px solid #dee2e6;
}
</style>

<div class="test-form">
    <h3>🧪 Teste do Formulário PUT</h3>
    <form method="POST" action="">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="test_submit" value="1">
        
        <p><strong>Nome do Projeto:</strong></p>
        <input type="text" name="name" value="Projeto Teste" style="width: 300px; padding: 5px;">
        
        <p><strong>Descrição:</strong></p>
        <textarea name="description" style="width: 400px; height: 80px; padding: 5px;">Teste de descrição para verificar o funcionamento do formulário.</textarea>
        
        <p><strong>Status:</strong></p>
        <select name="status" style="padding: 5px;">
            <option value="em_andamento">Em Andamento</option>
            <option value="concluido">Concluído</option>
        </select>
        
        <br><br>
        <button type="submit" class="btn btn-success">🚀 Enviar Teste</button>
    </form>
</div>

<h2>5. Links de Navegação:</h2>
<a href="/projects" class="btn btn-primary">📋 Lista de Projetos</a>
<a href="/projects/1" class="btn btn-warning">👁️ Ver Projeto 1</a>
<a href="/projects/1/edit" class="btn btn-warning">✏️ Editar Projeto 1</a>

<h2>6. Debug do Sistema:</h2>
<details>
    <summary>Ver $_SERVER</summary>
    <pre><?= print_r($_SERVER, true) ?></pre>
</details>

<details>
    <summary>Ver $_SESSION</summary>
    <pre><?= print_r($_SESSION, true) ?></pre>
</details>
