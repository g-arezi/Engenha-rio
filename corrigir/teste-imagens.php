<!DOCTYPE html>
<html>
<head>
    <title>Teste de Imagens</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #35363a; color: white; }
        .test-section { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        img { max-width: 300px; border: 2px solid #ddd; margin: 10px; }
    </style>
</head>
<body>
    <h1>🖼️ Teste de Imagens - Debug</h1>
    
    <div class="test-section">
        <h2>1. Teste de Acesso Direto às Imagens</h2>
        
        <h3>Nova Logo (PNG):</h3>
        <p>Caminho: /assets/images/engenhario-logo-new.png</p>
        <img src="/assets/images/engenhario-logo-new.png" alt="Nova Logo PNG" onerror="this.style.border='2px solid red'; this.alt='ERRO: Não foi possível carregar'">
        
        <h3>Logo Antiga (WEBP):</h3>
        <p>Caminho: /assets/images/logo.webp</p>
        <img src="/assets/images/logo.webp" alt="Logo Antiga WEBP" onerror="this.style.border='2px solid red'; this.alt='ERRO: Não foi possível carregar'">
        
        <h3>Cópia da Nova Logo:</h3>
        <p>Caminho: /assets/images/logo-new.png</p>
        <img src="/assets/images/logo-new.png" alt="Cópia Nova Logo" onerror="this.style.border='2px solid red'; this.alt='ERRO: Não foi possível carregar'">
    </div>
    
    <div class="test-section">
        <h2>2. Verificação de Arquivos no Servidor</h2>
        <?php
        $imagePath = __DIR__ . '/public/assets/images/';
        
        echo "<h3>Arquivos na pasta /public/assets/images/:</h3>";
        if (is_dir($imagePath)) {
            $files = scandir($imagePath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $imagePath . $file;
                    $size = filesize($filePath);
                    echo "<p>📄 <strong>$file</strong> - Tamanho: " . number_format($size) . " bytes</p>";
                }
            }
        } else {
            echo "<p class='error'>❌ Diretório não encontrado!</p>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>3. Teste de URLs Diretas</h2>
        <p><a href="/assets/images/engenhario-logo-new.png" target="_blank" style="color: #17a2b8;">🔗 Testar Nova Logo PNG</a></p>
        <p><a href="/assets/images/logo.webp" target="_blank" style="color: #17a2b8;">🔗 Testar Logo Antiga WEBP</a></p>
        <p><a href="/assets/images/logo-new.png" target="_blank" style="color: #17a2b8;">🔗 Testar Cópia Nova Logo</a></p>
    </div>
    
    <div class="test-section">
        <h2>4. Simulação das Páginas</h2>
        
        <h3>Como aparece na Home:</h3>
        <div style="background: #35363a; padding: 20px; text-align: center;">
            <img src="/assets/images/engenhario-logo-new.png" alt="Logo Home" style="width: 300px; height: auto; max-width: 100%;">
        </div>
        
        <h3>Como aparece no Login:</h3>
        <div style="background: #35363a; padding: 20px; text-align: center;">
            <img src="/assets/images/engenhario-logo-new.png" alt="Logo Login" style="width: 200px; height: auto;">
        </div>
        
        <h3>Como aparece na Sidebar:</h3>
        <div style="background: #35363a; padding: 20px;">
            <img src="/assets/images/engenhario-logo-new.png" alt="Logo Sidebar" style="width: 160px; height: auto; display: block; margin: 0 auto;">
        </div>
    </div>
    
    <script>
        // Log de erros de imagem
        document.querySelectorAll('img').forEach(img => {
            img.onload = function() {
                console.log('✅ Imagem carregada:', this.src);
            };
            img.onerror = function() {
                console.error('❌ Erro ao carregar imagem:', this.src);
                this.style.border = '3px solid red';
                this.style.background = '#ffebee';
            };
        });
    </script>
</body>
</html>
