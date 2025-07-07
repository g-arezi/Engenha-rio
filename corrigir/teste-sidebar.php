<?php
// Teste da Sidebar com logo
require_once __DIR__ . '/init.php';

// Simular usuário logado para teste
$_SESSION['user_id'] = 1;
$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Admin Teste',
    'email' => 'admin@test.com',
    'role' => 'admin'
];

// Definir menu ativo
$activeMenu = 'dashboard';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Sidebar - Engenha Rio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #35363a;
            color: white;
        }
        .main-content {
            margin-left: 280px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <h1>🧪 Teste da Sidebar</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle"></i> Status da Logo</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Caminho da Logo:</strong> <code>/assets/images/engenhario-logo-new.png</code></p>
                            <p><strong>Teste de Carregamento:</strong></p>
                            <img src="/assets/images/engenhario-logo-new.png?v=<?= time() ?>" 
                                 alt="Teste Logo" 
                                 style="max-width: 200px; border: 2px solid #6c757d; padding: 10px; background: white;"
                                 onload="this.nextElementSibling.innerHTML = '✅ Logo carregada com sucesso!'"
                                 onerror="this.nextElementSibling.innerHTML = '❌ Erro ao carregar logo'">
                            <div class="mt-2" id="logo-status">⏳ Carregando...</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5><i class="fas fa-cog"></i> Debug da Sidebar</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Usuário:</strong> <?= $_SESSION['user']['name'] ?></p>
                            <p><strong>Role:</strong> <?= $_SESSION['user']['role'] ?></p>
                            <p><strong>Menu Ativo:</strong> <?= $activeMenu ?></p>
                            <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Debug adicional
        console.log('Teste Sidebar carregado');
        
        // Verificar se a imagem da sidebar carregou
        setTimeout(() => {
            const sidebarImg = document.querySelector('.sidebar img');
            if (sidebarImg) {
                console.log('Imagem da sidebar encontrada:', sidebarImg.src);
                console.log('Imagem da sidebar visível:', sidebarImg.style.display !== 'none');
            } else {
                console.log('Imagem da sidebar não encontrada');
            }
        }, 1000);
    </script>
</body>
</html>
