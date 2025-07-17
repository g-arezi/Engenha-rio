<?php
$pageTitle = 'Gerenciar Tickets';
$activeMenu = 'tickets';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .response-card {
            transition: all 0.3s ease;
        }
        .response-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .response-card.new-response {
            border-left: 4px solid #28a745;
            background-color: #f8f9fa;
        }
        #loadingResponses {
            border: 1px dashed #dee2e6;
            border-radius: 0.375rem;
            margin: 0.5rem 0;
        }
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }
        .pulse-effect {
            animation: pulse 0.5s ease-in-out;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-ticket-alt me-2"></i>Sistema de Tickets
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="/profile">
                    <i class="fas fa-user me-1"></i>Perfil
                </a>
                <a class="nav-link" href="/logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-ticket-alt me-2"></i>Gerenciar Tickets</h2>
            <span class="badge bg-primary" id="totalTickets">0 tickets</span>
        </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtros</h5>
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Todos os Status</option>
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="resolvido">Resolvido</option>
                        <option value="fechado">Fechado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="priorityFilter">
                        <option value="">Todas as Prioridades</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchUser" placeholder="Buscar por usuário">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" onclick="loadAllTickets()">
                        <i class="fas fa-search me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>        <!-- Lista de Tickets -->
        <div class="card">
            <div class="card-body">
                <div id="ticketsContainer">
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="mt-2 text-muted">Carregando tickets...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal para Ver/Responder Ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Ticket #<span id="ticketId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Detalhes do Ticket -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <strong id="ticketSubject"></strong>
                            <br>
                            <small class="text-muted">
                                Por: <span id="ticketUser"></span> | 
                                <span id="ticketDate"></span>
                            </small>
                        </div>
                        <div>
                            <span class="badge" id="ticketStatus"></span>
                            <span class="badge ms-1" id="ticketPriority"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Descrição do problema:</strong>
                        </div>
                        <div id="ticketMessage" class="text-muted" style="white-space: pre-wrap;"></div>
                    </div>
                </div>

                <!-- Respostas -->
                <div id="ticketResponses"></div>

                <!-- Formulário de Resposta -->
                <div class="card">
                    <div class="card-header">
                        <strong>Nova Resposta</strong>
                    </div>
                    <div class="card-body">
                        <form id="responseForm">
                            <div class="mb-3">
                                <textarea class="form-control" id="responseMessage" rows="4" placeholder="Digite sua resposta..."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" id="newStatus">
                                        <option value="">Manter status atual</option>
                                        <option value="em_andamento">Em Andamento</option>
                                        <option value="resolvido">Resolvido</option>
                                        <option value="fechado">Fechado</option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="refreshTicketResponses()" title="Atualizar respostas">
                                        <i class="fas fa-sync-alt me-1"></i>Atualizar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-reply me-1"></i>Responder
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTicketId = null;

// Carregar todos os tickets
function loadAllTickets() {
    const statusFilter = document.getElementById('statusFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const searchUser = document.getElementById('searchUser').value;
    
    fetch('/api/tickets/all?' + new URLSearchParams({
        status: statusFilter,
        priority: priorityFilter,
        user: searchUser
    }), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTickets(data.tickets);
            document.getElementById('totalTickets').textContent = data.tickets.length + ' tickets';
        } else {
            showAlert('Erro ao carregar tickets: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao carregar tickets', 'error');
    });
}

// Exibir tickets na tabela
function displayTickets(tickets) {
    const container = document.getElementById('ticketsContainer');
    
    if (tickets.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted"></i>
                <p class="mt-3 text-muted">Nenhum ticket encontrado</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assunto</th>
                        <th>Projeto</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Prioridade</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    tickets.forEach(ticket => {
        const statusBadge = getStatusBadge(ticket.status);
        const priorityBadge = getPriorityBadge(ticket.priority);
        const date = new Date(ticket.created_at).toLocaleDateString('pt-BR');
        
        html += `
            <tr>
                <td>#${ticket.id}</td>
                <td>${ticket.subject}</td>
                <td><span class="text-info"><i class="fas fa-folder"></i> ${ticket.project_name || 'N/A'}</span></td>
                <td>${ticket.user_name}</td>
                <td>${statusBadge}</td>
                <td>${priorityBadge}</td>
                <td>${date}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="viewTicket('${ticket.id}')">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

// Ver detalhes do ticket
function viewTicket(ticketId) {
    currentTicketId = ticketId;
    
    return fetch(`/tickets/${ticketId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ticket = data.ticket;
            
            document.getElementById('ticketId').textContent = ticket.id;
            document.getElementById('ticketSubject').textContent = ticket.subject;
            document.getElementById('ticketUser').textContent = ticket.user_name;
            document.getElementById('ticketDate').textContent = new Date(ticket.created_at).toLocaleString('pt-BR');
            document.getElementById('ticketMessage').textContent = ticket.description;
            document.getElementById('ticketStatus').innerHTML = getStatusBadge(ticket.status);
            document.getElementById('ticketPriority').innerHTML = getPriorityBadge(ticket.priority);
            
            // Carregar respostas
            displayResponses(ticket.responses || []);
            
            // Mostrar modal apenas se não estiver aberto
            const modal = document.getElementById('ticketModal');
            if (!modal.classList.contains('show')) {
                new bootstrap.Modal(modal).show();
            }
            
            return ticket; // Retornar o ticket para encadeamento
        } else {
            showAlert('Erro ao carregar ticket: ' + data.message, 'error');
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao carregar ticket', 'error');
        throw error;
    });
}

// Exibir respostas
function displayResponses(responses) {
    const container = document.getElementById('ticketResponses');
    
    // Remover loading se existir
    const loading = document.getElementById('loadingResponses');
    if (loading) {
        loading.remove();
    }
    
    if (responses.length === 0) {
        container.innerHTML = '<div class="text-muted text-center py-3">Nenhuma resposta ainda</div>';
        return;
    }
    
    // Ordenar respostas por data (mais antigas primeiro)
    const sortedResponses = responses.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    
    let html = '<h6 class="mb-3">Respostas:</h6>';
    
    sortedResponses.forEach((response, index) => {
        const date = new Date(response.created_at).toLocaleString('pt-BR');
        const roleClass = response.user_role === 'admin' ? 'text-danger' : 
                         response.user_role === 'analista' ? 'text-warning' : 'text-primary';
        const roleText = response.user_role === 'admin' ? '(Administrador)' : 
                        response.user_role === 'analista' ? '(Analista)' : '(Cliente)';
        
        // Adicionar indicador de nova resposta para a última
        const isLast = index === sortedResponses.length - 1;
        const newIndicator = isLast && sortedResponses.length > 1 ? 
            '<span class="badge bg-success ms-2">Nova</span>' : '';
        
        html += `
            <div class="card mb-2 response-card" data-response-index="${index}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="${roleClass}">
                                ${response.user_name} ${roleText}
                            </strong>
                            ${newIndicator}
                        </div>
                        <small class="text-muted">${date}</small>
                    </div>
                    <div class="text-muted" style="white-space: pre-wrap;">${response.message}</div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Enviar resposta
document.getElementById('responseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const message = document.getElementById('responseMessage').value.trim();
    const newStatus = document.getElementById('newStatus').value;
    
    console.log('Enviando resposta:', { message, newStatus, ticketId: currentTicketId });
    
    if (!message) {
        showAlert('Por favor, digite uma resposta', 'warning');
        return;
    }
    
    const formData = {
        message: message
    };
    
    // Se houver mudança de status, incluir no payload
    if (newStatus) {
        formData.status = newStatus;
    }
    
    console.log('Dados enviados:', formData);
    
    fetch(`/api/tickets/${currentTicketId}/respond`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showAlert('Resposta enviada com sucesso!', 'success');
            document.getElementById('responseMessage').value = '';
            document.getElementById('newStatus').value = '';
            
            // Mostrar loading enquanto recarrega
            const responsesContainer = document.getElementById('ticketResponses');
            const loadingHtml = `
                <div class="d-flex justify-content-center py-3" id="loadingResponses">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <span class="text-muted">Carregando nova resposta...</span>
                </div>
            `;
            responsesContainer.innerHTML += loadingHtml;
            
            // Recarregar ticket imediatamente
            viewTicket(currentTicketId).then(() => {
                // Scroll para a última resposta após carregar
                setTimeout(() => {
                    const responsesContainer = document.getElementById('ticketResponses');
                    const lastResponse = responsesContainer.lastElementChild;
                    if (lastResponse && lastResponse.classList.contains('card')) {
                        lastResponse.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                        // Destacar brevemente a nova resposta
                        lastResponse.style.backgroundColor = '#e3f2fd';
                        setTimeout(() => {
                            lastResponse.style.backgroundColor = '';
                        }, 2000);
                    }
                }, 100);
            });
            
            // Atualizar lista de tickets
            loadAllTickets();
        } else {
            showAlert('Erro ao enviar resposta: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao enviar resposta', 'error');
    });
});

// Funções auxiliares
function getStatusBadge(status) {
    const badges = {
        'aberto': '<span class="badge bg-success">Aberto</span>',
        'em_andamento': '<span class="badge bg-warning">Em Andamento</span>',
        'resolvido': '<span class="badge bg-info">Resolvido</span>',
        'fechado': '<span class="badge bg-secondary">Fechado</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
}

function getPriorityBadge(priority) {
    const badges = {
        'baixa': '<span class="badge bg-light text-dark">Baixa</span>',
        'media': '<span class="badge bg-primary">Média</span>',
        'alta': '<span class="badge bg-warning">Alta</span>',
        'urgente': '<span class="badge bg-danger">Urgente</span>'
    };
    return badges[priority] || '<span class="badge bg-secondary">' + priority + '</span>';
}

function showAlert(message, type) {
    const alertTypes = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const alertHtml = `
        <div class="alert ${alertTypes[type]} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Carregar filtros do localStorage
document.addEventListener('DOMContentLoaded', function() {
    const savedStatus = localStorage.getItem('ticket_status_filter');
    const savedPriority = localStorage.getItem('ticket_priority_filter');
    
    if (savedStatus) document.getElementById('statusFilter').value = savedStatus;
    if (savedPriority) document.getElementById('priorityFilter').value = savedPriority;
    
    // Carregar tickets iniciais
    loadAllTickets();
    
    // Salvar filtros no localStorage quando mudarem
    document.getElementById('statusFilter').addEventListener('change', function() {
        localStorage.setItem('ticket_status_filter', this.value);
    });
    
    document.getElementById('priorityFilter').addEventListener('change', function() {
        localStorage.setItem('ticket_priority_filter', this.value);
    });
});

// Função para atualizar respostas manualmente
function refreshTicketResponses() {
    if (!currentTicketId) return;
    
    const refreshBtn = document.querySelector('button[onclick="refreshTicketResponses()"]');
    const icon = refreshBtn.querySelector('i');
    
    // Mostrar loading no botão
    icon.className = 'fas fa-spinner fa-spin me-1';
    refreshBtn.disabled = true;
    
    viewTicket(currentTicketId)
        .then(() => {
            showAlert('Respostas atualizadas!', 'success', 2000);
        })
        .catch(() => {
            showAlert('Erro ao atualizar respostas', 'error');
        })
        .finally(() => {
            // Restaurar botão
            icon.className = 'fas fa-sync-alt me-1';
            refreshBtn.disabled = false;
        });
}

// Função para mostrar alertas com duração personalizada
function showAlert(message, type = 'info', duration = 4000) {
    const alertContainer = document.getElementById('alertContainer') || createAlertContainer();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    // Auto-remover após duração especificada
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, duration);
}

// Criar container de alertas se não existir
function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alertContainer';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    container.style.maxWidth = '400px';
    document.body.appendChild(container);
    return container;
}

// Polling automático para atualizar tickets mais frequentemente quando modal estiver aberto
let ticketPollingInterval;

function startTicketPolling() {
    // Parar polling anterior se existir
    if (ticketPollingInterval) {
        clearInterval(ticketPollingInterval);
    }
    
    // Iniciar polling a cada 15 segundos quando modal estiver aberto
    ticketPollingInterval = setInterval(() => {
        const modal = document.getElementById('ticketModal');
        if (modal && modal.classList.contains('show') && currentTicketId) {
            viewTicket(currentTicketId).catch(() => {
                // Silenciosamente ignorar erros no polling automático
            });
        }
    }, 15000);
}

// Parar polling quando modal for fechado
document.getElementById('ticketModal').addEventListener('hidden.bs.modal', () => {
    if (ticketPollingInterval) {
        clearInterval(ticketPollingInterval);
        ticketPollingInterval = null;
    }
});

// Iniciar polling quando modal for aberto
document.getElementById('ticketModal').addEventListener('shown.bs.modal', () => {
    startTicketPolling();
});

// Atualizar automaticamente a cada 30 segundos
setInterval(loadAllTickets, 30000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
