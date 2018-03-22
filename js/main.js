
setInterval(function(){
    notif_img();
    online_user();
}, 1000);


function del_alert(){
	var element = document.getElementById("alert_div");
	element.parentNode.removeChild(element);
}


$(".notif_container").mouseleave(function(){
    $('.notif_container').hide();
});

notif_img();

function notif_img(){
    var formData = {
        'submit'         : "ok",
    };

    $.ajax({
        type        : 'POST',
        url         : '../php/img_notif.php',
        data        : formData,
        encode      : true,
        success     : function(data){
            $('.img_notif').remove();
            $('.float_menu_rigth').append(data); 
        }
    })
}

///////// NOTIF


function notif(){
    var formData = {
        'submit'         : "open",

    };
    $('.notif_container').show();

    $.ajax({
        type        : 'POST',
        url         : '../php/notif.php',
        data        : formData,
        encode      : true,
        success     : function(data){
            $('.notif_container').html(data); 
        }
    })

    notif_img()
}

function notif_del(id){
    var formData = {
        'id'             : id,
        'submit'         : "del",
    };

    $.ajax({
        type        : 'POST',
        url         : '../php/notif.php',
        data        : formData,
        encode      : true,
        success     : function(data){
            $('.notif_container').html(data); 
        }
    })
    notif_img()
}

function    online_user(){
    $.ajax({
        type        : 'POST',
        url         : '../php/online_user.php',
        encode      : true,
    })
}

function go_profil_notif(login, id){
    window.open("http://localhost:8888/user_profil.php?login=" + login);
}


///////// GO PROFIL

function go_profil(login){
        window.open("http://localhost:8888/user_profil.php?login=" + login);
}