<?php

include("../../config/config.php");

if(isset($_POST['oldPassword']) && isset($_POST['newPassword'])){

    $username = $_SESSION['username'];
    $newPassword = $_POST['newPassword'];
    $newPassword = md5($newPassword);
    $oldPassword = $_POST['oldPassword'];
    $oldPassword = md5($oldPassword);

    $check_password = mysqli_query($con, "SELECT password FROM users WHERE username='$username'");
    if($oldPassword == mysqli_fetch_array($check_password)['password']){

        mysqli_query($con,"UPDATE users SET password='$newPassword' WHERE username='$username'");

    } else {
        echo "<div class='alert alert-danger alert-dismissible'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        <strong>OOPs!</strong> Incorrect Password</div>";
    }
}

?>
