<?php
require_once 'init.php';

use App\Core\Auth;
use App\Models\User;

// Fazer login como admin
if (Auth::login('admin@engenhario.com', 'admin123')) {
    echo "✅ Login realizado com sucesso!<br>";
    echo "Redirecionando para templates...<br>";
    echo "<script>window.location.href = '/admin/document-templates/create';</script>";
} else {
    echo "❌ Falha no login<br>";
    
    // Verificar usuários disponíveis
    $userModel = new User();
    $users = $userModel->all();
    
    echo "<h3>Usuários disponíveis:</h3>";
    foreach ($users as $user) {
        echo "- Email: " . $user['email'] . " | Role: " . $user['role'] . "<br>";
    }
}
?>
