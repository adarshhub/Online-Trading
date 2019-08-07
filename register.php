<?php
require 'config/config.php';
include 'handlers/login_handler.php';
include 'handlers/register_handler.php';

?>


<!DOCTYPE html>
<head>
    <title>Register Youself!</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css"  href="assets/css/register.css" />


    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/register.js"></script>
</head>
<body>

    <?php
        if(isset($_POST['register_button'])){
            if($error_array){
                foreach($error_array as $error){
                    echo "<div id='register-notice-box'>
                <div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOPs!</strong> $error</div>
                </div>";
                }
            } 
        }
    ?>
    <div class="wrapper">
        <div class="login-header">
            <h2>P2P-Trade</h2>
            Trade Online from Anywhere
        </div>
        <div id="login-box">
            <form method="POST" action="register.php">
                <input type="text" name="login_email" required placeholder="Email">
                <br>
                <input type="password" name="login_password" required placeholder="Password">
                <br>
                <button type="submit" name="login_button">Login</button>
                <br>
                <a href="#" id="gotoSignup">New Here. SignUp For FREE!</a>
                <a href="security_check.php" id="forgotPassword">Forgot Password</a>
            </form>
        </div>
        <div id="register-box">
            <form method="POST" action="register.php">
                <input type="text" name="register_username" required placeholder="Username" value="<?php if(isset($_SESSION['register_username'])){
                   echo $_SESSION['register_username']; } ?>">
                <br>
                <input type="text" name="register_firstname" required placeholder="Firstname" value="<?php if(isset($_SESSION['register_firstname'])){
                   echo $_SESSION['register_firstname']; } ?>">
                <br>
                <input type="text" name="register_lastname" required placeholder="Lastname" value="<?php if(isset($_SESSION['register_lastname'])){
                   echo $_SESSION['register_lastname']; } ?>">
                <br>
                <input type="text" name="register_email" required placeholder="Email" value="<?php if(isset($_SESSION['register_email'])){
                   echo $_SESSION['register_email']; } ?>">
                <br>
                <input type="password" name="register_password_1" required placeholder="Password">
                <br>
                <input type="password" name="register_password_2" required placeholder="Confirm Password">
                <br>
                <select id="security_question" name="security_question" style="margin-bottom: 5px"></select>
                <br>
                <input type="text" name="security_answer" required placeholder="Security Answer">
                <br>
                <button type="Submit" name="register_button">Register</button>
                <br>
                <a href="#" id="gotoLogin">Already a Member? Login.</a>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript">
    
    $.ajax({
        url: "handlers/ajax/get_security_questions.php",
        method: "GET",
        dataType: 'json',
        success: function(data){
            var html;
            var questions_dropdown = document.getElementById('security_question');
            for (var i = 0; i < data.length; i++) {
                html = '<option value="'+(i+1)+'">'+data[i]+'</option>';

                questions_dropdown.insertAdjacentHTML('beforeend', html);
            }
        }
    })
</script>
</html>