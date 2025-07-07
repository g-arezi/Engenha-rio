<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Visual Final - Logos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .test-section h2 {
            color: #35363a;
            margin-top: 0;
        }
        .logo-test {
            margin: 15px 0;
            padding: 15px;
            background: #35363a;
            border-radius: 5px;
            text-align: center;
        }
        .logo-test.white-bg {
            background: white;
            border: 1px solid #ddd;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status.ok { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .iframe-container {
            margin: 15px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            height: 400px;
            border: none;
        }
        .links-section {
            margin: 20px 0;
        }
        .links-section a {
            display: inline-block;
            margin: 5px 10px;
            padding: 10px 15px;
            background: #35363a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .links-section a:hover {
            background: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Teste Visual Final - Sistema Engenha Rio</h1>
        <p><strong>Data:</strong> <?= date('d/m/Y H:i:s') ?></p>
        
        <div class="test-section">
            <h2>📋 Status das Alterações</h2>
            <ul>
                <li>✅ Fundo alterado para #35363a (home, login, registro)</li>
                <li>✅ Logo antiga (logo.webp) substituída por nova PNG</li>
                <li>✅ Tema azul removido das telas de autenticação</li>
                <li>✅ Botões e cards com tons de cinza (#6c757d)</li>
            </ul>
        </div>

        <div class="test-section">
            <h2>🖼️ Teste de Logos</h2>
            
            <div class="logo-test">
                <h3 style="color: white;">Logo em Fundo Escuro (#35363a)</h3>
                <img src="/assets/images/engenhario-logo-new.png?v=<?= time() ?>" alt="Nova Logo" style="max-width: 250px; height: auto;" onerror="this.style.border='2px solid red'; this.alt='ERRO: Logo não carregou'">
                <span class="status ok" id="logo-dark-status">Carregando...</span>
            </div>
            
            <div class="logo-test white-bg">
                <h3 style="color: #35363a;">Logo em Fundo Claro</h3>
                <img src="/assets/images/engenhario-logo-new.png?v=<?= time() ?>" alt="Nova Logo" style="max-width: 250px; height: auto;" onerror="this.style.border='2px solid red'; this.alt='ERRO: Logo não carregou'">
                <span class="status ok" id="logo-light-status">Carregando...</span>
            </div>
        </div>

        <div class="test-section">
            <h2>🔗 Links para Testar Páginas</h2>
            <div class="links-section">
                <a href="/" target="_blank">🏠 Home</a>
                <a href="/login" target="_blank">🔐 Login</a>
                <a href="/register" target="_blank">📝 Registro</a>
                <a href="/assets/images/engenhario-logo-new.png" target="_blank">🖼️ Logo Direta</a>
            </div>
        </div>

        <div class="test-section">
            <h2>📱 Preview das Páginas</h2>
            
            <h3>🏠 Página Home</h3>
            <div class="iframe-container">
                <iframe src="/" title="Home Page"></iframe>
            </div>
            
            <h3>🔐 Página Login</h3>
            <div class="iframe-container">
                <iframe src="/login" title="Login Page"></iframe>
            </div>
            
            <h3>📝 Página Registro</h3>
            <div class="iframe-container">
                <iframe src="/register" title="Register Page"></iframe>
            </div>
        </div>

        <div class="test-section">
            <h2>🔍 Informações Técnicas</h2>
            <p><strong>Servidor:</strong> <?= $_SERVER['SERVER_NAME'] ?>:<?= $_SERVER['SERVER_PORT'] ?></p>
            <p><strong>Logo Path:</strong> /assets/images/engenhario-logo-new.png</p>
            <p><strong>Cor de Fundo:</strong> #35363a</p>
            <p><strong>Cor dos Botões:</strong> #6c757d</p>
            
            <?php
            $logoFile = __DIR__ . '/public/assets/images/engenhario-logo-new.png';
            if (file_exists($logoFile)) {
                $size = filesize($logoFile);
                echo "<p><strong>Arquivo Logo:</strong> Existe ($size bytes)</p>";
            } else {
                echo "<p><strong>Arquivo Logo:</strong> <span style='color: red;'>NÃO ENCONTRADO</span></p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Verificar se as logos carregaram
        document.addEventListener('DOMContentLoaded', function() {
            const imgs = document.querySelectorAll('img[src*="engenhario-logo-new.png"]');
            
            imgs.forEach((img, index) => {
                img.onload = function() {
                    const statusId = index === 0 ? 'logo-dark-status' : 'logo-light-status';
                    document.getElementById(statusId).textContent = 'OK';
                    document.getElementById(statusId).className = 'status ok';
                };
                
                img.onerror = function() {
                    const statusId = index === 0 ? 'logo-dark-status' : 'logo-light-status';
                    document.getElementById(statusId).textContent = 'ERRO';
                    document.getElementById(statusId).className = 'status error';
                };
            });
        });
    </script>
</body>
</html>
