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

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Project;
use App\Models\Document;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projectModel = new Project();
        $documentModel = new Document();
        $userModel = new User();
        
        $data = [
            'user' => $user,
            'stats' => $this->getStats($user, $projectModel, $documentModel, $userModel),
            'recentProjects' => $this->getRecentProjects($user, $projectModel),
            'recentDocuments' => $this->getRecentDocuments($user, $documentModel)
        ];
        
        $this->view('dashboard.index', $data);
    }
    
    private function getStats($user, $projectModel, $documentModel, $userModel)
    {
        $stats = [];
        
        if (Auth::isAdmin()) {
            $stats = [
                'users' => $userModel->count() ?? 3,
                'projects' => $projectModel->count() ?? 3,
                'documents' => $documentModel->count() ?? 8,
                'pending' => count($projectModel->getByStatus('pendente') ?? [1]),
                'aguardando' => count($projectModel->getByStatus('aguardando') ?? []),
                'atrasado' => count($projectModel->getByStatus('atrasado') ?? []),
                'aprovado' => count($projectModel->getByStatus('aprovado') ?? []),
                'totalProjects' => $projectModel->count(),
                'totalDocuments' => $documentModel->count(),
                'totalUsers' => $userModel->count()
            ];
        } elseif (Auth::isAnalyst()) {
            $userProjects = $projectModel->getByAnalyst($user['id'] ?? '') ?? [];
            $stats = [
                'users' => 1,
                'projects' => count($userProjects),
                'documents' => count($documentModel->getByUser($user['id'] ?? '') ?? []),
                'pending' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'pendente')),
                'aguardando' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'aguardando')),
                'atrasado' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'atrasado')),
                'aprovado' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'aprovado')),
                'myProjects' => count($userProjects),
                'myDocuments' => count($documentModel->getByUser($user['id'] ?? '') ?? [])
            ];
        } else {
            // Para clientes, usar getByClient em vez de getByUser
            $userProjects = $projectModel->getByClient($user['id'] ?? '') ?? [];
            $stats = [
                'users' => 1,
                'projects' => count($userProjects),
                'documents' => count($documentModel->getByUser($user['id'] ?? '') ?? []),
                'pending' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'pendente')),
                'aguardando' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'aguardando')),
                'atrasado' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'atrasado')),
                'aprovado' => count(array_filter($userProjects, fn($p) => ($p['status'] ?? '') === 'aprovado')),
                'myProjects' => count($userProjects),
                'myDocuments' => count($documentModel->getByUser($user['id'] ?? '') ?? [])
            ];
        }
        
        return $stats;
    }
    
    private function getRecentProjects($user, $projectModel)
    {
        if (Auth::isAdmin()) {
            return $projectModel->getRecentProjects(6);
        } elseif (Auth::isAnalyst()) {
            $projects = $projectModel->getByAnalyst($user['id'] ?? '') ?? [];
            usort($projects, function($a, $b) {
                return strtotime($b['created_at'] ?? '1970-01-01') - strtotime($a['created_at'] ?? '1970-01-01');
            });
            return array_slice($projects, 0, 6);
        } else {
            // Para clientes, usar getByClient
            $projects = $projectModel->getByClient($user['id'] ?? '') ?? [];
            usort($projects, function($a, $b) {
                return strtotime($b['created_at'] ?? '1970-01-01') - strtotime($a['created_at'] ?? '1970-01-01');
            });
            return array_slice($projects, 0, 6);
        }
    }
    
    private function getRecentDocuments($user, $documentModel)
    {
        if (Auth::isAdmin()) {
            return $documentModel->getRecentDocuments(6);
        } else {
            $documents = $documentModel->getByUser($user['id'] ?? '') ?? [];
            usort($documents, function($a, $b) {
                return strtotime($b['created_at'] ?? '1970-01-01') - strtotime($a['created_at'] ?? '1970-01-01');
            });
            return array_slice($documents, 0, 6);
        }
    }
}
