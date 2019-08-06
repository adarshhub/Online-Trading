<!DOCTYPE html>
<html>
<head>
	<title>Security Check</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
	<style type="text/css">
		.wrapperL{
			margin-left: auto;
			margin-right: auto;
			max-width: 600px;
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
<body >
	<div class="wrapperL">
<h1 class="display-2">Forgot Password</h1>
    <div class="form-group">
        <label>Email:</label>
        <input class="form-control" id="forgot_email" />
    </div>
    <input type="button" value="Find" class="btn btn-primary" onclick="findEmail()" />
    <br />
    <a href="register.php" >Back To Login</a>
    <br />
    <br />
     <h2 class="h2" id="forgot_username"></h2>
    <div id="security_box" class="card border-secondary p-3" hidden>
        <div class="form-group">
            <label id="security_question"></label>
            <input class="form-control" id="security_answer" />
        </div>
        <input type="button" value="Reset" class="btn btn-warning" onclick="checkAnswer()" />
    </div>
    </div>
    <div id="msg_box"></div>
    <script>
        var email;
        var username;

        function findEmail() {
            email = document.getElementById('forgot_email').value;
            var security_box = document.getElementById('security_box');
            var security_question = document.getElementById('security_question');

            $.ajax({
                url: './handlers/ajax/checkEmail.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function (data) {
                	var username_field = document.getElementById('forgot_username');
                    if (data == false) {
                        username_field.innerText = "Not Found";
                        security_box.hidden = true;
                    } else {
                        security_question.innerText = data["question"];
                        username = data["username"];
                        username_field.innerText = data["username"];
                        security_box.hidden = false;
                    }
                }
            });
        }


        function checkAnswer() {

            var security_answer = document.getElementById('security_answer').value;
            
            $.ajax({
                url: './handlers/ajax/check_security_answer.php',
                method: 'POST',
                data: { username: username, answer: security_answer},
                dataType: 'json',
                success: function (data) {

                	console.log(data);
                    
                    if (data == false) {
                        document.getElementById('msg_box').innerHTML = '<div class="alert alert-dismissible alert-warning"  > <button type="button" class="close" data-dismiss="alert">&times;</button><h4 class="alert-heading">Not Successfull!</h4><p class="mb-0">Incorrect</p></div>';
                    } else {
                        window.location.href = "reset_password.php";
                    }
                }
            });
        }
    </script>
</body>
</html>