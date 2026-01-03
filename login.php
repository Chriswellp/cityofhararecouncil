<?php
session_start();
$conn = new mysqli("localhost", "root", "", "zrpt_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tracking = $conn->real_escape_string($_POST['tracking_code']);
    $id_num = $conn->real_escape_string($_POST['app_id']);

    $sql = "SELECT * FROM registrations WHERE tracking_code = '$tracking' AND app_id = '$id_num' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user_data'] = $result->fetch_assoc();
        header("Location: profile.php");
    } else {
        echo "<script>alert('Invalid Account Number or ID Number'); window.location='index.html';</script>";
    }
}
?>