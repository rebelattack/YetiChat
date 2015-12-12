$(function() {
    $( document ).ready(function() {
        setInterval(getshout, 2000);
    });

var key_map = {13: false, 16: false};
        
$( "textarea.input-large" ).keydown(function( e ) {
        if (e.keyCode in key_map) {
            key_map[e.keyCode] = true;
            if (key_map[13] && !key_map[16]) {                
                message = $(this).val();
                message = message.replace(/^\s+|\s+$/g,"");
                var  formData = 'a=postnewshout&m='+message; 
                $.ajax({
                    url : "ajax.php",
                    type: "POST",
                    data : formData,
                    success: function(data, textStatus, jqXHR)
                    {
                        getshout();
                        $( 'textarea.input-large' ).val('');
                        $( 'textarea.input-large' ).focus();
                    }
                });
            }
        }
    }).keyup(function(e) {
        if (e.keyCode in key_map) {
            key_map[e.keyCode] = false;
        }
 });


$(".submit-btn").click(function(){
    message = $('textarea.input-large').val();
    message = message.replace(/^\s+|\s+$/g,"");
    if(message != ""){
        var  formData = 'a=postnewshout&m='+message; 
        $.ajax({
            url : "ajax.php",
            type: "POST",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                if(data != -1 && data != 1){
                    var htmlString = data + $('div.chat').html() ;
                    $('div.chat').html(htmlString);
                }
                getshout();
                $( 'textarea.input-large' ).val('');
                $( 'textarea.input-large' ).focus();
            }
        });
    }
});

function getshout()
{
    var thisItem = $('div.chat').attr('data-nb');    
    var  formData = 'a=getnewshout&i='+thisItem;  //Name value Pair
    $.ajax({
        url : "ajax.php",
        type: "POST",
        data : formData,
        success: function(data, textStatus, jqXHR)
        {
            
            if(data != '-1')
            {                
                var htmlString = $('div.chat').html() + data;
                $('div.chat').html(htmlString);

                var total_item = parseInt(thisItem) + 1;
                $('div.chat').attr('data-nb', total_item);
            }
        }
    });
}
});
