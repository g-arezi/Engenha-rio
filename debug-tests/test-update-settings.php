<?php
// Teste do método updateSettings
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings/update');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'site_name' => 'Test Site',
    'admin_email' => 'test@example.com',
    'site_description' => 'Test Description'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);

// Primeiro, fazer login
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/force-login.php');
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
curl_exec($ch);

// Agora testar o updateSettings
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings/update');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'site_name' => 'Test Site',
    'admin_email' => 'test@example.com',
    'site_description' => 'Test Description'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);

$result = curl_exec($ch);
curl_close($ch);

echo "=== TESTE DO MÉTODO updateSettings ===\n";
echo "Resposta: " . $result . "\n";

// Verificar se é JSON válido
$json = json_decode($result, true);
if ($json !== null) {
    echo "✅ JSON válido retornado\n";
    echo "Success: " . ($json['success'] ? 'true' : 'false') . "\n";
    echo "Message: " . $json['message'] . "\n";
} else {
    echo "❌ Resposta não é JSON válido\n";
}

// Verificar se há erros
if (strpos($result, 'Fatal error') !== false) {
    echo "❌ ERRO FATAL encontrado!\n";
} else {
    echo "✅ Nenhum erro fatal encontrado\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "TESTE CONCLUÍDO!\n";
