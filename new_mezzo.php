<?php
	include("config.inc.php");
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];	
	} else {
		$inviato = '';
	}
	if ($inviato == 1) {
		$tipo = $_POST['tipo'];
		$targa = $_POST['targa'];
		$sql = "SELECT count(*) FROM `mezzi` WHERE targa = '".$targa."';";
		$rs_cod = $db->Execute($sql);
		if ($rs_cod->fields[0] < 1) {
			$sql = "INSERT INTO `mezzi` (`id_tipo`,`targa`,`data`) VALUES ('".$tipo."', '".$targa."', NOW());";
			if ($db->Execute($sql) === false) {
				$message = 'error inserting: '.$db->ErrorMsg().'<br />';
				$message_class = "ko";
			}
			
			$message = $db->Insert_ID();
		} else {
			$message = "Errore: Targa gia' presente in archivio !";
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	echo $message;
?>