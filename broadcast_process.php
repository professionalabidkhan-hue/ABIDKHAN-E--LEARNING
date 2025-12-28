<?php
session_start();
require_once 'connect.php'; // Secured Oracle Bridge

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['directive'])) {
    // 1. Sanitize the Master's Word
    $msg  = htmlspecialchars($_POST['directive']);
    $dept = $_SESSION['user_dept'] ?? 'IT'; // Captured from session

    // 2. Oracle SQL - Atomic Insert
    $sql = "INSERT INTO AK_DIRECTIVES (DEPT_TARGET, MESSAGE_TEXT) 
            VALUES (:dept, :msg)";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':dept', $dept);
    oci_bind_by_name($stmt, ':msg',  $msg);
    
    if (oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
        echo json_encode(['status' => 'success', 'message' => 'Directive Broadcasted.']);
    } else {
        $e = oci_error($stmt);
        echo json_encode(['status' => 'error', 'message' => $e['message']]);
    }
    oci_free_statement($stmt);
}
?>