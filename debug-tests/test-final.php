<?php
// Final comprehensive test of user management system
require_once 'vendor/autoload.php';
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/AdminController.php';

use App\Controllers\AdminController;
use App\Models\User;

session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "=== COMPREHENSIVE USER MANAGEMENT SYSTEM TEST ===\n\n";

// Test 1: Direct Model Methods
echo "1. Testing User Model Methods:\n";
$userModel = new User();
$users = $userModel->all();
echo "   - Total users: " . count($users) . "\n";

$testUser = $userModel->find('analyst_001');
if ($testUser) {
    echo "   - Found user: {$testUser['name']} ({$testUser['email']})\n";
} else {
    echo "   - User not found!\n";
}

// Test 2: Controller Methods
echo "\n2. Testing AdminController Methods:\n";
$controller = new AdminController();

// Test editUser method
echo "   - Testing editUser method:\n";
ob_start();
try {
    $controller->editUser('analyst_001');
} catch (Exception $e) {
    echo "     Error: " . $e->getMessage() . "\n";
}
$output = ob_get_clean();
$jsonData = json_decode($output, true);
if ($jsonData && isset($jsonData['success']) && $jsonData['success']) {
    echo "     ✅ editUser method works correctly\n";
    echo "     User: {$jsonData['user']['name']}\n";
} else {
    echo "     ❌ editUser method failed\n";
    echo "     Output: $output\n";
}

// Test 3: HTTP Requests
echo "\n3. Testing HTTP Requests:\n";

// Test edit endpoint
$editUrl = "http://localhost:8000/admin/users/analyst_001/edit";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Content-Type: application/json\r\n"
    ]
]);

$response = @file_get_contents($editUrl, false, $context);
if ($response) {
    $responseData = json_decode($response, true);
    if ($responseData && isset($responseData['success']) && $responseData['success']) {
        echo "   - ✅ Edit endpoint works correctly\n";
        echo "     Response: User {$responseData['user']['name']}\n";
    } else {
        echo "   - ❌ Edit endpoint failed\n";
        echo "     Response: $response\n";
    }
} else {
    echo "   - ❌ Edit endpoint not accessible\n";
}

// Test update endpoint
$updateUrl = "http://localhost:8000/admin/users/analyst_001";
$updateData = json_encode([
    'name' => 'João Silva - Test Update',
    'email' => 'analista@engenhario.com',
    'role' => 'user',
    'active' => true,
    'approved' => true
]);

$updateContext = stream_context_create([
    'http' => [
        'method' => 'PUT',
        'header' => "Content-Type: application/json\r\n",
        'content' => $updateData
    ]
]);

$updateResponse = @file_get_contents($updateUrl, false, $updateContext);
if ($updateResponse) {
    $updateResult = json_decode($updateResponse, true);
    if ($updateResult && isset($updateResult['success']) && $updateResult['success']) {
        echo "   - ✅ Update endpoint works correctly\n";
    } else {
        echo "   - ❌ Update endpoint failed\n";
        echo "     Response: $updateResponse\n";
    }
} else {
    echo "   - ❌ Update endpoint not accessible\n";
}

// Test 4: Interface Access
echo "\n4. Testing Interface Access:\n";
$adminUsersUrl = "http://localhost:8000/admin/users";
$adminContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Content-Type: text/html\r\n"
    ]
]);

$adminResponse = @file_get_contents($adminUsersUrl, false, $adminContext);
if ($adminResponse && strpos($adminResponse, 'Gerenciamento de Usuários') !== false) {
    echo "   - ✅ Admin users interface accessible\n";
    echo "   - Page contains expected content\n";
} else {
    echo "   - ❌ Admin users interface not accessible or missing content\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "All major components tested. System should be functional.\n";
?>
