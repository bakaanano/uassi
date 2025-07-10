<?php
require_once 'connection.php';

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan.'];

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id_pegawai']) && isset($data['password_lama']) && isset($data['password_baru'])) {
        $id_pegawai = $data['id_pegawai'];
        $password_lama = $data['password_lama'];
        $password_baru = $data['password_baru'];

        $stmt_check = $conn->prepare("SELECT id_pegawai FROM pegawai WHERE id_pegawai = ? AND password = ?");
        $stmt_check->bind_param("ss", $id_pegawai, $password_lama);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            
            $stmt_check->close();

            $stmt_update = $conn->prepare("UPDATE pegawai SET password = ? WHERE id_pegawai = ?");
            $stmt_update->bind_param("ss", $password_baru, $id_pegawai);

            if ($stmt_update->execute()) {
                $response = ['status' => 'success', 'message' => 'Password berhasil diubah.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Gagal mengubah password.'];
            }
            $stmt_update->close();
        } else {
            http_response_code(401);
            $response = ['status' => 'error', 'message' => 'Password lama salah.'];
        }
    } else {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Data tidak lengkap. Harap kirim id_pegawai, password_lama, dan password_baru.'];
    }
} else {
    http_response_code(405);
    $response = ['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan PUT atau POST.'];
}

$conn->close();
echo json_encode($response);
?>