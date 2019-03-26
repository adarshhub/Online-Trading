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
            <h2><span id="asset-name" class="label label-primary">Asset Name</span></h2>
            <div id="sell-orders-box">
                Sell Order
                <table id="sell-order-table" class="table">
                    <thead>
                        <tr>
                            <th>Volume</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="danger">
                            <td>10.00</td>
                            <td>50.00</td>
                        </tr>
                        <tr class="danger">
                            <td>11.00</td>
                            <td>49.00</td>
                        </tr>
                        <tr class="danger">
                            <td>10.00</td>
                            <td>50.00</td>
                        </tr>
                        <tr class="danger">
                            <td>11.00</td>
                            <td>49.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="buy-orders-box">
                Buy orders
                <table id="buy-order-table" class="table">
                    <thead>
                        <tr>
                            <th>Volume</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="danger">
                            <td>10.00</td>
                            <td>50.00</td>
                        </tr>
                        <tr class="danger">
                            <td>11.00</td>
                            <td>49.00</td>
                        </tr>
                        <tr class="danger">
                            <td>10.00</td>
                            <td>50.00</td>
                        </tr>
                        <tr class="danger">
                            <td>11.00</td>
                            <td>49.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="trade-container">
            <div id="sell-box">
                <input type="number" class="form-control" placeholder="Volume" id="sell-volume">
                <input type="number" class="form-control" placeholder="Rate" id="sell-rate">
                <button class="btn btn-primary" id="sell-button" onclick="place_order('sell')">Sell</button>
            </div>
            <div id="buy-box">
                <input type="number" class="form-control" placeholder="Volume" id="buy-volume">
                <input type="number" class="form-control" placeholder="Rate" id="buy-rate">
                <button class="btn btn-primary" id="buy-button" onclick="place_order('buy')">Buy</button>
            </div>
            
        </div>
    </div>
    


   
