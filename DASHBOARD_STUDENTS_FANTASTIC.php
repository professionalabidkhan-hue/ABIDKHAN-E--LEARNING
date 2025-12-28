<?php
session_start();
require_once 'connect.php'; // Secured Oracle Bridge

// 1. INSTITUTE ACCESS CONTROL
if (!isset($_SESSION['user_id']) || !isset($_SESSION['category'])) {
    header("Location: signin.php?unauthorized");
    exit();
}

$user_id = $_SESSION['user_id'];
$name    = $_SESSION['user_name'];
$category = $_SESSION['category']; // 'IT Student' or 'Holy Quran Recitation'

// 2. ADAPTIVE THEME ENGINE (Cyber Blue vs Sacred Green)
$isIT = ($category === 'IT Student');
$primary_glow = $isIT ? "#00d4ff" : "#2ecc71"; 
$accent_gold  = $isIT ? "#7000ff" : "#fbbf24"; 
$bg_canvas    = $isIT 
    ? "radial-gradient(circle at bottom left, #0d1117, #010409)" 
    : "radial-gradient(circle at bottom left, #051b07, #010409)";

// 3. DEPARTMENT CONTENT LOGIC
$portal_title = $isIT ? "IT MASTERY HUB" : "QURANIC STUDIES HUB";
$phase_label  = $isIT ? "Current Sprint" : "Current Surah";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $portal_title; ?> | ABID KHAN INSTITUTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --accent: <?php echo $primary_glow; ?>; --glow-dim: <?php echo $primary_glow; ?>22; }
        body { 
            background: <?php echo $bg_canvas; ?>; color: #e2e8f0; 
            font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; overflow-x: hidden; 
        }
        
        /* NEON SIDEBAR */
        .sidebar { 
            width: 270px; height: 100vh; position: fixed; 
            background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(30px);
            border-right: 1px solid rgba(255,255,255,0.05); padding: 40px 20px; z-index: 1000;
        }
        .nav-link { 
            color: #94a3b8; padding: 14px 18px; border-radius: 15px; margin-bottom: 8px; 
            transition: 0.4s; display: flex; align-items: center; text-decoration: none;
        }
        .nav-link:hover, .nav-link.active { 
            background: var(--glow-dim); color: var(--accent); 
            transform: translateX(8px);
        }

        /* MAIN STAGE */
        .main-stage { margin-left: 270px; padding: 50px; }
        .glass-panel { 
            background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255,255,255,0.08); 
            border-radius: 30px; padding: 35px; backdrop-filter: blur(15px);
        }

        /* BROADCAST BOX (ORACLE SYNC) */
        .broadcast-alert {
            background: linear-gradient(90deg, <?php echo $primary_glow; ?>33, transparent);
            border-left: 4px solid var(--accent); padding: 20px; border-radius: 12px; margin-bottom: 40px;
        }

        /* STUDY WORKSTATION SECURITY */
        @media print { body { display:none; } }
        .viewport-frame {
            width: 100%; height: 650px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);
            background: #000; overflow: hidden; pointer-events: none; user-select: none;
        }

        .stat-card { text-align: center; padding: 25px; border-radius: 20px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); }
        .stat-val { font-size: 2.2rem; font-weight: 800; color: var(--accent); }
    </style>
</head>
<body oncontextmenu="return false;">

<div class="sidebar">
    <div class="mb-5 px-3">
        <h5 class="fw-bold text-white mb-0">ABID KHAN</h5>
        <span class="small fw-bold text-uppercase" style="color: var(--accent); font-size: 0.65rem; letter-spacing: 2px;">E-Learning Institute</span>
    </div>

    <nav>
        <a href="#" class="nav-link active"><i class="fas fa-columns me-2"></i> Dashboard</a>
        <a href="#" class="nav-link"><i class="fas <?php echo $isIT ? 'fa-laptop-code' : 'fa-book-open'; ?> me-2"></i> Resources</a>
        <a href="#" class="nav-link"><i class="fas fa-medal me-2"></i> Grades</a>
        <a href="logout.php" class="nav-link text-danger mt-5"><i class="fas fa-power-off me-2"></i> Logoff Vault</a>
    </nav>
</div>

<div class="main-stage">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h6 class="text-secondary fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Disciple Access Granted</h6>
            <h1 class="fw-extrabold display-5 text-white"><?php echo explode(' ', $name)[0]; ?>.</h1>
        </div>
        <div class="text-end">
            <div class="small text-secondary mb-1">INSTITUTE CLOCK</div>
            <h5 class="fw-bold" id="hubClock" style="color: var(--accent);">00:00:00</h5>
        </div>
    </div>

    <div class="broadcast-alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-broadcast-tower me-3 text-white"></i>
            <span class="fw-bold text-white small uppercase">Faculty Command Directive</span>
        </div>
        <p class="mb-0" id="broadcastText" style="font-style: italic; color: #fff; transition: 0.5s;">Connecting to Command Center...</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4"><div class="stat-card"><div class="small text-secondary">ATTENDANCE</div><div class="stat-val">94%</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="small text-secondary">PROGRESS</div><div class="stat-val">78%</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="small text-secondary">INSTITUTE CREDITS</div><div class="stat-val" style="color: <?php echo $accent_gold; ?>;">1,250</div></div></div>
    </div>

    <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="fas fa-microchip me-2"></i> Secure Workstation</h4>
            <span class="badge rounded-pill px-3 py-2 bg-dark border border-secondary">
                <?php echo $phase_label; ?>: <?php echo $isIT ? "Oracle Apex Mastery" : "Surah Al-Mulk"; ?>
            </span>
        </div>
        <div class="viewport-frame">
            <iframe src="<?php echo $isIT ? 'assets/it_curriculum.pdf' : 'assets/quran_sabaq_sheet.pdf'; ?>" 
                    width="100%" height="100%" style="border:none;"></iframe>
        </div>
    </div>
</div>

<script>
    // 1. ORACLE LIVE DIRECTIVE SYNC
    async function fetchInstituteDirective() {
        try {
            const response = await fetch('fetch_directive.php');
            const data = await response.json();
            const box = document.getElementById('broadcastText');
            
            if(data.message && box.innerText !== data.message) {
                box.style.opacity = 0;
                setTimeout(() => {
                    box.innerText = data.message;
                    box.style.opacity = 1;
                    box.style.color = "#fbbf24"; // Highlight new directive in gold
                }, 500);
            }
        } catch (err) { console.log("Signal Interrupted"); }
    }
    setInterval(fetchInstituteDirective, 5000); // Poll every 5 seconds
    fetchInstituteDirective();

    // 2. INSTITUTE CLOCK
    function updateClock() {
        const now = new Date();
        document.getElementById('hubClock').innerText = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);

    // 3. SECURITY PROTOCOLS
    document.onkeydown = function(e) {
        if(e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || (e.ctrlKey && e.keyCode == 85)) return false;
    };
</script>
</body>
</html>