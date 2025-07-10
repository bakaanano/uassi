<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $employee_id = trim($_POST['id_pegawai']);
    $password = trim($_POST['password']);

    if (empty($employee_id) || empty($password)) {
        die(json_encode(['status' => 'error', 'message' => 'Employee ID and Password cannot be empty!']));
    }

    $sql = "SELECT id_pegawai, nama, password FROM pegawai WHERE id_pegawai = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $employee_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $employee = $result->fetch_assoc();

            if ($password == $employee['password']) {
                
                $_SESSION['loggedin'] = true;
                $_SESSION['employee_id'] = $employee['id_pegawai'];
                $_SESSION['name'] = $employee['nama'];

                echo json_encode(['status' => 'success', 'message' => 'Login successful!']);

            } else {
                // Incorrect Password
                echo json_encode(['status' => 'error', 'message' => 'The password you entered is incorrect.']);
            }
        } else {
            // Employee ID not found
            echo json_encode(['status' => 'error', 'message' => 'Employee ID not found.']);
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();

} else {
    // If the file is accessed directly, deny access
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access Denied.']);
}
?>