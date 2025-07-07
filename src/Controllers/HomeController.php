<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('home');
    }
}
