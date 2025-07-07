<?php
// Teste final da funcionalidade de aprovação/rejeição
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

Session::start();

// Login automático
if (!Auth::check()) {
    Session::set('user_id', 'admin_002');
    Session::set('user_data', [
        'id' => 'admin_002',
        'name' => 'Administrador do Sistema',
        'email' => 'admin@sistema.com',
        'role' => 'admin',
        'active' => true,
        'approved' => true
    ]);
}

echo "<!DOCTYPE html><html><head><title>Teste Final</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<style>body { padding: 20px; } .card { margin-bottom: 20px; }</style>";
echo "</head><body><div class='container'>";

echo "<h1>🧪 Teste Final - Aprovação/Rejeição de Usuários</h1>";

// Verificar se já existe um usuário de teste
$userModel = new User();
$testUser = null;

// Procurar por usuário de teste
$allUsers = $userModel->all();
foreach ($allUsers as $user) {
    if (strpos($user['email'], 'teste') !== false && !($user['approved'] ?? false)) {
        $testUser = $user;
        break;
    }
}

if (!$testUser) {
    echo "<div class='card'>";
    echo "<div class='card-header bg-warning text-dark'>";
    echo "<h3>Criando Usuário de Teste</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    // Criar usuário de teste
    $testUserData = [
        'name' => 'Usuário Teste Aprovação',
        'email' => 'teste.aprovacao@exemplo.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'role' => 'cliente',
        'active' => true,
        'approved' => false,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $testUserId = $userModel->create($testUserData);
    if ($testUserId) {
        $testUser = $userModel->find($testUserId);
        echo "✅ Usuário de teste criado com sucesso!<br>";
        echo "ID: {$testUserId}<br>";
        echo "Email: {$testUserData['email']}<br>";
    } else {
        echo "❌ Erro ao criar usuário de teste<br>";
    }
    echo "</div></div>";
}

if ($testUser) {
    echo "<div class='card'>";
    echo "<div class='card-header bg-primary text-white'>";
    echo "<h3>Usuário de Teste Encontrado</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($testUser['name']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($testUser['email']) . "</p>";
    echo "<p><strong>Role:</strong> " . htmlspecialchars($testUser['role']) . "</p>";
    echo "<p><strong>Status:</strong> " . (($testUser['active'] ?? true) ? 'Ativo' : 'Inativo') . "</p>";
    echo "<p><strong>Aprovado:</strong> " . (($testUser['approved'] ?? false) ? 'SIM' : 'NÃO') . "</p>";
    
    if (!($testUser['approved'] ?? false)) {
        echo "<div class='btn-group' role='group'>";
        echo "<button class='btn btn-success' onclick=\"approveUser('{$testUser['id']}')\">✅ Aprovar</button>";
        echo "<button class='btn btn-danger' onclick=\"rejectUser('{$testUser['id']}')\">❌ Rejeitar</button>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-success'>Usuário já foi aprovado!</div>";
    }
    echo "</div></div>";
}

echo "<div class='card'>";
echo "<div class='card-header bg-success text-white'>";
echo "<h3>Links de Teste</h3>";
echo "</div>";
echo "<div class='card-body'>";
echo "<div class='d-grid gap-2'>";
echo "<a href='/admin/users' class='btn btn-primary'>📋 Página Principal de Usuários</a>";
echo "<a href='/teste-aprovacao-usuarios.php' class='btn btn-secondary'>🧪 Teste Completo</a>";
echo "<a href='/dashboard' class='btn btn-info'>🏠 Dashboard</a>";
echo "</div>";
echo "</div></div>";

echo "<div class='card'>";
echo "<div class='card-header bg-info text-white'>";
echo "<h3>Log de Ações</h3>";
echo "</div>";
echo "<div class='card-body'>";
echo "<div id='log' style='background: #f8f9fa; padding: 15px; border-radius: 5px; min-height: 100px;'>";
echo "Log de ações aparecerá aqui...<br>";
echo "</div>";
echo "</div></div>";

echo "</div>"; // container

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
echo "<script>";
echo "function log(msg) {";
echo "  const logDiv = document.getElementById('log');";
echo "  const time = new Date().toLocaleTimeString();";
echo "  logDiv.innerHTML += `[${time}] ${msg}<br>`;";
echo "}";

echo "function approveUser(userId) {";
echo "  log('Iniciando aprovação do usuário: ' + userId);";
echo "  fetch(`/admin/users/${userId}/approve`, {";
echo "    method: 'POST',";
echo "    headers: { 'Content-Type': 'application/json' },";
echo "    body: JSON.stringify({ user_id: userId })";
echo "  })";
echo "  .then(response => {";
echo "    log('Status da resposta: ' + response.status);";
echo "    return response.json();";
echo "  })";
echo "  .then(data => {";
echo "    if (data.success) {";
echo "      log('✅ Sucesso: ' + (data.message || 'Usuário aprovado'));";
echo "      alert('Usuário aprovado com sucesso!');";
echo "      setTimeout(() => location.reload(), 1000);";
echo "    } else {";
echo "      log('❌ Erro: ' + (data.error || 'Erro desconhecido'));";
echo "      alert('Erro: ' + (data.error || 'Erro desconhecido'));";
echo "    }";
echo "  })";
echo "  .catch(error => {";
echo "    log('❌ Erro de rede: ' + error.message);";
echo "    alert('Erro de rede: ' + error.message);";
echo "  });";
echo "}";

echo "function rejectUser(userId) {";
echo "  if (confirm('Tem certeza que deseja rejeitar este usuário?')) {";
echo "    log('Iniciando rejeição do usuário: ' + userId);";
echo "    fetch(`/admin/users/${userId}/reject`, {";
echo "      method: 'POST',";
echo "      headers: { 'Content-Type': 'application/json' },";
echo "      body: JSON.stringify({ user_id: userId })";
echo "    })";
echo "    .then(response => {";
echo "      log('Status da resposta: ' + response.status);";
echo "      return response.json();";
echo "    })";
echo "    .then(data => {";
echo "      if (data.success) {";
echo "        log('✅ Sucesso: ' + (data.message || 'Usuário rejeitado'));";
echo "        alert('Usuário rejeitado com sucesso!');";
echo "        setTimeout(() => location.reload(), 1000);";
echo "      } else {";
echo "        log('❌ Erro: ' + (data.error || 'Erro desconhecido'));";
echo "        alert('Erro: ' + (data.error || 'Erro desconhecido'));";
echo "      }";
echo "    })";
echo "    .catch(error => {";
echo "      log('❌ Erro de rede: ' + error.message);";
echo "      alert('Erro de rede: ' + error.message);";
echo "    });";
echo "  }";
echo "}";

echo "document.addEventListener('DOMContentLoaded', function() {";
echo "  log('Página carregada e pronta para teste');";
echo "});";
echo "</script>";

echo "</body></html>";
?>
