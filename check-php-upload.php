<?php
echo "<h1>Configurações PHP para Upload</h1>";

$configs = [
    'file_uploads',
    'upload_max_filesize', 
    'post_max_size',
    'max_execution_time',
    'max_input_time',
    'memory_limit',
    'upload_tmp_dir'
];

foreach ($configs as $config) {
    echo "<p><strong>$config:</strong> " . ini_get($config) . "</p>";
}

echo "<h2>Upload Errors Constants</h2>";
$errors = [
    UPLOAD_ERR_OK => 'UPLOAD_ERR_OK',
    UPLOAD_ERR_INI_SIZE => 'UPLOAD_ERR_INI_SIZE',
    UPLOAD_ERR_FORM_SIZE => 'UPLOAD_ERR_FORM_SIZE',
    UPLOAD_ERR_PARTIAL => 'UPLOAD_ERR_PARTIAL',
    UPLOAD_ERR_NO_FILE => 'UPLOAD_ERR_NO_FILE',
    UPLOAD_ERR_NO_TMP_DIR => 'UPLOAD_ERR_NO_TMP_DIR',
    UPLOAD_ERR_CANT_WRITE => 'UPLOAD_ERR_CANT_WRITE',
    UPLOAD_ERR_EXTENSION => 'UPLOAD_ERR_EXTENSION'
];

foreach ($errors as $code => $name) {
    echo "<p>$code = $name</p>";
}

echo "<h2>Temp Directory</h2>";
echo "<p>sys_get_temp_dir(): " . sys_get_temp_dir() . "</p>";
echo "<p>is_writable(temp): " . (is_writable(sys_get_temp_dir()) ? 'YES' : 'NO') . "</p>";

echo "<h2>Documents Directory</h2>";
$docsDir = __DIR__ . '/documents/';
echo "<p>Documents dir: $docsDir</p>";
echo "<p>exists: " . (is_dir($docsDir) ? 'YES' : 'NO') . "</p>";
echo "<p>writable: " . (is_writable($docsDir) ? 'YES' : 'NO') . "</p>";
?>
