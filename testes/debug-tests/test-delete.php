<?php
// Test delete functionality
require_once 'vendor/autoload.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';

use App\Models\User;

echo "=== TESTING DELETE FUNCTIONALITY ===\n\n";

$userModel = new User();

// Test 1: Count users before
echo "1. Counting users before test:\n";
$users = $userModel->all();
echo "   - Total users: " . count($users) . "\n";

// Test 2: Create a test user for deletion
echo "\n2. Creating test user for deletion:\n";
$testUserData = [
    'name' => 'Test User for Deletion',
    'email' => 'delete-test@example.com',
    'password' => password_hash('test123', PASSWORD_DEFAULT),
    'role' => 'user',
    'active' => true,
    'approved' => true
];

$testUserId = $userModel->create($testUserData);
echo "   - Created test user with ID: $testUserId\n";

// Test 3: Verify user exists
echo "\n3. Verifying test user exists:\n";
$testUser = $userModel->find($testUserId);
if ($testUser) {
    echo "   - ✅ Test user found: {$testUser['name']}\n";
} else {
    echo "   - ❌ Test user not found!\n";
    exit;
}

// Test 4: Delete the test user
echo "\n4. Deleting test user:\n";
$success = $userModel->delete($testUserId);
if ($success) {
    echo "   - ✅ Delete successful\n";
} else {
    echo "   - ❌ Delete failed\n";
}

// Test 5: Verify user is deleted
echo "\n5. Verifying user is deleted:\n";
$deletedUser = $userModel->find($testUserId);
if ($deletedUser) {
    echo "   - ❌ User still exists after deletion!\n";
} else {
    echo "   - ✅ User successfully deleted\n";
}

// Test 6: Count users after
echo "\n6. Counting users after test:\n";
$usersAfter = $userModel->all();
echo "   - Total users: " . count($usersAfter) . "\n";

echo "\n=== TEST COMPLETE ===\n";
?>
