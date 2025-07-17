<?php

namespace App\Models;

use App\Core\Model;

class DocumentTemplate extends Model
{
    protected $table = 'document_templates';
    protected $dataFile = 'data/document_templates.json';
    
    protected function getDataFile()
    {
        return __DIR__ . '/../../data/document_templates.json';
    }
    
    protected $fillable = [
        'name',
        'description', 
        'project_type',
        'required_documents',
        'optional_documents',
        'instructions',
        'active',
        'created_by',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Obter todos os templates ativos
     */
    public function getActive()
    {
        $templates = $this->all();
        return array_filter($templates, function($template) {
            return $template['active'] ?? true;
        });
    }
    
    /**
     * Obter templates por tipo de projeto
     */
    public function getByProjectType($projectType)
    {
        $templates = $this->getActive();
        return array_filter($templates, function($template) use ($projectType) {
            return $template['project_type'] === $projectType;
        });
    }
    
    /**
     * Obter template com documentos formatados
     */
    public function getWithDocuments($id)
    {
        $template = $this->find($id);
        if (!$template) {
            return null;
        }
        
        // Decodificar JSONs se necessário
        if (is_string($template['required_documents'])) {
            $template['required_documents'] = json_decode($template['required_documents'], true) ?: [];
        }
        
        if (is_string($template['optional_documents'])) {
            $template['optional_documents'] = json_decode($template['optional_documents'], true) ?: [];
        }
        
        return $template;
    }
    
    /**
     * Criar template com validação
     */
    public function createTemplate($data)
    {
        // Validar dados obrigatórios
        $required = ['name', 'project_type', 'required_documents'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Campo obrigatório: $field");
            }
        }
        
        // Garantir que documentos sejam arrays
        if (is_string($data['required_documents'])) {
            $data['required_documents'] = json_decode($data['required_documents'], true) ?: [];
        }
        
        if (is_string($data['optional_documents'])) {
            $data['optional_documents'] = json_decode($data['optional_documents'], true) ?: [];
        }
        
        $data['active'] = $data['active'] ?? true;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Atualizar template
     */
    public function updateTemplate($id, $data)
    {
        // Garantir que documentos sejam arrays
        if (isset($data['required_documents']) && is_string($data['required_documents'])) {
            $data['required_documents'] = json_decode($data['required_documents'], true) ?: [];
        }
        
        if (isset($data['optional_documents']) && is_string($data['optional_documents'])) {
            $data['optional_documents'] = json_decode($data['optional_documents'], true) ?: [];
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($id, $data);
    }
    
    /**
     * Obter estatísticas de uso do template
     */
    public function getUsageStats($id)
    {
        $projectModel = new Project();
        $projects = $projectModel->all();
        
        $usage = array_filter($projects, function($project) use ($id) {
            return ($project['document_template_id'] ?? null) === $id;
        });
        
        return [
            'projects_count' => count($usage),
            'projects' => array_values($usage)
        ];
    }
    
    /**
     * Duplicar template
     */
    public function duplicate($id, $newName)
    {
        $template = $this->find($id);
        if (!$template) {
            return false;
        }
        
        unset($template['id']);
        $template['name'] = $newName;
        $template['created_at'] = date('Y-m-d H:i:s');
        $template['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($template);
    }
    
    /**
     * Validar estrutura de documento
     */
    public function validateDocumentStructure($document)
    {
        $required = ['name', 'description', 'type'];
        foreach ($required as $field) {
            if (empty($document[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Obter tipos de documentos padrão
     */
    public static function getDefaultDocumentTypes()
    {
        return [
            'identity' => [
                'label' => 'Documento de Identidade',
                'description' => 'RG, CNH ou documento oficial com foto',
                'accept' => '.pdf,.jpg,.jpeg,.png'
            ],
            'cpf' => [
                'label' => 'CPF',
                'description' => 'Cadastro de Pessoa Física',
                'accept' => '.pdf,.jpg,.jpeg,.png'
            ],
            'cnpj' => [
                'label' => 'CNPJ',
                'description' => 'Cadastro Nacional de Pessoa Jurídica',
                'accept' => '.pdf,.jpg,.jpeg,.png'
            ],
            'social_contract' => [
                'label' => 'Contrato Social',
                'description' => 'Contrato social da empresa atualizado',
                'accept' => '.pdf'
            ],
            'address_proof' => [
                'label' => 'Comprovante de Endereço',
                'description' => 'Conta de luz, água ou telefone recente',
                'accept' => '.pdf,.jpg,.jpeg,.png'
            ],
            'property_deed' => [
                'label' => 'Escritura do Imóvel',
                'description' => 'Documento de propriedade do imóvel',
                'accept' => '.pdf'
            ],
            'building_permit' => [
                'label' => 'Alvará de Construção',
                'description' => 'Licença municipal para construção',
                'accept' => '.pdf'
            ],
            'topographic_survey' => [
                'label' => 'Levantamento Topográfico',
                'description' => 'Planta topográfica do terreno',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'soil_analysis' => [
                'label' => 'Análise do Solo',
                'description' => 'Laudo técnico de análise do solo',
                'accept' => '.pdf'
            ],
            'architectural_project' => [
                'label' => 'Projeto Arquitetônico',
                'description' => 'Plantas e projetos arquitetônicos',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'structural_project' => [
                'label' => 'Projeto Estrutural',
                'description' => 'Cálculos e plantas estruturais',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'electrical_project' => [
                'label' => 'Projeto Elétrico',
                'description' => 'Projeto de instalações elétricas',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'plumbing_project' => [
                'label' => 'Projeto Hidráulico',
                'description' => 'Projeto de instalações hidráulicas',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'memorial' => [
                'label' => 'Memorial Descritivo',
                'description' => 'Descrição detalhada do projeto',
                'accept' => '.pdf,.doc,.docx'
            ],
            'budget' => [
                'label' => 'Orçamento',
                'description' => 'Planilha orçamentária do projeto',
                'accept' => '.pdf,.xls,.xlsx'
            ],
            'technical_report' => [
                'label' => 'Laudo Técnico',
                'description' => 'Relatório técnico especializado',
                'accept' => '.pdf'
            ],
            'environmental_license' => [
                'label' => 'Licença Ambiental',
                'description' => 'Licenciamento ambiental quando necessário',
                'accept' => '.pdf'
            ],
            'fire_safety' => [
                'label' => 'Projeto de Prevenção de Incêndio',
                'description' => 'Projeto de segurança contra incêndios',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'accessibility' => [
                'label' => 'Projeto de Acessibilidade',
                'description' => 'Adequações para acessibilidade',
                'accept' => '.pdf,.dwg,.dxf'
            ],
            'other' => [
                'label' => 'Outros Documentos',
                'description' => 'Documentos adicionais específicos do projeto',
                'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png,.dwg,.dxf'
            ]
        ];
    }
}
