<?php

namespace App\Services;

use App\Core\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class NotificationService
{
    private $mailer;
    
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }
    
    private function setupMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = Config::mail('host');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = Config::mail('username');
        $this->mailer->Password = Config::mail('password');
        $this->mailer->SMTPSecure = Config::mail('encryption');
        $this->mailer->Port = Config::mail('port');
        $this->mailer->CharSet = 'UTF-8';
        
        $this->mailer->setFrom(
            Config::mail('from.address'),
            Config::mail('from.name')
        );
    }
    
    public function sendProjectCreated($project, $user)
    {
        try {
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Novo projeto criado - ' . $project['name'];
            
            $body = $this->getProjectCreatedTemplate($project, $user);
            $this->mailer->Body = $body;
            
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    public function sendProjectStatusUpdate($project, $user, $oldStatus, $newStatus)
    {
        try {
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Status do projeto atualizado - ' . $project['name'];
            
            $body = $this->getProjectStatusUpdateTemplate($project, $user, $oldStatus, $newStatus);
            $this->mailer->Body = $body;
            
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    public function sendProjectDeadlineReminder($project, $user)
    {
        try {
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Lembrete: Prazo do projeto se aproxima - ' . $project['name'];
            
            $body = $this->getProjectDeadlineReminderTemplate($project, $user);
            $this->mailer->Body = $body;
            
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    private function getProjectCreatedTemplate($project, $user)
    {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
                .content { background: #f8f9fa; padding: 20px; }
                .button { background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Engenha Rio</h1>
                    <h2>Novo Projeto Criado</h2>
                </div>
                <div class='content'>
                    <p>Olá, <strong>{$user['name']}</strong>!</p>
                    <p>Seu projeto foi criado com sucesso em nosso sistema.</p>
                    
                    <h3>Detalhes do Projeto:</h3>
                    <ul>
                        <li><strong>Nome:</strong> {$project['name']}</li>
                        <li><strong>Status:</strong> " . ucfirst($project['status']) . "</li>
                        <li><strong>Prazo:</strong> " . date('d/m/Y', strtotime($project['deadline'])) . "</li>
                        <li><strong>Criado em:</strong> " . date('d/m/Y H:i', strtotime($project['created_at'])) . "</li>
                    </ul>
                    
                    <p>Você pode acompanhar o progresso do seu projeto através do nosso sistema.</p>
                    
                    <a href='" . Config::app('url') . "/projects/{$project['id']}' class='button'>Ver Projeto</a>
                </div>
                <div class='footer'>
                    <p>&copy; 2025 Engenha Rio - Sistema de Gestão de Projetos de Arquitetura</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getProjectStatusUpdateTemplate($project, $user, $oldStatus, $newStatus)
    {
        $statusColors = [
            'aguardando' => '#17a2b8',
            'pendente' => '#ffc107',
            'atrasado' => '#dc3545',
            'aprovado' => '#28a745'
        ];
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
                .content { background: #f8f9fa; padding: 20px; }
                .status-badge { padding: 8px 16px; border-radius: 20px; color: white; font-weight: bold; }
                .button { background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Engenha Rio</h1>
                    <h2>Status do Projeto Atualizado</h2>
                </div>
                <div class='content'>
                    <p>Olá, <strong>{$user['name']}</strong>!</p>
                    <p>O status do seu projeto foi atualizado.</p>
                    
                    <h3>Detalhes da Atualização:</h3>
                    <ul>
                        <li><strong>Projeto:</strong> {$project['name']}</li>
                        <li><strong>Status Anterior:</strong> <span class='status-badge' style='background: {$statusColors[$oldStatus]}'>" . ucfirst($oldStatus) . "</span></li>
                        <li><strong>Novo Status:</strong> <span class='status-badge' style='background: {$statusColors[$newStatus]}'>" . ucfirst($newStatus) . "</span></li>
                        <li><strong>Atualizado em:</strong> " . date('d/m/Y H:i') . "</li>
                    </ul>
                    
                    <a href='" . Config::app('url') . "/projects/{$project['id']}' class='button'>Ver Projeto</a>
                </div>
                <div class='footer'>
                    <p>&copy; 2025 Engenha Rio - Sistema de Gestão de Projetos de Arquitetura</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getProjectDeadlineReminderTemplate($project, $user)
    {
        $daysLeft = ceil((strtotime($project['deadline']) - time()) / (60 * 60 * 24));
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f39c12; color: white; padding: 20px; text-align: center; }
                .content { background: #f8f9fa; padding: 20px; }
                .alert { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .button { background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Engenha Rio</h1>
                    <h2>⚠️ Lembrete de Prazo</h2>
                </div>
                <div class='content'>
                    <p>Olá, <strong>{$user['name']}</strong>!</p>
                    
                    <div class='alert'>
                        <p><strong>Atenção:</strong> O prazo do seu projeto está se aproximando!</p>
                    </div>
                    
                    <h3>Detalhes do Projeto:</h3>
                    <ul>
                        <li><strong>Nome:</strong> {$project['name']}</li>
                        <li><strong>Prazo:</strong> " . date('d/m/Y', strtotime($project['deadline'])) . "</li>
                        <li><strong>Dias restantes:</strong> {$daysLeft} dias</li>
                        <li><strong>Status atual:</strong> " . ucfirst($project['status']) . "</li>
                    </ul>
                    
                    <p>Certifique-se de que todos os documentos necessários foram enviados e que o projeto está em andamento.</p>
                    
                    <a href='" . Config::app('url') . "/projects/{$project['id']}' class='button'>Ver Projeto</a>
                </div>
                <div class='footer'>
                    <p>&copy; 2025 Engenha Rio - Sistema de Gestão de Projetos de Arquitetura</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
