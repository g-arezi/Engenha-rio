<?php

namespace App\Models;

use PDO;

class Ticket
{
    private $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::getInstance();
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO tickets (user_id, subject, description, priority) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['subject'],
            $data['description'],
            $data['priority'] ?? 'media'
        ]);
    }
    
    public function getById($id)
    {
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email, 
                       a.name as assigned_name 
                FROM tickets t 
                LEFT JOIN users u ON t.user_id = u.id 
                LEFT JOIN users a ON t.assigned_to = a.id 
                WHERE t.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByUser($userId)
    {
        $sql = "SELECT t.*, u.name as user_name, a.name as assigned_name 
                FROM tickets t 
                LEFT JOIN users u ON t.user_id = u.id 
                LEFT JOIN users a ON t.assigned_to = a.id 
                WHERE t.user_id = ? 
                ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll()
    {
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email, 
                       a.name as assigned_name 
                FROM tickets t 
                LEFT JOIN users u ON t.user_id = u.id 
                LEFT JOIN users a ON t.assigned_to = a.id 
                ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByAssignedTo($userId)
    {
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email 
                FROM tickets t 
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE t.assigned_to = ? 
                ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($id, $status, $assignedTo = null)
    {
        if ($assignedTo !== null) {
            $sql = "UPDATE tickets SET status = ?, assigned_to = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $assignedTo, $id]);
        } else {
            $sql = "UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
        }
    }
    
    public function getResponses($ticketId)
    {
        $sql = "SELECT tr.*, u.name as user_name, u.role as user_role 
                FROM ticket_responses tr 
                LEFT JOIN users u ON tr.user_id = u.id 
                WHERE tr.ticket_id = ? AND tr.is_internal = 0
                ORDER BY tr.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addResponse($ticketId, $userId, $message, $isInternal = false)
    {
        $sql = "INSERT INTO ticket_responses (ticket_id, user_id, message, is_internal) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$ticketId, $userId, $message, $isInternal]);
    }
    
    public function getOpenTicketsCount()
    {
        $sql = "SELECT COUNT(*) as count FROM tickets WHERE status IN ('aberto', 'em_andamento')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }
}
