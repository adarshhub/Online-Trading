<?php
require("config/config.php");
if (isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    echo "<script>userLoggedIn = '$userLoggedIn';</script>";
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
    <script src="assets/js/script.js"></script>

	<!-- CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel='stylesheet' type='text/css' href='assets/css/style.css' >
</head>
<body>
    <div id="navigation">
        <nav>
            <a href="#" id="logo">On-Trade</a>
            <a href="#" onclick="logout()">Logout</a>
            <a href="#" onclick="openPage('account.php')">Account</a>
            <a href="#" onclick="openPage('index.php')">Market</a>
            <a href="#">Home</a>
        </nav>
    </div>

    <div id="main-container"class="wrapper">

