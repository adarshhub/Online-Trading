<?php

$url = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$parameters = [
  'start' => '1',
  'limit' => '9',
  'convert' => 'INR',
  'aux' => 'max_supply'
];

$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: c329159b-f362-4f25-89a8-5083c76db677'
];
$qs = http_build_query($parameters); // query string encode the parameters
$request = "{$url}?{$qs}"; // create the request URL


$curl = curl_init(); // Get cURL resource

curl_setopt($curl , CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl , CURLOPT_SSL_VERIFYHOST, false);

if (!$curl) {
    die("Couldn't initialize a cURL handle"); 
}

// Set cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => $request,            // set the request URL
  CURLOPT_HTTPHEADER => $headers,     // set the headers 
  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
));

$response = curl_exec($curl); // Send the request, save the response

$array = json_decode($response, true); // print json decoded response
curl_close($curl); // Close request

$data = $array['data'];

$symbol = array();
$price = array();
$change = array();

foreach ($data as $key => $value) {

	array_push($symbol, $value['symbol']);

	$quote = $value['quote'];
	$inr = $quote['INR'];

	array_push($price, $inr['price']);
  array_push($change, $inr['percent_change_1h']);
}

$assets = new \stdClass();

$assets->symbol = $symbol;
$assets->price = $price;
$assets->change = $change;

echo json_encode($assets);
?>