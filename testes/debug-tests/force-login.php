<?php
// Forçar login e redirecionar para admin/users
session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

header('Location: /admin/users');
exit;
?>
