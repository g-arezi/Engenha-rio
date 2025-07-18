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
use App\Core\Session;
use App\Models\DocumentTemplate;
use App\Models\Project;
use Exception;

class DocumentTemplateController extends Controller
{
    private $templateModel;
    private $projectModel;
    
    public function __construct()
    {
        $this->templateModel = new DocumentTemplate();
        $this->projectModel = new Project();
    }
    
    /**
     * Redirecionar para a área administrativa
     */
    public function redirectToAdmin()
    {
        $this->redirect('/admin/document-templates');
    }
    
    /**
     * Listar todos os templates (admin e analista)
     */
    public function index()
    {
        try {
            // Debug log
            error_log("DocumentTemplateController::index() - Iniciando método");
            
            // Verificar se há usuário logado
            if (!Auth::check()) {
                Session::flash('error', 'Você precisa estar logado para acessar esta página.');
                $this->redirect('/login');
                return;
            }
            
            // Verificar se pode gerenciar templates
            if (!Auth::canManageTemplates()) {
                error_log("DocumentTemplateController::index() - Usuário não pode gerenciar templates");
                Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem gerenciar templates.');
                $this->redirect('/dashboard');
                return;
            }
            
            error_log("DocumentTemplateController::index() - Carregando templates");
            $templates = $this->templateModel->all();
            
            // Simplificar - não usar estatísticas por enquanto para evitar erros
            foreach ($templates as &$template) {
                $template['usage_count'] = 0;
            }
            
            error_log("DocumentTemplateController::index() - Renderizando view com " . count($templates) . " templates");
            
            // Usar view mais simples
            $this->view('document_templates/index_simple_fixed', [
                'templates' => $templates ?? []
            ]);
            
        } catch (Exception $e) {
            error_log("DocumentTemplateController::index() - Erro: " . $e->getMessage());
            error_log("DocumentTemplateController::index() - Stack trace: " . $e->getTraceAsString());
            
            // Criar uma página de erro simples
            echo "<div class='container mt-5'>";
            echo "<div class='alert alert-danger'>";
            echo "<h4>Erro nos Templates de Documentos</h4>";
            echo "<p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='/admin' class='btn btn-primary'>Voltar para Admin</a></p>";
            echo "</div>";
            echo "</div>";
            exit;
        }
    }
    
    /**
     * Mostrar formulário de criação de template
     */
    public function create()
    {
        // Debug log
        error_log("DocumentTemplateController::create() - Iniciando método");
        
        // Verificar se há usuário logado
        if (!Auth::check()) {
            Session::flash('error', 'Você precisa estar logado para acessar esta página.');
            $this->redirect('/login');
            return;
        }
        
        // Verificar se pode gerenciar templates
        if (!Auth::canManageTemplates()) {
            error_log("DocumentTemplateController::create() - Usuário não pode gerenciar templates");
            Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem gerenciar templates. Apenas administradores e analistas podem gerenciar templates.');
            $this->redirect('/dashboard');
            return;
        }
        
        error_log("DocumentTemplateController::create() - Usuário autorizado, carregando view");
        
        try {
            // Dados básicos que a view espera
            $documentTypes = DocumentTemplate::getDefaultDocumentTypes();
            $projectTypes = [
                'residencial' => 'Residencial',
                'comercial' => 'Comercial/Industrial', 
                'reforma' => 'Reforma e Adequação',
                'regularizacao' => 'Regularização Predial',
                'urbano' => 'Projeto Urbano',
                'infraestrutura' => 'Infraestrutura',
                'outro' => 'Outros'
            ];
            
            error_log("DocumentTemplateController::create() - Dados preparados, renderizando view");
            
            $this->view('document_templates.create', [
                'documentTypes' => $documentTypes,
                'projectTypes' => $projectTypes
            ]);
            
        } catch (Exception $e) {
            error_log("DocumentTemplateController::create() - Erro: " . $e->getMessage());
            Session::flash('error', 'Erro ao carregar página: ' . $e->getMessage());
            $this->redirect('/admin/document-templates');
        }
    }
    
