<?php
    require_once 'connection.php';

    function updatePgw($id_pegawai, $nama, $jabatan, $status_kepegawaian, $gaji_pokok) {
        $sql = "UPDATE pegawai SET 
                    nama='$nama', 
                    jabatan='$jabatan', 
                    status_kepegawaian='$status_kepegawaian',
                    gaji_pokok='$gaji_pokok' 
                WHERE id_pegawai='$id_pegawai'";

        global $conn;
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['id_pegawai']) && isset($_POST['nama']) && isset($_POST['jabatan']) && isset($_POST['status_kepegawaian']) && isset($_POST['gaji_pokok'])) {
            $id_pegawai = $_POST['id_pegawai'];
            $nama = $_POST['nama'];
            $jabatan = $_POST['jabatan'];
            $status_kepegawaian = $_POST['status_kepegawaian'];
            $gaji_pokok = $_POST['gaji_pokok'];

            updatePgw($id_pegawai, $nama, $jabatan, $status_kepegawaian, $gaji_pokok);
        } else {
            echo 'Data input tidak lengkap';
        }
    }
?>