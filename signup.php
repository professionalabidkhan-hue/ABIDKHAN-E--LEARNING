<?php
/**
 * ABID KHAN HUB - SIGNUP SENTINEL
 * Role-Based Registration for Oracle Vault
 */
require_once 'connect.php'; // Secured Oracle Bridge

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and Sanitise
    $name     = htmlspecialchars($_POST['full_name']);
    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $whatsapp = htmlspecialchars($_POST['whatsapp']);
    $location = htmlspecialchars($_POST['location']);
    
    // Crucial for Routing:
    $dept     = $_POST['department']; // Values: 'IT' or 'QURAN'
    $role     = $_POST['role'];       // Values: 'student' or 'trainer'
    $timing   = htmlspecialchars($_POST['timing']);
    
    // 2. Cyber Security: Professional Hashing
    $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 3. Oracle SQL - Prepared Statement
    $sql = "INSERT INTO AK_USERS (
                FULL_NAME, EMAIL, PASSWORD, WHATSAPP_NO, 
                LOCATION, DEPARTMENT, USER_ROLE, PREFERRED_TIMING, 
                STATUS, CREATED_AT
            ) VALUES (
                :name, :email, :pass, :wa, 
                :loc, :dept, :role, :time, 
                'ACTIVE', CURRENT_TIMESTAMP
            )";

    $stmt = oci_parse($conn, $sql);

    // 4. Bind variables to protect against SQL Injection
    oci_bind_by_name($stmt, ':name',  $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':pass',  $hashed_pass);
    oci_bind_by_name($stmt, ':wa',    $whatsapp);
    oci_bind_by_name($stmt, ':loc',   $location);
    oci_bind_by_name($stmt, ':dept',  $dept);
    oci_bind_by_name($stmt, ':role',  $role);
    oci_bind_by_name($stmt, ':time',  $timing);

    // 5. Execution
    if (oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
        $message = "<div class='alert alert-success border-0 bg-success text-white py-3'>
                        <i class='fas fa-check-circle me-2'></i> 
                        VAULT ENTRANCE GRANTED. Redirecting to Sign-in...
                    </div>";
        header("Refresh: 2; url=signin.php");
    } else {
        $e = oci_error($stmt);
        $message = "<div class='alert alert-danger border-0 bg-danger text-white py-3'>
                        <i class='fas fa-exclamation-triangle me-2'></i> 
                        VAULT ERROR: " . htmlentities($e['message']) . "
                    </div>";
    }
    oci_free_statement($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Entry | ABID KHAN HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-blue: #4fc3f7; --dark-bg: #07090d; --border: rgba(255, 255, 255, 0.1); }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top right, #1a1c2c, #07090d); 
            color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 0;
        }
        .signup-card { 
            background: rgba(13, 17, 23, 0.8); backdrop-filter: blur(25px); 
            border: 1px solid var(--border); border-radius: 35px; padding: 40px; 
            width: 100%; max-width: 600px; box-shadow: 0 40px 100px rgba(0,0,0,0.7);
        }
        .form-control, .form-select { 
            background: #000 !important; border: 1px solid var(--border); 
            color: #fff !important; border-radius: 12px; padding: 12px; margin-bottom: 20px;
        }
        .master-label { font-size: 0.7rem; font-weight: 800; color: var(--primary-blue); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; display: block; }
        .btn-request { 
            background: var(--primary-blue); color: #000; font-weight: 800; 
            border: none; padding: 15px; border-radius: 15px; width: 100%; text-transform: uppercase;
            transition: 0.3s;
        }
        .btn-request:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(79, 195, 247, 0.3); }
    </style>
</head>
<body oncontextmenu="return false;">

<div class="signup-card">
    <div class="text-center mb-4">
        <h2 style="font-weight:800; letter-spacing: 2px;">ABID KHAN HUB</h2>
        <div class="text-info small fw-bold">NEW IDENTITY REGISTRATION</div>
    </div>

    <?php echo $message; ?>
    
    <form method="POST" action="signup.php">
        <div class="row">
            <div class="col-md-6">
                <span class="master-label">Full Name</span>
                <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="col-md-6">
                <span class="master-label">Master Email</span>
                <input type="email" name="email" class="form-control" placeholder="name@hub.com" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <span class="master-label">Access Path (Department)</span>
                <select name="department" class="form-select" required>
                    <option value="IT">IT Mastery</option>
                    <option value="QURAN">Quranic Studies</option>
                </select>
            </div>
            <div class="col-md-6">
                <span class="master-label">Identity Role</span>
                <select name="role" class="form-select" required>
                    <option value="student">Student</option>
                    <option value="trainer">Trainer</option>
                </select>
            </div>
        </div>

        <span class="master-label">Create Access Key (Password)</span>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>

        <div class="row">
            <div class="col-md-6">
                <span class="master-label">WhatsApp Number</span>
                <input type="text" name="whatsapp" class="form-control" placeholder="+92..." required>
            </div>
            <div class="col-md-6">
                <span class="master-label">Preferred Timing</span>
                <input type="text" name="timing" class="form-control" placeholder="e.g. 8PM - 10PM">
            </div>
        </div>

        <span class="master-label">Location</span>
        <input type="text" name="location" class="form-control" placeholder="City, Country">

        <button type="submit" class="btn-request">Initiate Vault Entry</button>
    </form>
    
    <p class="text-center mt-4 small">Already a member? <a href="signin.php" class="text-info text-decoration-none fw-bold">Enter Vault</a></p>
</div>

<script>
    // SECURITY: Shielding Source Code
    document.onkeydown = function(e) {
        if(e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || (e.ctrlKey && e.keyCode == 85)) return false;
    };
</script>
</body>
</html>