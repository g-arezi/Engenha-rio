<?php

namespace App\Utils;

class FileUploadSecurity
{
    /**
     * Tipos MIME permitidos por categoria
     */
    const ALLOWED_MIME_TYPES = [
        'documents' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        'images' => [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp'
        ],
        'cad' => [
            'application/octet-stream', // .dwg, .dxf
            'application/acad',
            'image/vnd.dwg'
        ]
    ];
    
    /**
     * Extensões permitidas por categoria
     */
    const ALLOWED_EXTENSIONS = [
        'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'cad' => ['dwg', 'dxf']
    ];
    
    /**
     * Assinaturas de arquivo (magic numbers) para verificação adicional
     */
    const FILE_SIGNATURES = [
        'pdf' => ['25504446'],
        'jpg' => ['FFD8FF'],
        'jpeg' => ['FFD8FF'],
        'png' => ['89504E47'],
        'gif' => ['47494638'],
        'doc' => ['D0CF11E0'],
        'docx' => ['504B0304'],
        'xls' => ['D0CF11E0'],
        'xlsx' => ['504B0304']
    ];
    
    /**
     * Validar arquivo enviado
     */
    public static function validateFile($file, $category = 'documents')
    {
        $errors = [];
        
        // Verificar se o arquivo foi enviado
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'Arquivo não foi enviado corretamente';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Verificar tamanho
        $maxSize = self::getMaxFileSize();
        if ($file['size'] > $maxSize) {
            $errors[] = 'Arquivo muito grande. Máximo permitido: ' . self::formatBytes($maxSize);
        }
        
        // Verificar extensão
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = self::getAllowedExtensions($category);
        
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'Extensão de arquivo não permitida. Permitidas: ' . implode(', ', $allowedExtensions);
        }
        
        // Verificar tipo MIME
        $allowedMimes = self::getAllowedMimeTypes($category);
        if (!in_array($file['type'], $allowedMimes)) {
            $errors[] = 'Tipo de arquivo não permitido';
        }
        
        // Verificar assinatura do arquivo (se disponível)
        if (isset(self::FILE_SIGNATURES[$extension])) {
            if (!self::verifyFileSignature($file['tmp_name'], $extension)) {
                $errors[] = 'Arquivo corrompido ou tipo incorreto';
            }
        }
        
        // Verificar se o arquivo não é executável
        if (self::isExecutableFile($file['name'])) {
            $errors[] = 'Arquivos executáveis não são permitidos';
        }
        
        // Verificar conteúdo malicioso em PDFs
        if ($extension === 'pdf' && self::containsMaliciousContent($file['tmp_name'])) {
            $errors[] = 'PDF contém conteúdo suspeito';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Obter extensões permitidas por categoria
     */
    public static function getAllowedExtensions($category)
    {
        $extensions = [];
        
        if ($category === 'all' || $category === 'documents') {
            $extensions = array_merge($extensions, self::ALLOWED_EXTENSIONS['documents']);
        }
        
        if ($category === 'all' || $category === 'images') {
            $extensions = array_merge($extensions, self::ALLOWED_EXTENSIONS['images']);
        }
        
        if ($category === 'all' || $category === 'cad') {
            $extensions = array_merge($extensions, self::ALLOWED_EXTENSIONS['cad']);
        }
        
        return array_unique($extensions);
    }
    
    /**
     * Obter tipos MIME permitidos por categoria
     */
    public static function getAllowedMimeTypes($category)
    {
        $mimes = [];
        
        if ($category === 'all' || $category === 'documents') {
            $mimes = array_merge($mimes, self::ALLOWED_MIME_TYPES['documents']);
        }
        
        if ($category === 'all' || $category === 'images') {
            $mimes = array_merge($mimes, self::ALLOWED_MIME_TYPES['images']);
        }
        
        if ($category === 'all' || $category === 'cad') {
            $mimes = array_merge($mimes, self::ALLOWED_MIME_TYPES['cad']);
        }
        
        return array_unique($mimes);
    }
    
    /**
     * Verificar assinatura do arquivo
     */
    private static function verifyFileSignature($filePath, $extension)
    {
        if (!isset(self::FILE_SIGNATURES[$extension])) {
            return true; // Se não tem assinatura definida, passa
        }
        
        $signatures = self::FILE_SIGNATURES[$extension];
        $fileHeader = bin2hex(file_get_contents($filePath, false, null, 0, 10));
        
        foreach ($signatures as $signature) {
            if (strpos(strtoupper($fileHeader), $signature) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar se é arquivo executável
     */
    private static function isExecutableFile($fileName)
    {
        $executableExtensions = [
            'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar',
            'php', 'asp', 'aspx', 'jsp', 'py', 'pl', 'rb', 'sh'
        ];
        
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($extension, $executableExtensions);
    }
    
    /**
     * Verificar conteúdo malicioso em PDFs
     */
    private static function containsMaliciousContent($filePath)
    {
        $content = file_get_contents($filePath);
        
        // Padrões suspeitos em PDFs
        $suspiciousPatterns = [
            '/\/JavaScript/i',
            '/\/JS/i',
            '/\/OpenAction/i',
            '/\/Launch/i',
            '/\/URI/i'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Gerar nome de arquivo seguro
     */
    public static function generateSecureFileName($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Remover caracteres perigosos
        $baseName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $baseName);
        
        // Remover múltiplos underscores
        $baseName = preg_replace('/_+/', '_', $baseName);
        
        // Trim underscores
        $baseName = trim($baseName, '_');
        
        // Gerar ID único
        $uniqueId = uniqid();
        
        return $uniqueId . '_' . $baseName . '.' . $extension;
    }
    
    /**
     * Obter tamanho máximo de arquivo
     */
    private static function getMaxFileSize()
    {
        return 10 * 1024 * 1024; // 10MB
    }
    
    /**
     * Formatar bytes em formato legível
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
