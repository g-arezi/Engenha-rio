<?php
// Test update functionality
require_once 'vendor/autoload.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';

use App\Models\User;

echo "=== TESTING UPDATE FUNCTIONALITY ===\n\n";

$userModel = new User();

// Test 1: Find user before update
echo "1. Finding user before update:\n";
$user = $userModel->find('analyst_001');
if ($user) {
    echo "   - User found: {$user['name']}\n";
    echo "   - Email: {$user['email']}\n";
    echo "   - Role: {$user['role']}\n";
    echo "   - Active: " . ($user['active'] ? 'true' : 'false') . "\n";
} else {
    echo "   - User not found!\n";
    exit;
}

// Test 2: Update user
echo "\n2. Updating user:\n";
$updateData = [
    'name' => 'João Silva - Updated via Test',
    'role' => 'moderator'
];

$success = $userModel->update('analyst_001', $updateData);
if ($success) {
    echo "   - ✅ Update successful\n";
} else {
    echo "   - ❌ Update failed\n";
}

// Test 3: Find user after update
echo "\n3. Finding user after update:\n";
$updatedUser = $userModel->find('analyst_001');
if ($updatedUser) {
    echo "   - User found: {$updatedUser['name']}\n";
    echo "   - Email: {$updatedUser['email']}\n";
    echo "   - Role: {$updatedUser['role']}\n";
    echo "   - Active: " . ($updatedUser['active'] ? 'true' : 'false') . "\n";
    echo "   - Updated at: {$updatedUser['updated_at']}\n";
} else {
    echo "   - User not found after update!\n";
}

// Test 4: Revert changes
echo "\n4. Reverting changes:\n";
$revertData = [
    'name' => 'João Silva',
    'role' => 'user'
];

$revertSuccess = $userModel->update('analyst_001', $revertData);
if ($revertSuccess) {
    echo "   - ✅ Revert successful\n";
} else {
    echo "   - ❌ Revert failed\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
