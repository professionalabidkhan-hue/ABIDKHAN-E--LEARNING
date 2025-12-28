<?php
session_start();
require_once 'connect.php';

/**
 * DOCTOR OF IT - ACCESS CONTROL
 * Only the Founder can enter this vault.
 */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'trainer' || $_SESSION['category'] !== 'Founder') {
    header("Location: login.php?error=founder_only");
    exit();
}

/**
 * LIVE INTELLIGENCE QUERIES (Oracle FreeSQL)
 */
// 1. Total Population
$sql_total = "SELECT COUNT(*) AS TOTAL FROM AK_USERS";
$stmt_total = oci_parse($conn, $sql_total);
oci_execute($stmt_total);
$row_total = oci_fetch_array($stmt_total, OCI_ASSOC);
$total_population = $row_total['TOTAL'] ?? 0;

// 2. IT Engineering Population
$sql_it = "SELECT COUNT(*) AS TOTAL FROM AK_USERS WHERE DEPARTMENT = 'IT'";
$stmt_it = oci_parse($conn, $sql_it);
oci_execute($stmt_it);
$row_it = oci_fetch_array($stmt_it, OCI_ASSOC);
$it_count = $row_it['TOTAL'] ?? 0;

// 3. Quranic Faculty Population
$sql_quran = "SELECT COUNT(*) AS TOTAL FROM AK_USERS WHERE DEPARTMENT = 'QURAN'";
$stmt_quran = oci_parse($conn, $sql_quran);
oci_execute($stmt_quran);
$row_quran = oci_fetch_array($stmt_quran, OCI_ASSOC);
$quran_count = $row_quran['TOTAL'] ?? 0;

