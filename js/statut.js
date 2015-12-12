$(function() {
    $( document ).ready(function() {
        setInterval(updateStatut, 2000);
    });
   
   
   var window_focus = false;
   
   $(window).focus(function() {
        window_focus = true;
    }).blur(function() {
        window_focus = false;
    });

   function updateStatut() {
       statut = 2;
       if(window_focus){
           statut = 3;
       }
       
       var  formData = 'a=updatestatus&s='+statut; 
        $.ajax({
            url : "ajax.php",
            type: "POST",
            data : formData
        });

        var  formData = 'a=getstatus'; 
        $.ajax({
            url : "ajax.php",
            type: "POST",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                $('div.users').html(data);                
            }
        });
        
        
   }
    
});
