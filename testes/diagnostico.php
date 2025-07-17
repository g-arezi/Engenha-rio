<?php
// Diagnóstico do servidor para resolver erro 403

echo "<h1>Diagnóstico do Servidor - Engenha Rio</h1>";

echo "<h2>Informações Básicas</h2>";
echo "<p><strong>Data/Hora:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Servidor:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</p>";
echo "<p><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";

echo "<h2>Variáveis de Ambiente</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "</p>";

echo "<h2>Teste de Arquivos</h2>";
$files_to_check = [
    'index.php',
    'composer.json',
    'vendor/autoload.php',
    'config/security.php',
    'data/users.json',
    '.htaccess'
];

foreach ($files_to_check as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $writable = $exists ? is_writable($path) : false;
    
    echo "<p><strong>$file:</strong> ";
    echo "Existe: " . ($exists ? '✅' : '❌') . " | ";
    echo "Legível: " . ($readable ? '✅' : '❌') . " | ";
    echo "Gravável: " . ($writable ? '✅' : '❌');
    echo "</p>";
}

echo "<h2>Teste de Diretórios</h2>";
$dirs_to_check = [
    'src',
    'views',
    'public',
    'vendor',
    'data',
    'config'
];

foreach ($dirs_to_check as $dir) {
    $path = __DIR__ . '/' . $dir;
    $exists = is_dir($path);
    $readable = $exists ? is_readable($path) : false;
    
    echo "<p><strong>$dir/:</strong> ";
    echo "Existe: " . ($exists ? '✅' : '❌') . " | ";
    echo "Legível: " . ($readable ? '✅' : '❌');
    echo "</p>";
}

echo "<h2>Teste de Permissões</h2>";
echo "<p><strong>Usuário atual do processo:</strong> " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'N/A') . "</p>";
echo "<p><strong>Grupo atual do processo:</strong> " . (function_exists('posix_getgrgid') ? posix_getgrgid(posix_getegid())['name'] : 'N/A') . "</p>";

echo "<h2>Módulos Apache Carregados</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $important_modules = ['mod_rewrite', 'mod_headers', 'mod_expires'];
    
    foreach ($important_modules as $module) {
        echo "<p><strong>$module:</strong> " . (in_array($module, $modules) ? '✅ Carregado' : '❌ Não encontrado') . "</p>";
    }
} else {
    echo "<p>Função apache_get_modules() não disponível</p>";
}

echo "<h2>Teste de Autoload</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<p>✅ Autoload carregado com sucesso</p>";
        
        // Testar classes principais
        $classes_to_test = [
            'App\\Core\\Router',
            'App\\Core\\Session',
            'App\\Core\\Auth'
        ];
        
        foreach ($classes_to_test as $class) {
            echo "<p><strong>$class:</strong> " . (class_exists($class) ? '✅ Encontrada' : '❌ Não encontrada') . "</p>";
        }
    } else {
        echo "<p>❌ Arquivo vendor/autoload.php não encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Erro ao carregar autoload: " . $e->getMessage() . "</p>";
}

echo "<h2>Informações de Erro (Detalhadas)</h2>";
$error_reporting = ini_get('error_reporting');
$display_errors = ini_get('display_errors');
$log_errors = ini_get('log_errors');

echo "<p><strong>Error Reporting Numérico:</strong> $error_reporting</p>";

// Decodificar o valor numérico do error_reporting
$error_levels = [
    E_ERROR => 'E_ERROR',
    E_WARNING => 'E_WARNING', 
    E_PARSE => 'E_PARSE',
    E_NOTICE => 'E_NOTICE',
    E_CORE_ERROR => 'E_CORE_ERROR',
    E_CORE_WARNING => 'E_CORE_WARNING',
    E_COMPILE_ERROR => 'E_COMPILE_ERROR',
    E_COMPILE_WARNING => 'E_COMPILE_WARNING',
    E_USER_ERROR => 'E_USER_ERROR',
    E_USER_WARNING => 'E_USER_WARNING',
    E_USER_NOTICE => 'E_USER_NOTICE',
    E_STRICT => 'E_STRICT',
    E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
    E_DEPRECATED => 'E_DEPRECATED',
    E_USER_DEPRECATED => 'E_USER_DEPRECATED'
];

