<?php include("header.php"); ?>
<script src="js/autocomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="js/partenze.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Gestione Rientri Mezzi ai Comandi</h2>
	
	<?php
	$message = '';
	$message_class = "ok";
	
	if (isset($_GET['message'])) {
		$message = $_GET['message'];
		$message_class = "ko";
	} 
		
	// controllo le request
	if (isset($_REQUEST['action'])) {
		$azione = $_REQUEST['action'];	
	} else {
		$azione = '';
	}
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];
	} else {
		$inviato = '';
	}
	if (isset($_POST['targa'])) {
		if(is_numeric($_POST['targa'])) {
			$targa = $_POST['targa'];
		} else {
			$targa = 0;
		}
	}
	if (isset($_POST['add_user'])) {
		$nome = $_POST['nome'];
		$codice = $_POST['codice'];
		if(is_numeric($codice) and $nome != '') {
			$sql = "SELECT count(*) FROM anagrafica WHERE codice = '".$codice."';";
			$rs_cod = $db->Execute($sql);
			if ($rs_cod->fields[0] < 1) {
				$sql = 'INSERT INTO `barcode`.`anagrafica` (`id`, `nome`, `sesso`, `provenienza`, `livello`, `codice`, `periodo`, `note`, `data`) VALUES (NULL, \''.$nome.'\', \''.$_POST['sesso'].'\', \''.$_POST['provenienza'].'\', \''.$_POST['livello'].'\', \''.$codice.'\', \''.$_POST['periodo'].'\', \'Inserito manualmente\', NOW());'; 
				if ($db->Execute($sql) === false) {
					$message = 'error inserting: '.$conn->ErrorMsg().'<br />';
					$message_class = "ko";
				} else {
					$message = "Il nominativo ".$nome." &egrave; stato inserito correttamente con il codice ".$codice;
				}
			} else {
				$message_class = "ko";
				$message = 'Il codice '.$codice.' &egrave; gi&agrave; stato utilizzato !';
			}
		}
	}
	
	// e' stato richiesto di aggiungere manualmente una persona in anagrafica
	if ($azione == "add") {
		$codice = $_GET['codice'];
		$nome = $_GET['nome'];
		$periodo = $_GET['periodo'];
		$sql = 'INSERT INTO `barcode`.`anagrafica` (`id`, `nome`, `livello`, `codice`, `periodo`, `note`, `data`) VALUES (NULL, \''.$nome.'\', \'7\', \''.$codice.'\', \'3\', \'Inserito manualmente\', NOW());'; 
		if ($db->Execute($sql) === false) {
			$message = 'error inserting: '.$conn->ErrorMsg().'<br />';
			$message_class = "ko";
		} else {
			$message = "Il nominativo ".$nome." &egrave; stato inserito correttamente con il codice ".$codice;
		}
		
	// aggiorno un codice gia' esistente
	} elseif ($azione == "force_update") {
		$codice = $_GET['codice'];
		$idval = $_GET['id'];
		$rs_cod = $db->Execute("SELECT * FROM `barcode`.`anagrafica` WHERE `id` = '".$idval."' LIMIT 1 ;");
		$r_ass_cod = $rs_cod->GetRowAssoc();
		$note = $r_ass_cod['NOTE']."\rModificato il codice precedente: ".$r_ass_cod['CODICE'];
		$db->Execute("UPDATE `barcode`.`anagrafica` SET `note` =  '".$note."', `codice` = '".$codice."' WHERE `anagrafica`.`id` = ".$idval." LIMIT 1 ;");
		$message = "Il codice e' stato aggiornato correttamente";
	}
	
	// richiesto un inserimento codice e passato un id valido
	elseif ($inviato == "1" and $targa > 0 ) {
		# controllo se ho gia' usato il codice
		$sql = "SELECT * FROM `mezzi` WHERE `targa` = '".$targa."' ;";
		$rs_targa = $db->Execute($sql);
		
		
		if ($rs_targa->RecordCount() <> 1) {
			$message_class = "ko";
			$message = "Registrazione uscita fallita. <br />";
			$message .= "Contatta l'amministratore e segnati su un foglio questo: <br /><span style=\"background:#fff;color:#000;padding:0 .5em 0 .5em;\">Uscita fallita per la targa: ".$_POST['targa']." alle ore:".$_POST['rientro']."</span>";
		} else {
			if (strlen($_POST['rientro']) <> 16 ) {
				$message = "Formato della data di uscita non corretto";
				
			}
			$a_data_ora = explode(' ',$_POST['rientro']);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);

			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$ora_out = date("Y-m-d H:i:s",$data_timestamp);

			$query = "UPDATE mezzi SET data_out = '".$ora_out."' WHERE targa= '".$targa."';";
			// echo $query;
			// exit;
			$db->Execute($query);
			$message = "Uscita aggiornata correttamente";
		}  
	} 	

	?>
	<?php 
	if ($message <> '') {
		$soundfile = ($message_class == "ko") ? "error" : "ok";
		?><div id="message" class="<?php echo $message_class ?>"><?php echo $message ?>
			<audio autoplay="autoplay">
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.wav' ?>" type="audio/wav" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.ogg' ?>" type="audio/ogg" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.mp3' ?>" type="audio/mpeg" />
			Your browser does not support this audio
			</audio>
		</div><?php
	}
	?>
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
		
		<p class="evidente"><label for="targa">Targa:</label>
			<input type="text" name="targa" id="targa" />
			<input type="hidden" id="id_targa" name="id_targa" /></p>
		<p class="evidente"><label for="rientro">Data e ora rientro:</label>
			<input type="text" name="rientro" id="rientro" />
			<button value="1" id="rientro_setdt">Ora Attuale</button></p>
		<p><input type="hidden" id="inviato" name="inviato" value="1" />
		<input type="submit" value="Conferma Partenza &rarr;" /></p>
	</form>
	
	<div id="log">

		<h2>Ultime operazioni</h2>
		<?php
			$query = "SELECT m.id, t.nome AS tipo, m.targa, a.nome AS nome_resp, a.cognome AS cognome_resp, c.nome AS comando, m.data_in, m.data_out FROM mezzi AS m INNER JOIN tipi_mezzi AS t ON t.id = m.id_tipo INNER JOIN anagrafica AS a ON m.id_resp = a.id INNER JOIN comandi as c ON m.id_comando = c.id WHERE m.data_out <> '1970-01-01 00:00:00' ORDER BY m.data DESC LIMIT 10;";
			$result = $db->Execute($query);

			if ($result === false) die("failed");
			?><table class="pretty-table">
				<tr><th scope="col">ID</th>
					<th scope="col">Tipo</th>
					<th scope="col">Targa</th>
					<th scope="col">Nome Resp.</th>
					<th scope="col">Cognome Resp.</th>
					<th scope="col">Comando</th>
					<th scope="col">Ingresso</th>
					<th scope="col">Uscita</th>
					<th scope="col">Azioni</th>
					</tr><?php
			while (!$result->EOF) {
				?><tr><?php
				for ($i=0, $max=$result->FieldCount(); $i < $max; $i++) {
					?><td><?php echo $result->fields[$i] ?></td><?php
				}
				?><td><a href="annulla_uscita_mezzi.php?id=<?php echo $result->fields[0] ?>">Annulla</a></td></tr><?php
			   $result->MoveNext();
			}
		?></table>  
		
	</div>
<?php include("footer.php");  ?>
