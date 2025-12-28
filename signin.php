<?php
session_start();
// If already signed in, bypass the gate
if (isset($_SESSION['user_id'])) {
    $path = ($_SESSION['user_role'] === 'trainer') ? 'DASHBOARD_MASTER_TRAINER.php' : 'DASHBOARD_STUDENTS_FANTASTIC.php';
    header("Location: $path");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Gatekeeper | ABID KHAN HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4fc3f7; --dark-bg: #07090d; --border: rgba(255, 255, 255, 0.1); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: radial-gradient(circle at bottom left, #1a1c2c, #07090d); color: #fff; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; user-select: none; }
        .signin-card { background: rgba(13, 17, 23, 0.8); backdrop-filter: blur(25px); border: 1px solid var(--border); border-radius: 35px; padding: 50px; width: 450px; text-align: center; }
        .master-label { font-size: 0.7rem; font-weight: 800; color: var(--primary-blue); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; display: block; text-align: left; }
        .form-control { background-color: #000 !important; border: 1px solid var(--border); color: #fff !important; border-radius: 12px; padding: 14px; margin-bottom: 25px; }
        .btn-gate { background: var(--primary-blue); color: #000; font-weight: 800; border: none; padding: 18px; border-radius: 15px; width: 100%; text-transform: uppercase; cursor: pointer; }
        .loading-shimmer { opacity: 0.5; animation: pulse 1.5s infinite; }
        @keyframes pulse { 50% { opacity: 1; } }
    </style>
</head>
<body>

<div class="signin-card">
    <div class="mb-4">
        <h2 style="font-weight:800; letter-spacing: 2px;">ABID KHAN HUB</h2>
        <div class="text-info small fw-bold">VAULT ACCESS</div>
    </div>
    <form id="signin-form">
        <span class="master-label">Master Identity (Email)</span>
        <input type="email" id="signin-email" class="form-control" placeholder="yourname@hub.com" required>
        
        <span class="master-label">Secure Access Key</span>
        <input type="password" id="signin-password" class="form-control" placeholder="••••••••" required>
        
        <button type="button" id="gate-btn" class="btn-gate">Verify Identity</button>
    </form>
    <p class="mt-4 small text-secondary">New Seeker? <a href="signup.php" class="text-info text-decoration-none fw-bold">Request Entry</a></p>
</div>

<script>
    document.getElementById('gate-btn').onclick = async () => {
        const btn = document.getElementById('gate-btn');
        const email = document.getElementById('signin-email').value;
        const password = document.getElementById('signin-password').value;

        if(!email || !password) {
            alert("VAULT ERROR: Identification required.");
            return;
        }

        btn.innerText = "VERIFYING...";
        btn.classList.add('loading-shimmer');

        try {
            const res = await fetch('signin_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const result = await res.json();

            if(result.success) {
                // The portal opens... redirecting based on Oracle Role
                window.location.href = result.redirect; 
            } else {
                alert("VAULT DENIED: " + result.message);
                btn.innerText = "Verify Identity";
                btn.classList.remove('loading-shimmer');
            }
        } catch (err) {
            alert("SENTINEL ERROR: Connection failed.");
            btn.innerText = "Verify Identity";
            btn.classList.remove('loading-shimmer');
        }
    };

    // SECURITY PROTOCOLS
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.onkeydown = (e) => {
        if(e.keyCode == 123 || (e.ctrlKey && e.shiftKey && [73, 67, 74].includes(e.keyCode)) || (e.ctrlKey && e.keyCode == 85)) return false;
    };
</script>
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
</body>
</html>