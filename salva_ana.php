<?php
	include("header.php");
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];	
	} else {
		$inviato = '';
	}
	if ($inviato == 1) {
		$nome = $_POST['nome'];
		$cognome = $_POST['cognome'];
		$tel = $_POST['tel'];
		$qual = $_POST['qual'];
		//$mezzo = $_POST['mezzo'];
		$tenda = $_POST['tenda'];
		$comando = $_POST['com'];
		$mansione = $_POST['mansione'];

		$data_in = $_POST['data_in'];
		if (strlen ($data_in) <> 16) {
			$data_in = "01/01/1970";
		}
		$a_data_ora = explode(' ',$data_in);
		$a_data = explode('/',$a_data_ora[0]);
		$a_ora = explode(':',$a_data_ora[1]);
		
		$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
		$d_in = date("Y-m-d H:i:s",$data_timestamp);

		$data_out_pres = $_POST['data_out_pres'];
		$a_data = explode('/',$data_out_pres);
		$data_timestamp = mktime(0,0,0,$a_data[1],$a_data[0],$a_data[2]);
		$d_out_pres = date("Y-m-d H:i:s",$data_timestamp);
		
		$message_class = "ok";
		if ($tel == '') {
			$message_class = "ko";
			$message = "Il numero telefonico e' un campo obbligatorio";
		} else {
			$sql = "SELECT count(*) FROM `anagrafica` WHERE data_out = '1970-01-01 00:00:00' AND tel = '".$tel."';";
			$rs_cod = $db->Execute($sql);
			if ($rs_cod->fields[0] < 1) {
							
				$sql = "INSERT INTO `anagrafica` (
				`nome`,
				`cognome`,
				`tel`,
				`idqual`,
				`comando`,
				`tenda`,
				`idmansione`,
				`data`,
				`data_in`,
				`data_out_pres`
				) VALUES (
				".$db->qstr($nome).",
				".$db->qstr($cognome).",
				".$db->qstr($tel).",
				".$qual.",
				".$comando.",
				'".$tenda."',
				'".$mansione."',
				NOW(),
				".$db->DBTimeStamp($d_in).",
				".$db->DBTimeStamp($d_out_pres)."
				);";
					
				if ($db->Execute($sql) === false) {
					$message = 'error inserting: '.$db->ErrorMsg().'<br />';
					$message_class = "ko";
				} else {
					$message = "Inserimento avvenuto con successo";
				}
				
			} else {
				$message = "Errore: Record gia' presente in archivio, verifica l'unicita' del numero telefonico !";
			}
		
		}
	} else {
		$message = "Errore nell'invio dati";
	}
	
?>
<?php include("menu.php"); ?>
	<h2>Risultato</h2>
	<?php
	if ($message <> '') {
		$soundfile = ($message_class == "ko") ? "error" : "ok";
		?><div id="message" class="<?php echo $message_class ?>"><?php echo $message ?>
			<?php if ($options['audio']) { ?>
			<audio autoplay="autoplay">
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.wav' ?>" type="audio/wav" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.ogg' ?>" type="audio/ogg" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.mp3' ?>" type="audio/mpeg" />
			Your browser does not support this audio
			</audio>
			<?php } ?>
		</div><?php
	}
	
	?>
	<p><a href="reception.php">Torna alla pagina principale</a></p>
<?php include("footer.php");  ?>