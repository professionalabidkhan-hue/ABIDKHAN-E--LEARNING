<?php
if (function_exists('oci_connect')) {
    echo "<h2 style='color:cyan;'>Master, the Oracle Sentinel is ACTIVE!</h2>";
} else {
    echo "<h2 style='color:red;'>Sentinel is still LOCKED. Did you restart Apache?</h2>";
    phpinfo();
}
?>