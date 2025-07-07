<?php
// Test AdminController directly
require_once 'vendor/autoload.php';
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/AdminController.php';

use App\Controllers\AdminController;

session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

$controller = new AdminController();

echo "Testing AdminController editUser method...\n";

// Capture output
ob_start();
$controller->editUser('analyst_001');
$output = ob_get_contents();
ob_end_clean();

echo "Output: " . $output . "\n";

// Test without output buffering
echo "\nTesting without output buffering:\n";
$controller->editUser('analyst_001');
?>
