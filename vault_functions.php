<?php
// MASTER ARCHITECT: ABID KHAN
// PURPOSE: OTP GENERATION & VAULT INSERTION

function generateAndStoreOTP($conn, $email) {
    // 1. Generate a 6-Digit Sovereign Code
    $otp = rand(100000, 999999);
    
    // 2. Clear any old OTPs for this email to maintain purity
    $deleteSql = "DELETE FROM AK_OTP_VAULT WHERE email = :email";
    $stmtDel = oci_parse($conn, $deleteSql);
    oci_bind_by_name($stmtDel, ":email", $email);
    oci_execute($stmtDel);

    // 3. Insert the new code into the AK_OTP_VAULT
    $insertSql = "INSERT INTO AK_OTP_VAULT (email, otp_code) VALUES (:email, :otp)";
    $stmtIns = oci_parse($conn, $insertSql);
    oci_bind_by_name($stmtIns, ":email", $email);
    oci_bind_by_name($stmtIns, ":otp", $otp);
    
    if (oci_execute($stmtIns)) {
        oci_commit($conn);
        return $otp; // Return to send via Email/WhatsApp
    }
    return false;
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