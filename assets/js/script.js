var userLoggedIn,
currentAsset,
currentAsset_Price,
notice_box = document.getElementById('notice-box');

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

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function init_market(){

    $.ajax({
        url: './handlers/ajax/asset_api.php',
        method: 'GET',
        dataType: 'json',
        success: function(data){
            var img;

            var assetListContainer = document.getElementById('asset-list');
            var total_assets = data['symbol'].length;
            document.querySelector('.loader').classList.toggle('hide');
            for(var i=0; i<total_assets; i++){
                img = (data['change'][i] > 0 ? 'up.png' : 'down.png');
                var htmlString = '<tr class="asset-list asset table-primary"><td>'+data['symbol'][i]+'</td><td>'+data['price'][i].format(2, 3)+'</td><td><img class="hr_change_img" src="assets/img/'+ img +'"></td></tr>';
            assetListContainer.insertAdjacentHTML('beforeend', htmlString);
            }
            assetListener();
            console.log(data);
        }
    })

    /*
    var request = new XMLHttpRequest();
    request.open('GET','https://koinex.in/api/ticker');

    request.onload =  function(){
        document.querySelector('.loader').classList.toggle('hide');
        var assetListContainer = document.getElementById('asset-list');
        var data = JSON.parse(request.responseText);
        var coinData = data.stats.inr;

        var assets = {};

        for(var coin in coinData){

            assets[coin]  = 0;

            var htmlString = '<tr class="asset-list asset table-primary"><td>'+coin+'</td><td>'+coinData[coin].last_traded_price+'</td><td>'+coinData[coin].lowest_ask+'</td></tr>';
            //var htmlString = '<div class="asset"><span class="asset-name vertical-center">'+coin+'</span><span class="asset-last-trade-price vertical-center">'+coinData[coin].last_traded_price+'</span> <span class="asset-current-bid vertical-center">'+coinData[coin].lowest_ask+'</span></div>';
            assetListContainer.insertAdjacentHTML('beforeend', htmlString);
        }

         sessionStorage.setItem('assets',JSON.stringify(assets));
        
    }

    request.onerror = function(){
        notice_box.innerHTML = "<div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOps!</strong> Error in Connection</div>";
    }

    request.send(); 

    request.addEventListener('loadend',assetListener);

    */

}

function assetListener(){ 

    var assets =  document.querySelectorAll('.asset');

    assets.forEach(function(asset){
    
        asset.addEventListener('click',function(ele){

            var asset;
            if(ele.target.parentElement.classList[0] == 'asset-list'){
                asset = ele.target.parentNode.childNodes[0].textContent;
            } else if(ele.target.classList[0] == 'asset-list'){
                asset = ele.srcElement.childNodes[0].textContent;
            }
            asset=asset.toLowerCase();
            loadAsset(asset);
        });

    });
}

function loadAsset(asset){
    
    var encodedURL = encodeURI('index.php?asset='+asset);
    //openPage(encodedURL);
    $("body").scrollTop(0);
    history.pushState(null, null, encodedURL);

    initAsset(asset);

    var empty_obj = { rates: [], volumes: []};

    init_orders("buy",empty_obj);
    init_orders("sell",empty_obj);

    $.post("handlers/ajax/asset_details.php",{type: "buy", asset: asset}).done(function(obj){
       var json_obj = JSON.parse(obj.toString());
       
        init_orders("buy",json_obj);
    });

    $.post("handlers/ajax/asset_details.php",{type: "sell", asset: asset}).done(function(obj){
       var json_obj = JSON.parse(obj.toString());
        init_orders("sell",json_obj);
    });
}

function initAsset(asset){
    currentAsset = asset.toLowerCase();
    document.getElementById('asset-name').innerHTML = asset.toUpperCase();
}

function editProfile(){
    var button = document.querySelector('#edit-profile-button');
    var inputs = document.querySelectorAll('#profile-details-container input');

    if(button.textContent == "Edit Profile"){
        
        button.textContent = "Save";

        inputs.forEach(function(input){
            input.disabled = false;
        });

    } else {

        var new_firstname, new_lastname, new_email;
    
        inputs.forEach(function(input){
            if(input.id == "profile-firstname"){
                new_firstname = input.value;
            }
            if(input.id == "profile-lastname"){
                new_lastname = input.value;
            }
            if(input.id == "profile-email"){
                new_email = input.value;
            }
        });

        $.post("handlers/ajax/edit_profile.php",{ firstname: new_firstname, lastname: new_lastname, email: new_email }).done(function(error){

            if (error != "") {
				box.innerHTML = error;
				return;
            }
            
            document.getElementById('notice-box').innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Updated profile details</div>";
			button.textContent = "Edit Profile";

            inputs.forEach(function(input){
                input.disabled = true;
            });
        });
    }
    
}