// Calculate Progress Percentages for the Cinematic UI
$it_percent = ($total_population > 0) ? ($it_count / $total_population) * 100 : 0;
$quran_percent = ($total_population > 0) ? ($quran_count / $total_population) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Command | Founder Abid Khan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --deep-bg: #07090d;
            --accent-gold: #ffd700;
            --it-blue: #00d2ff;
            --quran-green: #00ff87;
            --glass: rgba(255, 255, 255, 0.03);
        }
        body { 
            background: var(--deep-bg); 
            color: #fff; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: radial-gradient(circle at top right, #1a1c2c, #07090d);
        }
        .founder-header { padding: 60px 0; background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent); }
        .profile-ring { width: 120px; height: 120px; border: 3px solid var(--accent-gold); padding: 5px; margin: 0 auto 20px; border-radius: 50%; box-shadow: 0 0 30px rgba(255, 215, 0, 0.2); }
        .stat-card { background: var(--glass); border: 1px solid rgba(255,255,255,0.1); padding: 25px; border-radius: 20px; backdrop-filter: blur(10px); }
        .command-box { background: rgba(0,0,0,0.4); border: 1px solid var(--accent-gold); padding: 40px; border-radius: 30px; margin-top: 40px; }
        .form-control, .form-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 12px; }
        .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.1); color: #fff; border-color: var(--accent-gold); box-shadow: none; }
        .status-indicator { color: #00ff00; animation: blink 2s infinite; font-size: 0.8rem; }
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
        .hub-btn { border-radius: 12px; padding: 12px 25px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: 0.4s; }
        .btn-it { background: var(--it-blue); color: #000; }
        .btn-quran { background: var(--quran-green); color: #000; }
        .btn-warning { background: var(--accent-gold); color: #000; border: none; }
        .audit-log-item { background: rgba(255,255,255,0.03); padding: 10px 15px; border-left: 3px solid var(--accent-gold); margin-bottom: 10px; font-family: 'Courier New', monospace; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="founder-header text-center">
    <div class="profile-ring">
        <img src="ABID KHAN.png" class="rounded-circle w-100 h-100" style="object-fit: cover;" alt="Founder">
    </div>
    <h1 class="fw-bold text-white mb-1">FOUNDER COMMAND CENTER</h1>
    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
        <span class="status-indicator"><i class="fas fa-circle me-1 small"></i> System Live</span>
        <p class="text-warning text-uppercase mb-0 fw-bold small">Abid Khan Hub Global Oversight</p>
    </div>
    
    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="DASHBOARDITTRAINER.php" class="btn hub-btn btn-it"><i class="fas fa-microchip me-2"></i> IT Wing</a>
        <a href="DASHBOARDQURANTRAINER.php" class="btn hub-btn btn-quran"><i class="fas fa-book-quran me-2"></i> Quran Faculty</a>
    </div>
</div>

<div class="container py-5">
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted d-block mb-1">TOTAL HUB POPULATION</small>
                <h2 class="fw-bold mb-0"><?php echo number_format($total_population); ?></h2>
                <div class="text-success small mt-2"><i class="fas fa-sync fa-spin me-1"></i> Oracle Live</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted d-block mb-1">IT ENGINEERING</small>
                <h2 class="fw-bold text-info mb-0"><?php echo $it_count; ?></h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar bg-info" style="width: <?php echo $it_percent; ?>%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted d-block mb-1">ISLAMIC STUDIES</small>
                <h2 class="fw-bold text-success mb-0"><?php echo $quran_count; ?></h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar bg-success" style="width: <?php echo $quran_percent; ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="command-box">
        <h4 class="mb-4 text-warning fw-bold"><i class="fas fa-user-edit me-2"></i> Distinguished Data Entry</h4>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="small text-muted mb-1">Student Full Name</label>
                <input type="text" id="manual_name" class="form-control" placeholder="Identity Name">
            </div>
            <div class="col-md-6">
                <label class="small text-muted mb-1">Master Identity (Email)</label>
                <input type="email" id="manual_email" class="form-control" placeholder="email@hub.com">
            </div>
            <div class="col-md-6">
                <label class="small text-muted mb-1">Faculty Track</label>
                <select id="manual_track" class="form-select">
                    <option value="IT">IT Engineering</option>
                    <option value="QURAN">Quran Faculty</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="small text-muted mb-1">Vault Status</label>
                <select id="manual_status" class="form-select">
                    <option value="ACTIVE">ACTIVE</option>
                    <option value="PENDING">PENDING</option>
                </select>
            </div>
        </div>
        <button class="btn btn-warning w-100 mt-4 fw-bold py-3" onclick="processManualEntry()">
            COMMIT TO FREE SQL VAULT
        </button>
    </div>
    
    <div class="mt-5">
        <h6 class="text-muted mb-3 text-uppercase small">Intelligence Audit Log</h6>
        <div id="auditLogs">
            <div class="audit-log-item text-muted">Awaiting Founder commands...</div>
        </div>
    </div>
</div>

<script>
/**
 * ASYNCHRONOUS DATA COMMIT
 * Communicates with founder_process.php to update Oracle FreeSQL
 */
async function processManualEntry() {
    const name = document.getElementById('manual_name').value;
    const email = document.getElementById('manual_email').value;
    const track = document.getElementById('manual_track').value;
    const status = document.getElementById('manual_status').value;

    if(!name || !email) {
        alert("Founder, please provide the Student Name and Email.");
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('track', track);
    formData.append('status', status);

    try {
        const response = await fetch('founder_process.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();

        if(result.success) {
            // Update UI Log
            const logBox = document.getElementById('auditLogs');
            const time = new Date().toLocaleTimeString();
            const entry = `
                <div class="audit-log-item">
                    <span class="text-success fw-bold">[DATABASE]</span> Committed: ${name} (${track}) 
                    <br><small class="text-muted">${time}</small>
                </div>`;
            logBox.insertAdjacentHTML('afterbegin', entry);
            
            // Success Feedback
            alert(result.message);
            
            // Clear inputs for next entry
            document.getElementById('manual_name').value = '';
            document.getElementById('manual_email').value = '';
        } else {
            alert("VAULT ERROR: " + result.message);
        }
    } catch (error) {
        alert("SENTINEL: Connection to Oracle failed.");
    }
}
</script>
</body>
</html>