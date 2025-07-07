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
