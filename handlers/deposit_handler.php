<?php  

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

require_once("../config/config.php");

$amount = 0;

if(isset($_POST["deposit-amount"])){
	$amount = $_POST["deposit-amount"];
} else {
	header("Location: account.php");
}

?>
<html>
<head>
<title>Deposit</title>
</head>
<body>
<center>
<h1 class="display-4">Processing Your Order. Do not Refresh</h1>
	<form method="post" action="../paytm/pgRedirect.php" name="deposit_form" hidden>
		<table class="table">
			<tbody>
				<tr>
					<th>S.No</th>
					<th>Label</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>1</td>
					<td><label>ORDER_ID::*</label></td>
					<td><input id="ORDER_ID" tabindex="1" maxlength="20" size="20"
						name="ORDER_ID" autocomplete="off"
						value="<?php echo  "ORDS" . rand(10000,99999999)?>" >
					</td>
				</tr>
				<tr hidden>
					<td>2</td>
					<td><label>CUSTID ::*</label></td>
					<td><input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo $_SESSION['username']; ?>" ></td>
				</tr>
				<tr hidden>
					<td>3</td>
					<td><label>INDUSTRY_TYPE_ID ::*</label></td>
					<td><input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Trade"></td>
				</tr>
				<tr hidden>
					<td>4</td>
					<td><label>Channel ::*</label></td>
					<td><input id="CHANNEL_ID" tabindex="4" maxlength="12"
						size="12" name="CHANNEL_ID" autocomplete="off" value="WEB" >
					</td>
				</tr>
				<tr>
					<td>5</td>
					<td><label>txnAmount*</label></td>
					<td><input title="TXN_AMOUNT" tabindex="10"
						type="text" name="TXN_AMOUNT"
						value="<?php echo $amount; ?>">
					</td>
				</tr>
			</tbody>
		</table>
		* - Mandatory Fields
		<script type="text/javascript">
			document.deposit_form.submit();
		</script>
	</form>
	</center>
</body>
</html>