<?php
session_start();
include "./php/get_data.php";
 
// Check if the user is logged in
if (isset($_SESSION['id'])) {
    $data = getDataById($_SESSION['id']);
} else {
    $data = ["id" => '', "firstname" => '',"lastname" => '', "email" => '', "password" => '', "role" => "user"];
}
?>