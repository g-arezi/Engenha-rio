<?php
// Script de teste para debug do formulário de template

echo "<h1>Teste de Submissão de Template</h1>";

echo "<h2>Dados recebidos via POST:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>Dados recebidos via GET:</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h2>Headers da requisição:</h2>";
echo "<pre>";
print_r(getallheaders());
echo "</pre>";

// Simular o processamento do template
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Processando dados do template...</h2>";
    
    $name = $_POST['name'] ?? '';
    $project_type = $_POST['project_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $required_documents = $_POST['required_documents'] ?? [];
    $optional_documents = $_POST['optional_documents'] ?? [];
    
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($name) . "</p>";
    echo "<p><strong>Tipo de Projeto:</strong> " . htmlspecialchars($project_type) . "</p>";
    echo "<p><strong>Descrição:</strong> " . htmlspecialchars($description) . "</p>";
    
    echo "<p><strong>Documentos Obrigatórios:</strong></p>";
    if (is_array($required_documents)) {
        echo "<ul>";
        foreach ($required_documents as $doc) {
            echo "<li>" . htmlspecialchars($doc) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum documento obrigatório</p>";
    }
    
    echo "<p><strong>Documentos Opcionais:</strong></p>";
    if (is_array($optional_documents)) {
        echo "<ul>";
        foreach ($optional_documents as $doc) {
            echo "<li>" . htmlspecialchars($doc) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum documento opcional</p>";
    }
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; margin: 10px 0;'>";
    echo "<strong>✅ Dados processados com sucesso!</strong>";
    echo "</div>";
}
?>

<h2>Formulário de Teste:</h2>
<form method="POST" action="">
    <p>
        <label>Nome: <input type="text" name="name" value="Template Teste"></label>
    </p>
    <p>
        <label>Tipo: 
            <select name="project_type">
                <option value="residencial">Residencial</option>
                <option value="comercial">Comercial</option>
            </select>
        </label>
    </p>
    <p>
        <label>Descrição: <textarea name="description">Descrição do teste</textarea></label>
    </p>
    
    <input type="hidden" name="required_documents[]" value="rg">
    <input type="hidden" name="required_documents[]" value="cpf">
    <input type="hidden" name="optional_documents[]" value="escritura">
    
    <button type="submit">Testar Submissão</button>
</form>
