<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function check()
    {
        return Session::has('user_id');
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }
        
        $userId = Session::get('user_id');
        $userModel = new User();
        return $userModel->find($userId);
    }

    public static function login($email, $password)
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // Verificar se o usuário está aprovado (admin sempre aprovado)
            if ($user['role'] === 'admin' || ($user['approved'] ?? false)) {
                Session::set('user_id', $user['id']);
                Session::regenerate();
                return true;
            }
        }
        
        return false;
    }

    public static function register($data)
    {
        $userModel = new User();
        
        // Verificar se o email já existe
        if ($userModel->findByEmail($data['email'])) {
            return false;
        }
        
        // Hash da senha
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = $data['role'] ?? 'cliente';
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $userId = $userModel->create($data);
        
        if ($userId) {
            // Não fazer login automático - aguardar aprovação
            return true;
        }
        
        return false;
    }

    public static function logout()
    {
        Session::remove('user_id');
        Session::destroy();
    }

    public static function id()
    {
        return Session::get('user_id');
    }

    public static function isAdmin()
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function isAnalyst()
    {
        $user = self::user();
        return $user && $user['role'] === 'analista';
    }

    public static function isClient()
    {
        $user = self::user();
        return $user && $user['role'] === 'cliente';
    }

    public static function can($permission)
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        
        $permissions = [
            'admin' => ['*'], // Admin pode tudo
            'analista' => [
                'view_projects', 
                'create_projects', 
                'update_projects', 
                'validate_projects',
                'delete_projects',
                'view_documents', 
                'upload_documents',
                'manage_documents',
                'view_users'
            ],
            'cliente' => [
                'view_projects',      // Pode ver projetos vinculados
                'view_documents',     // Pode ver documentos
                'upload_documents'    // Pode enviar documentos APENAS aos projetos vinculados
            ]
        ];
        
        $userPermissions = $permissions[$user['role']] ?? [];
        
        return in_array('*', $userPermissions) || in_array($permission, $userPermissions);
    }
    
    /**
     * Verificar se pode criar/validar projetos
     */
    public static function canManageProjects()
    {
        $user = self::user();
        return $user && in_array($user['role'], ['admin', 'analista']);
    }
    
    /**
     * Verificar se cliente pode enviar documento para um projeto específico
     */
    public static function canUploadToProject($projectId)
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        
        // Admin e analista podem enviar para qualquer projeto
        if (in_array($user['role'], ['admin', 'analista'])) {
            return true;
        }
        
        // Cliente só pode enviar para projetos vinculados
        if ($user['role'] === 'cliente') {
            // Verificar se o cliente está vinculado ao projeto
            return self::isClientLinkedToProject($user['id'], $projectId);
        }
        
        return false;
    }
    
    /**
     * Verificar se cliente está vinculado ao projeto
     */
    private static function isClientLinkedToProject($clientId, $projectId)
    {
        $projectModel = new \App\Models\Project();
        $project = $projectModel->find($projectId);
        
        if (!$project) {
            return false;
        }
        
        // Verificar se o cliente é o responsável pelo projeto ou está na lista de clientes
        return ($project['client_id'] ?? null) === $clientId || 
               in_array($clientId, $project['clients'] ?? []);
    }
}
