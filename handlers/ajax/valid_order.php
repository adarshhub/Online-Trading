<?php

require("../../config/config.php");

if(isset($_POST['rate']) && isset($_POST['asset'])){

	$assets_api = $_SESSION['assets_api'];

	$index = 0;
	$symbol = $assets_api->symbol;

	for ($i=0; $i <sizeof($symbol) ; $i++) { 
		if($symbol[$i] == $_POST['asset']){
			$index = $i;
			break;
		}
	}

	$diff = abs($_POST['rate'] - $assets_api->price[$index]);

	if($diff > ($assets_api->price[$index]*0.1)){
		echo "false";
	} else {
		echo "true";
	}
}

?>