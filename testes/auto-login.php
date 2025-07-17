<?php
/**
 * Auto-login para testar edição de projetos
 */

session_start();

require_once 'vendor/autoload.php';
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';

// Iniciar sessão usando a classe Session
\App\Core\Session::start();

echo "<h1>🔓 Auto-Login para Teste (Corrigido)</h1>";

try {
    $userModel = new \App\Models\User();
    
    // Buscar um usuário admin
    $adminUser = $userModel->find('admin_002');
    
    if ($adminUser) {
        echo "<h2>✅ Usuário Admin Encontrado</h2>";
        echo "Nome: {$adminUser['name']}<br>";
        echo "Email: {$adminUser['email']}<br>";
        echo "Role: {$adminUser['role']}<br>";
        
        // Fazer login usando a classe Session
        \App\Core\Session::set('user_id', $adminUser['id']);
        \App\Core\Session::set('user_name', $adminUser['name']);
        \App\Core\Session::set('user_email', $adminUser['email']);
        \App\Core\Session::set('role', $adminUser['role']);
        \App\Core\Session::set('logged_in', true);
        
        echo "<h2>✅ Login Realizado com Session::set()</h2>";
        echo "Dados de sessão:<br>";
        echo "- user_id: " . \App\Core\Session::get('user_id') . "<br>";
        echo "- role: " . \App\Core\Session::get('role') . "<br>";
        
        // Verificar se Auth reconhece
        $isLoggedIn = \App\Core\Auth::check();
        echo "Auth::check(): " . ($isLoggedIn ? 'true' : 'false') . "<br>";
        
        $user = \App\Core\Auth::user();
        echo "Auth::user(): " . ($user ? $user['name'] : 'null') . "<br>";
        
        if ($isLoggedIn) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h3>🎉 LOGIN REALIZADO COM SUCESSO!</h3>";
            echo "<p>Agora você pode testar a edição de projetos:</p>";
            echo "<ul>";
            echo "<li><a href='http://localhost:8000/projects' target='_blank'>📋 Ver Projetos</a></li>";
            echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>✏️ EDITAR Projeto 1</a></li>";
            echo "<li><a href='http://localhost:8000/projects/project_002/edit' target='_blank'>✏️ EDITAR Projeto 2</a></li>";
            echo "</ul>";
            echo "</div>";
            
            // Redirecionar automaticamente para a página de projetos
            echo "<script>";
            echo "setTimeout(function() {";
            echo "  window.location.href = 'http://localhost:8000/projects';";
            echo "}, 3000);";
            echo "</script>";
            echo "<p><em>Redirecionando para a página de projetos em 3 segundos...</em></p>";
            
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h3>❌ Problema na Autenticação</h3>";
            echo "<p>O login foi definido na sessão, mas Auth::check() retorna false.</p>";
            echo "<p>Isso indica um problema na implementação do Auth::check().</p>";
            echo "</div>";
        }
        
    } else {
        echo "<h2>❌ Usuário Admin Não Encontrado</h2>";
        echo "Tentando com ID 'admin_001'...<br>";
        
        // Criar um usuário temporário
        $_SESSION['user_id'] = 'admin_001';
        $_SESSION['user_name'] = 'Admin Teste';
        $_SESSION['user_email'] = 'admin@teste.com';
        $_SESSION['role'] = 'admin';
        $_SESSION['logged_in'] = true;
        
        echo "Usuário temporário criado na sessão.<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}

echo "<h2>📊 Estado da Sessão</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h2>🔧 Se ainda der 404...</h2>";
echo "<p>O problema pode estar em:</p>";
echo "<ol>";
echo "<li><strong>Cookie de sessão</strong> não sendo mantido</li>";
echo "<li><strong>Implementação do Auth::check()</strong></li>";
echo "<li><strong>Middleware de roteamento</strong></li>";
echo "</ol>";

echo "<h2>🎯 Links de Teste Direto</h2>";
echo "<ul>";
echo "<li><a href='/projects/project_001/edit' target='_blank'>Relativo: /projects/project_001/edit</a></li>";
echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>Absoluto: Editar Projeto 1</a></li>";
echo "</ul>";
?>
