<?php
// Debug específico para autenticação
session_start();

// Forçar login como admin
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

require_once 'vendor/autoload.php';
require_once 'src/Core/Session.php';
require_once 'src/Core/Auth.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';

use App\Core\Auth;
use App\Core\Session;
use App\Models\User;

echo "=== DEBUG AUTENTICAÇÃO ===\n\n";

// Verificar Session
echo "1. Verificando Session:\n";
echo "   - Session ID: " . session_id() . "\n";
echo "   - Session user_id: " . ($_SESSION['user_id'] ?? 'não definido') . "\n";
echo "   - Session role: " . ($_SESSION['role'] ?? 'não definido') . "\n";

// Verificar Session class
echo "\n2. Verificando Session class:\n";
echo "   - Session::has('user_id'): " . (Session::has('user_id') ? 'true' : 'false') . "\n";
echo "   - Session::get('user_id'): " . (Session::get('user_id') ?? 'null') . "\n";

// Verificar Auth
echo "\n3. Verificando Auth:\n";
echo "   - Auth::check(): " . (Auth::check() ? 'true' : 'false') . "\n";

$user = Auth::user();
if ($user) {
    echo "   - Auth::user(): encontrado\n";
    echo "     - ID: " . $user['id'] . "\n";
    echo "     - Nome: " . $user['name'] . "\n";
    echo "     - Role: " . $user['role'] . "\n";
    echo "   - Auth::isAdmin(): " . (Auth::isAdmin() ? 'true' : 'false') . "\n";
} else {
    echo "   - Auth::user(): não encontrado\n";
}

// Verificar User model diretamente
echo "\n4. Verificando User model:\n";
$userModel = new User();
$directUser = $userModel->find('admin_001');
if ($directUser) {
    echo "   - User::find('admin_001'): encontrado\n";
    echo "     - Nome: " . $directUser['name'] . "\n";
    echo "     - Role: " . $directUser['role'] . "\n";
} else {
    echo "   - User::find('admin_001'): não encontrado\n";
}

echo "\n=== FIM DEBUG ===\n";
?>
