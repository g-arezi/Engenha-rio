<?php
// Test script to check documents functionality
require_once __DIR__ . '/vendor/autoload.php';

echo "Testing Documents Functionality...\n";

// Test 1: Check if the document model works
try {
    $document = new App\Models\Document();
    $documents = $document->all();
    echo "✓ Document model works - found " . count($documents) . " documents\n";
} catch (Exception $e) {
    echo "✗ Document model error: " . $e->getMessage() . "\n";
}

// Test 2: Check if the view file exists
$viewFile = __DIR__ . '/views/documents/show.php';
if (file_exists($viewFile)) {
    echo "✓ View file exists: documents/show.php\n";
} else {
    echo "✗ View file missing: documents/show.php\n";
}

// Test 3: Check if Auth usage is fixed
$content = file_get_contents($viewFile);
if (strpos($content, 'Auth::') !== false) {
    echo "✗ Auth:: usage still present in view\n";
} else {
    echo "✓ Auth:: usage removed from view\n";
}

echo "\nTest completed.\n";
