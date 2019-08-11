<?php
require("config/config.php");
if (isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    echo "<script>userLoggedIn = '$userLoggedIn';</script>";
} else {
    header("Location: register.php");
}

$username =$_SESSION['username'];

$output = '';
$unseen_notification = 0;

$query = mysqli_query($con, "SELECT seen_all FROM notify_users WHERE username = '$username'");

if(mysqli_num_rows($query)){

    $row = mysqli_fetch_array($query);
    $unseen_notification = $row[0];

    if($unseen_notification != 0){

        $query = mysqli_query($con, "SELECT msg FROM notifications WHERE for_username = '$username' ORDER BY time DESC LIMIT $unseen_notification ");

        if(mysqli_num_rows($query) > 0){

            while($row = mysqli_fetch_array($query)){
                $output .= '<li class="w-100 jus"><h5>'.$row[0].'</h5></li>';
            }

        } else {
            $output .= '<li >No notifications</li>'; 
        }
    } else {
        $output .= '<li >No notifications</li>';
    }

} else {
    $output .= '<li>No notifications</li>';
}

?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
    <title>Online Trading</title>   

	<!-- CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel='stylesheet' type='text/css' href='assets/css/style.css' >

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#" >P2P Trade</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
                    </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a href="#" onclick="openPage('index.php')">Market</a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="openPage('account.php')">Account</a>
                    
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                 <li><a href="#" onclick="logout()">Logout</a></li>
                 <li class="dropdown" onclick="notificationSeen()">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-pill badge-danger count" id="unseen_notification" style="border-radius:10px;"><?php if($unseen_notification){ echo $unseen_notification; } ?></span> <span class="glyphicon glyphicon-bell" style="font-size:18px;"></span></a>
                      <ul class="dropdown-menu pl-2 pr-2" id="notification_menu"><?php echo $output; ?></ul>
                 </li>
            </ul>
        </div>
    </nav>
    
    <div id="main-container" class="wrapper row">

