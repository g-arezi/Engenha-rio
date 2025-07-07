<?php
session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

header('Content-Type: application/json');

// Simular a chamada do router
require_once 'vendor/autoload.php';
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/AdminController.php';

use App\Controllers\AdminController;

$userId = $_GET['id'] ?? 'admin_001';

try {
    $controller = new AdminController();
    $controller->editUser($userId);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
