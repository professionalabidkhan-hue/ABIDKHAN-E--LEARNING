<?php
/**
 * ABID KHAN HUB - SENTINEL AUTH ENGINE
 * Logic for Oracle SQL (FreeSQL)
 */
header('Content-Type: application/json');
session_start();
require_once 'connect.php';

// Get JSON Input from the Gatekeeper Page
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing Credentials']);
    exit();
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];

try {
    // Search the Oracle AK_USERS Table
    $sql = "SELECT USER_ID, FULL_NAME, PASSWORD, DEPARTMENT, USER_ROLE, STATUS FROM AK_USERS WHERE EMAIL = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_execute($stmt);

    $user = oci_fetch_array($stmt, OCI_ASSOC);

    if ($user) {
        // Verify the Secure Access Key (Hashed)
        if (password_verify($password, $user['PASSWORD'])) {
            
            // Check Account Status
            if ($user['STATUS'] !== 'ACTIVE') {
                echo json_encode(['success' => false, 'message' => 'Vault Access Pending Approval.']);
            } else {
                // Initialize Master Session
                $_SESSION['user_id'] = $user['USER_ID'];
                $_SESSION['user_name'] = $user['FULL_NAME'];
                $_SESSION['category'] = $user['DEPARTMENT']; // Maps to your Routing Engine
                $_SESSION['user_role'] = $user['USER_ROLE'];

                echo json_encode([
                    'success' => true, 
                    'category' => $user['DEPARTMENT']
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Access Key.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Identity Not Found.']);
    }

    oci_free_statement($stmt);
    oci_close($conn);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Sentinel Error: ' . $e->getMessage()]);
}
?>