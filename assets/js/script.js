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

function init_market(){
    var request = new XMLHttpRequest();
    request.open('GET','https://koinex.in/api/ticker');

    request.onload =  function(){
        var assetListContainer = document.getElementById('asset-list');
        var data = JSON.parse(request.responseText);
        var coinData = data.stats.inr;

        for(var coin in coinData){

            var htmlString = '<div class="asset"><span class="asset-name vertical-center">'+coin+'</span><span class="asset-last-trade-price vertical-center">'+coinData[coin].last_traded_price+'</span> <span class="asset-current-bid vertical-center">'+coinData[coin].lowest_ask+'</span></div>';
            assetListContainer.insertAdjacentHTML('beforeend', htmlString);
        }
        
    }

    request.onerror = function(){
        notice_box.innerHTML = "<div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOps!</strong> Error in Connection</div>";
    }

    request.send(); 

    request.addEventListener('loadend',assetListener);

}

function assetListener(){ 

    var assets =  document.querySelectorAll('.asset');

    assets.forEach(function(asset){
    
        asset.addEventListener('click',function(ele){
            var asset;
            if(ele.target.parentElement.className == 'asset'){
                asset = ele.target.parentNode.childNodes[0].textContent;
            } else if(ele.target.className == 'asset'){
                asset = ele.srcElement.childNodes[0].textContent;
            }
            loadAsset(asset);
        });

    });
}

function loadAsset(asset){
    var encodedURL = encodeURI('index.php?asset='+asset);
    openPage(encodedURL);
}

function initAsset(asset){
    currentAsset = asset;
    document.getElementById('asset-name').innerHTML = asset;
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
    var volume = parseInt(document.getElementById(type+'-volume').value);
    var rate = parseInt(document.getElementById(type+'-rate').value);

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
                    }
                });
            }
        }
    }

    /*
    request.onload = function(volume) {
        var data = JSON.parse(request.responseText);
        var currentAsset_Price = data.prices.inr[currentAsset];
        var diff = Math.abs(currentAsset_Price - rate);
        if(diff > (currentAsset_Price*0.1)){
            document.getElementById('notice-box').innerHTML = "<div class='alert alert-warning alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>OOPs!</strong> Rate cannot be greater or less than 10%</div>"
        } else {
            temp(volume);
            //$.post("handlers/ajax/sell_order.php",{asset: currentAsset})
        }
    }
*/
    request.send(); 
    
}

function init_order_list(name, volume, rate, type){
    var htmlString = '<li class="asset order-list-item"><span class="asset-name vertical-center">%Name%</span><span class="asset-name vertical-center">%Type%</span><span class="asset-name vertical-center">%Volume%</span><span class="asset-name vertical-center">%Rate%</span><span onclick="deleteOrder(event)" class="close">&times;</span></li>';
    
    htmlString = htmlString.replace("%Name%",name);
    htmlString = htmlString.replace("%Volume%",volume);
    htmlString = htmlString.replace("%Rate%",rate);
    htmlString = htmlString.replace("%Type%",type);

    my_orders =document.getElementById('my-orders');

    my_orders.insertAdjacentHTML('beforeend',htmlString);

}

function deleteOrder(ele){
    var childNodes = ele.target.parentNode.childNodes;

    var asset, type, rate, volume;

    asset = childNodes[0].textContent;
    type = childNodes[1].textContent;
    rate = parseFloat(childNodes[3].textContent);
    volume = parseFloat(childNodes[2].textContent);

    
    $.post("handlers/ajax/cancel_order.php",{asset: asset,type: type, rate: rate, volume: volume}).done(function(error){
        if(error){
            document.getElementById('notice-box').innerHTML = error;
        } else {
            document.getElementById('notice-box').innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Order Canceled</div>";

            openPage('account.php');
        }
    });
}

