<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "dbuassi";
    $conn = new mysqli($host, $user, $pass, $db);
    date_default_timezone_set('Asia/Jakarta');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>