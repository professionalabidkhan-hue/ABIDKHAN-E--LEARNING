<?php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['email']) && isset($input['password'])) {
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    
    // 1. Query Oracle
    $sql = "SELECT USER_ID, FULL_NAME, PASSWORD, USER_ROLE, DEPARTMENT FROM AK_USERS WHERE EMAIL = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_execute($stmt);
    $user = oci_fetch_array($stmt, OCI_ASSOC);

    // 2. Security Check
    if ($user && password_verify($input['password'], $user['PASSWORD'])) {
        // Set Primary Sessions
        $_SESSION['user_id']   = $user['USER_ID'];
        $_SESSION['user_name'] = $user['FULL_NAME'];
        $_SESSION['user_role'] = $user['USER_ROLE'];
        
        // Match the Student Dashboard's 'category' requirement
        $_SESSION['category'] = ($user['DEPARTMENT'] === 'IT') ? 'IT Student' : 'Holy Quran Recitation';

        // 3. Smart Redirect
        $redirect = ($user['USER_ROLE'] === 'trainer') 
                    ? 'DASHBOARD_MASTER_TRAINER.php' 
                    : 'DASHBOARD_STUDENTS_FANTASTIC.php';

        echo json_encode(['success' => true, 'redirect' => $redirect]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Vault denied: Incorrect credentials.']);
    }
}
?>