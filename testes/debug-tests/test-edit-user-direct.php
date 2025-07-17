<?php
// Test edit user functionality
require_once 'vendor/autoload.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';

use App\Models\User;

// Initialize session
session_start();

// Set admin session
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

// Test direct User model method
$userModel = new User();
$users = $userModel->all();

echo "=== TESTING EDIT USER FUNCTIONALITY ===\n";

// List all users first
echo "\n1. Available Users:\n";
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Status: {$user['status']}\n";
}

// Test finding a specific user
$testUserId = 'analyst_001'; // Use a known user ID
$user = $userModel->find($testUserId);

if ($user) {
    echo "\n2. Found User ID $testUserId:\n";
    echo "   Name: {$user['name']}\n";
    echo "   Email: {$user['email']}\n";
    echo "   Role: {$user['role']}\n";
    echo "   Status: {$user['status']}\n";
} else {
    echo "\n2. User ID $testUserId not found!\n";
}

// Test edit user via HTTP request
echo "\n3. Testing HTTP GET to /admin/users/edit/$testUserId\n";
$url = "http://localhost:8000/admin/users/edit/$testUserId";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Content-Type: application/json\r\n"
    ]
]);

$response = file_get_contents($url, false, $context);
if ($response) {
    echo "Response: $response\n";
} else {
    echo "Error: No response received\n";
}
?>
