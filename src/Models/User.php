<?php
/**
 * Sistema de Gestão de Projetos - Engenha Rio
 * 
 * © 2025 Engenha Rio - Todos os direitos reservados
 * Desenvolvido por: Gabriel Arezi
 * Portfolio: https://portifolio-beta-five-52.vercel.app/
 * GitHub: https://github.com/g-arezi
 * 
 * Este software é propriedade intelectual protegida.
 * Uso não autorizado será processado judicialmente.
 */

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected function getDataFile()
    {
        return __DIR__ . '/../../data/users.json';
    }
    
    public function findByEmail($email)
    {
        return $this->first('email', $email);
    }
    
    public function getByRole($role)
    {
        return $this->where('role', $role);
    }
    
    public function getActiveUsers()
    {
        return $this->where('active', true);
    }
    
    public function create($attributes)
    {
        // Definir valores padrão
        $attributes['active'] = $attributes['active'] ?? true;
        $attributes['approved'] = $attributes['approved'] ?? false; // Usuários começam não aprovados
        $attributes['created_at'] = $attributes['created_at'] ?? date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        
        return parent::create($attributes);
    }
    
    public function update($id, $attributes)
    {
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        
        // Find the user by ID and update it
        foreach ($this->data as $key => $user) {
            if ($user['id'] === $id) {
                $this->data[$key] = array_merge($this->data[$key], $attributes);
                $this->saveData();
                return true;
            }
        }
        
        return false;
    }
    
    public function delete($id)
    {
        // Find the user by ID and delete it
        foreach ($this->data as $key => $user) {
            if ($user['id'] === $id) {
                unset($this->data[$key]);
                $this->saveData();
                return true;
            }
        }
        
        return false;
    }
    
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public function validatePassword($password)
    {
        return strlen($password) >= 6;
    }
    
    public function getPendingUsers()
    {
        return $this->where('approved', false);
    }
    
    public function getApprovedUsers()
    {
        return $this->where('approved', true);
    }
    
    public function approveUser($id)
    {
        return $this->update($id, ['approved' => true]);
    }
    
    public function rejectUser($id)
    {
        return $this->delete($id);
    }
    
    public function getUserStats()
    {
        $users = $this->all();
        $total = count($users);
        $active = count($this->getActiveUsers());
        $pending = count($this->getPendingUsers());
        $approved = count($this->getApprovedUsers());
        
        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'approved' => $approved,
            'inactive' => $total - $active
        ];
    }
    
    public function resetPassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
    
    public function toggleStatus($id)
    {
        $user = $this->findById($id);
        if ($user) {
            return $this->update($id, ['active' => !$user['active']]);
        }
        return false;
    }
    
    public function getUsersWithStats()
    {
        $users = $this->all();
        
        foreach ($users as &$user) {
            $user['projects_count'] = $this->getUserProjectsCount($user['id']);
            $user['documents_count'] = $this->getUserDocumentsCount($user['id']);
            $user['last_login'] = $this->getLastLogin($user['id']);
        }
        
        return $users;
    }
    
    public function getUserProjectsCount($userId)
    {
        $projectModel = new \App\Models\Project();
        $projects = $projectModel->getByUser($userId);
        return $projects ? count($projects) : 0;
    }
    
    public function getUserDocumentsCount($userId)
    {
        $documentModel = new \App\Models\Document();
        $documents = $documentModel->getByUser($userId);
        return $documents ? count($documents) : 0;
    }
    
    public function getLastLogin($userId)
    {
        // Verificar logs de login (implementar se necessário)
        return null; // Por enquanto retorna null
    }
    
    public function searchUsers($query)
    {
        $users = $this->all();
        
        if (empty($query)) {
            return $users;
        }
        
        $query = strtolower($query);
        
        return array_filter($users, function($user) use ($query) {
            return strpos(strtolower($user['name']), $query) !== false ||
                   strpos(strtolower($user['email']), $query) !== false ||
                   strpos(strtolower($user['role']), $query) !== false;
        });
    }
    
    public function getUsersByStatus($status)
    {
        switch ($status) {
            case 'active':
                return $this->where('active', true);
            case 'inactive':
                return $this->where('active', false);
            case 'pending':
                return $this->getPendingUsers();
            case 'approved':
                return $this->where('approved', true);
            default:
                return $this->all();
        }
    }
    
    public function getUsersByRole($role)
    {
        if ($role === 'all') {
            return $this->all();
        }
        
        return $this->getByRole($role);
    }
    
    public function findById($id)
    {
        foreach ($this->data as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }
    
    public function find($id)
    {
        return $this->findById($id);
    }
}
