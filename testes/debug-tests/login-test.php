<!DOCTYPE html>
<html>
<head>
    <title>Login de Teste</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin: 15px 0; }
        input[type="email"], input[type="password"] { width: 300px; padding: 10px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Login de Teste - Sistema Engenha-rio</h1>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Email:</label><br>
            <input type="email" name="email" value="admin@sistema.com" required>
        </div>
        <div class="form-group">
            <label>Senha:</label><br>
            <input type="password" name="password" value="password" required>
        </div>
        <button type="submit">Fazer Login</button>
    </form>
    
    <?php
    if ($_POST) {
        require_once 'vendor/autoload.php';
        
        // Inicia a sessão
        session_start();
        \App\Core\Session::start();
        
        echo "<div class='result'>";
        echo "<h2>Resultado do Login:</h2>";
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        try {
            $result = \App\Core\Auth::login($email, $password);
            
            if ($result) {
                echo "<div class='success'>";
                echo "✅ <strong>Login realizado com sucesso!</strong><br>";
                
                $user = \App\Core\Auth::user();
                if ($user) {
                    echo "Nome: " . $user['name'] . "<br>";
                    echo "Email: " . $user['email'] . "<br>";
                    echo "Role: " . $user['role'] . "<br>";
                    
                    $isAdmin = \App\Core\Auth::isAdmin();
                    $isAnalyst = \App\Core\Auth::isAnalyst();
                    
                    echo "É Admin: " . ($isAdmin ? 'SIM' : 'NÃO') . "<br>";
                    echo "É Analista: " . ($isAnalyst ? 'SIM' : 'NÃO') . "<br>";
                    
                    if ($isAdmin || $isAnalyst) {
                        echo "<strong>✅ DROPDOWN ADMINISTRATIVO DEVE APARECER</strong><br>";
                    } else {
                        echo "<strong>❌ DROPDOWN ADMINISTRATIVO NÃO DEVE APARECER</strong><br>";
                    }
                    
                    echo "<br><a href='/dashboard' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir para Dashboard</a>";
                }
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "❌ <strong>Erro no login</strong><br>";
                
                // Debug do erro
                $userModel = new \App\Models\User();
                $user = $userModel->findByEmail($email);
                
                if ($user) {
                    echo "Usuário encontrado: " . $user['name'] . "<br>";
                    echo "Role: " . $user['role'] . "<br>";
                    echo "Ativo: " . ($user['active'] ? 'SIM' : 'NÃO') . "<br>";
                    echo "Aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
                    
                    $passwordTest = password_verify($password, $user['password']);
                    echo "Senha válida: " . ($passwordTest ? 'SIM' : 'NÃO') . "<br>";
                } else {
                    echo "Usuário não encontrado<br>";
                }
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "❌ Erro: " . $e->getMessage() . "<br>";
            echo "</div>";
        }
        
        echo "</div>";
    }
    ?>
    
    <hr>
    <p><a href="/dashboard">Dashboard</a> | <a href="/login">Login Oficial</a></p>
</body>
</html>
