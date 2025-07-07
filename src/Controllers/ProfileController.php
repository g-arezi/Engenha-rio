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
        
        $data = [
            'name' => $_POST['name'] ?? $user['name'],
            'email' => $_POST['email'] ?? $user['email']
        ];
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        $userModel->update($user['id'], $data);
        
        header('Location: /profile?success=1');
        exit;
    }
}
