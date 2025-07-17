<?php
// Teste básico de funcionamento
echo "✅ Servidor PHP funcionando!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Data: " . date('Y-m-d H:i:s') . "<br>";

// Teste de arquivo
if (file_exists('index.php')) {
    echo "✅ index.php encontrado<br>";
} else {
    echo "❌ index.php não encontrado<br>";
}

// Teste de composer
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer autoload encontrado<br>";
} else {
    echo "❌ Composer autoload não encontrado<br>";
}

echo "<hr>";
echo '<a href="index.php">🔗 Ir para o sistema</a><br>';
echo '<a href="diagnostico.php">🔗 Diagnóstico completo</a>';
?>
