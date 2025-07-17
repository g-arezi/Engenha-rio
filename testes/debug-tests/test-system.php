<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Sistema - ENGENHÁRIO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">🔧 Teste do Sistema ENGENHÁRIO</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once 'vendor/autoload.php';
                        
                        use App\Core\Session;
                        use App\Core\Auth;
                        use App\Core\Config;
                        use App\Models\User;
                        
                        // Inicializar
                        Config::load();
                        Session::start();
                        
                        echo "<h4>🔍 Verificações do Sistema</h4>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-6'>";
                        
                        // Testar conexão com dados
                        echo "<h5>📊 Dados</h5>";
                        $userModel = new User();
                        $users = $userModel->all();
                        echo "<ul>";
                        echo "<li>Usuários cadastrados: <strong>" . count($users) . "</strong></li>";
                        echo "<li>Arquivo users.json: " . (file_exists('data/users.json') ? "✅ Existe" : "❌ Não existe") . "</li>";
                        echo "</ul>";
                        
                        // Testar autenticação
                        echo "<h5>🔐 Autenticação</h5>";
                        echo "<ul>";
                        echo "<li>Usuário logado: " . (Auth::check() ? "✅ Sim" : "❌ Não") . "</li>";
                        
                        if (Auth::check()) {
                            $user = Auth::user();
                            echo "<li>Nome: <strong>" . htmlspecialchars($user['name'] ?? 'N/A') . "</strong></li>";
                            echo "<li>Email: <strong>" . htmlspecialchars($user['email'] ?? 'N/A') . "</strong></li>";
                            echo "<li>Role: <strong>" . htmlspecialchars($user['role'] ?? 'N/A') . "</strong></li>";
                            echo "<li>É Admin: " . (Auth::isAdmin() ? "✅ Sim" : "❌ Não") . "</li>";
                            echo "<li>É Analista: " . (Auth::isAnalyst() ? "✅ Sim" : "❌ Não") . "</li>";
                        }
                        echo "</ul>";
                        
                        echo "</div>";
                        echo "<div class='col-md-6'>";
                        
                        // Testar rotas
                        echo "<h5>🛤️ Rotas Disponíveis</h5>";
                        echo "<ul>";
                        $routes = [
                            '/dashboard' => 'Dashboard',
                            '/projects' => 'Projetos',
                            '/documents' => 'Documentos',
                            '/admin' => 'Painel Admin',
                            '/admin/users' => 'Gerenciar Usuários',
                            '/profile' => 'Perfil'
                        ];
                        
                        foreach ($routes as $route => $name) {
                            $accessible = true;
                            if (str_contains($route, '/admin') && !Auth::isAdmin()) {
                                $accessible = false;
                            }
                            
                            echo "<li>";
                            echo "<a href='$route' class='text-decoration-none'>";
                            echo ($accessible ? "✅" : "❌") . " $name";
                            echo "</a>";
                            echo "</li>";
                        }
                        echo "</ul>";
                        
                        echo "</div>";
                        echo "</div>";
                        
                        // Mostrar usuários disponíveis
                        echo "<hr>";
                        echo "<h4>👥 Usuários Disponíveis</h4>";
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-sm'>";
                        echo "<thead><tr><th>Nome</th><th>Email</th><th>Role</th><th>Status</th><th>Ações</th></tr></thead>";
                        echo "<tbody>";
                        
                        foreach ($users as $u) {
                            $statusClass = ($u['status'] ?? 'active') === 'active' ? 'success' : 'danger';
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($u['name'] ?? 'N/A') . "</td>";
                            echo "<td>" . htmlspecialchars($u['email'] ?? 'N/A') . "</td>";
                            echo "<td><span class='badge bg-info'>" . htmlspecialchars($u['role'] ?? 'N/A') . "</span></td>";
                            echo "<td><span class='badge bg-$statusClass'>" . htmlspecialchars($u['status'] ?? 'active') . "</span></td>";
                            echo "<td>";
                            if (!Auth::check()) {
                                echo "<button class='btn btn-sm btn-primary' onclick='testLogin(\"" . $u['email'] . "\", \"123456\")'>Login</button>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        
                        if (!Auth::check()) {
                            echo "<div class='alert alert-info'>";
                            echo "<strong>💡 Dica:</strong> Use a senha padrão <code>123456</code> para todos os usuários de teste.";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testLogin(email, password) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/login';
            
            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = email;
            
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;
            
            form.appendChild(emailInput);
            form.appendChild(passwordInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
