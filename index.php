<?php
require("config/config.php");
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
} else {
    header("Location: register.php");
}
?>

<!DOCTYPE html>

<head>
    <title>Online Trading</title>   

    <!-- JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
</head>
<body>
    <div id="navigation">
        <?php include "includes/header/header.php"; ?>
    </div>
</body>
</html>