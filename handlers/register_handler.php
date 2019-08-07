<?php
$error_array = array();
$uname = "";
$fname = "";
$lname = "";
$email = "";
$date = "";
$password1 = "";
$password2 = "";

if(isset($_POST['register_button'])){

    $uname = strip_tags($_POST['register_username']);
    $uname = str_replace(" ","",$uname);
    $uname = strtolower($uname);
    $_SESSION['register_username'] = $uname;

    $fname = strip_tags($_POST['register_firstname']);
    $fname = str_replace(" ","",$fname);
    $_SESSION['register_firstname'] = $fname;

    $lname = strip_tags($_POST['register_lastname']);
    $lname = str_replace(" ","",$lname);
    $_SESSION['register_lastname'] = $lname;

    $email = strip_tags($_POST['register_email']);
    $email = str_replace(" ","",$email);
    $email = strtolower($email);
    $_SESSION['register_email'] = $email;

    $password1 = strip_tags($_POST['register_password_1']);
	$password2 = strip_tags($_POST['register_password_2']);

    $security_answer = strip_tags($_POST['security_answer']);
    $security_question = $_POST['security_question'];


    $date = date('Y-m-d');

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $email_check = mysqli_query($con,"SELECT email FROM users WHERE email='$email'");
        if(mysqli_num_rows($email_check)){

            array_push($error_array,"Email already exists");

        } else {

            if($password1 == $password2){

                $password1 = md5($password1);
                $uname_query = mysqli_query($con,"SELECT * FROM users WHERE username='$uname'");
                $i=0;

                while(mysqli_num_rows($uname_query)){
                    $i++;
                    $uname = $uname.$i;
                    $uname_query = mysqli_query($con,"SELECT * FROM users WHERE username='$uname'");
                }

                $registering = mysqli_query($con,"INSERT INTO users (username, firstname, lastname, email, password, dateOfJoining) VALUES ('$uname', '$fname', '$lname', '$email', '$password1', '$date')");

                mysqli_query($con, "INSERT INTO security_answers VALUES ('$uname', '$security_question', '$security_answer')");
                
                if($registering){
                    

                    echo "<div id='register-notice-box'>
                    <div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Registration Successfull</div>
                    </div>";
                    
                } else {
                    echo "<div id='register-notice-box'>
                    <div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOPs!</strong> something went Wrong</div>
                    </div>";
                }
            } else {

                array_push($error_array, "Passwords do not Match");

            }
        }
        

    } else {

        array_push($error_array, "Invalid Email");

    }

}

?>