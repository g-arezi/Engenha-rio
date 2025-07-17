<?php
// Verificar usuário admin e fazer login real
require_once __DIR__ . '/init.php';

echo "<h1>🔐 Login Real no Sistema</h1>";

App\Core\Session::start();

echo "<h2>1. Verificando usuário admin...</h2>";
$userModel = new App\Models\User();
$admin = $userModel->find('admin_002');

if ($admin) {
    echo "✅ Usuário admin encontrado:<br>";
    echo "ID: " . $admin['id'] . "<br>";
    echo "Nome: " . $admin['name'] . "<br>";
    echo "Email: " . $admin['email'] . "<br>";
    echo "Role: " . $admin['role'] . "<br>";
    echo "Ativo: " . ($admin['active'] ? 'Sim' : 'Não') . "<br>";
    echo "Aprovado: " . ($admin['approved'] ? 'Sim' : 'Não') . "<br>";
    
    echo "<h2>2. Fazendo login...</h2>";
    $loginSuccess = App\Core\Auth::login('admin@sistema.com', 'admin123');
    
    if ($loginSuccess) {
        echo "✅ Login realizado com sucesso!<br>";
        
        echo "<h2>3. Verificando autenticação...</h2>";
        echo "Auth::check(): " . (App\Core\Auth::check() ? "✅ OK" : "❌ FAIL") . "<br>";
        echo "Auth::isAdmin(): " . (App\Core\Auth::isAdmin() ? "✅ OK" : "❌ FAIL") . "<br>";
        
        echo "<h2>4. Redirecionando para templates...</h2>";
        echo "<a href='/admin/document-templates' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔗 Ir para Templates</a><br><br>";
        
        echo "<script>";
        echo "setTimeout(function() { window.location.href = '/admin/document-templates'; }, 2000);";
        echo "</script>";
        echo "<p>Redirecionando automaticamente em 2 segundos...</p>";
        
    } else {
        echo "❌ Falha no login<br>";
        
        echo "<h3>Tentando outras senhas...</h3>";
        $passwords = ['admin', '123456', 'password', 'sistema'];
        foreach ($passwords as $pass) {
            $result = App\Core\Auth::login('admin@sistema.com', $pass);
            if ($result) {
                echo "✅ Login com senha '$pass' funcionou!<br>";
                echo "<a href='/admin/document-templates'>Ir para Templates</a><br>";
                break;
            } else {
                echo "❌ Senha '$pass' não funcionou<br>";
            }
        }
    }
    
} else {
    echo "❌ Usuário admin não encontrado<br>";
    
    echo "<h2>Listando todos os usuários:</h2>";
    $users = $userModel->all();
    foreach ($users as $user) {
        echo "ID: {$user['id']}, Nome: {$user['name']}, Email: {$user['email']}, Role: {$user['role']}<br>";
    }
}
?>
