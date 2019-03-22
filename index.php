<?php  include "includes/includedFiles.php"; 
    echo "<script>init_market()</script>";
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
            Order Container
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
    


   
