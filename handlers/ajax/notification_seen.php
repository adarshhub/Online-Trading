<?php
require_once("../../config/config.php");

$username = $_SESSION['username'];
mysqli_query($con,"UPDATE notify_users SET seen_all = 0 WHERE username = '$username'");

?>