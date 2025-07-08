<?php
    require_once 'connection.php';

    function inputPegawai($id_pegawai, $nama, $jabatan, $status_kepegawaian, $gaji_pokok, $conn ) {
        $response = array();    

        if(!$conn){
            $response['status'] = 'error';
            $response['message'] = 'Database connection error' + mysqli_connect_error();
            return $response;
        }

        $sql = "INSERT INTO pegawai (id_pegawai, nama, jabatan, status_kepegawaian, gaji_pokok) 
                VALUES ('$id_pegawai', '$nama', '$jabatan', '$status_kepegawaian', '$gaji_pokok')";
        
        if(mysqli_query($conn, $sql)) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        }
        return $response;
    }
?>