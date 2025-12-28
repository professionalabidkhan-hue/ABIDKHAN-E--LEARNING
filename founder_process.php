<?php
session_start();
require_once 'connect.php';

// Security check: Only Founder category can commit
if (!isset($_SESSION['user_id']) || $_SESSION['category'] !== 'Founder') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized Access']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = htmlspecialchars($_POST['name']);
    $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $track  = $_POST['track'];
    $status = $_POST['status'];

    // Oracle Insert Statement
    $sql = "INSERT INTO AK_STUDENTS (FULL_NAME, EMAIL, MAJOR_TRACK, STATUS, ENROLLMENT_DATE) 
            VALUES (:name, :email, :track, :status, CURRENT_DATE)";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':track', $track);
    oci_bind_by_name($stmt, ':status', $status);

    if (oci_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => "Master, $name is now secured in the Oracle Vault."]);
    } else {
        $e = oci_error($stmt);
        echo json_encode(['success' => false, 'message' => $e['message']]);
    }
    oci_free_statement($stmt);
    oci_close($conn);
}
?>