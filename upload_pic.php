<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_email'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$target_dir = "uploads/";
// Create directory if it doesn't exist
if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

$file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
$new_filename = "user_" . $_SESSION['user_id'] . "_" . time() . "." . $file_ext;
$target_file = $target_dir . $new_filename;

// Security Strike: Check if image is real
if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
    $host = "localhost"; $db_name = "abid_khan_e_learning_hub"; $username = "root"; $password = "";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $sql = "UPDATE members SET profile_pic = :pic WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':pic' => $target_file, ':email' => $_SESSION['user_email']]);
        
        echo json_encode(["success" => true, "message" => "IDENTITY UPDATED", "path" => $target_file]);
    } catch(PDOException $e) { echo json_encode(["success" => false, "message" => "Database Error"]); }
} else {
    echo json_encode(["success" => false, "message" => "Upload Failed"]);
}
?>
<script>
    // DISABLE RIGHT CLICK
    document.addEventListener('contextmenu', event => event.preventDefault());

    // DISABLE KEYBOARD SHORTCUTS (F12, Ctrl+Shift+I, Ctrl+U)
    document.onkeydown = function(e) {
        if(e.keyCode == 123) return false; // F12
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false; // Inspect
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) return false; // Elements
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false; // Console
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false; // View Source
    };
</script>
<style>
    /* PREVENT TEXT SELECTION */
    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>