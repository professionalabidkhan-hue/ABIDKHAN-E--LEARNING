<?php
if (function_exists('oci_connect')) {
    echo "<h1>SUCCESS: Oracle Sentinel is ACTIVE!</h1>";
} else {
    echo "<h1>FAILURE: Sentinel is still LOCKED. Check php.ini.</h1>";
    phpinfo(); // This will help you see which extensions are actually loaded
}
?>