<?php
session_start();

echo "<h1>Teste Final - Login e Edição de Projetos</h1>";

// Incluir dependências
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';

echo "<h2>Status Atual:</h2>";
echo "<p><strong>Sessão Ativa:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Sim' : 'Não') . "</p>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Usuário Logado:</strong> " . (Auth::check() ? 'Sim' : 'Não') . "</p>";

if (Auth::check()) {
    $user = Auth::user();
    echo "<p><strong>Dados do Usuário:</strong></p>";
    echo "<ul>";
    echo "<li>ID: " . ($user['id'] ?? 'N/A') . "</li>";
    echo "<li>Nome: " . ($user['name'] ?? 'N/A') . "</li>";
    echo "<li>Email: " . ($user['email'] ?? 'N/A') . "</li>";
    echo "<li>Role: " . ($user['role'] ?? 'N/A') . "</li>";
    echo "</ul>";
}

// Ação de login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    echo "<h2>Fazendo Login...</h2>";
    
    $email = 'admin@engenhario.com';
    $password = 'admin123';
    
    if (Auth::login($email, $password)) {
        echo "<p style='color: green;'>✅ Login realizado com sucesso!</p>";
        // Reload para atualizar o status
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<p style='color: red;'>❌ Falha no login!</p>";
    }
}

// Ação de logout
if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    Auth::logout();
    echo "<p style='color: orange;'>🚪 Logout realizado!</p>";
    echo "<script>window.location.reload();</script>";
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
.btn-danger { background: #dc3545; color: white; }
.btn-warning { background: #ffc107; color: black; }
.btn-info { background: #17a2b8; color: white; }
</style>

<h2>Ações:</h2>

<?php if (!Auth::check()): ?>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="action" value="login">
        <button type="submit" class="btn btn-primary">🔑 Fazer Login como Admin</button>
    </form>
<?php else: ?>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="action" value="logout">
        <button type="submit" class="btn btn-danger">🚪 Logout</button>
    </form>
<?php endif; ?>

<h2>Navegação:</h2>

<a href="/login" class="btn btn-info">📄 Página de Login</a>
<a href="/dashboard" class="btn btn-success">🏠 Dashboard</a>
<a href="/projects" class="btn btn-success">📋 Lista de Projetos</a>

<?php if (Auth::check()): ?>
    <h3>🔓 Usuário Logado - Links Funcionais:</h3>
    <a href="/projects/1/edit" class="btn btn-warning">✏️ Editar Projeto 1</a>
    <a href="/projects/2/edit" class="btn btn-warning">✏️ Editar Projeto 2</a>
    <a href="/projects/3/edit" class="btn btn-warning">✏️ Editar Projeto 3</a>
<?php else: ?>
    <h3>🔒 Usuário NÃO Logado - Links que Devem Redirecionar:</h3>
    <a href="/projects/1/edit" class="btn btn-warning">✏️ Tentar Editar Projeto 1 (deve redirecionar)</a>
    <a href="/projects/2/edit" class="btn btn-warning">✏️ Tentar Editar Projeto 2 (deve redirecionar)</a>
    <a href="/projects/3/edit" class="btn btn-warning">✏️ Tentar Editar Projeto 3 (deve redirecionar)</a>
<?php endif; ?>

<h2>Debug do Sistema:</h2>
<details>
    <summary>Ver Dados da Sessão</summary>
    <pre><?= print_r($_SESSION, true) ?></pre>
</details>

<details>
    <summary>Ver Variáveis do Servidor</summary>
    <pre><?= print_r($_SERVER, true) ?></pre>
</details>

<h2>Instruções:</h2>
<ol>
    <li><strong>Se não estiver logado:</strong> Clique em "Fazer Login como Admin"</li>
    <li><strong>Teste os links de edição:</strong> Devem funcionar se logado, redirecionar se não logado</li>
    <li><strong>Verifique o comportamento:</strong> O sistema deve redirecionar corretamente</li>
</ol>
