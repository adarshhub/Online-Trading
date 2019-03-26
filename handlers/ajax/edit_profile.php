<?php

include("../../config/config.php");

if(isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['email'])){
    $email = $_POST['email'];
    $lastname = $_POST['lastname'];
    $firstname =$_POST['firstname'];

    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $email_check = mysqli_query($con,"SELECT email FROM users WHERE email='$email'");
    if(mysqli_num_rows($email_check) && $email != mysqli_fetch_array($email_check)['email']){
        
        echo "<div class='alert alert-danger alert-dismissible'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        <strong>OOPs!</strong> Email already in use</div>";
    } else {
        $username = $_SESSION['username'];
        $query = mysqli_query($con,"UPDATE users set email='$email', firstname='$firstname', lastname='$lastname' WHERE username='$username'" );
    }
}

?>