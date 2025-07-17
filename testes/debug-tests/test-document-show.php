<?php
// Simple test to check if the document show route works
echo "Testing document show route...\n";

// Simulate a request to the documents show endpoint
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/documents/doc_001';

// Start session
session_start();

// Set up a basic auth session for testing
$_SESSION['user'] = [
    'id' => 'client_001',
    'name' => 'Test User',
    'email' => 'test@example.com',
    'role' => 'admin'
];

try {
    // Include the main application file
    require_once __DIR__ . '/index.php';
    echo "✓ Document show route works without errors\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
