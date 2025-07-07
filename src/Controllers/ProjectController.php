<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Project;
use App\Models\User;
use App\Models\Document;

class ProjectController extends Controller
{
    private $projectModel;
    private $userModel;
    private $documentModel;
    
    public function __construct()
    {
        $this->projectModel = new Project();
        $this->userModel = new User();
        $this->documentModel = new Document();
    }
    
    public function index()
    {
        $user = Auth::user();
        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? '';
        
        if (Auth::isAdmin()) {
            $projects = $status ? $this->projectModel->getByStatus($status) : $this->projectModel->all();
        } elseif (Auth::isAnalyst()) {
            $userProjects = $this->projectModel->getByAnalyst($user['id'] ?? '') ?? [];
            $projects = $status ? array_filter($userProjects, fn($p) => ($p['status'] ?? '') === $status) : $userProjects;
        } else {
            // Para clientes, usar o método específico getByClient
            $userProjects = $this->projectModel->getByClient($user['id'] ?? '') ?? [];
            $projects = $status ? array_filter($userProjects, fn($p) => ($p['status'] ?? '') === $status) : $userProjects;
        }
        
        $this->view('projects.index', [
            'projects' => $projects,
            'currentStatus' => $status,
            'user' => $user
        ]);
    }
    
    public function create()
    {
        // Apenas admin e analista podem criar projetos
        if (!Auth::canManageProjects()) {
            $this->json(['error' => 'Acesso negado. Apenas administradores e analistas podem criar projetos.'], 403);
            return;
        }
        
        $analysts = Auth::isAdmin() ? $this->userModel->getByRole('analista') : [];
        $clients = $this->userModel->getByRole('cliente');
        
        $this->view('projects.create', [
            'analysts' => $analysts,
            'clients' => $clients
        ]);
    }
    
    public function store()
    {
        // Apenas admin e analista podem criar projetos
        if (!Auth::canManageProjects()) {
            $this->json(['error' => 'Acesso negado. Apenas administradores e analistas podem criar projetos.'], 403);
            return;
        }
        
        $data = $_POST;
        $user = Auth::user();
        
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'deadline' => 'required',
            'client_id' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect('/projects/create');
        }
        
        // Verificar se o cliente existe e é válido
        $client = $this->userModel->find($data['client_id']);
        if (!$client || $client['role'] !== 'cliente') {
            Session::flash('error', 'Cliente selecionado não é válido');
            Session::flash('old', $data);
            $this->redirect('/projects/create');
            return;
        }
        
