<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Document;
use App\Models\Project;
use Exception;

class DocumentController extends Controller
{
    private $documentModel;
    private $projectModel;
    
    public function __construct()
    {
        $this->documentModel = new Document();
        $this->projectModel = new Project();
    }
    
    public function index()
    {
        $user = Auth::user();
        $projectId = $_GET['project_id'] ?? null;
        $type = $_GET['type'] ?? '';
        
        if (Auth::isAdmin()) {
            $documents = $this->documentModel->all() ?? [];
        } else {
            $documents = $this->documentModel->getByUser($user['id'] ?? null) ?? [];
        }
        
        if ($projectId) {
            $documents = array_filter($documents, fn($d) => ($d['project_id'] ?? null) === $projectId);
        }
        
        if ($type) {
            $documents = array_filter($documents, fn($d) => ($d['type'] ?? '') === $type);
        }
        
        $this->view('documents.index', [
            'documents' => $documents,
            'currentProject' => $projectId,
            'currentType' => $type,
            'user' => $user,
            'isAdmin' => Auth::isAdmin()
        ]);
    }
    
    public function upload()
    {
        if (!Auth::can('upload_documents')) {
            $this->json(['error' => 'Não autorizado'], 403);
            return;
        }
        
        $user = Auth::user();
        $projectId = $_POST['project_id'] ?? '';
        
        // Verificar se o usuário pode enviar documentos para este projeto
        if (!Auth::canUploadToProject($projectId)) {
            $this->json(['error' => 'Acesso negado. Você não pode enviar documentos para este projeto.'], 403);
            return;
        }
        
        // Validar dados do formulário
        $documentData = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? 'outros',
            'user_id' => $user['id'],
            'project_id' => $projectId
        ];
        
        // Validar dados obrigatórios
        $validation = $this->documentModel->validateDocumentData($documentData);
        if (!$validation['valid']) {
            $this->json(['error' => implode(', ', $validation['errors'])], 400);
            return;
        }
        
