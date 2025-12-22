<?php
// save.php - Script PHP untuk menyimpan file ke folder target
// Hanya berjalan di Localhost (XAMPP)

header('Content-Type: application/json');

// Ambil input JSON dari JavaScript
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
    exit;
}

$content = $input['content'];
// Bersihkan path agar aman
$targetPath = rtrim($input['path'], '/\\'); 
$workflowDir = $targetPath . '/.github/workflows';
$filePath = $workflowDir . '/deploy.yml';

// Validasi 1: Cek apakah folder proyek ada
if (!is_dir($targetPath)) {
    echo json_encode([
        'status' => 'error', 
        'message' => "Folder Project tidak ditemukan:\n$targetPath"
    ]);
    exit;
}

// Validasi 2: Cek/Buat folder .github/workflows
if (!is_dir($workflowDir)) {
    if (!mkdir($workflowDir, 0777, true)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Gagal membuat folder .github. Cek permission folder.'
        ]);
        exit;
    }
}

// Eksekusi: Tulis File
if (file_put_contents($filePath, $content)) {
    echo json_encode([
        'status' => 'success', 
        'message' => "File konfigurasi berhasil disimpan di:\n$filePath"
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Gagal menulis file. Pastikan folder tidak Read-Only.'
    ]);
}
?>
