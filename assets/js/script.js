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
        var assetListContainer = document.getElementById('asset-list');
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

    request.addEventListener('loadend',assetListener);

}

function assetListener(){ 

    var assets =  document.querySelectorAll('.asset');

    assets.forEach(function(asset){
    
        asset.addEventListener('click',function(ele){
        console.log(ele.target.childNodes[0].textContent);
        });

    });
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
            var box = document.getElementById('notice-box');

            if (error != "") {
				box.innerHTML = error;
				return;
            }
            
            box.innerHTML = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Updated profile details</div>";
			button.textContent = "Edit Profile";

            inputs.forEach(function(input){
                input.disabled = true;
            });
        });
    }
    
}

 