echo "<p><strong>Níveis de Erro Ativos:</strong> ";
$active_levels = [];
foreach ($error_levels as $level => $name) {
    if ($error_reporting & $level) {
        $active_levels[] = $name;
    }
}
echo implode(', ', $active_levels) ?: 'Nenhum';
echo "</p>";

echo "<p><strong>Display Errors:</strong> " . ($display_errors ? "✅ Habilitado ($display_errors)" : "❌ Desabilitado") . "</p>";
echo "<p><strong>Log Errors:</strong> " . ($log_errors ? "✅ Habilitado ($log_errors)" : "❌ Desabilitado") . "</p>";
echo "<p><strong>Error Log File:</strong> " . (ini_get('error_log') ?: 'Padrão do sistema') . "</p>";

// Verificar se conseguimos escrever logs
$log_test_file = __DIR__ . '/logs/test.log';
$log_dir = dirname($log_test_file);

if (!is_dir($log_dir)) {
    $created = @mkdir($log_dir, 0755, true);
    echo "<p><strong>Diretório de Logs:</strong> " . ($created ? "✅ Criado: $log_dir" : "❌ Falha ao criar: $log_dir") . "</p>";
} else {
    echo "<p><strong>Diretório de Logs:</strong> ✅ Existe: $log_dir</p>";
}

$can_write_log = @file_put_contents($log_test_file, 'Teste de log: ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
echo "<p><strong>Escrita de Logs:</strong> " . ($can_write_log ? "✅ Funcional" : "❌ Bloqueada") . "</p>";

if ($can_write_log) {
    @unlink($log_test_file); // Limpar arquivo de teste
}

echo "<h2>🔧 Sistema de Log Personalizado</h2>";
echo "<p>Como o log_errors está desabilitado no servidor, implementamos um sistema próprio:</p>";

// Verificar se CustomLogger está carregado
if (class_exists('CustomLogger')) {
    echo "<p><strong>CustomLogger:</strong> ✅ Carregado</p>";
    
    // Testar log personalizado
    $test_result = CustomLogger::info('Teste do sistema de log personalizado - ' . date('Y-m-d H:i:s'));
    echo "<p><strong>Teste de escrita:</strong> " . ($test_result ? '✅ Sucesso' : '❌ Falhou') . "</p>";
    
    // Listar arquivos de log existentes
    $log_files = CustomLogger::getLogFiles();
    if (!empty($log_files)) {
        echo "<p><strong>Arquivos de log encontrados:</strong></p>";
        echo "<ul>";
        foreach ($log_files as $file) {
            echo "<li>📄 $file</li>";
        }
        echo "</ul>";
        echo "<p><a href='visualizar-logs.php' class='btn btn-primary'>📋 Ver Logs Detalhados</a></p>";
    } else {
        echo "<p><strong>Arquivos de log:</strong> Nenhum encontrado ainda</p>";
    }
} else {
    echo "<p><strong>CustomLogger:</strong> ❌ Não carregado</p>";
}

echo "<h2>💡 Recomendações para Hostinger</h2>";
echo "<div style='background:#fff3cd; padding:15px; border-radius:5px;'>";
echo "<p><strong>Problema identificado:</strong> O servidor Hostinger tem log_errors desabilitado por padrão.</p>";
echo "<p><strong>Solução implementada:</strong> Sistema de log personalizado que contorna essa limitação.</p>";
echo "<p><strong>Benefícios:</strong></p>";
echo "<ul>";
echo "<li>✅ Logs funcionam independente das configurações do servidor</li>";
echo "<li>✅ Logs organizados por tipo (error, warning, info, debug, system)</li>";
echo "<li>✅ Interface web para visualização de logs</li>";
echo "<li>✅ Handlers automáticos de erro e exceção</li>";
echo "</ul>";
echo "</div>";

echo "<h2>Teste de Acesso ao Sistema</h2>";
echo '<p><a href="/login">🔗 Testar página de Login</a></p>';
echo '<p><a href="/dashboard">🔗 Testar Dashboard</a></p>';
echo '<p><a href="/documents">🔗 Testar Documentos</a></p>';

echo "<hr>";
echo "<p><small>Diagnóstico gerado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>
