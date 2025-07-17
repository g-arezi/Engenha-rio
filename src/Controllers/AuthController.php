<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login');
    }
    
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $errors = $this->validate($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $_POST);
            $this->redirect('/login');
        }
        
        if (Auth::login($email, $password)) {
            $this->redirect('/dashboard');
        } else {
            Session::flash('error', 'Credenciais inválidas ou conta não aprovada');
            Session::flash('old', $_POST);
            $this->redirect('/login');
        }
    }
    
    public function showRegister()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.register');
    }
    
    public function register()
    {
        $data = $_POST;
        
        // Forçar o role como 'cliente' para registro público
        $data['role'] = 'cliente';
        
        $errors = $this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);
        
        // Validação adicional: apenas 'cliente' é permitido no registro público
        if ($data['role'] !== 'cliente') {
            $errors['role'] = 'Apenas clientes podem se registrar publicamente';
        }
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect('/register');
        }
        
        if (Auth::register($data)) {
            Session::flash('success', 'Conta criada com sucesso! Aguarde a aprovação do administrador.');
            $this->redirect('/login');
        } else {
            Session::flash('error', 'Este email já está sendo usado');
            Session::flash('old', $data);
            $this->redirect('/register');
        }
    }
    
    public function logout()
    {
        Auth::logout();
        $this->redirect('/');
    }
}
