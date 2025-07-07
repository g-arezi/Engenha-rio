<?php

namespace App\Middleware;

use App\Core\Auth;

class ProjectAccessMiddleware
{
    /**
     * Verificar se o usuário pode acessar a funcionalidade de projetos
     */
    public static function checkAccess($action, $projectId = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return [
                'allowed' => false,
                'message' => 'Usuário não autenticado'
            ];
        }
        
        switch ($action) {
            case 'create':
            case 'edit':
            case 'delete':
            case 'validate':
                // Apenas admin e analista podem gerenciar projetos
                if (!in_array($user['role'], ['admin', 'analista'])) {
                    return [
                        'allowed' => false,
                        'message' => 'Apenas administradores e analistas podem ' . 
                                   self::getActionText($action) . ' projetos'
                    ];
                }
                break;
                
            case 'view':
                // Todos podem ver projetos, mas com restrições
                if ($user['role'] === 'cliente' && $projectId) {
                    // Cliente só pode ver projetos vinculados
                    if (!Auth::canUploadToProject($projectId)) {
                        return [
                            'allowed' => false,
                            'message' => 'Você não tem acesso a este projeto'
                        ];
                    }
                }
                break;
                
            case 'upload_document':
                // Verificar se pode enviar documento para o projeto
                if ($projectId && !Auth::canUploadToProject($projectId)) {
                    return [
                        'allowed' => false,
                        'message' => 'Você não pode enviar documentos para este projeto'
                    ];
                }
                break;
                
            default:
                return [
                    'allowed' => false,
                    'message' => 'Ação não reconhecida'
                ];
        }
        
        return [
            'allowed' => true,
            'message' => 'Acesso permitido'
        ];
    }
    
    /**
     * Obter texto da ação para mensagens
     */
    private static function getActionText($action)
    {
        $actions = [
            'create' => 'criar',
            'edit' => 'editar',
            'delete' => 'excluir',
            'validate' => 'validar'
        ];
        
        return $actions[$action] ?? $action;
    }
    
    /**
     * Filtrar projetos baseado no usuário
     */
    public static function filterProjectsForUser($projects, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        if (!$user) {
            return [];
        }
        
        // Admin e analista veem todos os projetos
        if (in_array($user['role'], ['admin', 'analista'])) {
            return $projects;
        }
        
        // Cliente vê apenas projetos vinculados
        if ($user['role'] === 'cliente') {
            return array_filter($projects, function($project) use ($user) {
                return ($project['client_id'] ?? null) === $user['id'] || 
                       in_array($user['id'], $project['clients'] ?? []);
            });
        }
        
        return [];
    }
    
    /**
     * Obter lista de projetos que o cliente pode acessar
     */
    public static function getClientProjects($clientId)
    {
        $projectModel = new \App\Models\Project();
        $allProjects = $projectModel->all();
        
        return array_filter($allProjects, function($project) use ($clientId) {
            return ($project['client_id'] ?? null) === $clientId || 
                   in_array($clientId, $project['clients'] ?? []);
        });
    }
    
    /**
     * Verificar se um cliente está vinculado a um projeto
     */
    public static function isClientLinkedToProject($clientId, $projectId)
    {
        $projectModel = new \App\Models\Project();
        $project = $projectModel->find($projectId);
        
        if (!$project) {
            return false;
        }
        
        return ($project['client_id'] ?? null) === $clientId || 
               in_array($clientId, $project['clients'] ?? []);
    }
}
