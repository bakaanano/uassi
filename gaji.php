<?php   
    require_once 'connection.php';
    header('Content-Type: application/json');

    if (!isset($_GET['id_pegawai'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Parameter id_pegawai wajib diisi"
        ]);
        exit;
    }

    $id_pegawai = $_GET['id_pegawai'];

    $sql = "
        SELECT 
            p.id_pegawai,
            p.nama,
            p.jabatan,
            p.gaji_pokok,
            d.tanggal,
            t.jenis AS jenis_tunjangan,
            d.jumlah_diterima
        FROM detail_gaji d
        JOIN pegawai p ON d.id_pegawai = p.id_pegawai
        JOIN tunjangan t ON d.id_tunjangan = t.id_tunjangan
        WHERE d.id_pegawai = ?
        ORDER BY d.tanggal DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_pegawai);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Data gaji tidak ditemukan",
            "data" => []
        ]);
        exit;
    }

    $gaji_per_tanggal = [];

    while ($row = $result->fetch_assoc()) {
        $tanggal = $row['tanggal'];
        if (!isset($gaji_per_tanggal[$tanggal])) {
            $gaji_per_tanggal[$tanggal] = [
                "id_pegawai" => $row['id_pegawai'],
                "nama" => $row['nama'],
                "jabatan" => $row['jabatan'],
                "gaji_pokok" => floatval($row['gaji_pokok']),
                "tanggal" => $tanggal,
                "tunjangan" => [],
                "total_tunjangan" => 0,
                "total_gaji" => 0
            ];
        }

    $gaji_per_tanggal[$tanggal]['tunjangan'][] = [
        "jenis" => $row['jenis_tunjangan'],
        "jumlah" => floatval($row['jumlah_diterima'])
    ];

    $gaji_per_tanggal[$tanggal]['total_tunjangan'] += floatval($row['jumlah_diterima']);
    $gaji_per_tanggal[$tanggal]['total_gaji'] = $gaji_per_tanggal[$tanggal]['gaji_pokok'] + $gaji_per_tanggal[$tanggal]['total_tunjangan'];
    }

    echo json_encode([
        "status" => "success",
        "data" => array_values($gaji_per_tanggal)
    ]);
?>