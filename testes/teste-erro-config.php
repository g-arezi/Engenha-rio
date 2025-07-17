<?php
// Teste específico para configurações de erro na hospedagem

echo "<h1>🔍 Teste de Configurações de Erro - Hospedagem</h1>";

echo "<h2>📊 Estado Atual das Configurações</h2>";

// Informações básicas
echo "<div style='background:#f8f9fa; padding:15px; border-radius:5px; margin:10px 0;'>";
echo "<h3>Informações do Sistema</h3>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>SAPI:</strong> " . php_sapi_name() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";
echo "<p><strong>Host:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "</p>";
echo "</div>";

// Configurações de erro
echo "<div style='background:#e8f5e8; padding:15px; border-radius:5px; margin:10px 0;'>";
echo "<h3>⚙️ Configurações de Erro</h3>";

$error_reporting = ini_get('error_reporting');
echo "<p><strong>Error Reporting (numérico):</strong> $error_reporting</p>";

// Decodificar error_reporting
$all_errors = E_ALL;
$current_errors = (int)$error_reporting;

echo "<p><strong>E_ALL valor:</strong> $all_errors</p>";
echo "<p><strong>Configuração atual:</strong> $current_errors</p>";

if ($current_errors == 0) {
    echo "<p><span style='color:red'>❌ Nenhum erro será reportado</span></p>";
} elseif ($current_errors == $all_errors) {
    echo "<p><span style='color:green'>✅ Todos os erros serão reportados</span></p>";
} else {
    echo "<p><span style='color:orange'>⚠️ Alguns erros serão reportados</span></p>";
}

echo "<p><strong>Display Errors:</strong> " . (ini_get('display_errors') ? '✅ ON' : '❌ OFF') . "</p>";
echo "<p><strong>Log Errors:</strong> " . (ini_get('log_errors') ? '✅ ON' : '❌ OFF') . "</p>";
echo "<p><strong>Error Log:</strong> " . (ini_get('error_log') ?: 'Sistema padrão') . "</p>";
echo "</div>";

// Teste de escrita de log
echo "<div style='background:#fff3cd; padding:15px; border-radius:5px; margin:10px 0;'>";
echo "<h3>📝 Teste de Logs</h3>";

$log_dir = __DIR__ . '/logs';
$log_file = $log_dir . '/test_error.log';

if (!is_dir($log_dir)) {
    $created = @mkdir($log_dir, 0755, true);
    echo "<p><strong>Criação do diretório logs:</strong> " . ($created ? '✅ Sucesso' : '❌ Falhou') . "</p>";
}

if (is_dir($log_dir)) {
    echo "<p><strong>Diretório logs:</strong> ✅ Existe</p>";
    echo "<p><strong>Permissão de escrita:</strong> " . (is_writable($log_dir) ? '✅ OK' : '❌ Negada') . "</p>";
    
    // Testar escrita de log
    $test_message = "Teste de log: " . date('Y-m-d H:i:s');
    $write_result = @error_log($test_message, 3, $log_file);
    echo "<p><strong>Teste de escrita de log:</strong> " . ($write_result ? '✅ Sucesso' : '❌ Falhou') . "</p>";
    
    if ($write_result && file_exists($log_file)) {
        echo "<p><strong>Arquivo de log criado:</strong> ✅ $log_file</p>";
        echo "<p><strong>Conteúdo:</strong> <code>" . htmlspecialchars(file_get_contents($log_file)) . "</code></p>";
        @unlink($log_file); // Limpar teste
    }
}
echo "</div>";

// Teste de trigger de erro
echo "<div style='background:#f8d7da; padding:15px; border-radius:5px; margin:10px 0;'>";
echo "<h3>🧪 Teste de Trigger de Erro</h3>";
echo "<p>Vamos gerar um erro controlado para testar:</p>";

// Capturar output para verificar se erros aparecem
ob_start();
@trigger_error("Este é um erro de teste - deve aparecer nos logs", E_USER_NOTICE);
$error_output = ob_get_clean();

if ($error_output) {
    echo "<p><span style='color:red'>❌ Erro apareceu na tela (display_errors está ON):</span></p>";
    echo "<pre style='background:#000; color:#0f0; padding:10px;'>" . htmlspecialchars($error_output) . "</pre>";
} else {
    echo "<p><span style='color:green'>✅ Erro não apareceu na tela (display_errors está OFF - correto para produção)</span></p>";
}
echo "</div>";

// Configurações recomendadas
echo "<div style='background:#d1ecf1; padding:15px; border-radius:5px; margin:10px 0;'>";
echo "<h3>💡 Configurações Recomendadas para Produção</h3>";
echo "<ul>";
echo "<li><strong>error_reporting:</strong> " . (E_ERROR | E_WARNING | E_PARSE) . " (só erros críticos)</li>";
echo "<li><strong>display_errors:</strong> OFF (0)</li>";
echo "<li><strong>log_errors:</strong> ON (1)</li>";
echo "<li><strong>error_log:</strong> Arquivo específico da aplicação</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Links úteis:</strong></p>";
echo "<p><a href='index.php'>🔗 Ir para o sistema</a></p>";
echo "<p><a href='diagnostico.php'>🔗 Diagnóstico completo</a></p>";
echo "<p><a href='teste.php'>🔗 Teste básico</a></p>";

echo "<hr>";
echo "<p><small>Teste realizado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>
