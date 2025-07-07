<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Project;
use App\Models\Document;
use App\Models\User;
use Exception;
use ZipArchive;

class AdminController extends Controller
{
    public function __construct()
    {
        if (!Auth::isAdmin()) {
            header('Location: /dashboard');
            exit;
        }
    }
    
    public function index()
    {
        $user = Auth::user();
        $projectModel = new Project();
        $documentModel = new Document();
        $userModel = new User();
        
        $data = [
            'user' => $user,
            'stats' => $this->getStats($projectModel, $documentModel, $userModel),
            'recentProjects' => $projectModel->getRecentProjects(3),
            'recentActivities' => $this->getRecentActivities()
        ];
        
        $this->view('admin.index', $data);
    }
    
    public function users()
    {
        $userModel = new User();
        $users = $userModel->all();
        $pendingUsers = $userModel->getPendingUsers();
        
        $data = [
            'users' => $users,
            'pendingUsers' => $pendingUsers
        ];
        
        $this->view('admin.users', $data);
    }
    
    public function projects()
    {
        $projectModel = new Project();
        $userModel = new User();
        
        try {
            $projects = $projectModel->all();
            
            // Enriquecer os dados dos projetos com informações do cliente
            foreach ($projects as &$project) {
                if (!empty($project['client_id'])) {
                    $client = $userModel->find($project['client_id']);
                    $project['client_name'] = $client ? $client['name'] : 'Cliente não encontrado';
                } else {
                    $project['client_name'] = 'Sem cliente vinculado';
                }
            }
            
        } catch (Exception $e) {
            $projects = [];
        }
        
        $data = [
            'user' => Auth::user(),
            'projects' => $projects
        ];
        
        $this->view('admin.projects', $data);
    }
    
    public function documents()
    {
        $documentModel = new Document();
        $documents = $documentModel->all();
        
        $data = [
            'documents' => $documents
        ];
        
        $this->view('admin.documents', $data);
    }
    
