<?php
// Teste de acesso direto ao histórico
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/history');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

// Primeiro, fazer login
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/force-login.php');
$loginResult = curl_exec($ch);

// Agora acessar o histórico
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/history');
$historyResult = curl_exec($ch);

curl_close($ch);

echo "=== TESTE DE ACESSO AO HISTÓRICO ===\n";
echo "Tamanho da resposta: " . strlen($historyResult) . " bytes\n";

// Verificar se há warnings
if (strpos($historyResult, 'Undefined array key') !== false) {
    echo "ERRO: Warnings encontrados!\n";
    
    // Extrair warnings
    preg_match_all('/Warning:.*?Undefined array key.*?line \d+/', $historyResult, $matches);
    foreach ($matches[0] as $warning) {
        echo "  " . $warning . "\n";
    }
} else {
    echo "OK: Nenhum warning encontrado!\n";
}

// Verificar se a página foi renderizada
if (strpos($historyResult, 'Histórico do Sistema') !== false) {
    echo "OK: Página renderizada corretamente!\n";
} else {
    echo "ERRO: Página não foi renderizada corretamente!\n";
}

// Verificar se há elementos essenciais
if (strpos($historyResult, 'historyContainer') !== false) {
    echo "OK: Container de histórico presente!\n";
} else {
    echo "ERRO: Container de histórico ausente!\n";
}

// Salvar resultado para análise
file_put_contents('debug-history-output.html', $historyResult);
echo "Saída salva em: debug-history-output.html\n";
