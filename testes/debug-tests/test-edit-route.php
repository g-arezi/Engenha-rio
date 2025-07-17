<?php
// Test the admin routes directly
$url = "http://localhost:8000/admin/users/analyst_001/edit";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            "Content-Type: application/json",
            "Cookie: PHPSESSID=" . session_id()
        ]
    ]
]);

session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "Testing URL: $url\n";
$response = file_get_contents($url, false, $context);
if ($response) {
    echo "Response: $response\n";
} else {
    echo "Error: No response received\n";
    if (isset($http_response_header)) {
        echo "HTTP Headers: " . implode("\n", $http_response_header) . "\n";
    }
}
?>
