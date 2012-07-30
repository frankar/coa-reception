<?php
	include("config.inc.php");
	if (isset($_GET['targa'])) {
		$query = "UPDATE mezzi SET data_out = '1970-01-01 00:00:00' WHERE targa = '".$_GET['targa']."';";
		
		//echo $query;
		//exit;
		
		if ($db->Execute($query) === false) {
			$message = 'Error inserting: '.$db->ErrorMsg().'<br />';
			$message_class = "ko";
		} else {
			$message = "Annullamento avvenuto con successo";
		}
	}
	
	header("location: partenze.php"); 
?> 