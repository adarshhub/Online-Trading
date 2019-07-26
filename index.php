<?php  include "includes/includedFiles.php"; 
include "config/config.php"; 

    $url = $_SERVER['REQUEST_URI'];
    $username = $_SESSION['username'];

    if($url == "index.php" || strpos($url, '?') == FALSE){
        $url = $url.'?asset=eth';
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
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Lowest Ask</th>
                    <th scope="col">Last Traded Price</th>
                </tr>
            </thead>
            <tbody id="asset-list">
            </tbody>
        </table>
        <div class="loader"></div>
    </div>
    
    <div id="asset-details-container">
        <div id="order-container" >
            <h2 class="display-4"><span id="asset-name">Asset Name</span></h2>
            <div id="sell-orders-box" class="col-sm-6">
                <h3>Sell Orders</h3>
                <table id="sell-order-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Volume</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody id="sell-order-body" >
                        <?php
                        
                        $sell_order_query = mysqli_query($con,"SELECT rate,volume FROM $asset WHERE order_type='sell' ORDER BY rate ASC LIMIT 4");

                        if($sell_order_query){
                            order_query('sell',$sell_order_query);
                        }
                        
                        ?>
                    </tbody>
                </table>
                <span id="no-sell-order-msg">No Order</span>
            </div>
            <div id="buy-orders-box" class="col-sm-6">
                <h3>Buy Orders</h3>
                <table id="buy-order-table" class="table table-striped">
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
                <span id="no-buy-order-msg">No Order</span>
            </div>
        </div>
        <div id="trade-container">
            <div id="sell-box" >
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
    


   
