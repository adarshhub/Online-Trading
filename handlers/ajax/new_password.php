<?php
require('../../config/config.php');

if(isset($_SESSION['edit']) && $_POST['password']){

	$username = $_SESSION['edit'];

	$new_password = strip_tags($_POST['password']);
	$new_password = md5($new_password);

	$query = mysqli_query($con,"UPDATE users SET password = '$new_password' WHERE username = '$username'");

	if($query){
		unset($_SESSION['edit']);
		echo "true";
	} else {
		echo "false";
	}

}

?>