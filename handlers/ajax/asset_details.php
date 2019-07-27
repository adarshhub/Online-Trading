<?php

include("../../config/config.php");

function order_query($type,$query){
    if(mysqli_num_rows($query)>0){
                       
        $rate_array = array();
        $volume_array = array();

        while($row = mysqli_fetch_array($query)){
            $rate = $row['rate'];
            $volume = $row['volume'];

            array_push($rate_array,$rate);
            array_push($volume_array,$volume);
        }

        $order = new \stdClass();

        $order->rates = $rate_array;
        $order->volumes = $volume_array;

        echo json_encode($order);
    }
}

if(isset($_POST['type']) && isset($_POST['asset'])){

	$type = $_POST['type'];
	$asset = $_POST['asset'];
	$query = "";

	if($type == 'buy'){
		$query = mysqli_query($con,"SELECT rate,volume FROM $asset WHERE order_type='buy' ORDER BY rate DESC LIMIT 4");
	} else {

		$query = mysqli_query($con,"SELECT rate,volume FROM $asset WHERE order_type='sell' ORDER BY rate ASC LIMIT 4");
	}

	order_query($type,$query);
}


?>