<?php
// Teste de login automático para resolver o problema de acesso
session_start();

echo "<h2>Teste de Login e Acesso</h2>";

// Incluir as classes necessárias
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';

// Dados de login para teste (admin)
$email = 'admin@engenhario.com';
$password = 'admin123';

echo "<h3>1. Estado Inicial da Sessão:</h3>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Dados da sessão: " . print_r($_SESSION, true) . "</p>";
echo "<p>Auth::check(): " . (Auth::check() ? 'true' : 'false') . "</p>";

if (isset($_POST['fazer_login'])) {
    echo "<h3>2. Fazendo Login:</h3>";
    
    try {
        $user = User::authenticate($email, $password);
        
        if ($user) {
            echo "<p>✅ Usuário autenticado com sucesso!</p>";
            echo "<p>Dados do usuário: " . print_r($user, true) . "</p>";
            
            // Fazer login manual
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role']);
            
            echo "<p>✅ Sessão configurada!</p>";
            echo "<p>Dados da sessão após login: " . print_r($_SESSION, true) . "</p>";
            echo "<p>Auth::check() após login: " . (Auth::check() ? 'true' : 'false') . "</p>";
            
        } else {
            echo "<p>❌ Falha na autenticação!</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erro durante login: " . $e->getMessage() . "</p>";
    }
}

if (isset($_POST['testar_acesso'])) {
    echo "<h3>3. Testando Acesso ao Projeto:</h3>";
    
    if (Auth::check()) {
        echo "<p>✅ Usuário está autenticado!</p>";
        echo "<p><a href='/projects/1/edit' target='_blank' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Tentar Editar Projeto 1</a></p>";
        echo "<p><a href='/projects' target='_blank' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Ver Lista de Projetos</a></p>";
    } else {
        echo "<p>❌ Usuário não está autenticado!</p>";
    }
}

if (isset($_POST['limpar_sessao'])) {
    session_destroy();
    session_start();
    echo "<p>🧹 Sessão limpa!</p>";
}

?>

<hr>
<h3>Ações:</h3>
<form method="POST" style="display: inline;">
    <button type="submit" name="fazer_login" style="background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;">Fazer Login</button>
</form>

<form method="POST" style="display: inline;">
    <button type="submit" name="testar_acesso" style="background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;">Testar Acesso</button>
</form>

<form method="POST" style="display: inline;">
    <button type="submit" name="limpar_sessao" style="background: #dc3545; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;">Limpar Sessão</button>
</form>

<hr>
<h3>Links Diretos:</h3>
<p><a href="/login" style="background: #6c757d; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">Página de Login</a></p>
<p><a href="/projects" style="background: #17a2b8; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">Lista de Projetos</a></p>
<p><a href="/projects/1/edit" style="background: #ffc107; color: black; padding: 10px; text-decoration: none; border-radius: 5px;">Editar Projeto 1</a></p>

<hr>
<h3>Debug Info:</h3>
<p><strong>REQUEST_URI:</strong> <?= $_SERVER['REQUEST_URI'] ?? 'não definido' ?></p>
<p><strong>HTTP_HOST:</strong> <?= $_SERVER['HTTP_HOST'] ?? 'não definido' ?></p>
<p><strong>Session Status:</strong> <?= session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa' ?></p>
