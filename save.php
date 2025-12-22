<?php
// save.php - Backend untuk menulis file
header('Content-Type: application/json');

// Ambil data JSON yang dikirim dari index.html
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada data dikirim']);
    exit;
}

$yamlContent = $input['content'];
// Bersihkan path folder dari slash berlebih
$targetFolder = rtrim($input['path'], '/\\'); 

// Tentukan lokasi file akhir
// Target: {folder_project}/.github/workflows/deploy.yml
$workflowDir = $targetFolder . '/.github/workflows';
$filePath = $workflowDir . '/deploy.yml';

// Cek apakah folder project ada?
if (!is_dir($targetFolder)) {
    echo json_encode(['status' => 'error', 'message' => "Folder Project tidak ditemukan: $targetFolder"]);
    exit;
}

// Cek/Buat folder .github/workflows jika belum ada
if (!is_dir($workflowDir)) {
    // mkdir recursive (buat folder bertingkat sekaligus)
    if (!mkdir($workflowDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat folder .github/workflows (Cek Permission)']);
        exit;
    }
}

// Tulis File
if (file_put_contents($filePath, $yamlContent)) {
    echo json_encode(['status' => 'success', 'message' => "File berhasil disimpan di:\n$filePath"]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menulis file. Pastikan folder tidak Read-Only.']);
}
?>