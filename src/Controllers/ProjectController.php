<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Project;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentTemplate;

class ProjectController extends Controller
{
    private $projectModel;
    private $userModel;
    private $documentModel;
    private $documentTemplateModel;
    
    public function __construct()
    {
        $this->projectModel = new Project();
        $this->userModel = new User();
        $this->documentModel = new Document();
        $this->documentTemplateModel = new DocumentTemplate();
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
        
        // Se for requisição AJAX, retornar JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            $this->jsonResponse([
                'success' => true,
                'projects' => array_values($projects)
            ]);
            return;
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
            'client_id' => 'required',
            'project_type' => 'required'
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
        
        // Se for um analista criando o projeto, automaticamente ele será o analista responsável
        // A menos que ele tenha especificado outro analista
        $analystId = null;
        if (Auth::isAnalyst()) {
            // Analista criando: se não especificou outro analista, ele será o responsável
            $analystId = (!empty($data['analyst_id']) && $data['analyst_id'] !== '') ? $data['analyst_id'] : $user['id'];
        } else {
            // Admin criando: usa o analista especificado ou deixa null
            $analystId = (!empty($data['analyst_id']) && $data['analyst_id'] !== '') ? $data['analyst_id'] : null;
        }

        $projectData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'deadline' => $data['deadline'],
            'user_id' => $user['id'],
            'client_id' => $data['client_id'], // Cliente obrigatório
            'analyst_id' => $analystId,
            'project_type' => $data['project_type'],
            'document_template_id' => !empty($data['document_template_id']) ? $data['document_template_id'] : null,
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
        
        // Buscar template do projeto se existir
        $template = null;
        if (!empty($project['document_template_id'])) {
            $template = $this->documentTemplateModel->getWithDocuments($project['document_template_id']);
        }
        
        $this->view('projects.show', [
            'project' => $project,
            'user' => $user,
            'documents' => $documents,
            'template' => $template,
            'isAdmin' => Auth::isAdmin(),
            'isAnalyst' => Auth::isAnalyst(),
            'canUpload' => Auth::canUploadToProject($id)
        ]);
    }
    
    public function update($id)
    {
        // Verificar se o usuário pode editar projetos
        if (!Auth::canEditProjects()) {
            Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem editar projetos.');
            $this->redirect('/projects');
            return;
        }
        
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            Session::flash('error', 'Projeto não encontrado');
            $this->redirect('/projects');
            return;
        }
        
        $user = Auth::user();
        
        // Verificar permissões específicas do projeto
        $canEdit = false;
        if (Auth::isAdmin()) {
            $canEdit = true;
        } elseif (Auth::isAnalyst() && $project['analyst_id'] === $user['id']) {
            $canEdit = true;
        }
        
        if (!$canEdit) {
            Session::flash('error', 'Você não tem permissão para editar este projeto');
            $this->redirect('/projects/' . $id);
            return;
        }
        
        $data = $_POST;
        $updateData = [];
        
        // Campos que admin e analista podem editar
        if (isset($data['name'])) {
            $updateData['name'] = trim($data['name']);
        }
        
        if (isset($data['description'])) {
            $updateData['description'] = trim($data['description']);
        }
        
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }
        
        if (isset($data['priority'])) {
            $updateData['priority'] = $data['priority'];
        }
        
        if (isset($data['deadline'])) {
            $updateData['deadline'] = $data['deadline'];
        }
        
        if (isset($data['notes'])) {
            $updateData['notes'] = trim($data['notes']);
        }
        
        // Validar campos obrigatórios
        if (isset($updateData['name']) && empty($updateData['name'])) {
            Session::flash('error', 'Nome do projeto é obrigatório');
            $this->redirect('/projects/' . $id . '/edit');
            return;
        }
        
        if (isset($updateData['description']) && strlen($updateData['description']) < 10) {
            Session::flash('error', 'Descrição deve ter pelo menos 10 caracteres');
            $this->redirect('/projects/' . $id . '/edit');
            return;
        }
        
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        if ($this->projectModel->update($id, $updateData)) {
            Session::flash('success', 'Projeto atualizado com sucesso!');
            $this->redirect('/projects/' . $id);
        } else {
            Session::flash('error', 'Erro ao atualizar projeto');
            $this->redirect('/projects/' . $id . '/edit');
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
        
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? $_POST['status'] ?? null;
        
        if (!$status) {
            $this->json(['success' => false, 'message' => 'Status é obrigatório'], 400);
            return;
        }
        
        // Verificar permissões específicas baseadas no status
        if ($status === 'aprovado' && !Auth::canApproveProjects()) {
            $this->json(['success' => false, 'message' => 'Acesso negado. Apenas administradores e analistas podem aprovar projetos.'], 403);
            return;
        }
        
        if ($status === 'concluido' && !Auth::canCompleteProjects()) {
            $this->json(['success' => false, 'message' => 'Acesso negado. Apenas administradores e analistas podem concluir projetos.'], 403);
            return;
        }
        
        // Para outros status, verificar se pode alterar status de projetos
        if (!Auth::canChangeProjectStatus()) {
            $this->json(['success' => false, 'message' => 'Acesso negado. Apenas administradores e analistas podem alterar o status de projetos.'], 403);
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
        $canEdit = false;
        
        if (Auth::isAdmin()) {
            $canEdit = true;
        } elseif (Auth::isAnalyst() && ($project['analyst_id'] ?? null) === $user['id']) {
            $canEdit = true;
        } elseif (($project['user_id'] ?? null) === $user['id'] || ($project['client_id'] ?? null) === $user['id']) {
            $canEdit = true;
        }
        
        if (!$canEdit) {
            $_SESSION['project_message'] = 'Você não tem permissão para editar este projeto';
            $_SESSION['project_success'] = false;
            header('Location: /projects/' . $id);
            exit;
        }
        
        // Buscar informações adicionais se necessário
        $userModel = new \App\Models\User();
        
        // Buscar informações do cliente
        if (isset($project['client_id']) && $project['client_id']) {
            $client = $userModel->find($project['client_id']);
            $project['client_name'] = $client ? $client['name'] : 'Cliente não encontrado';
        } elseif (isset($project['user_id']) && $project['user_id']) {
            // Fallback para user_id como cliente
            $client = $userModel->find($project['user_id']);
            $project['client_name'] = $client ? $client['name'] : 'Cliente não encontrado';
        } else {
            $project['client_name'] = 'N/A';
        }
        
        // Buscar informações do analista
        if (isset($project['analyst_id']) && $project['analyst_id']) {
            $analyst = $userModel->find($project['analyst_id']);
            $project['analyst_name'] = $analyst ? $analyst['name'] : 'Analista não encontrado';
        } else {
            $project['analyst_name'] = 'N/A';
        }
        
        $data = [
            'project' => $project,
            'user' => $user
        ];
        
        $this->view('projects.edit', $data);
    }
    
    /**
     * Página de upload de documentos do projeto
     */
    public function documentsUpload($id)
    {
        $project = $this->projectModel->getWithDocumentTemplate($id);
        
        if (!$project) {
            Session::flash('error', 'Projeto não encontrado');
            $this->redirect('/projects');
            return;
        }
        
        $user = Auth::user();
        
        // Verificar se o usuário tem acesso ao projeto
        $hasAccess = false;
        if (Auth::isAdmin()) {
            $hasAccess = true;
        } elseif (Auth::isAnalyst() && $project['analyst_id'] === $user['id']) {
            $hasAccess = true;
        } elseif ($project['user_id'] === $user['id']) {
            $hasAccess = true;
        } elseif (isset($project['client_id']) && $project['client_id'] === $user['id']) {
            $hasAccess = true;
        }
        
        if (!$hasAccess) {
            Session::flash('error', 'Você não tem permissão para acessar este projeto');
            $this->redirect('/projects');
            return;
        }
        
        // Buscar documentos já enviados
        $documentModel = new \App\Models\Document();
        $uploadedDocuments = $documentModel->getByProject($id);
        
        $data = [
            'project' => $project,
            'uploadedDocuments' => $uploadedDocuments
        ];
        
        // Se tem template, incluir dados do template e estatísticas
        if (isset($project['document_template'])) {
            $data['template'] = $project['document_template'];
            $data['documentStats'] = $this->projectModel->getDocumentStats($id);
        }
        
        $this->view('projects.documents_upload', $data);
    }
    
    /**
     * API para listar projetos (JSON)
     */
    public function apiList()
    {
        $user = Auth::user();
        
        if (Auth::isAdmin()) {
            $projects = $this->projectModel->all();
        } elseif (Auth::isAnalyst()) {
            $projects = $this->projectModel->getByAnalyst($user['id'] ?? '') ?? [];
        } else {
            $projects = $this->projectModel->getByClient($user['id'] ?? '') ?? [];
        }
        
        // Simplificar dados para o select
        $simplifiedProjects = array_map(function($project) {
            return [
                'id' => $project['id'],
                'name' => $project['name']
            ];
        }, $projects);
        
        $this->json($simplifiedProjects);
    }
}
