<?php
$con = mysqli_connect("localhost", "root", "", "online_trading");

function check_match($asset){
    global $con;

    $query = mysqli_query($con, "SELECT * FROM $asset WHERE order_type= 'buy' ORDER BY rate DESC LIMIT 1");

    if($row = mysqli_fetch_array($query)){

        //1st person order details
        $first_username = $row['placed_by'];
        $first_order_volume = $row['volume'];
        $first_order_id = $row['id'];
        $first_order_rate = $row['rate'];
        $first_order_type = $row['order_type'];

        $query = mysqli_query($con, "SELECT * FROM $asset WHERE order_type= 'sell' ORDER BY rate LIMIT 1");

        if($row = mysqli_fetch_array($query)){

            //2nd person order details
            $second_Order_id = $row['id'];
            $second_order_volume = $row['volume'];
            $second_order_rate = $row['rate'];
            $second_username = $row['placed_by'];

            if($first_order_rate >= $second_order_rate){

                $first_volume = get_balance($first_username, $asset);
                $second_volume = get_balance($second_username, $asset);
                $trade_volume = $second_order_volume;

                //updating orders
                if($second_order_volume > $first_order_volume){

                    $first_volume =  $first_volume + $first_order_volume;
                    $trade_volume = $first_order_volume;

                    $remain_volume = $second_order_volume - $first_order_volume;
                    update_order_by_id($second_Order_id, $asset, $remain_volume);
                    delete_order_by_id($first_order_id, $asset);

                } else if($second_order_volume < $first_order_volume){

                    $first_volume = $first_volume + $second_order_volume;

                    $remain_volume = $first_order_volume - $second_order_volume;
                    update_order_by_id($first_order_id, $asset, $remain_volume);
                    delete_order_by_id($second_Order_id, $asset);
                } else {

                    $first_volume =  $first_volume + $first_order_volume;
                    
                    delete_order_by_id($second_Order_id, $asset);
                    delete_order_by_id($first_order_id, $asset);
                }

                //1. reduce volume of 2nd
                    //----Already done during placing order---
                //2. update volume of 1st 
                update_balance($first_username, $asset, $first_volume);
                //3. reduce inr of 1st
                $inr_required = $trade_volume * $second_order_rate;
                    //----Already done during placing order---
                    //----If trade completed in low rate
                if($first_order_rate > $second_order_rate){

                    $remain_balance = ($first_order_rate * $trade_volume) - $inr_required;
                    $current_inr = get_balance($first_username,'inr');
                    $updated_inr = $current_inr + $remain_balance;
                    update_balance($first_username, 'inr', $updated_inr);
                }
                //4. update inr of 2nd
                
                $current_inr = get_balance($second_username,'inr');
                $updated_inr = $current_inr + $inr_required;
                update_balance($second_username, 'inr', $updated_inr);


                //Notifications

                $TXNDATE  = date("Y-m-d H:i:s");

                mysqli_query($con, "INSERT INTO notifications VALUES ('Trade of $asset amount to $inr_required is successfull', '$first_username', '$TXNDATE')");

                mysqli_query($con, "INSERT INTO notifications VALUES ('Trade of $asset amount to $inr_required is successfull', '$second_username', '$TXNDATE')");

                mysqli_query($con, "UPDATE notify_users SET seen_all = seen_all + 1 WHERE username IN ('$first_username', '$second_username')");

                check_match($asset);
            }
            
        }

    }
}


