<?php
// File: delete.php
require_once 'connection.php';

// Periksa apakah id_pegawai diberikan melalui parameter GET
if(isset($_GET['id_pegawai'])) {
    $id_pegawai = $_GET['id_pegawai'];
    
    // Escape input untuk mencegah SQL injection
    $escaped_id = $conn->real_escape_string($id_pegawai);
    
    // Buat query DELETE
    $sql = "DELETE FROM pegawai WHERE id_pegawai = '$escaped_id'";
    
    // Eksekusi query
    if($conn->query($sql) === TRUE) {
        // Berikan respons JSON jika berhasil
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Data pegawai berhasil dihapus',
            'id_pegawai' => $id_pegawai
        ]);
    } else {
        // Berikan respons error jika gagal
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus data: ' . $conn->error
        ]);
    }
} else {
    // Berikan respons error jika parameter tidak lengkap
    header('Content-Type: application/json', true, 400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter id_pegawai tidak diberikan'
    ]);
}

// Tutup koneksi database
$conn->close();
?>