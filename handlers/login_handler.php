<?php

if(isset($_POST['login_button'])){
    $email = filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL);
    $password = md5($_POST['login_password']);
    $_SESSION['login_email'] = $email;
    $query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if(mysqli_num_rows($query)){
        $row = mysqli_fetch_array($query);
        $username = $row['username'];

        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        echo "email or password is incorrect";
    }
}




?>