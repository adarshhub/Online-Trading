function logout(){
    $.post("handlers/ajax/logout.php", function(){
        location.reload();
    });
}