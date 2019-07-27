<?php

$source = "";
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])){
    //do something
    //AJAX

    $source = "AJAX";
} else {
  
    include "includes/header/header.php";
    include "includes/footer/footer.php";

    $source = "DIRECT";

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";

    exit();
}

?>