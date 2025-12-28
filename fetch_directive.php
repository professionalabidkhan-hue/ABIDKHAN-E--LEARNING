<?php
session_start();
require_once 'connect.php';
header('Content-Type: application/json');

// Only fetch for the student's specific department
$dept = ($_SESSION['category'] === 'IT Student') ? 'IT' : 'QURAN';

// Fetch the absolute LATEST directive for this department
$sql = "SELECT MESSAGE_TEXT FROM (
            SELECT MESSAGE_TEXT FROM AK_DIRECTIVES 
            WHERE DEPT_TARGET = :dept 
            ORDER BY DIR_ID DESC
        ) WHERE ROWNUM = 1";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':dept', $dept);
oci_execute($stmt);

$row = oci_fetch_array($stmt, OCI_ASSOC);
echo json_encode(['message' => $row['MESSAGE_TEXT'] ?? "Waiting for Master's Signal..."]);
oci_free_statement($stmt);
?>