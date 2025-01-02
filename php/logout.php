<?php
/**
 * Logout
 * 
 * This script logs the user out by unsetting and destroying the session.
 */
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>