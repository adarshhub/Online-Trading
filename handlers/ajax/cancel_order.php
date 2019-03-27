<?php
include("../../config/config.php");
require("../../config/trade.php");

$error_msg = "<div class='alert alert-warning alert-dismissible'>
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
<strong>OOPs!</strong> Something Went Wrong</div>";

if(isset($_POST['asset']) && isset($_POST['type']) && isset($_POST['rate']) && isset($_POST['volume'])){

    $asset = $_POST['asset'];
    $type = $_POST['type'];
    $rate = $_POST['rate'];
    $volume = $_POST['volume'];
    $username = $_SESSION['username'];

    delete_order($asset, $type, $username, $volume, $rate);
    return;
    
} else {
    echo $error_msg;
}

?>