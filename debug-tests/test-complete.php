<?php
// Complete test of user management system
session_start();

// Set admin session
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test User Management System</title>
    <script>
        async function testComplete() {
            console.log('=== TESTING USER MANAGEMENT SYSTEM ===');
            
            // Test 1: Load edit user data
            console.log('1. Testing edit user data load...');
            try {
                const response = await fetch('/admin/users/analyst_001/edit');
                console.log('Edit response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Edit data:', data);
                    
                    if (data.success) {
                        console.log('✅ Edit user data loaded successfully');
                        console.log('User:', data.user);
                    } else {
                        console.log('❌ Edit failed:', data.error);
                    }
                } else {
                    console.log('❌ Edit request failed with status:', response.status);
                }
            } catch (error) {
                console.error('❌ Edit request error:', error);
            }
            
            // Test 2: Test update user (simulation)
            console.log('\\n2. Testing update user...');
            try {
                const updateData = {
                    name: 'João Silva - Updated',
                    email: 'analista@engenhario.com',
                    role: 'user',
                    active: true,
                    approved: true
                };
                
                const updateResponse = await fetch('/admin/users/analyst_001', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updateData)
                });
                
                console.log('Update response status:', updateResponse.status);
                
                if (updateResponse.ok) {
                    const updateResult = await updateResponse.json();
                    console.log('Update result:', updateResult);
                    
                    if (updateResult.success) {
                        console.log('✅ User updated successfully');
                    } else {
                        console.log('❌ Update failed:', updateResult.error);
                    }
                } else {
                    console.log('❌ Update request failed with status:', updateResponse.status);
                }
            } catch (error) {
                console.error('❌ Update request error:', error);
            }
            
            // Test 3: Test delete user (simulation - don't actually delete)
            console.log('\\n3. Testing delete user (simulation)...');
            console.log('Skipping actual delete to preserve test data');
            
            console.log('\\n=== TEST COMPLETE ===');
        }
        
        window.onload = testComplete;
    </script>
</head>
<body>
    <h1>User Management System Test</h1>
    <p>Session User ID: <?= $_SESSION['user_id'] ?? 'Not set' ?></p>
    <p>Session Role: <?= $_SESSION['role'] ?? 'Not set' ?></p>
    <button onclick="testComplete()">Run Tests</button>
    <p>Check browser console for results.</p>
</body>
</html>
