<?php
session_start();
require_once 'connect.php'; // Secured Oracle Bridge

// 1. ELITE ACCESS CONTROL
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'trainer') {
    header("Location: signin.php?vault_locked");
    exit();
}

$user_id = $_SESSION['user_id'];
$name    = $_SESSION['user_name'];

// 2. FACULTY DETECTION & THEME ENGINE
$sql = "SELECT DEPARTMENT FROM AK_USERS WHERE USER_ID = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':id', $user_id);
oci_execute($stmt);
$user_data = oci_fetch_array($stmt, OCI_ASSOC);

$dept = $user_data['DEPARTMENT'];
$_SESSION['user_dept'] = $dept; // Persist for broadcast logic
$isIT = ($dept === 'IT');

// Define Masterpiece Aesthetics
$primary_glow = $isIT ? "#00d4ff" : "#2ecc71"; 
$secondary    = $isIT ? "#7000ff" : "#fbbf24"; 
$bg_overlay   = $isIT 
    ? "radial-gradient(circle at top right, #0d1117, #010409)" 
    : "radial-gradient(circle at top right, #051b07, #010409)";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Trainer | <?php echo $dept; ?> Command | ABID KHAN INSTITUTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --accent: <?php echo $primary_glow; ?>; --glow-soft: <?php echo $primary_glow; ?>33; }
        body { background: <?php echo $bg_overlay; ?>; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; overflow-x: hidden; }
        
        .sidebar { 
            width: 280px; height: 100vh; position: fixed; 
            background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(25px);
            border-right: 1px solid rgba(255,255,255,0.05); padding: 40px 20px; z-index: 1000;
        }
        .nav-link { 
            color: #94a3b8; padding: 15px; border-radius: 12px; margin-bottom: 10px; 
            transition: 0.4s; display: flex; align-items: center; text-decoration: none;
        }
        .nav-link:hover, .nav-link.active { 
            background: var(--glow-soft); color: var(--accent); 
            box-shadow: inset 4px 0 0 var(--accent); transform: translateX(5px);
        }

        .main-stage { margin-left: 280px; padding: 50px; }
        .glass-card { 
            background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255,255,255,0.08); 
            border-radius: 25px; padding: 30px; transition: 0.3s;
        }
        .glass-card:hover { border-color: var(--accent); box-shadow: 0 0 30px var(--glow-soft); }

        .faculty-badge { font-size: 0.65rem; letter-spacing: 2.5px; color: var(--accent); font-weight: 800; text-transform: uppercase; }
        .stat-num { font-size: 2.8rem; font-weight: 800; color: #fff; }
        
        /* Command Input Styling */
        .command-area { background: #000 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--accent) !important; font-family: 'Courier New', monospace; border-radius: 15px; }
        .btn-broadcast { background: var(--accent); color: #000; font-weight: 800; border-radius: 15px; transition: 0.3s; }
        .btn-broadcast:hover { transform: scale(1.02); box-shadow: 0 0 20px var(--accent); }
    </style>
</head>
<body oncontextmenu="return false;">

<div class="sidebar">
    <div class="mb-5 px-3">
        <div class="faculty-badge mb-1"><?php echo $dept; ?> Faculty</div>
        <h5 class="fw-bold text-white">ABID KHAN</h5>
        <span class="small text-secondary fw-bold" style="font-size: 0.6rem;">E-LEARNING INSTITUTE</span>
    </div>

    <nav>
        <a href="#" class="nav-link active"><i class="fas fa-terminal me-3"></i> Intelligence Desk</a>
        <a href="#" class="nav-link"><i class="fas fa-users me-3"></i> Student Roster</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-line me-3"></i> Institute Metrics</a>
        <a href="logout.php" class="nav-link text-danger mt-5"><i class="fas fa-power-off me-3"></i> Exit Command</a>
    </nav>
</div>

<div class="main-stage">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h6 class="text-secondary fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Faculty Command Authorization</h6>
            <h1 class="fw-bold display-6">Master Trainer <?php echo explode(' ', $name)[0]; ?>.</h1>
        </div>
        <div class="glass-card py-2 px-4 d-flex align-items-center border-success">
            <div class="bg-success rounded-circle me-2 pulse" style="width:10px; height:10px; box-shadow: 0 0 10px #2ecc71;"></div>
            <span class="small fw-bold">ORACLE VAULT CONNECTED</span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4"><div class="glass-card text-center"><p class="text-secondary small mb-1">TOTAL DISCIPLES</p><div class="stat-num">42</div></div></div>
        <div class="col-md-4"><div class="glass-card text-center"><p class="text-secondary small mb-1">PENDING TASKS</p><div class="stat-num">07</div></div></div>
        <div class="col-md-4"><div class="glass-card text-center"><p class="text-secondary small mb-1">INSTITUTE SCORE</p><div class="stat-num" style="color: <?php echo $secondary; ?>;">4.9</div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="glass-card h-100">
                <h4 class="fw-bold mb-4">Disciple Registry</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle border-0 small">
                        <thead class="text-secondary">
                            <tr><th>IDENTITY</th><th>LAST LOGIN</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-circle-user me-2 text-info"></i> Student_01</td>
                                <td>2 Hours Ago</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success">Verified</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle-user me-2 text-info"></i> Student_02</td>
                                <td>Just Now</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success">Verified</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="glass-card h-100 border-info">
                <h4 class="fw-bold mb-2">Institute Memo</h4>
                <p class="small text-secondary mb-4">Directives update all <?php echo $dept; ?> student dashboards live.</p>
                
                <textarea id="directiveText" class="form-control command-area mb-3" rows="6" placeholder="[Enter System Directive...]"></textarea>
                
                <button onclick="broadcastDirective()" class="btn btn-broadcast w-100 py-3">
                    <i class="fas fa-paper-plane me-2"></i> EXECUTE BROADCAST
                </button>
                <div id="broadcastStatus" class="mt-3 small text-center fw-bold"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // AJAX ORACLE BROADCAST
    async function broadcastDirective() {
        const text = document.getElementById('directiveText').value;
        const statusBox = document.getElementById('broadcastStatus');
        
        if(!text) return alert("Enter a directive, Master.");

        statusBox.innerHTML = "Accessing Oracle...";
        
        try {
            const response = await fetch('broadcast_process.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `directive=${encodeURIComponent(text)}`
            });
            const data = await response.json();
            
            if(data.status === 'success') {
                statusBox.className = "mt-3 small text-center fw-bold text-success";
                statusBox.innerHTML = "DIRECTIVE SECURED IN VAULT";
                document.getElementById('directiveText').value = "";
            } else {
                statusBox.className = "mt-3 small text-center fw-bold text-danger";
                statusBox.innerHTML = "VAULT ERROR: " + data.message;
            }
        } catch (err) {
            statusBox.innerHTML = "Connection Failure.";
        }
    }

    // Security Lock
    document.onkeydown = function(e) {
        if(e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || (e.ctrlKey && e.keyCode == 85)) return false;
    };
</script>
</body>
</html>