function changePassword(){
    var currentPassword = document.getElementById('current-password').value;
    var newPassword = document.getElementById('new-password').value;
    var confirmPassword = document.getElementById('confirm-password').value;

    var notice_box = document.getElementById('change-password-notice');

    if( newPassword == confirmPassword){

        $.post("handlers/ajax/change_password.php",{oldPassword: currentPassword, newPassword: confirmPassword}).done(function(error){
            if(error != ""){
                notice_box.innerHTML = error;
                return;
            }

            notice_box.innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Password Changed</div>";

        });

    } else {
        notice_box.innerHTML = "<div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOPs!</strong> New passwords do not match!</div>";
    }
}

function place_order(type){
    var volume = document.getElementById(type+'-volume').value;
    var rate = document.getElementById(type+'-rate').value;

    var request = new XMLHttpRequest();
    
    request.open('GET','https://koinex.in/api/ticker' );
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = JSON.parse(request.responseText);
            var currentAsset_Price = data.prices.inr[currentAsset];
            var diff = Math.abs(currentAsset_Price - rate);
            var temp_notice_box = document.getElementById('notice-box');
            if(diff > (currentAsset_Price*0.1)){
                temp_notice_box.innerHTML = "<div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOPs!</strong> Rate cannot be greater or less than 10%</div>"
            } else {
                $.post("handlers/ajax/place_order.php",{type: type,asset: currentAsset, volume: volume, rate: rate}).done(function(error){
                    if(error){
                        temp_notice_box.innerHTML = error;
                    } else {
                        temp_notice_box.innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Order Placed</div>";

                        loadAsset(currentAsset);
                    }
                });
            }
        }
    }

    request.send(); 
    
}

function init_order_list(name, volume, rate, type){
    var htmlString = '<tr class="asset"><td>'+name+'</td><td>'+type+'</td><td>'+volume+'</td><td>'+rate+'<span onclick="deleteOrder(event)" class="close">&times;</span></td></tr>';
   
    my_orders =document.getElementById('my-orders');

    my_orders.insertAdjacentHTML('beforeend',htmlString);

}

function deleteOrder(ele){
    
    var childNodes = ele.target.parentElement.parentElement.childNodes;

    var asset, type, rate, volume;

    asset = childNodes[0].textContent;
    type = childNodes[1].textContent;
    volume = parseFloat(childNodes[2].textContent);
    rate = parseFloat(childNodes[3].textContent);

    
    $.post("handlers/ajax/cancel_order.php",{asset: asset,type: type, rate: rate, volume: volume}).done(function(error){
        if(error){
            document.getElementById('notice-box').innerHTML = error;
        } else {
            document.getElementById('notice-box').innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Order Canceled</div>";

            openPage('account.php');
        }
    });
}

function init_orders(type,obj){

    var htmlString;
    var size = obj.rates.length;
    var container = document.getElementById(type+'-order-body');
    var no_order_msg = document.getElementById('no-'+type+'-order-msg');

    container.innerHTML ="";

    if(size == 0){
        no_order_msg.hidden  = false;
    } else {
        no_order_msg.hidden  = true;

        for(var i = 0; i < size; i++){
            htmlString = "<tr><td>%volume%</td><td>%rate%</td></tr>"
            htmlString = htmlString.replace('%volume%',obj.volumes[i]);
            htmlString= htmlString.replace('%rate%',obj.rates[i]);

            container.insertAdjacentHTML('beforeend',htmlString);

        }
    } 
}

function init_balance(balance){


    var htmlString, i;
    var size = balance.assets.length;
    var container = document.getElementById('my-balance');
    var assets = JSON.parse( sessionStorage.assets);
    
    for(i = 0; i < size; i++){
        assets[balance.assets[i]] = balance.amounts[i];
    }

    sessionStorage.assets = JSON.stringify(assets);

    for(asset in assets){
        htmlString = '<li class="alert alert-success order-list-item"><span class="asset-name col-sm-4"><strong>%asset%</strong></span><span class="asset-name col-sm-3"><strong>%amount%</strong></span></li>';

        if(asset != 'inr'){
            htmlString = htmlString.replace('%asset%',asset);
            htmlString= htmlString.replace('%amount%',assets[asset]);

            container.insertAdjacentHTML('beforeend',htmlString);
        } else {
            document.getElementById('inr-balance').textContent = assets[asset];
        } 

    }
}

seen = false;

function notificationSeen(){

    if(seen == false){
        $.post("handlers/ajax/notification_seen.php").done(function(){
            seen = true;
        });
    }

    $('.count').hide();

}
