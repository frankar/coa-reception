$(document).ready(function() {
    
    // Scomparsa message.ok automatica
    setTimeout(function() {
        $('#message.ok').removeClass('ok')
    },3000);

	//
	// Qualifica
	//
	$("#add_qual").click(function(e) {
		$('#new_qual')
		    .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
        centered: true, 
        onLoad: function() { 
            $('#form2').find('input:first').focus()
            }
        });
		e.preventDefault();
	});
	
	$("#new_qual span.button").click(function(e) {
		var sigla_qual = $("#sigla_qual").val();
		var nome_qual = $("#nome_qual").val();
		$.ajax({
			type: "POST",
			url: "new_qual.php",
			data: "inviato=1&sigla=" + sigla_qual + "&nome=" + nome_qual,
			dataType: "html",
			success: function(msg)
			{
				alert("Ok, Qualifica inserita");
				$('#qual').append( new Option(sigla_qual,msg,msg,msg) );
				sort_select("#qual");
			},
			error: function()
			{
				alert("Chiamata fallita, si prega di riprovare...");
			}
		});
		
		$('#new_qual').trigger('close');
		e.preventDefault();
	});
	
	//
	// Nuovo Tipo Mezzo
	//
	$("#add_tipo_mezzo").click(function(e) {
		$('#new_tipo_mezzo')
		    .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
			centered: true, 
			onLoad: function() { 
				$('#form4').find('input:first').focus()
            }
        });
		e.preventDefault();
	});

	$("#new_tipo_mezzo span.button").click(function(e) {
		var nome_tipo_mezzo = $("#nome_tipo_mezzo").val();
		$.ajax({
			type: "POST",
			url: "new_tipo_mezzo.php",
			data: "inviato=1&nome=" + nome_tipo_mezzo,
			dataType: "html",
			success: function(msg)
			{
				alert("Ok, Tipo Mezzo inserito");
				$('#tipo_mezzo').append( new Option(nome_tipo_mezzo,msg,msg,msg) );
				sort_select("#tipo_mezzo");
			},
			error: function()
			{
				alert("Chiamata fallita, si prega di riprovare...");
			}
		});
		
		$('#new_tipo_mezzo').trigger('close');
		e.preventDefault();
	});
	
	//
	// New Comando
	//
	$("#add_comando, #add_com").click(function(e) {
		$("#new_comando")
		    .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
			centered: true, 
			onLoad: function() { 
				$('#form5').find('input:first').focus()
            }
        });
		e.preventDefault();
	});

	$("#new_comando span.button").click(function(e) {
		var sigla_comando = $("#nome_comando").val();
		var nome_comando = $("#nome_es_comando").val();
		var mail_comando = $("#mail_comando").val();
		var id_dir = $("#dir").val();
		$.ajax({
			type: "POST",
			url: "new_comando.php",
			data: "inviato=1&sigla=" + sigla_comando + "&nome=" + nome_comando + "&mail=" + mail_comando + "&iddir= " + id_dir,
			dataType: "html",
			success: function(msg)
			{
				if (is_int(msg)) {
    				alert("Ok, Comando inserito");
    				$('#comando_mezzo').append( new Option(sigla_comando + ' - '+nome_comando,msg,msg,msg) );
    				sort_select("#comando_mezzo");
    				$('#com').append( new Option(sigla_comando + ' - '+nome_comando,msg,msg,msg) );
    				sort_select("#com");
				} else {
				    alert(msg);
				}
			},
			error: function()
			{
				alert("Chiamata fallita, si prega di riprovare...");
			}
		});
		
		$('#new_comando').trigger('close');
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

	//
	// Date Time Picker
	//
	$( "#data_in, #data_out" ).datetimepicker({
		dateFormat: "dd/mm/yy",
		dayNames: ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"],
		dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
		monthNames: ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"],
		timeText: "Orario",
		hourText: "Ora",
		minuteText: "Minuti",
		currentText: "Adesso",
		closeText: "Fatto"
	});
	
	$( "#data_out_pres" ).datepicker({
	    defaultDate: +7,
		dateFormat: "dd/mm/yy",
		dayNames: ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"],
		dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
		monthNames: ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"]	    
	});
	
	$('#data_in_setdt').click(function(e){
		$("#data_in").datetimepicker('setDate', (new Date()) );
		e.preventDefault();
	});
	//
	// Nuova mansione
	//
	$("#add_mansione").click(function(e) {
		$('#new_mansione')
		    .append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
	        .lightbox_me({
			centered: true, 
			onLoad: function() { 
				$('#form6').find('input:first').focus()
            }
        });
		e.preventDefault();
	});

	$("#new_mansione span.button").click(function(e) {
		var nome_mansione = $("#nome_mansione").val();
		$.ajax({
			type: "POST",
			url: "new_mansione.php",
			data: "inviato=1&nome=" + nome_mansione,
			dataType: "html",
			success: function(msg)
			{
				alert("Ok, Mansione inserita");
				$('#mansione').append( new Option(nome_mansione,msg,msg,msg) );
				sort_select("#mansione");
			},
			error: function()
			{
				alert("Chiamata fallita, si prega di riprovare...");
			}
		});
		
		$('#new_mansione').trigger('close');
		e.preventDefault();
	});
	
	

	
});

function is_int(value){
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else {
      return false;
  }
}

// ordina le option di un select in base al loro testo 
function sort_select(select_id) {
    
    // get the select
    var $dd = $(select_id);
    if ($dd.length > 0) { // make sure we found the select we were looking for

        // save the selected value
        var selectedVal = $dd.val();

        // get the options and loop through them
        var $options = $('option', $dd);
        var arrVals = [];
        $options.each(function(){
            // push each option value and text into an array
            arrVals.push({
                val: $(this).val(),
                text: $(this).text()
            });
        });

        arrVals.sort(function(a, b){
            if(a.text>b.text){
                return 1;
            }
            else if (a.text==b.text){
                return 0;
            }
            else {
                return -1;
            }
        });
        
        // loop through the sorted array and set the text/values to the options
        for (var i = 0, l = arrVals.length; i < l; i++) {
            $($options[i]).val(arrVals[i].val).text(arrVals[i].text);
        }

        // set the selected value back
        $dd.val(selectedVal);
    }
}