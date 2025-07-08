<?php
    require_once 'connection.php';

    function updatePgw($id_pegawai, $nama, $jabatan, $status_kepegawaian, $gaji_pokok) {
        $sql = "UPDATE pegawai SET nama='$nama', jabatan='$jabatan', status_kepegawaian='$status_kepegawaian', gaji_pokok='$gaji_pokok' WHERE id_pegawai='$id_pegawai'";

        global $conn;
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }