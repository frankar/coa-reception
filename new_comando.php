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
		$mail = $_POST['mail'];
		$id_dir = $_POST['iddir'];
		$sql = "SELECT count(*) FROM `comandi` WHERE nome = '".$sigla."';";
		$rs_cod = $db->Execute($sql);
		if ($rs_cod->fields[0] < 1) {
			$sql = "INSERT INTO `comandi` (`nome`,`esteso`,`mail`,`id_dir`) VALUES ('".$sigla."','".$nome."','".$mail."','".$id_dir."');";
			if ($db->Execute($sql) === false) {
				$message = 'Error inserting: '.$db->ErrorMsg().'<br />';
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