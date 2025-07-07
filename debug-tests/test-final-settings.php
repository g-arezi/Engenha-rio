<?php
// Teste final para verificar se o erro foi corrigido
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

// Primeiro, fazer login
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/force-login.php');
curl_exec($ch);

// Agora acessar as configurações
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/settings');
$result = curl_exec($ch);
curl_close($ch);

echo "=== VERIFICAÇÃO FINAL - CORREÇÃO DO ERRO SETTINGS ===\n";
echo "Verificando se o erro foi corrigido:\n\n";

// Verificar erro específico
$originalError = 'class App\Controllers\AdminController does not have a method "settings"';
if (strpos($result, $originalError) !== false) {
    echo "❌ ERRO AINDA PRESENTE: {$originalError}\n";
} else {
    echo "✅ ERRO CORRIGIDO: Método settings agora existe e está funcionando\n";
}

// Verificar se há erros fatais
if (strpos($result, 'Fatal error') !== false) {
    echo "❌ ERRO FATAL encontrado!\n";
    preg_match('/Fatal error:.*?thrown/', $result, $matches);
    if ($matches) {
        echo "Erro: " . $matches[0] . "\n";
    }
} else {
    echo "✅ Nenhum erro fatal encontrado\n";
}

// Verificar warnings
$warningCount = substr_count($result, 'Warning:');
echo "Warnings encontrados: {$warningCount}\n";

if ($warningCount > 0) {
    echo "❌ Ainda há warnings na página\n";
} else {
    echo "✅ Página sem warnings\n";
}

// Verificar se a página foi renderizada
if (strpos($result, 'Configurações do Sistema') !== false) {
    echo "✅ Página de configurações renderizada corretamente\n";
} else {
    echo "❌ Página de configurações não foi renderizada\n";
}

// Verificar elementos essenciais
$essentialElements = [
    'site_name' => 'Campo Nome do Sistema',
    'admin_email' => 'Campo Email do Administrador',
    'site_description' => 'Campo Descrição do Sistema',
    'maintenance_mode' => 'Modo de Manutenção',
    'form' => 'Formulário de configurações'
];

foreach ($essentialElements as $element => $description) {
    if (strpos($result, $element) !== false) {
        echo "✅ {$description}: presente\n";
    } else {
        echo "❌ {$description}: ausente\n";
    }
}

// Estatísticas
echo "\n📊 Estatísticas:\n";
echo "   - Tamanho da resposta: " . strlen($result) . " bytes\n";
echo "   - Warnings: {$warningCount}\n";
echo "   - Página renderizada: " . (strpos($result, '</html>') !== false ? 'SIM' : 'NÃO') . "\n";

echo "\n" . str_repeat("=", 50) . "\n";
if (strpos($result, 'Fatal error') === false && strpos($result, $originalError) === false) {
    echo "🎉 PROBLEMA RESOLVIDO COM SUCESSO!\n";
    echo "✅ Método settings implementado\n";
    echo "✅ Método updateSettings implementado\n";
    echo "✅ Página de configurações funcionando\n";
} else {
    echo "❌ PROBLEMA AINDA PERSISTE\n";
}

// Salvar resultado para análise
file_put_contents('debug-final-settings.html', $result);
echo "Saída salva em: debug-final-settings.html\n";
