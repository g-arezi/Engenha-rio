<?php
// Teste direto da rota settings
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

// Primeiro, fazer login
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/force-login.php');
$loginResult = curl_exec($ch);

// Agora acessar as configurações
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings');
$settingsResult = curl_exec($ch);

curl_close($ch);

echo "=== TESTE DA ROTA SETTINGS ===\n";
echo "Tamanho da resposta: " . strlen($settingsResult) . " bytes\n";

// Verificar se há erros
if (strpos($settingsResult, 'Fatal error') !== false) {
    echo "❌ ERRO FATAL encontrado!\n";
    
    // Extrair o erro
    preg_match('/Fatal error:.*?thrown/', $settingsResult, $matches);
    if ($matches) {
        echo "Erro: " . $matches[0] . "\n";
    }
} else {
    echo "✅ Nenhum erro fatal encontrado!\n";
}

// Verificar se a página foi renderizada
if (strpos($settingsResult, 'Configurações do Sistema') !== false) {
    echo "✅ Página renderizada corretamente!\n";
} else {
    echo "❌ Página não foi renderizada corretamente!\n";
}

// Verificar elementos da página
$elements = [
    'settings-card' => 'Cards de configuração',
    'settingsForm' => 'Formulário de configurações',
    'v-pills-system' => 'Aba Sistema',
    'v-pills-users' => 'Aba Usuários',
    'v-pills-security' => 'Aba Segurança'
];

foreach ($elements as $element => $description) {
    if (strpos($settingsResult, $element) !== false) {
        echo "✅ {$description}: presente\n";
    } else {
        echo "❌ {$description}: ausente\n";
    }
}

// Salvar resultado para análise
file_put_contents('debug-settings-output.html', $settingsResult);
echo "Saída salva em: debug-settings-output.html\n";
