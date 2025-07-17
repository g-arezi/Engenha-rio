<?php
// Auto-login para admin e redirecionamento
require_once __DIR__ . '/init.php';

App\Core\Session::start();

// Fazer login como admin
$loginSuccess = App\Core\Auth::login('admin@sistema.com', 'admin123');

if ($loginSuccess) {
    // Redirecionar para templates
    header('Location: /admin/document-templates');
    exit;
} else {
    echo "Erro no login. <a href='/login'>Fazer login manual</a>";
}
?>
