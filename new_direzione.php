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
		if (strtolower(substr($mail, 0, 3)) == 'dir') {
			$sql = "SELECT count(*) FROM `comandi` WHERE nome = '".$sigla."';";
			$rs_cod = $db->Execute($sql);
			if ($rs_cod->fields[0] < 1) {
				$sql = "INSERT INTO `comandi` (`nome`,`esteso`,`mail`) VALUES ('".$sigla."','".$nome."','".$mail."');";
				if ($db->Execute($sql) === false) {
					$message = 'error inserting: '.$db->ErrorMsg().'<br />';
				}
				$message = $db->Insert_ID();
				$sql2 = "UPDATE comandi SET id_dir = '".$message."' WHERE id = '".$message."';";
				$db->Execute($sql2);
			} else {
				$message = "Errore: comando gia' presente in archivio !";
			}
		} else {
			$message = "Errore: email.direzione non valida";
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	echo $message;
?>