        $projectData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'deadline' => $data['deadline'],
            'user_id' => $user['id'],
            'client_id' => $data['client_id'], // Cliente obrigatório
            'analyst_id' => $data['analyst_id'] ?? null,
            'status' => 'aguardando',
            'priority' => $data['priority'] ?? 'normal'
        ];
        
        $projectId = $this->projectModel->create($projectData);
        
        if ($projectId) {
            Session::flash('success', 'Projeto criado com sucesso e vinculado ao cliente!');
            $this->redirect('/projects/' . $projectId);
        } else {
            Session::flash('error', 'Erro ao criar projeto');
            $this->redirect('/projects/create');
        }
    }
    
    public function show($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            Session::flash('error', 'Projeto não encontrado');
            $this->redirect('/projects');
        }
        
        $user = Auth::user();
        
        // Verificar permissão
        $hasAccess = false;
        
        if (Auth::isAdmin()) {
            $hasAccess = true;
        } elseif (Auth::isAnalyst() && $project['analyst_id'] === $user['id']) {
            $hasAccess = true;
        } elseif ($project['user_id'] === $user['id']) {
            $hasAccess = true;
        } elseif (isset($project['client_id']) && $project['client_id'] === $user['id']) {
            $hasAccess = true;
        } elseif (isset($project['clients']) && is_array($project['clients']) && in_array($user['id'], $project['clients'])) {
            $hasAccess = true;
        }
        
        if (!$hasAccess) {
            Session::flash('error', 'Acesso negado');
            $this->redirect('/projects');
        }
        
        // Buscar documentos relacionados ao projeto
        $documents = $this->documentModel->getByProject($id);
        
        // Enriquecer documentos com informações do usuário
        foreach ($documents as &$document) {
            if (isset($document['user_id'])) {
                $documentUser = $this->userModel->find($document['user_id']);
                $document['user_name'] = $documentUser ? $documentUser['name'] : 'Usuário desconhecido';
            }
        }
        
        $this->view('projects.show', [
            'project' => $project,
            'user' => $user,
            'documents' => $documents,
            'isAdmin' => Auth::isAdmin(),
            'isAnalyst' => Auth::isAnalyst(),
            'canUpload' => Auth::canUploadToProject($id)
        ]);
    }
    
    public function update($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            $this->json(['error' => 'Projeto não encontrado'], 404);
            return;
        }
        
        // Apenas admin e analista podem atualizar projetos
        if (!Auth::canManageProjects()) {
            $this->json(['error' => 'Acesso negado. Apenas administradores e analistas podem atualizar projetos.'], 403);
            return;
        }
        
        $user = Auth::user();
        
        $data = $_POST;
        
        $updateData = [];
        
        if (isset($data['status']) && Auth::can('update_projects')) {
            $updateData['status'] = $data['status'];
        }
        
        if (isset($data['notes']) && (Auth::isAdmin() || Auth::isAnalyst())) {
            $updateData['notes'] = $data['notes'];
        }
        
        if (isset($data['name']) && $project['user_id'] === $user['id']) {
            $updateData['name'] = $data['name'];
        }
        
        if (isset($data['description']) && $project['user_id'] === $user['id']) {
            $updateData['description'] = $data['description'];
        }
        
        if ($this->projectModel->update($id, $updateData)) {
            Session::flash('success', 'Projeto atualizado com sucesso!');
            $this->redirect('/projects/' . $id);
        } else {
            Session::flash('error', 'Erro ao atualizar projeto');
            $this->redirect('/projects/' . $id);
        }
    }
    
    public function destroy($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            $this->json(['success' => false, 'message' => 'Projeto não encontrado'], 404);
            return;
        }
        
        // Apenas admin e analista podem excluir projetos
        if (!Auth::canManageProjects()) {
            $this->json(['success' => false, 'message' => 'Acesso negado. Apenas administradores e analistas podem excluir projetos.'], 403);
            return;
        }
        
        if ($this->projectModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'Projeto excluído com sucesso']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao excluir projeto']);
        }
    }
    
    public function updateStatus($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            $this->json(['success' => false, 'message' => 'Projeto não encontrado'], 404);
            return;
        }
        
        // Apenas admin e analista podem validar/atualizar status de projetos
        if (!Auth::canManageProjects()) {
            $this->json(['success' => false, 'message' => 'Acesso negado. Apenas administradores e analistas podem validar projetos.'], 403);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? $_POST['status'] ?? null;
        
        if (!$status) {
            $this->json(['success' => false, 'message' => 'Status é obrigatório'], 400);
            return;
        }
        
        $validStatuses = ['aguardando', 'pendente', 'aprovado', 'atrasado', 'concluido'];
        if (!in_array($status, $validStatuses)) {
            $this->json(['success' => false, 'message' => 'Status inválido'], 400);
            return;
        }
        
        if ($this->projectModel->updateStatus($id, $status)) {
            $this->json(['success' => true, 'message' => 'Status do projeto atualizado com sucesso']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao atualizar status do projeto']);
        }
    }
    
    public function edit($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            $_SESSION['project_message'] = 'Projeto não encontrado';
            $_SESSION['project_success'] = false;
            header('Location: /projects');
            exit;
        }
        
        // Verificar permissões
        $user = Auth::user();
        if (!Auth::isAdmin() && !Auth::isAnalyst()) {
            if ($project['user_id'] !== $user['id']) {
                $_SESSION['project_message'] = 'Você não tem permissão para editar este projeto';
                $_SESSION['project_success'] = false;
                header('Location: /projects');
                exit;
            }
        }
        
        // Buscar informações adicionais se necessário
        $userModel = new \App\Models\User();
        if (isset($project['user_id'])) {
            $client = $userModel->find($project['user_id']);
            $project['client_name'] = $client ? $client['name'] : 'N/A';
        }
        
        if (isset($project['analyst_id'])) {
            $analyst = $userModel->find($project['analyst_id']);
            $project['analyst_name'] = $analyst ? $analyst['name'] : 'N/A';
        }
        
        $data = [
            'project' => $project,
            'user' => $user
        ];
        
        $this->view('projects.edit', $data);
    }
}
