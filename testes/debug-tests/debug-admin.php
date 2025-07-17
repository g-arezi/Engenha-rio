<?php
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;

Session::start();

echo "<h2>Debug Admin Status</h2>";
echo "<p><strong>Usuário logado:</strong> " . (Auth::check() ? 'SIM' : 'NÃO') . "</p>";

if (Auth::check()) {
    $user = Auth::user();
    echo "<p><strong>Dados do usuário:</strong></p>";
    echo "<pre>" . print_r($user, true) . "</pre>";
    
    echo "<p><strong>É Admin:</strong> " . (Auth::isAdmin() ? 'SIM' : 'NÃO') . "</p>";
    echo "<p><strong>É Analista:</strong> " . (Auth::isAnalyst() ? 'SIM' : 'NÃO') . "</p>";
    echo "<p><strong>Role:</strong> " . ($user['role'] ?? 'N/A') . "</p>";
}

echo "<hr>";
echo "<p><a href='/login'>Login</a> | <a href='/dashboard'>Dashboard</a></p>";
?>
