<?php
// Auto-login como admin para testar os botões
require_once 'init.php';

use App\Core\Auth;
use App\Models\User;

session_start();

$userModel = new User();
$admin = $userModel->findByEmail('admin@engenhario.com');

if ($admin) {
    // Fazer login automático
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['user_name'] = $admin['name'];
    $_SESSION['user_email'] = $admin['email'];
    $_SESSION['user_role'] = $admin['role'];
    
    echo "Login automático realizado como admin\n";
    echo "Acesse: http://localhost:8000/admin/users\n";
    echo "Você deve ver o usuário 'Teste user' pendente com botões Aprovar/Rejeitar\n";
    
    // Redirecionar para a página de usuários
    header('Location: /admin/users');
    exit;
} else {
    echo "Admin não encontrado!\n";
}
?>
