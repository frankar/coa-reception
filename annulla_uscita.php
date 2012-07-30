<?php
	include("config.inc.php");
	if (isset($_GET['id'])) {
		$query = "UPDATE anagrafica SET data_out = '1970-01-01 00:00:00' WHERE id = '".$_GET['id']."';";
		
		//echo $query;
		//exit;
		
		if ($db->Execute($query) === false) {
			$message = 'Error inserting: '.$db->ErrorMsg().'<br />';
			$message_class = "ko";
		} else {
			$message = "Inserimento avvenuto con successo";
		}
	}
	
	header("location: partenze.php"); 
?> 