<?php
include("../../config/config.php");

if(isset($_POST['type']) && isset($_POST['asset']) && isset($_POST['volume']) && isset($_POST['rate'])){

    $username = $_SESSION['username'];
    $type= $_POST['type'];
    $asset = $_POST['asset'];
    $volume = $_POST['volume'];
    $rate = $_POST['rate'];
   

    $same_order_query = mysqli_query($con,"SELECT * FROM $asset WHERE placed_by='$username' AND rate='$rate' AND order_type='$type'");

    if($row = mysqli_fetch_array($same_order_query)){

        $order_id = $row['id'];
        $new_volume = $row['volume'] + $volume;

        mysqli_query($con,"UPDATE $asset SET volume='$new_volume' WHERE id='$order_id'");
        return;
    }

    $new_order_asset_query = mysqli_query($con,"INSERT into $asset (order_type, placed_by, volume, rate) VALUES ('$type','$username','$volume','$rate')");

    $id_query = mysqli_query($con,"SELECT id FROM $asset WHERE placed_by='$username' AND rate='$rate' AND order_type='$type' LIMIT 1");
    $row = mysqli_fetch_array($id_query);
    $order_id  = $row['id'];
    
    mysqli_query($con,"INSERT into orders (asset, placed_by, num_id) VALUES ( '$asset', '$username', '$order_id')");
    /*
    $id_query = mysqli_query($con,"SELECT id FROM orders WHERE placed_by='$username' AND asset='$asset' LIMIT 1");

    $row = mysqli_fetch_array($id_query);
    $order_id  = $row['id'];

    */
    
   
} else {
    echo "<div class='alert alert-danger alert-dismissible'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>OOPs!</strong> Order not placed</div>";
}


?>