/*

function check_trade( $type, $asset, $id){
    global $con;

    $query = mysqli_query($con,"SELECT * FROM $asset WHERE id='$id'");
    $row = mysqli_fetch_array($query);

    //1st person order details
    $first_username = $row['placed_by'];
    $first_order_volume = $row['volume'];
    $first_order_id = $row['id'];
    $first_order_rate = $row['rate'];
    $first_order_type = $row['order_type'];

    $second_order_type = 'sell';

    if($first_order_type == $second_order_type){
        $second_order_type = 'buy';
    }

    if($first_order_type == 'buy'){
        $find_trader = mysqli_query($con,"SELECT * FROM $asset WHERE order_type='$second_order_type' ORDER BY rate DESC LIMIT 1");

        if($row = mysqli_fetch_array($find_trader)){
        
            //2nd person order details
            $second_Order_id = $row['id'];
            $second_order_volume = $row['volume'];
            $second_order_rate = $row['rate'];
            $second_username = $row['placed_by'];

            if($first_order_rate >= $second_order_rate){
                $first_volume = get_balance($first_username, $asset);
                $second_volume = get_balance($second_username, $asset);
                $trade_volume = $second_order_volume;

                //updating orders
                if($second_order_volume > $first_order_volume){
                    $first_volume =  $first_volume + $first_order_volume;
                    $trade_volume = $first_order_volume;

                    $remain_volume = $second_order_volume - $first_order_volume;
                    update_order_by_id($second_Order_id, $asset, $remain_volume);
                    delete_order_by_id($first_order_id, $asset);

                } else if($second_order_volume < $first_order_volume){
                    $first_volume = $first_volume + $second_order_volume;

                    $remain_volume = $first_order_volume - $second_order_volume;
                    update_order_by_id($first_order_id, $asset, $remain_volume);
                    delete_order_by_id($second_Order_id, $asset);
                } else {
                    $first_volume =  $first_volume + $first_order_volume;
                    
                    delete_order_by_id($second_Order_id, $asset);
                    delete_order_by_id($first_order_id, $asset);
                }

                //1. reduce volume of 2nd
                    //----Already done during placing order---
                //2. update volume of 1st 
                update_balance($first_username, $asset, $first_volume);
                //3. reduce inr of 1st
                $inr_required = $trade_volume * $second_order_rate;
                    //----Already done during placing order---
                    //----If trade completed in low rate
                if($first_order_rate > $second_order_rate){
                    $remain_balance = ($first_order_rate * $trade_volume) - $inr_required;
                    $current_inr = get_balance($first_username,'inr');
                    $updated_inr = $current_inr + $remain_balance;
                    update_balance($first_username, 'inr', $updated_inr);
                }
                //4. update inr of 2nd
                
                $current_inr = get_balance($second_username,'inr');
                echo $inr_required;
                $updated_inr = $current_inr + $inr_required;
                update_balance($second_username, 'inr', $updated_inr);
                
            }

        }

    } else {
        $find_trader = mysqli_query($con,"SELECT * FROM $asset WHERE order_type='$second_order_type' ORDER BY rate ASC LIMIT 1");
        
        if($row = mysqli_fetch_array($find_trader)){
           
            //2nd person order details
            $second_Order_id = $row['id'];
            $second_order_volume = $row['volume'];
            $second_order_rate = $row['rate'];
            $second_username = $row['placed_by'];

            if( $second_order_rate >= $first_order_rate){
                $first_volume = get_balance($first_username, $asset);
                $second_volume = get_balance($second_username, $asset);
                $trade_volume = $first_order_volume;

                if($first_order_volume > $second_order_volume){
                    $second_volume =  $second_volume + $second_order_volume;
                    $trade_volume = $second_order_volume;

                    //updating orders
                    $remain_volume = $first_order_volume - $second_order_volume;
                    update_order_by_id($first_order_id, $asset, $remain_volume);
                    delete_order_by_id($second_Order_id, $asset);

                } else if($first_order_volume < $second_order_volume){
                    $second_volume = $second_volume + $first_order_volume;

                    //updating orders
                    $remain_volume = $second_order_volume - $first_order_volume;
                    update_order_by_id($second_Order_id, $asset, $remain_volume);
                    delete_order_by_id($first_order_id, $asset);
                } else {
                    $second_volume =  $second_volume + $second_order_volume;

                    //updating orders
                    delete_order_by_id($second_Order_id, $asset);
                    delete_order_by_id($first_order_id, $asset);
                }

                //1. Update volume of 2nd
                update_balance($second_username, $asset, $second_volume);
                //2. Reduce volume of 1st
                    //----Already done during placing order--- 
                //3. Update inr of 1st
                    //----Already done during placing order---
                $inr_required = $trade_volume * $first_order_rate;

                $current_inr = get_balance($first_username,'inr');
                $updated_inr = $current_inr + $inr_required;
                update_balance($first_username, 'inr', $updated_inr);
                //4. Reduce inr of 2nd
                //----Already done during placing order---
                //----If trade completed in low rate
                if($second_order_rate > $first_order_rate){
                    $remain_balance = ($second_order_rate * $trade_volume) - $inr_required;
                    $current_inr = get_balance($second_username,'inr');
                    $updated_inr = $current_inr + $remain_balance;
                    update_balance($second_username, 'inr', $updated_inr);
                }
                
            }
        }
    }
}   

*/

function get_balance($username, $asset){
    global $con;
    
    $balance_query = mysqli_query($con,"SELECT amount FROM balance WHERE username='$username' AND asset='$asset'");
    if($row = mysqli_fetch_array($balance_query)){
        return $row['amount'];
    }
    return 0;
}

function update_balance($username, $asset, $amount){
    global $con;

    $balance_update = mysqli_query($con,"UPDATE balance SET amount='$amount' WHERE asset='$asset' AND username='$username'");
    return $balance_update;
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
    //check_trade($type, $asset, $order_id);
    check_match($asset);
    return True;
}

function update_order_by_id($id, $asset, $volume){
    global $con;

    $update_order = mysqli_query($con,"UPDATE $asset SET volume='$volume' WHERE id='$id'");
    return True;
}

function delete_order($asset, $type, $username, $volume, $rate){
    global $con;

    if($type == 'buy'){
        $current_balance = get_balance($username,'inr');
        $current_balance = $current_balance +($volume*$rate);
        
        update_balance($username, 'inr', $current_balance);
    } else {
        $current_balance = get_balance($username, $asset);
        $current_balance = $current_balance + $volume;
        
        update_balance($username, $asset, $current_balance);
    }

    $search_query = mysqli_query($con,"SELECT id, volume FROM $asset WHERE placed_by='$username' AND order_type='$type' AND rate='$rate' LIMIT 1");

    $row = mysqli_fetch_array($search_query);
    $id = $row['id'];
    $total_volume = (float)$row['volume'];

    if($total_volume == $volume){
       return delete_order_by_id($id, $asset);
    }
    $volume = $total_volume - $volume;

    mysqli_query($con,"UPDATE $asset SET volume='$volume' WHERE id='$id'");

    check_match($type, $asset);
 
    return true;
}

function delete_order_by_id($id, $asset){
    global $con;

    $delete1 = mysqli_query($con,"DELETE FROM $asset WHERE id='$id'");
    $delete2 = mysqli_query($con,"DELETE FROM orders WHERE num_id='$id'");

    if($delete1 && $delete2){
        check_match($asset);
        return true;
    } 
    return false;
    
}

?>