<?php
	include("config.inc.php");
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];	
	} else {
		$inviato = '';
	}
	if ($inviato == 1) {
		$nome = $_POST['nome'];
		$sql = "SELECT count(*) FROM `comandi` WHERE nome = '".$nome."';";
		$rs_cod = $db->Execute($sql);
		if ($rs_cod->fields[0] < 1) {
			$sql = "INSERT INTO `comandi` (`nome`) VALUES ('".$nome."');";
			if ($db->Execute($sql) === false) {
				$message = 'error inserting: '.$db->ErrorMsg().'<br />';
				$message_class = "ko";
			}
			
			$message = $db->Insert_ID();
		} else {
			$message = "Errore: comando gia' presente in archivio !";
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	echo $message;
?>