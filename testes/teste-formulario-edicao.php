<?php
session_start();

echo "<h1>Teste de Edição de Projeto</h1>";

// Incluir dependências
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';

// Fazer login automático se não estiver logado
if (!Auth::check()) {
    echo "<h2>Fazendo Login Automático...</h2>";
    if (Auth::login('admin@engenhario.com', 'admin123')) {
        echo "<p style='color: green;'>✅ Login realizado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>❌ Falha no login!</p>";
        exit;
    }
}

echo "<h2>Estado do Sistema:</h2>";
echo "<p><strong>Usuário:</strong> " . Auth::user()['name'] . " (" . Auth::user()['role'] . ")</p>";

// Testar se podemos acessar a página de edição
echo "<h2>Testando Acesso à Página de Edição:</h2>";
?>

<style>
.btn {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}
.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: black; }
.form-test {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    border: 1px solid #dee2e6;
}
</style>

<a href="/projects/1/edit" class="btn btn-primary" target="_blank">📝 Abrir Página de Edição do Projeto 1</a>

<h2>Teste de Formulário Manual:</h2>
<div class="form-test">
    <p>Vamos simular o envio do formulário de edição:</p>
    
    <form method="POST" action="/projects/1" target="_blank">
        <input type="hidden" name="_method" value="PUT">
        
        <p><strong>Nome do Projeto:</strong></p>
        <input type="text" name="name" value="Projeto Teste Editado" style="width: 300px; padding: 5px;">
        
        <p><strong>Descrição:</strong></p>
        <textarea name="description" style="width: 400px; height: 100px; padding: 5px;">Esta é uma descrição teste editada para verificar se o sistema está funcionando corretamente.</textarea>
        
        <p><strong>Status:</strong></p>
        <select name="status" style="padding: 5px;">
            <option value="aguardando">Aguardando</option>
            <option value="em_andamento" selected>Em Andamento</option>
            <option value="concluido">Concluído</option>
        </select>
        
        <p><strong>Prioridade:</strong></p>
        <select name="priority" style="padding: 5px;">
            <option value="baixa">Baixa</option>
            <option value="normal" selected>Normal</option>
            <option value="alta">Alta</option>
            <option value="urgente">Urgente</option>
        </select>
        
        <p><strong>Prazo:</strong></p>
        <input type="date" name="deadline" value="2025-12-31" style="padding: 5px;">
        
        <br><br>
        <button type="submit" class="btn btn-success">🚀 Testar Envio do Formulário</button>
    </form>
</div>

<h2>Debug de Requisições:</h2>
<div class="form-test">
    <p><strong>Método da Requisição Atual:</strong> <?= $_SERVER['REQUEST_METHOD'] ?></p>
    <p><strong>URI Atual:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
    
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3>Dados POST Recebidos:</h3>
        <pre><?= print_r($_POST, true) ?></pre>
        
        <?php if (isset($_POST['_method'])): ?>
            <p style='color: green;'><strong>✅ Campo _method detectado:</strong> <?= $_POST['_method'] ?></p>
        <?php else: ?>
            <p style='color: red;'><strong>❌ Campo _method NÃO encontrado!</strong></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<h2>Links de Teste:</h2>
<a href="/projects" class="btn btn-primary">📋 Lista de Projetos</a>
<a href="/projects/1" class="btn btn-warning">👁️ Ver Projeto 1</a>
<a href="/dashboard" class="btn btn-success">🏠 Dashboard</a>

<script>
// Log para debug
console.log('Página de teste carregada');
console.log('Current URL:', window.location.href);
</script>
