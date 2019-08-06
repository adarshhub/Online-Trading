<?php

require("../../config/config.php");

if(isset($_POST['email'])){
	$email = $_POST['email'];

	$query = mysqli_query($con,"SELECT username FROM users WHERE email='$email'");

	if(mysqli_num_rows($query) > 0){

		$row = mysqli_fetch_array($query);
		$username = $row[0];

		$query = mysqli_query($con,"SELECT question FROM security_answers INNER JOIN security_questions ON security_answers.question_id = security_questions.id WHERE username = '$username'");

		$row = mysqli_fetch_array($query);
		$question = $row[0];

		$data = new \stdClass();

		$data->username = $username;
		$data->question = $question;

		echo json_encode($data);

	} else {
		echo "false";
	}
}

?>