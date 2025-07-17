<?php

namespace App\Models;

class TicketJson
{
    private $ticketsPath;
    
    public function __construct()
    {
        $this->ticketsPath = __DIR__ . '/../../data/tickets/';
        if (!is_dir($this->ticketsPath)) {
            mkdir($this->ticketsPath, 0755, true);
        }
    }
    
    public function create($data)
    {
        $ticketId = 'TK' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $ticket = [
            'id' => $ticketId,
            'user_id' => $data['user_id'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'project_id' => $data['project_id'] ?? null,
            'project_name' => $data['project_name'] ?? 'Projeto não especificado',
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'media',
            'status' => 'aberto',
            'assigned_to' => null,
            'assigned_name' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'responses' => []
        ];
        
        $filename = $this->ticketsPath . $ticketId . '.json';
        file_put_contents($filename, json_encode($ticket, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return $ticketId;
    }
    
    public function getById($ticketId)
    {
        $filename = $this->ticketsPath . $ticketId . '.json';
        if (!file_exists($filename)) {
            return null;
        }
        
        $content = file_get_contents($filename);
        return json_decode($content, true);
    }
    
    public function getByUser($userId)
    {
        $tickets = [];
        $files = glob($this->ticketsPath . '*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $ticket = json_decode($content, true);
            
            if ($ticket && $ticket['user_id'] == $userId) {
                $tickets[] = $ticket;
            }
        }
        
        // Ordenar por data de criação (mais recente primeiro)
        usort($tickets, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $tickets;
    }
    
    public function getAll($filters = [])
    {
        $tickets = [];
        $files = glob($this->ticketsPath . '*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $ticket = json_decode($content, true);
            
            if ($ticket) {
                // Aplicar filtros
                if (!empty($filters['status']) && $ticket['status'] !== $filters['status']) {
                    continue;
                }
                
                if (!empty($filters['priority']) && $ticket['priority'] !== $filters['priority']) {
                    continue;
                }
                
                if (!empty($filters['user']) && stripos($ticket['user_name'], $filters['user']) === false) {
                    continue;
                }
                
                $tickets[] = $ticket;
            }
        }
        
        // Ordenar por data de criação (mais recente primeiro)
        usort($tickets, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $tickets;
    }
    
    public function getByAssignedTo($userId)
    {
        $tickets = [];
        $files = glob($this->ticketsPath . '*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $ticket = json_decode($content, true);
            
            if ($ticket && $ticket['assigned_to'] == $userId) {
                $tickets[] = $ticket;
            }
        }
        
        // Ordenar por data de criação (mais recente primeiro)
        usort($tickets, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $tickets;
    }
    
    public function updateStatus($ticketId, $status, $assignedTo = null, $assignedName = null)
    {
        $ticket = $this->getById($ticketId);
        if (!$ticket) {
            return false;
        }
        
        $ticket['status'] = $status;
        $ticket['updated_at'] = date('Y-m-d H:i:s');
        
        if ($assignedTo !== null) {
            $ticket['assigned_to'] = $assignedTo;
            $ticket['assigned_name'] = $assignedName;
        }
        
        $filename = $this->ticketsPath . $ticketId . '.json';
        return file_put_contents($filename, json_encode($ticket, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
    
    public function addResponse($ticketId, $userId, $userName, $userRole, $message)
    {
        $ticket = $this->getById($ticketId);
        if (!$ticket) {
            return false;
        }
        
        $response = [
            'id' => uniqid(),
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $ticket['responses'][] = $response;
        $ticket['updated_at'] = date('Y-m-d H:i:s');
        
        $filename = $this->ticketsPath . $ticketId . '.json';
        return file_put_contents($filename, json_encode($ticket, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
    
    public function getOpenTicketsCount()
    {
        $count = 0;
        $files = glob($this->ticketsPath . '*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $ticket = json_decode($content, true);
            
            if ($ticket && in_array($ticket['status'], ['aberto', 'em_andamento'])) {
                $count++;
            }
        }
        
        return $count;
    }
    
    public function getStatusCounts()
    {
        $counts = [
            'aberto' => 0,
            'em_andamento' => 0,
            'resolvido' => 0,
            'fechado' => 0
        ];
        
        $files = glob($this->ticketsPath . '*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $ticket = json_decode($content, true);
            
            if ($ticket && isset($counts[$ticket['status']])) {
                $counts[$ticket['status']]++;
            }
        }
        
        return $counts;
    }
}
