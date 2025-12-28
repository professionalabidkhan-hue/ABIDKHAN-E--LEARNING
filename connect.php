<?php
// Retrieve credentials from the server environment
$user = getenv('DB_USER') ?: 'YOUR_DEFAULT_USER';
$pass = getenv('DB_PASS') ?: ''; // Never hardcode the real pass here
$db   = getenv('DB_CONNECTION_STRING');

$conn = oci_connect($user, $pass, $db, 'AL32UTF8');

if (!$conn) {
    $e = oci_error();
    // SECURITY TIP: Do not show detailed $e['message'] to public users.
    // Log it to a file instead.
    error_log("Connection failed: " . $e['message']);
    die("VAULT OFFLINE: Please contact the Founder.");
}
?>