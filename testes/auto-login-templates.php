<?php
// Acesso direto com login automático
require_once __DIR__ . '/init.php';

// Auto-login
App\Core\Session::start();
$_SESSION['user_id'] = 'admin_002';

// Redirecionar para templates
header('Location: /admin/document-templates');
exit;
?>
