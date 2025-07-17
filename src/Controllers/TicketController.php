<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\TicketJson;
use App\Models\User;

class TicketController extends Controller
{
    private $ticketModel;
    private $userModel;
    
    public function __construct()
    {
        $this->ticketModel = new TicketJson();
        $this->userModel = new User();
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $user = Auth::user();
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['subject']) || empty($data['description']) || empty($data['project_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Projeto, assunto e descrição são obrigatórios']);
            return;
        }
        
        // Buscar nome do projeto
        $projectName = 'Projeto desconhecido';
        $projectsFile = __DIR__ . '/../../data/projects.json';
        
        if (file_exists($projectsFile)) {
            $projects = json_decode(file_get_contents($projectsFile), true);
            
            // O arquivo projects.json tem estrutura de objeto com chaves, então iteramos sobre os valores
            foreach ($projects as $projectKey => $project) {
                if (isset($project['id']) && $project['id'] == $data['project_id']) {
                    $projectName = $project['name'];
                    break;
                }
            }
        }
        
        $ticketData = [
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'project_id' => $data['project_id'],
            'project_name' => $projectName,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'media'
        ];
        
        $ticketId = $this->ticketModel->create($ticketData);
        
        if ($ticketId) {
            // Enviar notificação por email para admins e analistas
            $this->notifyAdminsAndAnalysts($ticketId, $ticketData);
            
            $this->jsonResponse(['success' => true, 'message' => 'Ticket criado com sucesso!', 'ticketId' => $ticketId]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erro ao criar ticket']);
        }
    }
    
    public function show($ticketId)
    {
        $user = Auth::user();
        if (!$user) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                $this->jsonResponse(['success' => false, 'message' => 'Não autorizado'], 401);
                return;
            }
            $this->redirect('/login');
            return;
        }
        
        $ticket = $this->ticketModel->getById($ticketId);
        if (!$ticket) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                $this->jsonResponse(['success' => false, 'message' => 'Ticket não encontrado'], 404);
                return;
            }
            Session::flash('error', 'Ticket não encontrado');
            $this->redirect('/dashboard');
            return;
        }
        
        // Verificar permissões
        if (!Auth::isAdmin() && !Auth::isAnalyst() && $ticket['user_id'] != $user['id']) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                $this->jsonResponse(['success' => false, 'message' => 'Você não tem permissão para ver este ticket'], 403);
                return;
            }
            Session::flash('error', 'Você não tem permissão para ver este ticket');
            $this->redirect('/dashboard');
            return;
        }
        
        // Se for requisição AJAX, retornar JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            $this->jsonResponse([
                'success' => true,
                'ticket' => $ticket
            ]);
            return;
        }
        
        // Se não for AJAX, renderizar view
        $this->view('tickets.show', ['ticket' => $ticket]);
    }
    
    public function respond($ticketId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $user = Auth::user();
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        $ticket = $this->ticketModel->getById($ticketId);
        if (!$ticket) {
            $this->jsonResponse(['success' => false, 'message' => 'Ticket não encontrado']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['message'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Mensagem é obrigatória']);
            return;
        }
        
        $success = $this->ticketModel->addResponse(
            $ticketId,
            $user['id'],
            $user['name'],
            $user['role'],
            $data['message']
        );
        
        if ($success) {
            // Atualizar status se fornecido
            if (!empty($data['status'])) {
                $this->ticketModel->updateStatus($ticketId, $data['status'], $user['id'], $user['name']);
            } else {
                // Atualizar status se for primeira resposta de admin/analista
                if ((Auth::isAdmin() || Auth::isAnalyst()) && $ticket['status'] === 'aberto') {
                    $this->ticketModel->updateStatus($ticketId, 'em_andamento', $user['id'], $user['name']);
                }
            }
            
            // Notificar o cliente por email
            $this->notifyTicketOwner($ticket, $data['message'], $user);
            
            $this->jsonResponse(['success' => true, 'message' => 'Resposta enviada com sucesso!']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erro ao enviar resposta']);
        }
    }
    
    public function updateStatus($ticketId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $user = Auth::user();
        if (!Auth::isAdmin() && !Auth::isAnalyst()) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $success = $this->ticketModel->updateStatus(
            $ticketId,
            $data['status'],
            $user['id'],
            $user['name']
        );
        
        if ($success) {
            $this->jsonResponse(['success' => true, 'message' => 'Status atualizado com sucesso!']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erro ao atualizar status']);
        }
    }
    
    public function getMyTickets()
    {
        $user = Auth::user();
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        $tickets = $this->ticketModel->getByUser($user['id']);
        $this->jsonResponse(['success' => true, 'tickets' => $tickets]);
    }
    
    public function getAllTickets()
    {
        if (!Auth::check() || (!Auth::isAdmin() && !Auth::isAnalyst())) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }

        try {
            $filters = [
                'status' => $_GET['status'] ?? '',
                'priority' => $_GET['priority'] ?? '',
                'user' => $_GET['user'] ?? ''
            ];
            
            $tickets = $this->ticketModel->getAll($filters);
            
            $this->jsonResponse([
                'success' => true,
                'tickets' => $tickets
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
        }
    }

    public function index()
    {
        if (!Auth::check() || (!Auth::isAdmin() && !Auth::isAnalyst())) {
            header('Location: /dashboard');
            exit;
        }

        include __DIR__ . '/../../views/tickets/index.php';
    }
    
    protected function notifyAdminsAndAnalysts($ticketId, $ticketData)
    {
        // Buscar todos os admins e analistas
        $users = $this->userModel->all();
        $recipients = array_filter($users, function($user) {
            return in_array($user['role'], ['admin', 'analista']);
        });
        
        $subject = "Novo Ticket: " . $ticketData['subject'];
        $message = "Um novo ticket foi criado:\n\n";
        $message .= "ID: " . $ticketId . "\n";
        $message .= "Cliente: " . $ticketData['user_name'] . "\n";
        $message .= "Assunto: " . $ticketData['subject'] . "\n";
        $message .= "Descrição: " . $ticketData['description'] . "\n";
        $message .= "Prioridade: " . ucfirst($ticketData['priority']) . "\n\n";
        $message .= "Acesse o sistema para responder: " . $_SERVER['HTTP_HOST'] . "/tickets/" . $ticketId;
        
        foreach ($recipients as $recipient) {
            // Aqui você pode implementar o envio de email
            // mail($recipient['email'], $subject, $message);
        }
    }
    
    protected function notifyTicketOwner($ticket, $responseMessage, $respondingUser)
    {
        $subject = "Resposta no Ticket: " . $ticket['subject'];
        $message = "Seu ticket recebeu uma nova resposta:\n\n";
        $message .= "Ticket ID: " . $ticket['id'] . "\n";
        $message .= "Respondido por: " . $respondingUser['name'] . " (" . ucfirst($respondingUser['role']) . ")\n";
        $message .= "Resposta: " . $responseMessage . "\n\n";
        $message .= "Acesse o sistema para ver: " . $_SERVER['HTTP_HOST'] . "/tickets/" . $ticket['id'];
        
        // Aqui você pode implementar o envio de email
        // mail($ticket['user_email'], $subject, $message);
    }
}
