<!DOCTYPE html>
<html>
<head>
    <title>AK HUB - Vault Connectivity Test</title>
</head>
<body>
    <h2>Sovereign OTP Test</h2>
    <input type="email" id="testEmail" placeholder="Enter Email to Test">
    <button onclick="triggerOtpStrike()">Strike the Vault</button>

    <p id="result"></p>

    <script>
        async function triggerOtpStrike() {
            const email = document.getElementById('testEmail').value;
            const response = await fetch('connect_hub.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'send_otp',
                    email: email
                })
            });
            const result = await response.json();
            document.getElementById('result').innerText = JSON.stringify(result, null, 2);
        }
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