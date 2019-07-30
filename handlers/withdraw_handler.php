<?php

require_once("../config/config.php");

$amount = 0;

if(isset($_POST["withdraw-amount"])){
	$amount = $_POST["withdraw-amount"];
} else {
	header("Location: account.php");
}

$username = $_SESSION['username'];

$balance_query = mysqli_query($con, "SELECT amount FROM balance WHERE asset = 'inr' AND username = '$username'");
$row = mysqli_fetch_array($balance_query);

$balance = $row[0];

if($balance < $amount){
	header("Location: account.php");
	die();
}

$CUST_ID = $_SESSION['username'];
$ORDER_ID = "ORDS" . rand(10000,99999999);
$TXNDATE  = date("Y-m-d H:i:s");

mysqli_query($con, "INSERT INTO withdraw_order VALUES ('$ORDER_ID', '$CUST_ID', 0, '$amount')");

mysqli_query($con, "UPDATE balance SET amount = amount-'$amount' WHERE asset = 'inr' AND username = '$username'");

mysqli_query($con, "INSERT INTO notifications VALUES ('Withdrawl request of $amount is accepted', '$username', '$TXNDATE')");

mysqli_query($con, "UPDATE notify_users SET seen_all = seen_all + 1 WHERE username = '$username'");

header("Location: http://localhost:8081/online%20trading/account.php");

?>