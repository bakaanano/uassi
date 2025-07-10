<?php
require_once 'connection.php';

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id_pegawai']) && isset($data['password'])) {
        $id_pegawai = $data['id_pegawai'];
        $password = $data['password'];

        $stmt = $conn->prepare("SELECT id_pegawai, nama, jabatan, status_kepegawaian FROM pegawai WHERE id_pegawai = ? AND password = ?");
        $stmt->bind_param("ss", $id_pegawai, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $pegawai = $result->fetch_assoc();
            $response = [
                'status' => 'success',
                'message' => 'Login berhasil!',
                'data' => $pegawai
            ];
        } else {
            http_response_code(401); 
            $response = ['status' => 'error', 'message' => 'ID Pegawai atau password salah.'];
        }
        $stmt->close();
    } else {
        http_response_code(400); 
        $response = ['status' => 'error', 'message' => 'Data tidak lengkap. Harap kirim id_pegawai dan password.'];
    }
} else {
    http_response_code(405);
    $response = ['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan POST.'];
}

$conn->close();
echo json_encode($response);
?>