    /**
     * Salvar novo template
     */
    public function store()
    {
        // Log inicial
        error_log("DocumentTemplateController::store() - Iniciando");
        
        // Verificar autenticação
        if (!Auth::check()) {
            error_log("DocumentTemplateController::store() - Usuário não logado");
            Session::flash('error', 'Você precisa estar logado.');
            $this->redirect('/login');
            return;
        }
        
        if (!Auth::canManageTemplates()) {
            error_log("DocumentTemplateController::store() - Usuário não pode gerenciar templates");
            Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem gerenciar templates. Apenas administradores e analistas podem gerenciar templates.');
            $this->redirect('/dashboard');
            return;
        }

        try {
            // Log dos dados recebidos
            error_log("DocumentTemplateController::store() - Dados POST: " . print_r($_POST, true));
            
            // Salvar debug em arquivo
            file_put_contents(__DIR__ . '/../../debug_store.log', 
                "=== " . date('Y-m-d H:i:s') . " ===\n" .
                "POST: " . print_r($_POST, true) . "\n" .
                "================\n\n", FILE_APPEND);
            
            $data = $_POST;
            $data['created_by'] = Auth::id();
            
            // Processar documentos obrigatórios
            $requiredDocs = [];
            error_log("DocumentTemplateController::store() - Required docs input: " . print_r($data['required_documents'] ?? 'não definido', true));
            
            if (isset($data['required_documents']) && is_array($data['required_documents'])) {
                error_log("DocumentTemplateController::store() - Processando " . count($data['required_documents']) . " documentos obrigatórios");
                foreach ($data['required_documents'] as $docId) {
                    error_log("DocumentTemplateController::store() - Processando doc ID: " . $docId);
                    $docInfo = $this->getDocumentTypeInfo($docId);
                    error_log("DocumentTemplateController::store() - Doc info: " . print_r($docInfo, true));
                    if ($docInfo) {
                        $requiredDocs[] = $docInfo;
                    }
                }
            }
            
            // Processar documentos customizados obrigatórios
            if (isset($data['custom_required_documents']) && is_array($data['custom_required_documents'])) {
                foreach ($data['custom_required_documents'] as $customDoc) {
                    if (!empty(trim($customDoc))) {
                        $requiredDocs[] = [
                            'type' => 'custom_required_' . uniqid(),
                            'name' => trim($customDoc),
                            'label' => trim($customDoc),
                            'description' => 'Documento customizado obrigatório: ' . trim($customDoc),
                            'accept' => '.pdf,.jpg,.jpeg,.png,.doc,.docx',
                            'max_size' => '10MB'
                        ];
                    }
                }
            }
            
            error_log("DocumentTemplateController::store() - Required docs final: " . print_r($requiredDocs, true));
            $data['required_documents'] = $requiredDocs;
            
            // Processar documentos opcionais
            $optionalDocs = [];
            if (isset($data['optional_documents']) && is_array($data['optional_documents'])) {
                foreach ($data['optional_documents'] as $docId) {
                    $docInfo = $this->getDocumentTypeInfo($docId);
                    if ($docInfo) {
                        $optionalDocs[] = $docInfo;
                    }
                }
            }
            
            // Processar documentos customizados opcionais
            if (isset($data['custom_optional_documents']) && is_array($data['custom_optional_documents'])) {
                foreach ($data['custom_optional_documents'] as $customDoc) {
                    if (!empty(trim($customDoc))) {
                        $optionalDocs[] = [
                            'type' => 'custom_optional_' . uniqid(),
                            'name' => trim($customDoc),
                            'label' => trim($customDoc),
                            'description' => 'Documento customizado opcional: ' . trim($customDoc),
                            'accept' => '.pdf,.jpg,.jpeg,.png,.doc,.docx',
                            'max_size' => '10MB'
                        ];
                    }
                }
            }
            
            $data['optional_documents'] = $optionalDocs;
            
            // Validar dados obrigatórios
            if (empty($data['name'])) {
                throw new Exception('Nome do template é obrigatório');
            }
            
            if (empty($data['project_type'])) {
                throw new Exception('Tipo de projeto é obrigatório');
            }
            
            if (empty($requiredDocs)) {
                throw new Exception('Pelo menos um documento obrigatório deve ser selecionado');
            }
            
            error_log("DocumentTemplateController::store() - Criando template");
            $templateId = $this->templateModel->createTemplate($data);
            
            if ($templateId) {
                error_log("DocumentTemplateController::store() - Template criado com ID: " . $templateId);
                Session::flash('success', 'Template criado com sucesso!');
                $this->redirect('/admin/document-templates');
            } else {
                error_log("DocumentTemplateController::store() - Erro ao criar template");
                Session::flash('error', 'Erro ao criar template');
                $this->redirect('/admin/document-templates/create');
            }
            
        } catch (Exception $e) {
            error_log("DocumentTemplateController::store() - Exception: " . $e->getMessage());
            error_log("DocumentTemplateController::store() - Stack trace: " . $e->getTraceAsString());
            Session::flash('error', 'Erro: ' . $e->getMessage());
            $this->redirect('/admin/document-templates/create');
        }
    }
    
