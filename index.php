<?php  include "includes/includedFiles.php"; 
include "config/config.php"; 

    $url = $_SERVER['REQUEST_URI'];
    $username = $_SESSION['username'];

    if($url == "index.php" || strpos($url, '?') == FALSE){
        $url = $url.'?asset=ETH';
        echo "<script>openPage('$url')</script>";
        return;
    } 

    echo "<script>init_market();</script>";

    $asset ="";
    
    if(isset($_GET['asset'])){

        $asset = $_GET['asset'];
       echo "<script>initAsset('$asset')</script>";

    }

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

            $order = json_encode($order);


            echo "<script>init_orders('$type',$order)</script>";
        }
    }
?>

    <div id="asset-list-container">
        <div class="title-box">
                <span class="name-title normal-title">Name</span>
                <span class="last-trade-price-title normal-title">Last Trade Price</span>
                <span class="current-bid-title normal-title">Lowest Ask</span>
        </div>
        <div id="asset-list">
            <div class="loader"></div>
        </div>
    </div>
    
    <div id="asset-details-container">
        <div id="order-container">
            <h2><span id="asset-name" class="label label-primary">Asset Name</span></h2>
            <div id="sell-orders-box">
                Sell Orders
                <table id="sell-order-table" class="table">
                    <thead>
                        <tr>
                            <th>Volume</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody id="sell-order-body">
                        <?php
                        
                        $sell_order_query = mysqli_query($con,"SELECT rate,volume FROM $asset WHERE order_type='sell' ORDER BY rate ASC LIMIT 4");

                        if($sell_order_query){
                            order_query('sell',$sell_order_query);
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="buy-orders-box">
                Buy Orders
                <table id="buy-order-table" class="table">
                    <thead>
                        <tr>
                            <th>Volume</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody id="buy-order-body">
                        <?php
                        
                        $buy_order_query = mysqli_query($con,"SELECT rate,volume FROM $asset WHERE order_type='buy' ORDER BY rate DESC LIMIT 4");

                        if($buy_order_query){
                            order_query('buy',$buy_order_query);
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="trade-container">
            <div id="sell-box">
                <input type="number" class="form-control" placeholder="Volume" id="sell-volume" step="0.01" min="0">
                <input type="number" class="form-control" placeholder="Rate" id="sell-rate" step="0.01" min="0">
                <button class="btn btn-primary" id="sell-button" onclick="place_order('sell')">Sell</button>
            </div>
            <div id="buy-box">
                <input type="number" class="form-control" placeholder="Volume" id="buy-volume" step="0.01" min="0">
                <input type="number" class="form-control" placeholder="Rate" id="buy-rate" step="0.01" min="0">
                <button class="btn btn-primary" id="buy-button" onclick="place_order('buy')">Buy</button>
            </div>
            
        </div>
    </div>
    


   
