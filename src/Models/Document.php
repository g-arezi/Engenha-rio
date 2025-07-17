<?php

namespace App\Models;

use App\Core\Model;

class Document extends Model
{
    protected function getDataFile()
    {
        return __DIR__ . '/../../data/documents.json';
    }
    
    public function getByProject($projectId)
    {
        return $this->where('project_id', $projectId);
    }
    
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId);
    }
    
    public function getByType($type)
    {
        return $this->where('type', $type);
    }
    
    public function create($attributes)
    {
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        
        return parent::create($attributes);
    }
    
    public function update($id, $attributes)
    {
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        return parent::update($id, $attributes);
    }
    
    public function getRecentDocuments($limit = 10)
    {
        $documents = $this->all();
        usort($documents, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($documents, 0, $limit);
    }
    
    /**
     * Validar arquivo de documento
     */
    public function validateFile($file)
    {
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/octet-stream', // Para arquivos .dwg
            'image/webp'
        ];
        
        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'dwg', 'dxf', 'webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        
        $errors = [];
        
        // Verificar tamanho
        if ($file['size'] > $maxSize) {
            $errors[] = 'Arquivo muito grande. Máximo permitido: 10MB';
        }
        
        // Verificar tipo MIME
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = 'Tipo de arquivo não permitido';
        }
        
        // Verificar extensão
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'Extensão de arquivo não permitida';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Sanitizar nome do arquivo
     */
    public function sanitizeFileName($fileName)
    {
        // Remover caracteres especiais e manter apenas letras, números, pontos, hífens e underscores
        $fileName = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $fileName);
        
        // Remover múltiplos underscores consecutivos
        $fileName = preg_replace('/_+/', '_', $fileName);
        
        // Remover underscores no início e fim
        $fileName = trim($fileName, '_');
        
        return $fileName;
    }
    
    /**
     * Gerar caminho único para arquivo
     */
    public function generateFilePath($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $sanitizedName = $this->sanitizeFileName($baseName);
        
        $uniqueId = uniqid();
        $fileName = $uniqueId . '_' . $sanitizedName . '.' . $extension;
        
        return 'documents/' . $fileName;
    }
    
    /**
     * Validar dados obrigatórios do documento
     */
    public function validateDocumentData($data)
    {
        $required = ['name', 'type', 'user_id'];
        $errors = [];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = "Campo '$field' é obrigatório";
            }
        }
        
        // Validar tipos permitidos
        $allowedTypes = ['projeto', 'contrato', 'licenca', 'planta', 'memorial', 'estrutural', 'hidraulico', 'eletrico', 'outros'];
        if (!empty($data['type']) && !in_array($data['type'], $allowedTypes)) {
            $errors[] = 'Tipo de documento inválido';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    public function getTotalSize()
    {
        $total = 0;
        foreach ($this->all() as $document) {
            $total += $document['size'] ?? 0;
        }
        return $total;
    }
}
