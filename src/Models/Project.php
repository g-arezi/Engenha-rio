<?php

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected function getDataFile()
    {
        return __DIR__ . '/../../data/projects.json';
    }
    
    public function getByStatus($status)
    {
        return $this->where('status', $status);
    }
    
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId);
    }
    
    public function getByClient($clientId)
    {
        // Buscar projetos onde o cliente está vinculado
        $projects = $this->all();
        $clientProjects = [];
        
        foreach ($projects as $project) {
            // Verificar se o cliente está no campo client_id
            if (isset($project['client_id']) && $project['client_id'] === $clientId) {
                $clientProjects[] = $project;
                continue;
            }
            
            // Verificar se o cliente está no array de clients
            if (isset($project['clients']) && is_array($project['clients']) && in_array($clientId, $project['clients'])) {
                $clientProjects[] = $project;
                continue;
            }
            
            // Fallback para user_id (compatibilidade)
            if (isset($project['user_id']) && $project['user_id'] === $clientId) {
                $clientProjects[] = $project;
            }
        }
        
        return $clientProjects;
    }
    
    /**
     * Obter projetos com template de documentos
     */
    public function getWithDocumentTemplate($id)
    {
        $project = $this->find($id);
        if (!$project) {
            return null;
        }
        
        // Se tem template associado, buscar detalhes
        if (!empty($project['document_template_id'])) {
            $templateModel = new DocumentTemplate();
            $template = $templateModel->getWithDocuments($project['document_template_id']);
            $project['document_template'] = $template;
        }
        
        return $project;
    }
    
    /**
     * Associar template de documentos ao projeto
     */
    public function associateDocumentTemplate($projectId, $templateId)
    {
        return $this->update($projectId, [
            'document_template_id' => $templateId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Obter projetos por template
     */
    public function getByDocumentTemplate($templateId)
    {
        return $this->where('document_template_id', $templateId);
    }
    
    /**
     * Obter estatísticas de documentos do projeto
     */
    public function getDocumentStats($projectId)
    {
        $project = $this->getWithDocumentTemplate($projectId);
        if (!$project || !isset($project['document_template'])) {
            return null;
        }
        
        $template = $project['document_template'];
        $requiredDocs = $template['required_documents'] ?? [];
        $optionalDocs = $template['optional_documents'] ?? [];
        
        // Buscar documentos enviados
        $documentModel = new Document();
        $uploadedDocs = $documentModel->getByProject($projectId);
        
        $stats = [
            'required_total' => count($requiredDocs),
            'required_uploaded' => 0,
            'optional_total' => count($optionalDocs),
            'optional_uploaded' => 0,
            'completion_percentage' => 0
        ];
        
        // Verificar quais documentos obrigatórios foram enviados
        foreach ($requiredDocs as $reqDoc) {
            foreach ($uploadedDocs as $uploaded) {
                if ($uploaded['type'] === $reqDoc['type']) {
                    $stats['required_uploaded']++;
                    break;
                }
            }
        }
        
        // Verificar documentos opcionais
        foreach ($optionalDocs as $optDoc) {
            foreach ($uploadedDocs as $uploaded) {
                if ($uploaded['type'] === $optDoc['type']) {
                    $stats['optional_uploaded']++;
                    break;
                }
            }
        }
        
        // Calcular porcentagem de conclusão baseada nos obrigatórios
        if ($stats['required_total'] > 0) {
            $stats['completion_percentage'] = round(($stats['required_uploaded'] / $stats['required_total']) * 100);
        }
        
        return $stats;
    }
    
    public function getByAnalyst($analystId)
    {
        return $this->where('analyst_id', $analystId);
    }
    
    public function create($attributes)
    {
        $attributes['status'] = $attributes['status'] ?? 'aguardando';
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        
        return parent::create($attributes);
    }
    
    public function update($id, $attributes)
    {
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        return parent::update($id, $attributes);
    }
    
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
    
    public function getStatusCounts()
    {
        $statuses = ['aguardando', 'pendente', 'atrasado', 'aprovado'];
        $counts = [];
        
        foreach ($statuses as $status) {
            $counts[$status] = count($this->where('status', $status));
        }
        
        return $counts;
    }
    
    public function getRecentProjects($limit = 10)
    {
        $projects = $this->all();
        usort($projects, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($projects, 0, $limit);
    }
}
