<?php
include("../../config/config.php");
require("../../config/trade.php");

if(isset($_POST['type']) && isset($_POST['asset']) && isset($_POST['volume']) && isset($_POST['rate'])){

    $username = $_SESSION['username'];
    $type= $_POST['type'];
    $asset = $_POST['asset'];
    $volume = $_POST['volume'];
    $rate = $_POST['rate'];

    $current_balance = 0;
    if($type == 'buy'){
        $current_balance = get_balance($username,'inr');
        $amount_required = $volume * $rate;

        if($current_balance == 0 || $current_balance < $amount_required){

                echo "<div class='alert alert-warning alert-dismissible'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>OOPs!</strong> Not Enough Balance</div>";

                return;

        }
    } else {
        $current_balance = get_balance($username, $asset);

        if($current_balance == 0 || $current_balance < $volume){

                echo "<div class='alert alert-warning alert-dismissible'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>OOPs!</strong> Not Enough Balance</div>";

                return;
        }
    }

    $same_order_query = mysqli_query($con,"SELECT * FROM $asset WHERE placed_by='$username' AND rate='$rate' AND order_type='$type'");

    if($row = mysqli_fetch_array($same_order_query)){

        $order_id = $row['id'];
        $new_volume = $row['volume'] + $volume;

        mysqli_query($con,"UPDATE $asset SET volume='$new_volume' WHERE id='$order_id'");
        return;
    }

    add_order($asset, $type, $username, $volume, $rate);
    return;

    } else {

    echo "<div class='alert alert-danger alert-dismissible'>
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>OOPs!</strong> Order not placed</div>";
}


?>

