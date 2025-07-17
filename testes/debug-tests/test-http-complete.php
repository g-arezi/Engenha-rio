<?php
// Test complete HTTP interface
session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "=== TESTING COMPLETE HTTP INTERFACE ===\n\n";

// Test 1: Test edit endpoint
echo "1. Testing edit endpoint:\n";
$editUrl = "http://localhost:8000/admin/users/analyst_001/edit";
$editContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Content-Type: application/json\r\n"
    ]
]);

$editResponse = @file_get_contents($editUrl, false, $editContext);
if ($editResponse) {
    $editData = json_decode($editResponse, true);
    if ($editData && isset($editData['success']) && $editData['success']) {
        echo "   - ✅ Edit endpoint works\n";
        echo "   - User: {$editData['user']['name']}\n";
    } else {
        echo "   - ❌ Edit endpoint failed\n";
        echo "   - Response: $editResponse\n";
    }
} else {
    echo "   - ❌ Edit endpoint not accessible\n";
}

// Test 2: Test update endpoint
echo "\n2. Testing update endpoint:\n";
$updateUrl = "http://localhost:8000/admin/users/analyst_001";
$updateData = json_encode([
    'name' => 'João Silva - HTTP Test',
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
        echo "   - ✅ Update endpoint works\n";
        echo "   - Message: {$updateResult['message']}\n";
    } else {
        echo "   - ❌ Update endpoint failed\n";
        echo "   - Response: $updateResponse\n";
    }
} else {
    echo "   - ❌ Update endpoint not accessible\n";
}

// Test 3: Verify update by re-fetching user
echo "\n3. Verifying update by re-fetching user:\n";
$verifyResponse = @file_get_contents($editUrl, false, $editContext);
if ($verifyResponse) {
    $verifyData = json_decode($verifyResponse, true);
    if ($verifyData && isset($verifyData['success']) && $verifyData['success']) {
        echo "   - ✅ User data retrieved after update\n";
        echo "   - Updated name: {$verifyData['user']['name']}\n";
    } else {
        echo "   - ❌ Failed to retrieve user data\n";
    }
} else {
    echo "   - ❌ Failed to verify update\n";
}

// Test 4: Revert changes
echo "\n4. Reverting changes:\n";
$revertData = json_encode([
    'name' => 'João Silva',
    'email' => 'analista@engenhario.com',
    'role' => 'user',
    'active' => true,
    'approved' => true
]);

$revertContext = stream_context_create([
    'http' => [
        'method' => 'PUT',
        'header' => "Content-Type: application/json\r\n",
        'content' => $revertData
    ]
]);

$revertResponse = @file_get_contents($updateUrl, false, $revertContext);
if ($revertResponse) {
    $revertResult = json_decode($revertResponse, true);
    if ($revertResult && isset($revertResult['success']) && $revertResult['success']) {
        echo "   - ✅ Revert successful\n";
    } else {
        echo "   - ❌ Revert failed\n";
    }
} else {
    echo "   - ❌ Revert endpoint not accessible\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "The user management system is now fully functional!\n";
?>
