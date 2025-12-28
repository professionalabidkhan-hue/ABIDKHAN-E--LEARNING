<?php
/**
 * ABID KHAN E-LEARNING HUB 
 * Master Secure Login System (Oracle SQL)
 */
session_start();
require_once 'connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // 1. Fetch user data from AK_USERS
    $sql = "SELECT USER_ID, FULL_NAME, PASSWORD, USER_ROLE, STATUS FROM AK_USERS WHERE EMAIL = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_execute($stmt);

    $row = oci_fetch_array($stmt, OCI_ASSOC);

    if ($row) {
        // 2. Verify Hashed Password
        if (password_verify($password, $row['PASSWORD'])) {
            
            // Check if account is active
            if ($row['STATUS'] == 'PENDING') {
                $error = "Account pending approval. Please contact Admin.";
            } else {
                // 3. Set Master Sessions
                $_SESSION['user_id']   = $row['USER_ID'];
                $_SESSION['user_name'] = $row['FULL_NAME'];
                $_SESSION['user_role'] = $row['USER_ROLE'];

                // 4. Role-Based Redirect
                if ($row['USER_ROLE'] == 'trainer') {
                    header("Location: trainer_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit();
            }
        } else {
            $error = "Invalid Password. Please try again.";
        }
    } else {
        $error = "No account found with that email.";
    }
    oci_free_statement($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Abid Khan E-Learning Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container { max-width: 400px; margin: 100px auto; padding: 40px; background: rgba(255,255,255,0.05); backdrop-filter: blur(15px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        input { width: 100%; padding: 12px; margin: 15px 0; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px; }
        .error-msg { color: #ff4d4d; background: rgba(255,77,77,0.1); padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; font-size: 0.9rem; }
        .login-btn { width: 100%; padding: 12px; background: linear-gradient(45deg, #0b63d3, #00d4ff); border: none; color: white; font-weight: bold; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .login-btn:hover { transform: scale(1.02); box-shadow: 0 0 20px rgba(11, 99, 211, 0.4); }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 style="text-align:center; margin-bottom: 30px;" class="hero-title">Member Login</h2>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" class="login-btn">Secure Login</button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 0.85rem;">
            <a href="signup.php" style="color: #00d4ff; text-decoration: none;">New here? Create Master Account</a>
        </div>
    </div>

</body>
</html>
<?php oci_close($conn); ?>