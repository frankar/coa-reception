$(document).ready(function() {
	
    // Scomparsa message.ok automatica
    setTimeout(function() {
        $('#message.ok').removeClass('ok')
    },3000);
    
	//
	// Mod Comando
	//
	$(".mod_com").click(function(e) {
	    var target_id = $(this).attr('itemid');
	    var dir_id = $(this).attr('itemdir');
	    $("#mod_comando")
	        .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
			centered: true, 
			onLoad: function() { 
			    $('#id_comando').val(target_id);
			    $('#nome_comando').val($("#r_"+target_id+" td.f_Sigla").text());  
			    $('#nome_es_comando').val($("#r_"+target_id+" td.f_Nome").text());  
			    $('#mail_comando').val($("#r_"+target_id+" td.f_Email").text());
			    $('#dir option[value="'+dir_id+'"]').attr("selected","selected") ;
            }
        });
		e.preventDefault();
	});

	
	//
	// New Direzione
	//
	$("#add_dir").click(function(e) {
		$("#new_direzione")
		    .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
			centered: true, 
			onLoad: function() { 
				//$('#form6').find('input:first').focus()
            }
        });
		e.preventDefault();
	});

    $("#new_direzione span.button").click(function(e) {
     var sigla_direzione = $("#nome_direzione").val();
     var nome_direzione = $("#nome_es_direzione").val();
     var mail_drezione = $("#mail_direzione").val();
     $.ajax({
         type: "POST",
         url: "new_direzione.php",
         data: "inviato=1&sigla=" + sigla_direzione + "&nome=" + nome_direzione + "&mail=" + mail_drezione,
         dataType: "html",
         success: function(msg)
         {
             if (is_int(msg)) {
                 alert("Ok, Direzione inserita");
                 $('#dir').append( new Option(sigla_direzione,msg,msg,msg) );
                 sort_select("#dir");
             } else {
                 alert(msg);
             }
         },
         error: function()
         {
             alert("Chiamata fallita, si prega di riprovare...");
         }
     });
     
     $('#new_direzione').trigger('close');
     e.preventDefault();
    });

});

function confirmSubmit() {
    var agree=confirm("Sicuro di voler eliminare il Comando ?");
    if (agree)
    	return true ;
    else
    	return false ;
}

function is_int(value){
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else {
      return false;
  }
}