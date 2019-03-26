<?php
include("../../config/config.php");

$error_msg = "<div class='alert alert-warning alert-dismissible'>
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
<strong>OOPs!</strong> Something Went Wrong</div>";

if(isset($_POST['asset']) && isset($_POST['type']) && isset($_POST['rate']) && isset($_POST['volume'])){

    $asset = $_POST['asset'];
    $type = $_POST['type'];
    $rate = $_POST['rate'];
    $volume = $_POST['volume'];
    $username = $_SESSION['username'];


    $search_query = mysqli_query($con,"SELECT id, volume FROM $asset WHERE placed_by='$username' AND order_type='$type' LIMIT 1");

    $row = mysqli_fetch_array($search_query);
    $id = $row['id'];
    $total_volume = (float)$row['volume'];

    if($total_volume == $volume){

        $delete1 = mysqli_query($con,"DELETE FROM $asset WHERE id='$id'");
        $delete2 = mysqli_query($con,"DELETE FROM orders WHERE num_id='$id'");
        if($delete1 && $delete2){
            return;
        } else {
            echo $error_msg;
        }
    }
    $volume = $total_volume - $volume;

    mysqli_query($con,"UPDATE $asset SET volume='$volume' WHERE id='$id'");

    
} else {
    echo $error_msg;
}

?>