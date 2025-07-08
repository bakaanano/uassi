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
            $response['message'] = 'Error:' . mysqli_error($conn);    
        }

        return $response;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id_pegawai']) && isset($_POST['nama']) && isset($_POST['jabatan']) && isset($_POST['status_kepegawaian']) && isset($_POST['gaji_pokok'])) {
        $id_pegawai = $_POST['id_pegawai'];
        $nama = $_POST['nama'];
        $jabatan = $_POST['jabatan'];
        $status_kepegawaian = $_POST['status_kepegawaian'];
        $gaji_pokok = $_POST['gaji_pokok'];

        $result = inputPegawai($id_pegawai, $nama, $jabatan, $status_kepegawaian, $gaji_pokok, $conn);
    } else {
        $result = array(
            'status' => 'error',
            'message' => 'Data input tidak lengkap'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($result);
}
?>