    /**
     * Obter informações de um tipo de documento
     */
    private function getDocumentTypeInfo($docId)
    {
        $documentTypes = [
            'rg' => ['type' => 'rg', 'name' => 'RG/CNH', 'description' => 'Documento de identidade com foto'],
            'cpf' => ['type' => 'cpf', 'name' => 'CPF', 'description' => 'Cadastro de Pessoa Física'],
            'cnpj' => ['type' => 'cnpj', 'name' => 'CNPJ', 'description' => 'Cadastro Nacional de Pessoa Jurídica'],
            'escritura' => ['type' => 'escritura', 'name' => 'Escritura do Imóvel', 'description' => 'Documento de propriedade'],
            'comprovante_endereco' => ['type' => 'comprovante_endereco', 'name' => 'Comprovante de Endereço', 'description' => 'Conta de luz, água ou gás'],
            'levantamento_topografico' => ['type' => 'levantamento_topografico', 'name' => 'Levantamento Topográfico', 'description' => 'Planta topográfica do terreno'],
            'alvara_construcao' => ['type' => 'alvara_construcao', 'name' => 'Alvará de Construção', 'description' => 'Licença para construir'],
            'projeto_arquitetonico' => ['type' => 'projeto_arquitetonico', 'name' => 'Projeto Arquitetônico', 'description' => 'Plantas baixas e cortes'],
            'memorial_descritivo' => ['type' => 'memorial_descritivo', 'name' => 'Memorial Descritivo', 'description' => 'Descrição técnica da obra'],
            'art_rrt' => ['type' => 'art_rrt', 'name' => 'ART/RRT', 'description' => 'Anotação de Responsabilidade Técnica'],
            'analise_solo' => ['type' => 'analise_solo', 'name' => 'Análise do Solo', 'description' => 'Sondagem do terreno'],
            'projeto_estrutural' => ['type' => 'projeto_estrutural', 'name' => 'Projeto Estrutural', 'description' => 'Cálculo e detalhamento estrutural'],
            'projeto_eletrico' => ['type' => 'projeto_eletrico', 'name' => 'Projeto Elétrico', 'description' => 'Instalações elétricas'],
            'projeto_hidraulico' => ['type' => 'projeto_hidraulico', 'name' => 'Projeto Hidráulico', 'description' => 'Instalações hidráulicas'],
            'licenca_ambiental' => ['type' => 'licenca_ambiental', 'name' => 'Licença Ambiental', 'description' => 'Autorização ambiental'],
            'contrato_social' => ['type' => 'contrato_social', 'name' => 'Contrato Social', 'description' => 'Documento constitutivo da empresa'],
            'alvara_funcionamento' => ['type' => 'alvara_funcionamento', 'name' => 'Alvará de Funcionamento', 'description' => 'Licença para operar'],
            'plantas_atuais' => ['type' => 'plantas_atuais', 'name' => 'Plantas Atuais', 'description' => 'Plantas do estado atual da construção'],
            'laudo_estrutural' => ['type' => 'laudo_estrutural', 'name' => 'Laudo Estrutural', 'description' => 'Avaliação da estrutura existente'],
            'as_built' => ['type' => 'as_built', 'name' => 'As Built', 'description' => 'Levantamento da construção executada']
        ];
        
        if (isset($documentTypes[$docId])) {
            $doc = $documentTypes[$docId];
            $doc['accept'] = '.pdf,.jpg,.jpeg,.png';
            $doc['max_size'] = '10MB';
            return $doc;
        }
        
        return null;
    }
    
