<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
include("../config/config.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.

		$ORDER_ID = $_POST['ORDERID'];
		$TXNID  = $_POST['TXNID'];
		$TXNAMOUNT  = $_POST['TXNAMOUNT'];
		$PAYMENTMODE  = $_POST['PAYMENTMODE'];
		$TXNDATE  = date("Y-m-d H:i:s", strtotime($_POST['TXNDATE']));

		mysqli_query($con, "UPDATE deposit_order SET success = 1 WHERE order_id = '$ORDER_ID'");

		mysqli_query($con, "INSERT INTO payment_history VALUES ('$ORDER_ID', '$TXNID', '$TXNAMOUNT', '$TXNDATE', '$PAYMENTMODE')");

		$get_username = mysqli_query($con, "SELECT username FROM deposit_order WHERE order_id = '$ORDER_ID'");
		$row = mysqli_fetch_array($get_username);
		$username = $row[0]; 

		mysqli_query($con, "UPDATE balance SET amount = amount + '$TXNAMOUNT' WHERE asset = 'inr' AND username = '$username'");

		mysqli_query($con, "INSERT INTO notifications VALUES ('$TXNAMOUNT added to Wallet', '$username', '$TXNDATE')");

		mysqli_query($con, "UPDATE notify_users SET seen_all = seen_all + 1 WHERE username = '$username'");

		header("Location: http://localhost:8081/online%20trading/account.php");
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	/*

	if (isset($_POST) && count($_POST)>0 )
	{ 

		foreach($_POST as $paramName => $paramValue) {
				echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}
	*/

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>