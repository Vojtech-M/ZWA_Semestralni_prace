<?php
/*
    * Check if the user is logged in
    * 
    * 
*/
session_start();
include "./php/get_data.php";
 
// Check if the user is logged in
if (isset($_SESSION['id'])) {
    $user = getDataById($_SESSION['id']);
}
?>