    /**
     * Mostrar detalhes do template
     */
    public function show($id)
    {
        try {
            error_log("DocumentTemplateController::show() - Iniciando com ID: " . $id);
            
            if (!Auth::check()) {
                Session::flash('error', 'Você precisa estar logado para acessar esta página.');
                $this->redirect('/login');
                return;
            }
            
            if (!Auth::canManageTemplates()) {
                error_log("DocumentTemplateController::show() - Usuário não pode gerenciar templates");
                Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem gerenciar templates.');
                $this->redirect('/dashboard');
                return;
            }
            
            error_log("DocumentTemplateController::show() - Buscando template com ID: " . $id);
            $template = $this->templateModel->find($id);
            
            if (!$template) {
                error_log("DocumentTemplateController::show() - Template não encontrado: " . $id);
                Session::flash('error', 'Template não encontrado');
                $this->redirect('/admin/document-templates');
                return;
            }
            
            error_log("DocumentTemplateController::show() - Template encontrado: " . $template['name']);
            
            // Renderizar view
            $this->view('document_templates/show', [
                'template' => $template
            ]);
            
        } catch (Exception $e) {
            error_log("DocumentTemplateController::show() - Erro: " . $e->getMessage());
            error_log("DocumentTemplateController::show() - Stack trace: " . $e->getTraceAsString());
            
            // Página de erro simples
            echo "<div class='container mt-5'>";
            echo "<div class='alert alert-danger'>";
            echo "<h4>Erro ao Visualizar Template</h4>";
            echo "<p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='/admin/document-templates' class='btn btn-primary'>Voltar para Templates</a></p>";
            echo "</div>";
            echo "</div>";
            exit;
        }
    }
    
    /**
     * Mostrar formulário de edição
     */
    public function edit($id)
    {
        try {
            error_log("DocumentTemplateController::edit() - Iniciando com ID: " . $id);
            
            if (!Auth::check()) {
                Session::flash('error', 'Você precisa estar logado para acessar esta página.');
                $this->redirect('/login');
                return;
            }
            
            if (!Auth::canManageTemplates()) {
                error_log("DocumentTemplateController::edit() - Usuário não pode gerenciar templates");
                Session::flash('error', 'Acesso negado. Apenas administradores e analistas podem gerenciar templates.');
                $this->redirect('/dashboard');
                return;
            }
            
            error_log("DocumentTemplateController::edit() - Buscando template com ID: " . $id);
            $template = $this->templateModel->find($id);
            
            if (!$template) {
                error_log("DocumentTemplateController::edit() - Template não encontrado: " . $id);
                Session::flash('error', 'Template não encontrado');
                $this->redirect('/admin/document-templates');
                return;
            }
            
            error_log("DocumentTemplateController::edit() - Template encontrado: " . $template['name']);
            
            $documentTypes = DocumentTemplate::getDefaultDocumentTypes();
            $projectTypes = [
                'residencial' => 'Residencial',
                'comercial' => 'Comercial/Industrial',
                'reforma' => 'Reforma e Adequação',
                'regularizacao' => 'Regularização Predial',
                'urbano' => 'Projeto Urbano',
                'infraestrutura' => 'Infraestrutura',
                'outro' => 'Outros'
            ];
            
            // Renderizar view
            $this->view('document_templates/edit', [
                'template' => $template,
                'documentTypes' => $documentTypes,
                'projectTypes' => $projectTypes
            ]);
            
        } catch (Exception $e) {
            error_log("DocumentTemplateController::edit() - Erro: " . $e->getMessage());
            error_log("DocumentTemplateController::edit() - Stack trace: " . $e->getTraceAsString());
            
            // Página de erro simples
            echo "<div class='container mt-5'>";
            echo "<div class='alert alert-danger'>";
            echo "<h4>Erro ao Editar Template</h4>";
            echo "<p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='/admin/document-templates' class='btn btn-primary'>Voltar para Templates</a></p>";
            echo "</div>";
            echo "</div>";
            exit;
        }
    }
    
