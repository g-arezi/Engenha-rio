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
        
        // Controle de acesso por tipo de usuário
        if (Auth::isAdmin()) {
            // Admin pode ver todos os documentos
            $documents = $this->documentModel->all() ?? [];
        } elseif ($user['role'] === 'analista') {
            // Analista pode ver todos os documentos
            $documents = $this->documentModel->all() ?? [];
        } else {
            // Cliente só pode ver seus próprios documentos
            $documents = $this->documentModel->getByUser($user['id'] ?? null) ?? [];
        }
        
        if ($projectId) {
            $documents = array_filter($documents, fn($d) => ($d['project_id'] ?? null) === $projectId);
        }
        
        if ($type) {
            $documents = array_filter($documents, fn($d) => ($d['type'] ?? '') === $type);
        }
        
        // Função auxiliar para formatação de tamanhos
        $formatBytes = function($size) {
            $units = ['B', 'KB', 'MB', 'GB'];
            $unitIndex = 0;
            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
            }
            return round($size, 2) . ' ' . $units[$unitIndex];
        };
        
        $title = 'Documentos - Engenha Rio';
        $showSidebar = true;
        $activeMenu = 'documents';
        $isAdmin = Auth::isAdmin();
        $canUpload = Auth::isAdmin() || $user['role'] === 'analista' || $user['role'] === 'cliente';
        
        ob_start();
        include __DIR__ . '/../../views/documents/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }
    
    public function upload()
    {
        error_log("UPLOAD: Iniciando processo de upload");
        error_log("UPLOAD: REQUEST_METHOD = " . $_SERVER['REQUEST_METHOD']);
        error_log("UPLOAD: FILES = " . print_r($_FILES, true));
        error_log("UPLOAD: POST = " . print_r($_POST, true));
        
        try {
            $user = Auth::user();
            if (!$user) {
                error_log("UPLOAD: Usuário não autenticado");
                $this->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
                return;
            }
            
            error_log("UPLOAD: Usuário autenticado: " . $user['name'] . " (" . $user['role'] . ")");
            
            // Verificar se o usuário pode fazer upload (todos os tipos podem)
            if (!in_array($user['role'], ['admin', 'analista', 'cliente'])) {
                error_log("UPLOAD: Permissão negada para role: " . $user['role']);
                $this->json(['success' => false, 'message' => 'Permissão negada para upload'], 403);
                return;
            }
            
            // Validar se arquivo foi enviado
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = 'Erro no upload do arquivo';
                if (isset($_FILES['file']['error'])) {
                    $errorMsg .= ' (código: ' . $_FILES['file']['error'] . ')';
                }
                error_log("UPLOAD: " . $errorMsg);
                $this->json(['success' => false, 'message' => $errorMsg], 400);
                return;
            }
            
            $file = $_FILES['file'];
            error_log("UPLOAD: Arquivo recebido: " . $file['name'] . " (" . $file['size'] . " bytes)");
            
            // Validações básicas
            $maxSize = 50 * 1024 * 1024; // 50MB para permitir arquivos maiores
            if ($file['size'] > $maxSize) {
                error_log("UPLOAD: Arquivo muito grande: " . $file['size'] . " bytes");
                $this->json(['success' => false, 'message' => 'Arquivo muito grande (máximo 50MB)'], 400);
                return;
            }
            
            // Validar tipo de arquivo - mais tipos permitidos
            $allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'text/plain',
                'application/zip',
                'application/x-rar-compressed',
                'application/octet-stream'
            ];
            
            if (!in_array($file['type'], $allowedTypes)) {
                error_log("UPLOAD: Tipo de arquivo não permitido: " . $file['type']);
                $this->json(['success' => false, 'message' => 'Tipo de arquivo não permitido'], 400);
                return;
            }
            
            // Gerar nome único para o arquivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            
            // Definir diretório de upload
            $uploadDir = __DIR__ . '/../../public/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
                error_log("UPLOAD: Diretório criado: " . $uploadDir);
            }
            
            $filePath = $uploadDir . $fileName;
            error_log("UPLOAD: Tentando mover arquivo para: " . $filePath);
            
            // Mover arquivo
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                error_log("UPLOAD: Falha ao mover arquivo de " . $file['tmp_name'] . " para " . $filePath);
                $this->json(['success' => false, 'message' => 'Erro ao salvar arquivo'], 500);
                return;
            }
            
            error_log("UPLOAD: Arquivo movido com sucesso");
            
            // Preparar dados do documento
            $documentData = [
                'id' => uniqid('doc_'),
                'name' => $_POST['name'] ?: $file['name'],
                'description' => $_POST['description'] ?? '',
                'type' => $_POST['document_type'] ?? $_POST['type'] ?? 'outros',
                'document_type' => $_POST['document_type'] ?? $_POST['type'] ?? 'outros',
                'file_path' => 'documents/' . $fileName,
                'file_size' => $file['size'],
                'mime_type' => $this->getMimeTypeByExtension($fileName) ?: $file['type'],
                'user_id' => $user['id'],
                'uploaded_by' => $user['name'],
                'project_id' => !empty($_POST['project_id']) ? $_POST['project_id'] : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            error_log("UPLOAD: Dados do documento: " . print_r($documentData, true));
            
            // Salvar no modelo
            $result = $this->documentModel->create($documentData);
            
            if ($result) {
                error_log("UPLOAD: Documento salvo com sucesso no banco");
                $this->json(['success' => true, 'message' => 'Documento enviado com sucesso!']);
            } else {
                error_log("UPLOAD: Falha ao salvar no banco de dados");
                $this->json(['success' => false, 'message' => 'Erro ao salvar documento no banco de dados'], 500);
            }
            
        } catch (Exception $e) {
            error_log("UPLOAD: Exceção capturada: " . $e->getMessage());
            error_log("UPLOAD: Stack trace: " . $e->getTraceAsString());
            $this->json(['success' => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }
    
    public function show($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            Session::flash('error', 'Documento não encontrado');
            $this->redirect('/documents');
            return;
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
            Session::flash('error', 'Acesso negado');
            $this->redirect('/documents');
            return;
        }
        
        // Buscar projeto relacionado
        $project = null;
        if (isset($document['project_id'])) {
            $project = $this->projectModel->find($document['project_id']);
        }
        
        // Log da ação
        $this->logDocumentAction($id, 'view');
        
        // Para PDFs, tentar mostrar inline
        $mimeType = $document['mime_type'] ?? '';
        
        if ($mimeType === 'application/pdf') {
            // Buscar arquivo
            $possiblePaths = [
                __DIR__ . '/../../public/' . $document['file_path'],
                __DIR__ . '/../../' . $document['file_path'],
                __DIR__ . '/../../uploads/' . $document['file_path'],
                __DIR__ . '/../../uploads/documents/' . basename($document['file_path'])
            ];
            
            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
            
            if ($filePath && file_exists($filePath)) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $document['name'] . '"');
                readfile($filePath);
                exit;
            }
        }
        
        // Para outros tipos, redirecionar para download
        $this->redirect('/documents/' . $id . '/download');
    }
    
    private function generateDownloadName($document, $originalFileName, $mimeType)
    {
        $originalExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $documentName = $document['name'] ?? '';
        $documentExtension = pathinfo($documentName, PATHINFO_EXTENSION);
        
        // Garantir que temos um nome base válido
        if (empty($documentName) || $documentName === '') {
            $documentName = 'documento_' . ($document['id'] ?? 'unknown');
        }
        
        // Determinar nome final para download
        $downloadName = $documentName;
        
        // Se o nome do documento não tem extensão, usar a extensão do arquivo original
        if (!$documentExtension && $originalExtension) {
            $downloadName = $documentName . '.' . $originalExtension;
        }
        // Se as extensões são diferentes, priorizar a original (do arquivo físico)
        elseif ($documentExtension && $originalExtension && $documentExtension !== $originalExtension) {
            $downloadName = pathinfo($documentName, PATHINFO_FILENAME) . '.' . $originalExtension;
        }
        // Se não há extensão original, mas temos MIME type, deduzir extensão
        elseif (!$documentExtension && !$originalExtension) {
            $extensionFromMime = $this->getExtensionFromMimeType($mimeType);
            if ($extensionFromMime) {
                $downloadName = $documentName . '.' . $extensionFromMime;
            }
        }
        
        // Garantir que o nome seja seguro para download (preservar acentos)
        $downloadName = preg_replace('/[<>:"/\\|?*]/', '_', $downloadName);
        
        // Verificação final: se ainda não há extensão e é um PDF, adicionar
        if (!pathinfo($downloadName, PATHINFO_EXTENSION) && strpos($mimeType, 'pdf') !== false) {
            $downloadName = $documentName . '.pdf';
        }
        
        return $downloadName;
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
        
        // Verificar permissão - admin e analista podem ver todos, cliente só os seus
        $canAccess = false;
        if (Auth::isAdmin()) {
            $canAccess = true;
        } elseif ($user['role'] === 'analista') {
            $canAccess = true;
        } elseif ($user['role'] === 'cliente' && $document['user_id'] === $user['id']) {
            $canAccess = true;
        }
        
        if (!$canAccess) {
            http_response_code(403);
            echo 'Acesso negado';
            return;
        }
        
        // Tentar diferentes caminhos de arquivo
        $possiblePaths = [
            __DIR__ . '/../../public/' . $document['file_path'],
            __DIR__ . '/../../' . $document['file_path'],
            __DIR__ . '/../../uploads/' . $document['file_path'],
            __DIR__ . '/../../uploads/documents/' . basename($document['file_path']),
            __DIR__ . '/../../documents/' . basename($document['file_path'])
        ];
        
        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }
        
        if (!$filePath || !file_exists($filePath)) {
            http_response_code(404);
            echo 'Arquivo físico não encontrado';
            return;
        }
        
        // Determinar tipo MIME e extensão correta
        $mimeType = $document['mime_type'] ?? 'application/octet-stream';
        
        // Obter nome do arquivo original com extensão
        $originalFileName = basename($document['file_path']);
        
        // Gerar nome para download
        $downloadName = $this->generateDownloadName($document, $originalFileName, $mimeType);
        
        // Headers para download com máxima compatibilidade
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: attachment; filename="' . $downloadName . '"; filename*=UTF-8\'\'' . rawurlencode($downloadName));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('X-Content-Type-Options: nosniff');
        header('Content-Transfer-Encoding: binary');
        
        // Garantir que não há output antes do arquivo
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Log da ação
        $this->logDocumentAction($id, 'download');
        
        // Enviar arquivo
        readfile($filePath);
        exit;
    }
    
    public function downloadInfo($id)
    {
        $document = $this->documentModel->find($id);
        
        if (!$document) {
            $this->json(['error' => 'Documento não encontrado'], 404);
            return;
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        $canAccess = false;
        if (Auth::isAdmin()) {
            $canAccess = true;
        } elseif ($user['role'] === 'analista') {
            $canAccess = true;
        } elseif ($user['role'] === 'cliente' && $document['user_id'] === $user['id']) {
            $canAccess = true;
        }
        
        if (!$canAccess) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        // Verificar se arquivo existe
        $possiblePaths = [
            __DIR__ . '/../../public/' . $document['file_path'],
            __DIR__ . '/../../' . $document['file_path'],
            __DIR__ . '/../../uploads/' . $document['file_path'],
            __DIR__ . '/../../uploads/documents/' . basename($document['file_path']),
            __DIR__ . '/../../documents/' . basename($document['file_path'])
        ];
        
        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }
        
        // Determinar tipo MIME e nome do arquivo
        $mimeType = $document['mime_type'] ?? 'application/octet-stream';
        $originalFileName = basename($document['file_path']);
        $downloadName = $this->generateDownloadName($document, $originalFileName, $mimeType);
        
        $info = [
            'document' => [
                'id' => $document['id'],
                'name' => $document['name'],
                'description' => $document['description'],
                'type' => $document['type'],
                'file_path' => $document['file_path'],
                'size' => $document['size'],
                'mime_type' => $document['mime_type']
            ],
            'file_system' => [
                'file_exists' => $filePath !== null,
                'file_path_found' => $filePath,
                'file_size' => $filePath ? filesize($filePath) : null,
                'possible_paths' => $possiblePaths
            ],
            'download' => [
                'final_mime_type' => $mimeType,
                'original_filename' => $originalFileName,
                'original_extension' => pathinfo($originalFileName, PATHINFO_EXTENSION),
                'document_name' => $document['name'],
                'document_extension' => pathinfo($document['name'], PATHINFO_EXTENSION),
                'final_download_name' => $downloadName
            ],
            'headers' => [
                'Content-Type' => $mimeType,
                'Content-Length' => $filePath ? filesize($filePath) : null,
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"; filename*=UTF-8\'\'' . rawurlencode($downloadName)
            ]
        ];
        
        $this->json($info);
    }
    
    public function destroy($id)
    {
        try {
            $document = $this->documentModel->find($id);
            
            if (!$document) {
                $this->json(['success' => false, 'message' => 'Documento não encontrado'], 404);
                return;
            }
            
            $user = Auth::user();
            
            // Verificar permissão
            if (!Auth::isAdmin() && $document['user_id'] !== $user['id']) {
                $this->json(['success' => false, 'message' => 'Acesso negado'], 403);
                return;
            }
            
            // Tentar excluir arquivo físico
            $possiblePaths = [
                __DIR__ . '/../../public/' . $document['file_path'],
                __DIR__ . '/../../' . $document['file_path'],
                __DIR__ . '/../../uploads/' . $document['file_path'],
                __DIR__ . '/../../uploads/documents/' . basename($document['file_path'])
            ];
            
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                    break;
                }
            }
            
            // Excluir registro do banco
            if ($this->documentModel->delete($id)) {
                $this->logDocumentAction($id, 'delete');
                $this->json(['success' => true, 'message' => 'Documento excluído com sucesso!']);
            } else {
                $this->json(['success' => false, 'message' => 'Erro ao excluir documento do banco de dados'], 500);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao excluir documento: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Erro interno do servidor'], 500);
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
    
    private function getMimeTypeByExtension($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            // Documentos
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            
            // Imagens
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            
            // Texto
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'html' => 'text/html',
            'htm' => 'text/html',
            'xml' => 'text/xml',
            
            // Compactados
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            '7z' => 'application/x-7z-compressed',
            
            // Audio/Video
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            
            // Outros
            'json' => 'application/json',
            'js' => 'application/javascript',
            'css' => 'text/css'
        ];
        
        return $mimeTypes[$extension] ?? null;
    }
    
    private function getExtensionFromMimeType($mimeType)
    {
        $extensions = [
            // Documentos
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            
            // Imagens
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg',
            
            // Texto
            'text/plain' => 'txt',
            'text/csv' => 'csv',
            'text/html' => 'html',
            'text/xml' => 'xml',
            
            // Compactados
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/x-7z-compressed' => '7z',
            
            // Audio/Video
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
            'video/mp4' => 'mp4',
            'video/x-msvideo' => 'avi',
            
            // Outros
            'application/json' => 'json',
            'application/javascript' => 'js',
            'text/css' => 'css'
        ];
        
        return $extensions[$mimeType] ?? null;
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
