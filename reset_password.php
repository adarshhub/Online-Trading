<?php
require('config/config.php');

if(!isset($_SESSION['edit'])){
	header("Location: security_check.php");
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <style type="text/css">
    	.wrapper{
    		max-width: 600px;
    		margin-left: auto;
    		margin-right: auto;
    	}

    	#msg_box{
		    width: 400px;
		    bottom: 0;
		    position: absolute;
		    left: 50%;
		    transform: translateX(-50%);
		}
    </style>
</head>
<body>
	<div class="wrapper">
		<h1 class="display-2">Reset Password</h1>
    <div class="form-inline form-group mb-2" id="new_password_form">
        <label class="col-sm-5">New Password:</label>
        <input id="new_password" class=" form-control col-sm-6" />
        <div class="invalid-feedback" id="new_password_invalid"></div>
        <div class="valid-feedback" id="new_password_valid">Valid! Password</div>
    </div>
    <div class="form-inline form-group mb-2" id="confirm_password_form">
        <label class="col-sm-5">Confirm Password:</label>
        <input id="confirm_password" class=" form-control col-sm-6" type="password" />
        <div class="invalid-feedback" id="confirm_password_invalid">Password do not match</div>
        <div class="valid-feedback" id="confirm_password_valid">Password Matched</div>
    </div>
    <input type="button" id="change_password_btn" class="btn btn-danger" value="Change" style="float:right; margin-right: 66px;" onclick="resetPassowrd()"/>
    <a  href="register.php" >Go To Login</a>

 	<div id="msg_box"></div>
	</div>

	   <script type="text/javascript">

        function checkPassword(input) {
            var pass_pattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/;
            if (input.match(pass_pattern)) {
                return true;
            } else {
                return false;
            }
        }

        var new_password = document.getElementById('new_password');
        var confirm_password = document.getElementById('confirm_password');

 </script>
    <script src="assets/js/checkPassword.js"></script>
    <script>
        function resetPassowrd() {

            $.ajax({
                url: './handlers/ajax/new_password.php',
                method: 'POST',
                dataType: 'json',
                data: { password: new_password.value },
                success: function (data) {

                    var msg_box = document.getElementById('msg_box');

                    if (data == "false") {
                        msg_box.innerHTML = '<div class="alert alert-dismissible alert-warning"  ><h4 class="alert-heading">Not Successfull!</h4><p class="mb-0">Cannot Changed</p></div>';
                    } else {
                        msg_box.innerHTML = '<div class="alert alert-dismissible alert-success"  ><h4 class="alert-heading">Successfull!</h4><p class="mb-0">Password Changed</p></div>';
                    }
                }
            })
        }
        
    </script>
</body>
</html>