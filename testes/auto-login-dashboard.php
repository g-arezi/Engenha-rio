<?php
// Auto-login para visualizar a dashboard
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
    echo "Redirecionando para dashboard...\n";
    
    // Redirecionar para a dashboard
    header('Location: /dashboard');
    exit;
} else {
    echo "Admin não encontrado!\n";
}
?>
