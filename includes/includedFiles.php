<?php


if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])){
    //do something
    //AJAX


} else {
  
    include "includes/header/header.php";
    include "includes/footer/footer.php";

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";

    exit();
}

?>