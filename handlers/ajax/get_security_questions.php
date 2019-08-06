<?php
require("../../config/config.php");

$query = mysqli_query($con, "SELECT question FROM security_questions");

$questions = array();

while($row = mysqli_fetch_array($query)){
	array_push($questions, $row[0]);
}

echo json_encode($questions);

?>