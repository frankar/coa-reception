<?php
	include("config.inc.php");
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];	
	} else {
		$inviato = '';
	}
	if ($inviato == 1) {
		$sigla = $_POST['sigla'];
		$nome = $_POST['nome'];
		$sql = "SELECT count(*) FROM `qualifica` WHERE sigla = '".$sigla."';";
		$rs_cod = $db->Execute($sql);
		if ($rs_cod->fields[0] < 1) {
			$sql = "INSERT INTO `qualifica` (`sigla`, `nome`) VALUES ('".$sigla."', '".$nome."');";
			$new_id = $db->Execute($sql);
			if ($new_id === false) {
				$message = 'error inserting: '.$db->ErrorMsg().'<br />';
				$message_class = "ko";
			}
			
			$message = $db->Insert_ID();
		} else {
			$message = "Errore: Sigla gia' presente in archivio !";
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	echo $message;
?>