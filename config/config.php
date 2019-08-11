<?php
    ob_start();
    session_start();
    date_default_timezone_set("Asia/Kolkata");
    
    $con = mysqli_connect("localhost", "root", "", "online_trading");

    if(mysqli_connect_errno()){
        echo "Failed to connect to Mysql".mysqli_connect_errno();
    }

    $table_check = mysqli_query($con, "SELECT 1 FROM users LIMIT 1");

    if($table_check == False){
        $create_table = mysqli_query($con, "CREATE TABLE users (username varchar(30) PRIMARY KEY, firstname varchar(30), lastname varchar(30), email varchar(60), password varchar(255), dateOfJoining date)");
        
        if($create_table == False){
            echo "Table not Created";
        }
    }
?>