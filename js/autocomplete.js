$(function() {
    $("#nome").focus().autocomplete({
        source: "tagsearch.php",
        minLength: 2,
        select: function(event, ui) {
        			var selectedObj = ui.item;
        			$("#nome").val(selectedObj.nome);
        			$('#id_person').val(selectedObj.id_person);
        			return false;
        		}
    });

    $("#nome2").focus().autocomplete({
        source: "tagsearch.php",
        minLength: 2,
        select: function(event, ui) {
        			var selectedObj = ui.item;
        			$("#nome2").val(selectedObj.nome);
        			$('#id_person2').val(selectedObj.id_person);
        			return false;
        		}
    });

});

$(document).ready(function() {
    setTimeout(function() {
        $('#message.ok').removeClass('ok')
    },3000);
});