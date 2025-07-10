<?php
require_once 'connection.php';

$request_method = $_SERVER['REQUEST_METHOD'];

// Mengambil data input JSON
$data = json_decode(file_get_contents('php://input'), true);

switch ($request_method) {
    case 'GET':
        // GET tidak memerlukan body, jadi ambil dari query string
        $id_pegawai = !empty($_GET["id_pegawai"]) ? $_GET["id_pegawai"] : null;
        if ($id_pegawai) {
            getPresensiByPegawai($id_pegawai);
        } else {
            getAllPresensi();
        }
        break;
    case 'POST':
        // Fungsi ini sekarang menjadi Check-in
        checkIn($data);
        break;
    case 'PUT':
        // Fungsi ini sekarang menjadi Check-out
        checkOut($data);
        break;
    case 'DELETE':
        deletePresensi($data);
        break;
    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method tidak diizinkan"]);
        break;
}

// 1. Check-in (Sebelumnya addPresensi)
function checkIn($data) {
    global $conn;
    if (!isset($data['id_pegawai'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "id_pegawai wajib diisi"]);
        return;
    }

    $id_pegawai = $data['id_pegawai'];

    // Cek apakah pegawai sudah check-in hari ini dan belum check-out
    $stmt_check = $conn->prepare("SELECT id_presensi FROM presensi WHERE id_pegawai = ? AND DATE(waktu_masuk) = CURDATE()");
    $stmt_check->bind_param("s", $id_pegawai);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["status" => "error", "message" => "Anda sudah melakukan presensi masuk hari ini."]);
        $stmt_check->close();
        return;
    }
    $stmt_check->close();

    // Jika belum, lanjutkan proses check-in
    $lokasi = $data['lokasi'] ?? 'kantor';
    $status = $data['status_kehadiran'] ?? 'hadir';
    $waktu_masuk = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO presensi (id_pegawai, waktu_masuk, lokasi, status_kehadiran) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id_pegawai, $waktu_masuk, $lokasi, $status);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(["status" => "success", "message" => "Presensi masuk berhasil dicatat."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Gagal mencatat presensi masuk."]);
    }
    $stmt->close();
}

// 2. Check-out (Sebelumnya updatePresensi)
function checkOut($data) {
    global $conn;
    if (!isset($data['id_pegawai'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "id_pegawai wajib diisi"]);
        return;
    }

    $id_pegawai = $data['id_pegawai'];
    $waktu_keluar = date("Y-m-d H:i:s");

    // Cari presensi masuk hari ini yang belum ada waktu keluarnya
    $stmt = $conn->prepare("UPDATE presensi SET waktu_keluar = ? WHERE id_pegawai = ? AND DATE(waktu_masuk) = CURDATE() AND waktu_keluar IS NULL");
    $stmt->bind_param("ss", $waktu_keluar, $id_pegawai);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200); // OK
            echo json_encode(["status" => "success", "message" => "Presensi keluar berhasil diperbarui."]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "Tidak ada data presensi masuk yang perlu di-update untuk hari ini."]);
        }
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui presensi keluar."]);
    }
    $stmt->close();
}

// Fungsi lainnya (GET dan DELETE) tetap sama, hanya penyesuaian parameter
function getAllPresensi() {
    global $conn;
    $query = "SELECT * FROM presensi ORDER BY waktu_masuk DESC";
    $result = $conn->query($query);
    $presensi = [];
    while ($row = $result->fetch_assoc()) {
        $presensi[] = $row;
    }
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $presensi]);
}

function getPresensiByPegawai($id_pegawai) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM presensi WHERE id_pegawai = ? ORDER BY waktu_masuk DESC");
    $stmt->bind_param("s", $id_pegawai);
    $stmt->execute();
    $result = $stmt->get_result();
    $presensi = [];
    while ($row = $result->fetch_assoc()) {
        $presensi[] = $row;
    }
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $presensi]);
    $stmt->close();
}

function deletePresensi($data) {
    global $conn;
     if (!isset($data['id_presensi'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "id_presensi wajib diisi"]);
        return;
    }
    $id_presensi = $data['id_presensi'];

    $stmt = $conn->prepare("DELETE FROM presensi WHERE id_presensi = ?");
    $stmt->bind_param("i", $id_presensi);

    if ($stmt->execute()) {
        if($stmt->affected_rows > 0) {
             echo json_encode(["status" => "success", "message" => "Data presensi berhasil dihapus."]);
        } else {
             echo json_encode(["status" => "error", "message" => "Data presensi dengan ID tersebut tidak ditemukan."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal menghapus data presensi."]);
    }
    $stmt->close();
}
?>