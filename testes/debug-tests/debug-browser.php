<?php
// Verificar sessão e autenticação
session_start();

// Simular login de admin
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug - Teste de Edição de Usuário</title>
    <script>
        // Função para testar o endpoint diretamente
        async function testEditUser() {
            const userId = 'admin_001';
            const resultDiv = document.getElementById('result');
            
            try {
                console.log('Testando endpoint:', `/admin/users/${userId}/edit`);
                
                const response = await fetch(`/admin/users/${userId}/edit`);
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                const text = await response.text();
                console.log('Response text:', text);
                
                if (response.ok) {
                    try {
                        const data = JSON.parse(text);
                        console.log('Parsed JSON:', data);
                        
                        if (data.success) {
                            resultDiv.innerHTML = `
                                <div style="color: green;">
                                    <h3>✅ Sucesso!</h3>
                                    <p><strong>Nome:</strong> ${data.user.name}</p>
                                    <p><strong>Email:</strong> ${data.user.email}</p>
                                    <p><strong>Role:</strong> ${data.user.role}</p>
                                    <p><strong>Ativo:</strong> ${data.user.active ? 'Sim' : 'Não'}</p>
                                </div>
                            `;
                        } else {
                            resultDiv.innerHTML = `
                                <div style="color: red;">
                                    <h3>❌ Erro</h3>
                                    <p>${data.error || 'Erro desconhecido'}</p>
                                </div>
                            `;
                        }
                    } catch (jsonError) {
                        console.error('Erro ao parsear JSON:', jsonError);
                        resultDiv.innerHTML = `
                            <div style="color: orange;">
                                <h3>⚠️ Resposta não é JSON válido</h3>
                                <p>Status: ${response.status}</p>
                                <p>Texto: ${text}</p>
                            </div>
                        `;
                    }
                } else {
                    resultDiv.innerHTML = `
                        <div style="color: red;">
                            <h3>❌ Erro HTTP</h3>
                            <p>Status: ${response.status}</p>
                            <p>Texto: ${text}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                resultDiv.innerHTML = `
                    <div style="color: red;">
                        <h3>❌ Erro na Requisição</h3>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        }
        
        // Testar automaticamente ao carregar
        window.onload = testEditUser;
    </script>
</head>
<body>
    <h1>Debug - Teste de Edição de Usuário</h1>
    <p><strong>Sessão User ID:</strong> <?= $_SESSION['user_id'] ?? 'Não definido' ?></p>
    <p><strong>Sessão Role:</strong> <?= $_SESSION['role'] ?? 'Não definido' ?></p>
    <p><strong>Session ID:</strong> <?= session_id() ?></p>
    
    <button onclick="testEditUser()">Testar Edição de Usuário</button>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;">
        <p>Clique no botão para testar ou aguarde o teste automático...</p>
    </div>
    
    <div style="margin-top: 20px;">
        <h3>Links úteis:</h3>
        <ul>
            <li><a href="/admin/users" target="_blank">Página de Usuários</a></li>
            <li><a href="/admin/users/admin_001/edit" target="_blank">Endpoint direto</a></li>
            <li><a href="/dashboard" target="_blank">Dashboard</a></li>
        </ul>
    </div>
</body>
</html>
