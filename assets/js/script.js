var userLoggedIn;

function logout(){
    $.post("handlers/ajax/logout.php", function(){
        location.reload();
    });
}

function openPage(url){
    var encodedUrl = encodeURI(url);
    encodedUrl = encodedUrl.substring(encodedUrl.lastIndexOf('/')+1, encodedUrl.length);
    $("#main-container").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, encodedUrl);
}

function init_market(){
    var request = new XMLHttpRequest();
    request.open('GET','https://koinex.in/api/ticker');

    request.onload =  function(){
        var assetListContainer = document.getElementById('asset-list-container');
        var data = JSON.parse(request.responseText);
        var coinData = data.stats.inr;

        for(var coin in coinData){

            var htmlString = '<div class="asset"><span class="asset-name vertical-center">'+coin+'</span><span class="asset-last-trade-price vertical-center">'+coinData[coin].last_traded_price+'</span> <span class="asset-current-bid vertical-center">'+coinData[coin].lowest_ask+'</span></div>';
            assetListContainer.insertAdjacentHTML('beforeend', htmlString);
        }
        
        
    }

    request.onerror = function(){
        console.log("Error in connection");
    }

    request.send(); 

}