        // Validar arquivo
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['error' => 'Erro no upload do arquivo'], 400);
            return;
        }
        
        $file = $_FILES['file'];
        
        // Validar arquivo usando o método do modelo
        $fileValidation = $this->documentModel->validateFile($file);
        if (!$fileValidation['valid']) {
            $this->json(['error' => implode(', ', $fileValidation['errors'])], 400);
            return;
        }
        
        // Gerar caminho único e seguro para o arquivo
        $filePath = $this->documentModel->generateFilePath($file['name']);
        $fullPath = __DIR__ . '/../../' . $filePath;
        
        // Criar diretório se não existir
        $uploadDir = dirname($fullPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Mover arquivo para destino final
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->json(['error' => 'Erro ao salvar arquivo'], 500);
            return;
        }
        
        // Preparar dados finais do documento
        $documentData['name'] = $documentData['name'] ?: $file['name'];
        $documentData['file_path'] = $filePath;
        $documentData['size'] = $file['size'];
        $documentData['mime_type'] = $file['type'];
        
        $documentId = $this->documentModel->create($documentData);
        
        if ($documentId) {
            $this->logDocumentAction($documentId, 'upload');
            $this->json(['success' => true, 'message' => 'Documento enviado com sucesso!', 'id' => $documentId]);
        } else {
            $this->json(['error' => 'Erro ao salvar documento'], 500);
        }
    }
    
    public function show($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            Session::flash('error', 'Documento não encontrado');
            $this->redirect('/documents');
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            Session::flash('error', 'Acesso negado');
            $this->redirect('/documents');
        }
        
        // Buscar projeto relacionado
        $project = null;
        if (isset($document['project_id'])) {
            $project = $this->projectModel->find($document['project_id']);
        }
        
        // Log da ação
        $this->logDocumentAction($id, 'view');
        
        $this->view('documents.show', [
            'document' => $document,
            'user' => $user,
            'isAdmin' => Auth::isAdmin(),
            'project' => $project
        ]);
    }
    
    public function download($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            http_response_code(404);
            echo 'Documento não encontrado';
            return;
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            http_response_code(403);
            echo 'Acesso negado';
            return;
        }
        
        $filePath = __DIR__ . '/../../public/' . $document['file_path'];
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo 'Arquivo não encontrado';
            return;
        }
        
        header('Content-Type: ' . $document['mime_type']);
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: attachment; filename="' . $document['name'] . '"');
        
        // Log da ação
        $this->logDocumentAction($id, 'download');
        
        readfile($filePath);
    }
    
    public function destroy($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            $this->json(['error' => 'Documento não encontrado'], 404);
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            $this->json(['error' => 'Acesso negado'], 403);
        }
        
        // Excluir arquivo físico
        $filePath = __DIR__ . '/../../public/' . $document['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        if ($this->documentModel->delete($id)) {
            $this->logDocumentAction($id, 'delete');
            Session::flash('success', 'Documento excluído com sucesso!');
            $this->redirect('/documents');
        } else {
            Session::flash('error', 'Erro ao excluir documento');
            $this->redirect('/documents');
        }
    }
    
    public function preview($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            http_response_code(404);
            echo 'Documento não encontrado';
            return;
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            http_response_code(403);
            echo 'Acesso negado';
            return;
        }
        
        $filePath = __DIR__ . '/../../public/' . $document['file_path'];
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo 'Arquivo não encontrado';
            return;
        }
        
        // Para PDFs e imagens, permitir visualização inline
        if (strpos($document['mime_type'], 'pdf') !== false || strpos($document['mime_type'], 'image') !== false) {
            header('Content-Type: ' . $document['mime_type']);
            header('Content-Length: ' . filesize($filePath));
            header('Content-Disposition: inline; filename="' . $document['name'] . '"');
            
            // Log da ação
            $this->logDocumentAction($id, 'preview');
            
            readfile($filePath);
        } else {
            // Para outros tipos, redirecionar para download
            $this->redirect('/documents/' . $id . '/download');
        }
    }
    
    public function edit($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            Session::flash('error', 'Documento não encontrado');
            $this->redirect('/documents');
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            Session::flash('error', 'Acesso negado');
            $this->redirect('/documents');
        }
        
        // Buscar projeto relacionado
        $project = null;
        if (isset($document['project_id'])) {
            $project = $this->projectModel->find($document['project_id']);
        }
        
        // Buscar todos os projetos para o select
        $projects = $this->projectModel->all();
        
        $this->view('documents.edit', [
            'document' => $document,
            'user' => $user,
            'isAdmin' => Auth::isAdmin(),
            'project' => $project,
            'projects' => $projects
        ]);
    }
    
    public function update($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            $this->json(['error' => 'Documento não encontrado'], 404);
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            $this->json(['error' => 'Acesso negado'], 403);
        }
        
        // Validar dados
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'type' => trim($_POST['type'] ?? ''),
            'project_id' => trim($_POST['project_id'] ?? '')
        ];
        
        // Validações
        if (empty($data['name'])) {
            $this->json(['error' => 'Nome é obrigatório'], 400);
        }
        
        if (empty($data['type'])) {
            $this->json(['error' => 'Tipo é obrigatório'], 400);
        }
        
        // Atualizar documento
        try {
            $this->documentModel->update($id, $data);
            $this->logDocumentAction($id, 'update');
            $this->json(['success' => 'Documento atualizado com sucesso']);
        } catch (Exception $e) {
            $this->json(['error' => 'Erro ao atualizar documento'], 500);
        }
    }
    
    private function getFileIcon($type) {
        $icons = [
            'pdf' => 'pdf',
            'doc' => 'word',
            'docx' => 'word',
            'xls' => 'excel',
            'xlsx' => 'excel',
            'ppt' => 'powerpoint',
            'pptx' => 'powerpoint',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image'
        ];
        
        return $icons[strtolower($type)] ?? 'file';
    }
    
    private function logDocumentAction($documentId, $action, $userId = null)
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'document_id' => $documentId,
            'user_id' => $userId ?: (Auth::user()['id'] ?? 'system'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $logFile = __DIR__ . '/../../logs/documents.log';
        $logLine = json_encode($logEntry) . PHP_EOL;
        
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