    /**
     * Atualizar template
     */
    public function update($id)
    {
        if (!Auth::canManageTemplates()) {
            $this->json(['success' => false, 'message' => 'Acesso negado'], 403);
            return;
        }
        
        try {
            $data = $_POST;
            
            // Processar documentos (mesmo código do store)
            if (isset($data['required_documents']) && is_array($data['required_documents'])) {
                $requiredDocs = [];
                foreach ($data['required_documents'] as $doc) {
                    if (!empty($doc['type']) && !empty($doc['name'])) {
                        $requiredDocs[] = [
                            'type' => $doc['type'],
                            'name' => $doc['name'],
                            'description' => $doc['description'] ?? '',
                            'accept' => $doc['accept'] ?? '.pdf',
                            'max_size' => $doc['max_size'] ?? '10MB'
                        ];
                    }
                }
                $data['required_documents'] = $requiredDocs;
            }
            
            if (isset($data['optional_documents']) && is_array($data['optional_documents'])) {
                $optionalDocs = [];
                foreach ($data['optional_documents'] as $doc) {
                    if (!empty($doc['type']) && !empty($doc['name'])) {
                        $optionalDocs[] = [
                            'type' => $doc['type'],
                            'name' => $doc['name'],
                            'description' => $doc['description'] ?? '',
                            'accept' => $doc['accept'] ?? '.pdf',
                            'max_size' => $doc['max_size'] ?? '10MB'
                        ];
                    }
                }
                $data['optional_documents'] = $optionalDocs;
            }
            
            if ($this->templateModel->updateTemplate($id, $data)) {
                Session::flash('success', 'Template atualizado com sucesso!');
                $this->redirect('/admin/document-templates/' . $id);
            } else {
                Session::flash('error', 'Erro ao atualizar template');
                $this->redirect('/admin/document-templates/' . $id . '/edit');
            }
            
        } catch (Exception $e) {
            Session::flash('error', 'Erro: ' . $e->getMessage());
            $this->redirect('/admin/document-templates/' . $id . '/edit');
        }
    }
    
    /**
     * Excluir template
     */
    public function destroy($id)
    {
        if (!Auth::canManageTemplates()) {
            $this->json(['success' => false, 'message' => 'Acesso negado'], 403);
            return;
        }
        
        // Verificar se template está em uso
        $stats = $this->templateModel->getUsageStats($id);
        if ($stats['projects_count'] > 0) {
            $this->json([
                'success' => false, 
                'message' => 'Não é possível excluir template em uso por ' . $stats['projects_count'] . ' projeto(s)'
            ], 400);
            return;
        }
        
        if ($this->templateModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'Template excluído com sucesso']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao excluir template'], 500);
        }
    }
    
    /**
     * Duplicar template
     */
    public function duplicate($id)
    {
        if (!Auth::canManageTemplates()) {
            $this->json(['success' => false, 'message' => 'Acesso negado'], 403);
            return;
        }
        
        $newName = $_POST['name'] ?? 'Cópia de Template';
        
        $newId = $this->templateModel->duplicate($id, $newName);
        
        if ($newId) {
            $this->json([
                'success' => true, 
                'message' => 'Template duplicado com sucesso',
                'template_id' => $newId
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao duplicar template'], 500);
        }
    }
    
    /**
     * Ativar/desativar template
     */
    public function toggleActive($id)
    {
        if (!Auth::canManageTemplates()) {
            $this->json(['success' => false, 'message' => 'Acesso negado'], 403);
            return;
        }
        
        $template = $this->templateModel->find($id);
        if (!$template) {
            $this->json(['success' => false, 'message' => 'Template não encontrado'], 404);
            return;
        }
        
        $newStatus = !($template['active'] ?? true);
        
        if ($this->templateModel->update($id, ['active' => $newStatus])) {
            $this->json([
                'success' => true, 
                'message' => 'Status alterado com sucesso',
                'active' => $newStatus
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao alterar status'], 500);
        }
    }
    
    /**
     * API: Obter templates por tipo de projeto
     */
    public function getByProjectType()
    {
        $projectType = $_GET['project_type'] ?? '';
        
        if (empty($projectType)) {
            $this->json(['success' => false, 'message' => 'Tipo de projeto obrigatório'], 400);
            return;
        }
        
        $templates = $this->templateModel->getByProjectType($projectType);
        
        $this->json([
            'success' => true,
            'templates' => array_values($templates)
        ]);
    }
    
    /**
     * API: Obter detalhes de um template
     */
    public function getTemplateDetails($id)
    {
        $template = $this->templateModel->getWithDocuments($id);
        
        if (!$template) {
            $this->json(['success' => false, 'message' => 'Template não encontrado'], 404);
            return;
        }
        
        $this->json([
            'success' => true,
            'template' => $template
        ]);
    }
}
