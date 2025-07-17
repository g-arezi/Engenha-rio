<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user
        ];
        
        $this->view('profile.index', $data);
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }
        
        $user = Auth::user();
        $userModel = new User();
        
        // Verificar se é alteração de senha
        if (!empty($_POST['current_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
            return $this->updatePassword($user, $userModel);
        }
        
        // Atualizar outros dados do perfil se necessário
        $data = [
            'name' => $_POST['name'] ?? $user['name'],
            'email' => $_POST['email'] ?? $user['email']
        ];
        
        $userModel->update($user['id'], $data);
        
        header('Location: /profile?success=1');
        exit;
    }
    
    private function updatePassword($user, $userModel)
    {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validações
        if (empty($currentPassword)) {
            header('Location: /profile?error=current_password_required');
            exit;
        }
        
        if (empty($newPassword)) {
            header('Location: /profile?error=new_password_required');
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            header('Location: /profile?error=password_mismatch');
            exit;
        }
        
        if (strlen($newPassword) < 6) {
            header('Location: /profile?error=password_too_short');
            exit;
        }
        
        // Verificar senha atual
        if (!password_verify($currentPassword, $user['password'])) {
            header('Location: /profile?error=invalid_current_password');
            exit;
        }
        
        // Atualizar senha
        $data = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $userModel->update($user['id'], $data);
        
        header('Location: /profile?success=password_updated');
        exit;
    }
}
