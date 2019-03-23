<?php  include "includes/includedFiles.php"; 
    echo "<script>init_market()</script>";

    $url = $_SERVER['REQUEST_URI'];

    if($url == "index.php" || strpos($url, '?') == FALSE){
        $url = $url.'?asset=ETH';
        echo "<script>openPage('$url')</script>";
    }

    if(isset($_GET['asset'])){

        $asset = $_GET['asset'];
       echo "<script>initAsset('$asset')</script>";

    }
?>

    <div id="asset-list-container">
        <div class="title-box">
                <span class="name-title normal-title">Name</span>
                <span class="last-trade-price-title normal-title">Last Trade Price</span>
                <span class="current-bid-title normal-title">Lowest Ask</span>
        </div>
        <div id="asset-list">
        </div>
    </div>
    
    <div id="asset-details-container">
        <div id="order-container">
            <h2><span id="asset-name" class="label label-default">Asset Name</span></h2>
            <div id="sell-orders-box">
                sell orders
            </div>
            <div id="buy-orders-box">
                buy orders
            </div>
        </div>
        <div id="trade-container">
            <div id="sell-box">
                <input type="number" class="form-control" placeholder="Amount" >
                <input type="number" class="form-control" placeholder="Price" >
                <button class="btn btn-primary">Sell</button>
            </div>
            <div id="buy-box">
                <input type="number" class="form-control" placeholder="Amount" >
                <input type="number" class="form-control" placeholder="Price" >
                <button class="btn btn-primary">Buy</button>
            </div>
            
        </div>
    </div>
    


   
