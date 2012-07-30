$(document).ready(function() {
	$('#rientro').datetimepicker({
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
	$('#rientro_setdt').click(function(e){
		$("#rientro").datetimepicker('setDate', (new Date()) );
		e.preventDefault();
	});
});