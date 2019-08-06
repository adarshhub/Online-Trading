<?php

require('../../config/config.php');

if(isset($_POST['username']) && isset($_POST['answer'])){

	$username = $_POST['username'];
	$answer = $_POST['answer'];

	$query = mysqli_query($con,"SELECT answer FROM security_answers WHERE username = '$username'");
	$row = mysqli_fetch_array($query);

	$correct_answer = $row[0];

	if($answer == $correct_answer){
		$_SESSION['edit'] = $username;
		echo "true";
	} else {
		echo "false";
	}
}

?>