<?php
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Core\Session;
use App\Core\Auth;

// Inicializar
Config::load();
Session::start();

echo "<h2>🚪 Logout e Reset de Sessão</h2>";

// Fazer logout
Auth::logout();

echo "<p>✅ Logout realizado com sucesso!</p>";
echo "<p>Sessão limpa e resetada.</p>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>Agora você pode fazer login novamente:</h3>";
echo "<p><a href='/login' class='btn btn-primary'>Ir para Login</a></p>";
echo "<p><a href='/login-test.html' class='btn btn-secondary'>Página de Teste</a></p>";
echo "</div>";

echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>Credenciais disponíveis:</h4>";
echo "<ul>";
echo "<li><strong>admin@sistema.com</strong> / password (Administrador)</li>";
echo "<li><strong>admin@engenhario.com</strong> / 123456 (Administrador)</li>";
echo "<li><strong>analista@engenhario.com</strong> / 123456 (Analista)</li>";
echo "</ul>";
echo "</div>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
h2, h3, h4 { color: #333; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
.btn { 
    display: inline-block;
    padding: 10px 20px; 
    background: #007bff; 
    color: white; 
    text-decoration: none; 
    border-radius: 5px; 
    margin: 5px;
}
.btn-secondary { background: #6c757d; }
.btn:hover { opacity: 0.8; }
</style>
