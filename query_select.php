<?php
	include_once('adodb5/toexport.inc.php');
	include("config.inc.php");
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];	
	} else {
		$inviato = '';
	}
	if ($inviato == 1) {
		$formato = $_POST['f'];
		$sql = base64_decode($_POST['query']);
		$result = $db->Execute($sql);
		if ($result === false) {
			$message = 'error: '.$db->ErrorMsg().'<br />';
		} else {
			if ($formato == 'tsv') {
				$message = rs2tab($result);
			} else {
				$message = rs2csv($result);
			}
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	echo $message;
?>