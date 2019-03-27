<?php
$con = mysqli_connect("localhost", "root", "", "online_trading");

$assets = ['ETH'];


function check_trade( $type, $asset, $id){
    global $con;

    $query = mysqli_query($con,"SELECT volume, rate, order_type FROM $asset WHERE id='$id'");
    $row = mysqli_fetch_array($query);

    $first_volume = $row['volume'];
    $first_rate = $row['rate'];
    $type = $row['order_type'];
    $second_type = 'sell';

    if($type == $second_type){
        $second_type = 'buy';
    }
    if($second_type == 'sell'){
        $find_trader = mysqli_query($con,"SELECT volume, rate FROM $asset WHERE order_type='$second_type' ORDER BY rate DESC LIMIT 1");
        $row = mysqli_fetch_array($find_trader);

        $second_volume = $row['volume'];
        $second_rate = $row['rate'];

        if($first_rate >= $second_rate){
            if($second_volume > $first_volume){
                $new_volume = $second_volume - $first_volume;
            } else {}
        }

    } else {
        $find_trader = mysqli_query($con,"SELECT volume, rate FROM $asset WHERE order_type='$second_type' ORDER BY rate ASC LIMIT 1");
    }   

    return;
}

function asset_id($asset, $username, $rate, $type ){
    global $con;

    $id_query = mysqli_query($con,"SELECT id FROM $asset WHERE placed_by='$username' AND rate='$rate' AND order_type='$type' LIMIT 1");
    $row = mysqli_fetch_array($id_query);
    return $row['id'];
}

function add_order($asset, $type, $username, $volume, $rate){
    global $con;

    $new_order_asset_query = mysqli_query($con,"INSERT into $asset (order_type, placed_by, volume, rate) VALUES ('$type','$username','$volume','$rate')");
    $order_id  = asset_id($asset, $username, $rate, $type);
    
    mysqli_query($con,"INSERT into orders (asset, placed_by, num_id) VALUES ( '$asset', '$username', '$order_id')");
    check_trade($type, $asset, $order_id);
    return True;
}

function update_order_by_id($id, $asset, $volume){
    global $con;

    mysqli_query($con,"UPDATE $asset SET volume='$volume' WHERE id=:$id");
}

function delete_order($asset, $type, $username, $volume, $rate){
    global $con;

    $search_query = mysqli_query($con,"SELECT id, volume FROM $asset WHERE placed_by='$username' AND order_type='$type' AND rate='$rate' LIMIT 1");

    $row = mysqli_fetch_array($search_query);
    $id = $row['id'];
    $total_volume = (float)$row['volume'];

    if($total_volume == $volume){
       return delete_order_by_id($id, $asset);
    }
    $volume = $total_volume - $volume;

    mysqli_query($con,"UPDATE $asset SET volume='$volume' WHERE id='$id'");
    return true;
}

function delete_order_by_id($id, $asset){
    global $con;

    $delete1 = mysqli_query($con,"DELETE FROM $asset WHERE id='$id'");
    $delete2 = mysqli_query($con,"DELETE FROM orders WHERE num_id='$id'");

    if($delete1 && $delete2){
        return true;
    } 
    return false;
    
}

?>