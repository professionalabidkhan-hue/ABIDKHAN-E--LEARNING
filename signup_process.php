<?php
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and Clean Data
    $name     = htmlspecialchars($_POST['full_name']);
    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $whatsapp = htmlspecialchars($_POST['whatsapp']);
    $location = htmlspecialchars($_POST['location']);
    $dept     = $_POST['department'];
    $role     = $_POST['role'];
    $timing   = $_POST['timing'];
    
    // Security: Encrypt Password
    $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL for Oracle
    $sql = "INSERT INTO AK_USERS (FULL_NAME, EMAIL, PASSWORD, WHATSAPP_NO, LOCATION, DEPARTMENT, USER_ROLE, PREFERRED_TIMING, STATUS, CREATED_AT) 
            VALUES (:name, :email, :pass, :wa, :loc, :dept, :role, :time, 'PENDING', CURRENT_TIMESTAMP)";

    $stmt = oci_parse($conn, $sql);

    // Bindings to prevent SQL Injection
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':pass', $hashed_pass);
    oci_bind_by_name($stmt, ':wa',   $whatsapp);
    oci_bind_by_name($stmt, ':loc',  $location);
    oci_bind_by_name($stmt, ':dept', $dept);
    oci_bind_by_name($stmt, ':role', $role);
    oci_bind_by_name($stmt, ':time', $timing);

    if (oci_execute($stmt)) {
        // Successful - Send to Login or Welcome Page
        header("Location: login.php?msg=RegistrationComplete");
    } else {
        $e = oci_error($stmt);
        echo "Master Error: " . $e['message'];
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>