    public function approveUser($id)
    {
        $userModel = new User();
        $success = $userModel->approveUser($id);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Usuário aprovado com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao aprovar usuário'], 500);
        }
    }
    
    public function rejectUser($id)
    {
        $userModel = new User();
        $success = $userModel->rejectUser($id);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Usuário rejeitado com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao rejeitar usuário'], 500);
        }
    }
    
    public function toggleUserStatus($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'error' => 'Usuário não encontrado'], 404);
            return;
        }
        
        $newStatus = !($user['active'] ?? true);
        $success = $userModel->update($id, ['active' => $newStatus]);
        
        if ($success) {
            $statusText = $newStatus ? 'ativado' : 'desativado';
            $this->json(['success' => true, 'message' => "Usuário $statusText com sucesso", 'status' => $newStatus]);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao alterar status do usuário'], 500);
        }
    }
    
    public function resetPassword($id)
    {
        // Verificar se é FormData (POST) ou JSON
        $input = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se for FormData, usar $_POST
            if (!empty($_POST)) {
                $input = $_POST;
            } else {
                // Tentar JSON se $_POST estiver vazio
                $json = json_decode(file_get_contents('php://input'), true);
                if ($json) {
                    $input = $json;
                }
            }
        }
        
        $newPassword = $input['new_password'] ?? '';
        
        if (empty($id) || empty($newPassword)) {
            $this->json(['success' => false, 'error' => 'ID do usuário e nova senha são obrigatórios'], 400);
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $this->json(['success' => false, 'error' => 'A senha deve ter pelo menos 6 caracteres'], 400);
            return;
        }
        
        $userModel = new User();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $success = $userModel->update($id, ['password' => $hashedPassword]);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Senha resetada com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao resetar senha'], 500);
        }
    }
    
    public function deleteUser($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'error' => 'Usuário não encontrado'], 404);
            return;
        }
        
        // Não permitir excluir o próprio usuário
        if ($id == Auth::user()['id']) {
            $this->json(['success' => false, 'error' => 'Não é possível excluir seu próprio usuário'], 400);
            return;
        }
        
        $success = $userModel->delete($id);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Usuário excluído com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao excluir usuário'], 500);
        }
    }
    
    public function createUser()
    {
        // Verificar se é FormData (POST) ou JSON
        $input = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se for FormData, usar $_POST
            if (!empty($_POST)) {
                $input = $_POST;
            } else {
                // Tentar JSON se $_POST estiver vazio
                $json = json_decode(file_get_contents('php://input'), true);
                if ($json) {
                    $input = $json;
                }
            }
        }
        
        if (empty($input)) {
            $this->json(['success' => false, 'error' => 'Dados não fornecidos'], 400);
            return;
        }
        
        // Validar dados obrigatórios
        $required = ['name', 'email', 'password', 'role'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->json(['success' => false, 'error' => "Campo '$field' é obrigatório"], 400);
                return;
            }
        }
        
        // Validar email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'error' => 'Email inválido'], 400);
            return;
        }
        
        // Validar senha
        if (strlen($input['password']) < 6) {
            $this->json(['success' => false, 'error' => 'A senha deve ter pelo menos 6 caracteres'], 400);
            return;
        }
        
        // Validar função
        $allowedRoles = ['admin', 'analista', 'cliente'];
        if (!in_array($input['role'], $allowedRoles)) {
            $this->json(['success' => false, 'error' => 'Função inválida'], 400);
            return;
        }
        
        $userModel = new User();
        
        // Verificar se email já existe
        if ($userModel->findByEmail($input['email'])) {
            $this->json(['success' => false, 'error' => 'Email já cadastrado'], 400);
            return;
        }
        
        $userData = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'role' => $input['role'],
            'active' => isset($input['active']) ? ($input['active'] === 'on' || $input['active'] === true || $input['active'] === '1') : true,
            'approved' => isset($input['approved']) ? ($input['approved'] === 'on' || $input['approved'] === true || $input['approved'] === '1') : true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $success = $userModel->create($userData);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Usuário criado com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao criar usuário'], 500);
        }
    }
    
    public function updateUser($id)
    {
        // Verificar se é FormData (POST) ou JSON
        $input = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se for FormData, usar $_POST
            if (!empty($_POST)) {
                $input = $_POST;
            } else {
                // Tentar JSON se $_POST estiver vazio
                $json = json_decode(file_get_contents('php://input'), true);
                if ($json) {
                    $input = $json;
                }
            }
        }
        
        if (empty($input)) {
            $this->json(['success' => false, 'error' => 'Dados não fornecidos'], 400);
            return;
        }
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'error' => 'Usuário não encontrado'], 404);
            return;
        }
        
        // Validar dados
        $updateData = [];
        
        if (isset($input['name']) && !empty($input['name'])) {
            $updateData['name'] = $input['name'];
        }
        
        if (isset($input['email']) && !empty($input['email'])) {
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $this->json(['success' => false, 'error' => 'Email inválido'], 400);
                return;
            }
            $updateData['email'] = $input['email'];
        }
        
        if (isset($input['role']) && !empty($input['role'])) {
            $allowedRoles = ['admin', 'user', 'moderator'];
            if (!in_array($input['role'], $allowedRoles)) {
                $this->json(['success' => false, 'error' => 'Função inválida'], 400);
                return;
            }
            $updateData['role'] = $input['role'];
        }
        
        if (isset($input['active'])) {
            $updateData['active'] = (bool)$input['active'];
        }
        
        if (isset($input['approved'])) {
            $updateData['approved'] = (bool)$input['approved'];
        }
        
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        $success = $userModel->update($id, $updateData);
        
        if ($success) {
            $this->json(['success' => true, 'message' => 'Usuário atualizado com sucesso']);
        } else {
            $this->json(['success' => false, 'error' => 'Erro ao atualizar usuário'], 500);
        }
    }
    
    public function editUser($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'error' => 'Usuário não encontrado'], 404);
            return;
        }
        
        $this->json(['success' => true, 'user' => $user]);
    }
    
    public function viewUser($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'error' => 'Usuário não encontrado'], 404);
            return;
        }
        
        // Adicionar estatísticas do usuário
        $userData = [
            'user' => $user,
            'stats' => [
                'accountAge' => $this->calculateAccountAge($user['created_at'] ?? date('Y-m-d H:i:s')),
                'lastLogin' => $user['last_login'] ?? 'Nunca'
            ]
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('admin.view_user', $userData);
        } else {
            $this->json(['success' => true, 'data' => $userData]);
        }
    }
    
    public function bulkAction()
    {
        // Verificar se é FormData (POST) ou JSON
        $input = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se for FormData, usar $_POST
            if (!empty($_POST)) {
                $input = $_POST;
            } else {
                // Tentar JSON se $_POST estiver vazio
                $json = json_decode(file_get_contents('php://input'), true);
                if ($json) {
                    $input = $json;
                }
            }
        }
        
        if (empty($input['action']) || empty($input['users'])) {
            $this->json(['success' => false, 'error' => 'Ação e usuários são obrigatórios'], 400);
            return;
        }
        
        $action = $input['action'];
        $userIds = $input['users'];
        $userModel = new User();
        $results = [];
        
        foreach ($userIds as $userId) {
            $user = $userModel->find($userId);
            if (!$user) {
                $results[] = ['id' => $userId, 'success' => false, 'error' => 'Usuário não encontrado'];
                continue;
            }
            
            switch ($action) {
                case 'approve':
                    $success = $userModel->approveUser($userId);
                    $results[] = ['id' => $userId, 'success' => $success];
                    break;
                    
                case 'reject':
                    $success = $userModel->rejectUser($userId);
                    $results[] = ['id' => $userId, 'success' => $success];
                    break;
                    
                case 'activate':
                    $success = $userModel->update($userId, ['active' => true]);
                    $results[] = ['id' => $userId, 'success' => $success];
                    break;
                    
                case 'deactivate':
                    $success = $userModel->update($userId, ['active' => false]);
                    $results[] = ['id' => $userId, 'success' => $success];
                    break;
                    
                case 'delete':
                    // Não permitir excluir o próprio usuário
                    if ($userId == Auth::user()['id']) {
                        $results[] = ['id' => $userId, 'success' => false, 'error' => 'Não é possível excluir seu próprio usuário'];
                        break;
                    }
                    $success = $userModel->delete($userId);
                    $results[] = ['id' => $userId, 'success' => $success];
                    break;
                    
                default:
                    $results[] = ['id' => $userId, 'success' => false, 'error' => 'Ação inválida'];
            }
        }
        
        $this->json(['success' => true, 'message' => 'Ações processadas', 'results' => $results]);
    }
    
    public function exportUsers()
    {
        $userModel = new User();
        $users = $userModel->all();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="usuarios_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Cabeçalho CSV
        fputcsv($output, [
            'ID',
            'Nome',
            'Email',
            'Função',
            'Status',
            'Aprovado',
            'Data de Cadastro',
            'Última Atualização'
        ]);
        
        // Dados dos usuários
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['role'],
                ($user['active'] ?? true) ? 'Ativo' : 'Inativo',
                ($user['approved'] ?? false) ? 'Aprovado' : 'Pendente',
                $user['created_at'],
                $user['updated_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function exportProjects()
    {
        $projectModel = new Project();
        $userModel = new User();
        
        try {
            $projects = $projectModel->all();
            
            // Enriquecer os dados dos projetos com informações do cliente
            foreach ($projects as &$project) {
                if (!empty($project['client_id'])) {
                    $client = $userModel->find($project['client_id']);
                    $project['client_name'] = $client ? $client['name'] : 'Cliente não encontrado';
                } else {
                    $project['client_name'] = 'Sem cliente vinculado';
                }
            }
            
        } catch (Exception $e) {
            $projects = [];
        }
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="projetos_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Cabeçalho CSV
        fputcsv($output, [
            'ID',
            'Nome do Projeto',
            'Descrição',
            'Cliente',
            'Status',
            'Data de Criação',
            'Última Atualização'
        ]);
        
        // Dados dos projetos
        foreach ($projects as $project) {
            fputcsv($output, [
                $project['id'],
                $project['name'],
                $project['description'] ?? '',
                $project['client_name'],
                $project['status'],
                $project['created_at'],
                $project['updated_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function history()
    {
        $userModel = new User();
        $users = $userModel->all();
        
        // Filtros
        $type = $_GET['type'] ?? '';
        $userFilter = $_GET['user'] ?? '';
        $date = $_GET['date'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        try {
            $activities = $this->getRecentActivities($type, $userFilter, $date, $page);
            
            // Validar se o retorno tem a estrutura esperada
            if (!is_array($activities) || !isset($activities['data']) || !isset($activities['totalPages'])) {
                throw new Exception('Estrutura de dados inválida retornada por getRecentActivities');
            }
            
            $data = [
                'user' => Auth::user(),
                'users' => $users,
                'activities' => $activities['data'] ?? [],
                'currentPage' => $activities['currentPage'] ?? 1,
                'totalPages' => $activities['totalPages'] ?? 1
            ];
            
        } catch (Exception $e) {
            // Em caso de erro, usar dados padrão
            $data = [
                'user' => Auth::user(),
                'users' => $users,
                'activities' => [],
                'currentPage' => 1,
                'totalPages' => 1
            ];
        }
        
        $this->view('admin.history', $data);
    }
    
    private function getRecentActivities($type = '', $userFilter = '', $date = '', $page = 1)
    {
        // Simular dados de atividades (em um sistema real, viria de um log ou banco de dados)
        $allActivities = [
            [
                'timestamp' => '2025-07-04 20:00:00',
                'user_name' => 'Administrador',
                'action' => 'Login realizado',
                'details' => 'IP: 127.0.0.1',
                'type' => 'login',
                'description' => 'Login realizado', // Para compatibilidade com dashboard
                'user' => 'Administrador'
            ],
            [
                'timestamp' => '2025-07-04 19:45:00',
                'user_name' => 'Administrador',
                'action' => 'Usuário atualizado',
                'details' => 'Usuário: João Silva',
                'type' => 'user_update',
                'description' => 'Usuário atualizado',
                'user' => 'Administrador'
            ],
            [
                'timestamp' => '2025-07-04 19:30:00',
                'user_name' => 'João Silva',
                'action' => 'Documento enviado',
                'details' => 'Arquivo: projeto_estrutural.pdf',
                'type' => 'document_upload',
                'description' => 'Documento enviado',
                'user' => 'João Silva'
            ],
            [
                'timestamp' => '2025-07-04 19:15:00',
                'user_name' => 'Administrador',
                'action' => 'Novo usuário criado',
                'details' => 'Usuário: Maria Santos',
                'type' => 'user_create',
                'description' => 'Novo usuário criado',
                'user' => 'Administrador'
            ],
            [
                'timestamp' => '2025-07-04 18:30:00',
                'user_name' => 'Rafael Edinaldo',
                'action' => 'Projeto criado',
                'details' => 'Projeto: Edifício Comercial Downtown',
                'type' => 'project_create',
                'description' => 'Projeto criado',
                'user' => 'Rafael Edinaldo'
            ],
            [
                'timestamp' => '2025-07-04 17:45:00',
                'user_name' => 'João Silva',
                'action' => 'Login realizado',
                'details' => 'IP: 192.168.1.10',
                'type' => 'login',
                'description' => 'Login realizado',
                'user' => 'João Silva'
            ],
            [
                'timestamp' => '2025-07-04 16:30:00',
                'user_name' => 'Administrador',
                'action' => 'Configurações atualizadas',
                'details' => 'Módulo: Aprovação de usuários',
                'type' => 'settings_update',
                'description' => 'Configurações atualizadas',
                'user' => 'Administrador'
            ],
            [
                'timestamp' => '2025-07-04 15:15:00',
                'user_name' => 'Rafael Edinaldo',
                'action' => 'Documento enviado',
                'details' => 'Arquivo: memorial_descritivo.pdf',
                'type' => 'document_upload',
                'description' => 'Documento enviado',
                'user' => 'Rafael Edinaldo'
            ],
        ];
        
        // Aplicar filtros
        $filteredActivities = array_filter($allActivities, function($activity) use ($type, $userFilter, $date) {
            if ($type && $activity['type'] !== $type) return false;
            if ($userFilter && strpos($activity['user_name'], $userFilter) === false) return false;
            if ($date && !str_starts_with($activity['timestamp'], $date)) return false;
            return true;
        });
        
        // Se chamado sem filtros específicos (como no dashboard), limita a 5 atividades
        $isForDashboard = empty($type) && empty($userFilter) && empty($date) && $page === 1;
        if ($isForDashboard) {
            $filteredActivities = array_slice($filteredActivities, 0, 5);
        }
        
        // Paginação
        $perPage = $isForDashboard ? 5 : 10;
        $total = count($filteredActivities);
        $totalPages = $isForDashboard ? 1 : ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        $paginatedActivities = array_slice($filteredActivities, $offset, $perPage);
        
        return [
            'data' => $paginatedActivities,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'total' => $total
        ];
    }
    
    public function settings()
    {
        $userModel = new User();
        $users = $userModel->all();
        
        // Configurações do sistema em formato plano (compatível com a view existente)
        $settings = [
            'site_name' => 'Engenha-rio',
            'site_description' => 'Sistema de Gerenciamento de Projetos de Engenharia',
            'admin_email' => 'admin@engenhario.com',
            'timezone' => 'America/Sao_Paulo',
            'language' => 'pt-BR',
            'maintenance_mode' => false,
            'user_registration' => true,
            'auto_approve_users' => false,
            'require_email_verification' => true,
            'password_min_length' => 8,
            'session_timeout' => 60,
            'max_login_attempts' => 5,
            'max_file_size' => 10,
            'allowed_extensions' => 'pdf,doc,docx,jpg,jpeg,png,dwg,dxf',
            'allowed_file_types' => 'pdf,doc,docx,jpg,jpeg,png,dwg,dxf',
            'upload_path' => 'uploads/',
            'virus_scan' => false,
            'email_notifications' => true,
            'sms_notifications' => false,
            'push_notifications' => true,
            'admin_notifications' => true,
            'two_factor_auth' => false,
            'ip_whitelist' => '',
            'force_https' => false,
            'session_encryption' => true,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 'Sistema'
        ];
        
        $data = [
            'user' => Auth::user(),
            'users' => $users,
            'settings' => $settings
        ];
        
        $this->view('admin.settings', $data);
    }
    
    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/settings');
            exit;
        }
        
        try {
            // Validar dados recebidos
            $settingsData = $_POST;
            
            // Em um sistema real, aqui salvaria as configurações em banco/arquivo
            // Por enquanto, apenas simularemos o sucesso
            
            // Simular salvamento
            $success = true;
            
            if ($success) {
                $response = [
                    'success' => true,
                    'message' => 'Configurações atualizadas com sucesso!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erro ao atualizar configurações. Tente novamente.'
                ];
            }
            
            // Se for uma requisição AJAX, retornar JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            // Se não for AJAX, redirecionar com mensagem
            $_SESSION['settings_message'] = $response['message'];
            $_SESSION['settings_success'] = $response['success'];
            header('Location: /admin/settings');
            exit;
            
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['settings_message'] = $response['message'];
            $_SESSION['settings_success'] = false;
            header('Location: /admin/settings');
            exit;
        }
    }
    
    public function clearCache()
    {
        try {
            // Limpar cache do sistema
            $cacheFiles = glob('cache/*.cache');
            $cleared = 0;
            
            foreach ($cacheFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $cleared++;
                }
            }
            
            // Limpar cache de sessões antigas
            $sessionFiles = glob('sessions/sess_*');
            foreach ($sessionFiles as $file) {
                if (is_file($file) && (time() - filemtime($file) > 86400)) {
                    unlink($file);
                    $cleared++;
                }
            }
            
            $response = [
                'success' => true,
                'message' => "Cache limpo com sucesso! {$cleared} arquivos removidos.",
                'cleared' => $cleared
            ];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['admin_message'] = $response['message'];
            $_SESSION['admin_success'] = true;
            header('Location: /admin');
            exit;
            
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erro ao limpar cache: ' . $e->getMessage()
            ];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['admin_message'] = $response['message'];
            $_SESSION['admin_success'] = false;
            header('Location: /admin');
            exit;
        }
    }
    
    public function exportData()
    {
        try {
            $userModel = new User();
            $projectModel = new Project();
            $documentModel = new Document();
            
            $data = [
                'users' => $userModel->all(),
                'projects' => $projectModel->all(),
                'documents' => $documentModel->all(),
                'export_date' => date('Y-m-d H:i:s'),
                'exported_by' => Auth::user()['name'] ?? 'Sistema'
            ];
            
            $filename = 'engenhario_backup_' . date('Y-m-d_H-i-s') . '.json';
            
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['admin_message'] = 'Erro ao exportar dados: ' . $e->getMessage();
            $_SESSION['admin_success'] = false;
            header('Location: /admin');
            exit;
        }
    }
    
    public function viewLogs()
    {
        try {
            $logs = [];
            $logFiles = [
                'errors' => 'logs/errors.log',
                'access' => 'logs/access.log',
                'admin' => 'logs/admin.log',
                'system' => 'logs/system.log'
            ];
            
            foreach ($logFiles as $type => $file) {
                if (file_exists($file)) {
                    $content = file_get_contents($file);
                    $lines = array_filter(explode("\n", $content));
                    $logs[$type] = array_slice(array_reverse($lines), 0, 100); // Últimas 100 linhas
                } else {
                    $logs[$type] = [];
                }
            }
            
            $data = [
                'user' => Auth::user(),
                'logs' => $logs,
                'logFiles' => $logFiles,
                'totalLogs' => array_sum(array_map('count', $logs))
            ];
            
            $this->view('admin.logs', $data);
            
        } catch (Exception $e) {
            $_SESSION['admin_message'] = 'Erro ao carregar logs: ' . $e->getMessage();
            $_SESSION['admin_success'] = false;
            header('Location: /admin');
            exit;
        }
    }
    
    public function clearLogs()
    {
        try {
            $logFiles = [
                'logs/errors.log',
                'logs/access.log',
                'logs/admin.log',
                'logs/system.log'
            ];
            
            $cleared = 0;
            foreach ($logFiles as $file) {
                if (file_exists($file)) {
                    file_put_contents($file, '');
                    $cleared++;
                }
            }
            
            $response = [
                'success' => true,
                'message' => "Logs limpos com sucesso! {$cleared} arquivos limpos.",
                'cleared' => $cleared
            ];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['admin_message'] = $response['message'];
            $_SESSION['admin_success'] = true;
            header('Location: /admin/logs');
            exit;
            
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erro ao limpar logs: ' . $e->getMessage()
            ];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['admin_message'] = $response['message'];
            $_SESSION['admin_success'] = false;
            header('Location: /admin/logs');
            exit;
        }
    }
    
    public function downloadLogs()
    {
        try {
            $logFiles = [
                'errors' => 'logs/errors.log',
                'access' => 'logs/access.log',
                'admin' => 'logs/admin.log',
                'system' => 'logs/system.log'
            ];
            
            $zipFileName = 'engenhario_logs_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = 'temp/' . $zipFileName;
            
            // Criar diretório temp se não existir
            if (!is_dir('temp')) {
                mkdir('temp', 0755, true);
            }
            
            // Criar arquivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
                throw new Exception('Não foi possível criar o arquivo ZIP');
            }
            
            foreach ($logFiles as $name => $file) {
                if (file_exists($file)) {
                    $zip->addFile($file, $name . '.log');
                }
            }
            
            $zip->close();
            
            // Download do arquivo
            if (file_exists($zipPath)) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
                header('Content-Length: ' . filesize($zipPath));
                readfile($zipPath);
                unlink($zipPath); // Remover arquivo temporário
                exit;
            } else {
                throw new Exception('Arquivo ZIP não foi criado');
            }
            
        } catch (Exception $e) {
            $_SESSION['admin_message'] = 'Erro ao baixar logs: ' . $e->getMessage();
            $_SESSION['admin_success'] = false;
            header('Location: /admin/logs');
            exit;
        }
    }

    public function reports()
    {
        $user = Auth::user();
        $projectModel = new Project();
        $documentModel = new Document();
        $userModel = new User();
        
        // Obter todos os dados para estatísticas
        $projects = $projectModel->all();
        $documents = $documentModel->all();
        $users = $userModel->all();
        
        // Estatísticas gerais
        $stats = [
            'totalProjects' => count($projects),
            'totalDocuments' => count($documents),
            'totalUsers' => count($users),
            'pendingApprovals' => count($userModel->getPendingUsers())
        ];
        
        // Estatísticas por status de projeto
        $projectsByStatus = [];
        foreach ($projects as $project) {
            $status = $project['status'] ?? 'indefinido';
            $projectsByStatus[$status] = ($projectsByStatus[$status] ?? 0) + 1;
        }
        
        // Estatísticas por tipo de usuário
        $usersByRole = [];
        foreach ($users as $user) {
            $role = $user['role'] ?? 'indefinido';
            $usersByRole[$role] = ($usersByRole[$role] ?? 0) + 1;
        }
        
        // Estatísticas por mês (últimos 6 meses)
        $monthlyStats = $this->getMonthlyStats($projects, $documents, $users);
        
        // Top projetos por número de documentos
        $projectsWithDocCount = [];
        foreach ($projects as $project) {
            $docCount = 0;
            foreach ($documents as $doc) {
                if (($doc['project_id'] ?? '') === $project['id']) {
                    $docCount++;
                }
            }
            $projectsWithDocCount[] = [
                'project' => $project,
                'document_count' => $docCount
            ];
        }
        
        // Ordenar por número de documentos (decrescente)
        usort($projectsWithDocCount, function($a, $b) {
            return $b['document_count'] - $a['document_count'];
        });
        
        $data = [
            'user' => $user,
            'stats' => $stats,
            'projectsByStatus' => $projectsByStatus,
            'usersByRole' => $usersByRole,
            'monthlyStats' => $monthlyStats,
            'topProjects' => array_slice($projectsWithDocCount, 0, 5),
            'recentDocuments' => $this->getRecentDocuments($documents, 5)
        ];
        
        $this->view('admin.reports', $data);
    }

    private function getStats($projectModel, $documentModel, $userModel)
    {
        return [
            'totalProjects' => count($projectModel->all()),
            'totalDocuments' => count($documentModel->all()),
            'totalUsers' => count($userModel->all()),
            'pendingApprovals' => count($userModel->getPendingUsers())
        ];
    }
    
    private function calculateAccountAge($createdAt)
    {
        $created = new \DateTime($createdAt);
        $now = new \DateTime();
        $interval = $now->diff($created);
        
        if ($interval->y > 0) {
            return $interval->y . ' ano(s)';
        } elseif ($interval->m > 0) {
            return $interval->m . ' mês(es)';
        } elseif ($interval->d > 0) {
            return $interval->d . ' dia(s)';
        } else {
            return 'Hoje';
        }
    }
    
    private function getMonthlyStats($projects, $documents, $users)
    {
        $months = [];
        $now = new \DateTime();
        
        // Últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $month = clone $now;
            $month->modify("-$i months");
            $monthKey = $month->format('Y-m');
            $monthName = $month->format('M/Y');
            
            $months[$monthKey] = [
                'month' => $monthName,
                'projects' => 0,
                'documents' => 0,
                'users' => 0
            ];
        }
        
        // Contar projetos por mês
        foreach ($projects as $project) {
            if (isset($project['created_at'])) {
                $projectMonth = date('Y-m', strtotime($project['created_at']));
                if (isset($months[$projectMonth])) {
                    $months[$projectMonth]['projects']++;
                }
            }
        }
        
        // Contar documentos por mês
        foreach ($documents as $document) {
            if (isset($document['created_at'])) {
                $docMonth = date('Y-m', strtotime($document['created_at']));
                if (isset($months[$docMonth])) {
                    $months[$docMonth]['documents']++;
                }
            }
        }
        
        // Contar usuários por mês
        foreach ($users as $user) {
            if (isset($user['created_at'])) {
                $userMonth = date('Y-m', strtotime($user['created_at']));
                if (isset($months[$userMonth])) {
                    $months[$userMonth]['users']++;
                }
            }
        }
        
        return array_values($months);
    }
    
    private function getRecentDocuments($documents, $limit = 5)
    {
        // Ordenar documentos por data de criação (mais recente primeiro)
        usort($documents, function($a, $b) {
            $dateA = strtotime($a['created_at'] ?? '1970-01-01');
            $dateB = strtotime($b['created_at'] ?? '1970-01-01');
            return $dateB - $dateA;
        });
        
        return array_slice($documents, 0, $limit);
    }
}
