<?php
$host = "localhost"; 
$user = "root";      // Change this when you go online
$pass = "";          // Change this when you go online
$dbname = "abid_khan_hub";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Set error mode to exception for master-level debugging
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>