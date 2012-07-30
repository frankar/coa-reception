<?php include("header.php"); ?>
<script src="js/autocomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
<script src="js/reception.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Responsabile Mezzi</h2>
	
	<?php
		$message = '';
		$message_class = "ok";
		
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
	?>

	

	<?php if($inviato == 1) {
		
		
		$targa = $_POST['targa'];
		$resp = $_POST['id_person'];
		$resp2 = $_POST['id_person2'];
	
		$message_class = "ok";
		if ($targa == "") {
			$message_class = "ko";
			$message = "La targa e' un campo obbligatorio";
		} else {

			$query = "SELECT count(*) from mezzi where id_resp = '".$resp."' AND targa = '".$targa."';";
			
			
			$rs_targa = $db->Execute($query);
			if ($rs_targa->fields[0] == 1) {
				$sql = "UPDATE mezzi SET id_resp = '".$resp2."' WHERE targa = ".$targa.";";

				if ($db->Execute($sql) === false) {
					$message = 'error modifing: '.$db->ErrorMsg().'<br />';
					$message_class = "ko";
				} else {
					$message = "Modifica avvenuto con successo";
				}
			} else {
				$message = "Errore: Targa o responsabile precedente non validi !";
				$message_class = "ko";
			}
		}
	}	
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
	


	<div id="new_tipo_mezzo">
		<h2>Nuovo Tipo Mezzo</h2>
		<form id="form4" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			<p class="evidente"><label for="nome_tipo_mezzo">Nome:</label>
			<input type="text" name="nome_tipo_mezzo" id="nome_tipo_mezzo" />
			<input type="hidden" name="inviato_tipo_mezzo" value="1" /></p>
			<p><span class="button">Aggiungi tipo Mezzo</span></p>

		</form>
		<div id="risultato"></div>
	</div>
	
	<div id="new_comando">
		<h2>Nuovo Comando</h2>
		<form id="form5" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			<p class="evidente"><label for="nome_comando">Sigla:</label>
			<input type="text" name="nome_comando" id="nome_comando" />
			<input type="hidden" name="inviato_comando" value="1" /></p>
			<p><span class="button">Aggiungi Nome Comando</span></p>

		</form>
	</div>


	
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
	
		<p class="evidente"><label for="targa">Targa:</label>
		<input type="text" name="targa" id="targa" /></p>
		


		<p class="evidente"><label for="nome">Vecchio Responsabile:</label><br />
		<input type="text" name="nome" id="nome" />
		<input type="hidden" id="id_person" name="id_person" /></p>

		<p class="evidente"><label for="nome2">Nuovo Responsabile:</label><br />
		<input type="text" name="nome2" id="nome2" />
		<input type="hidden" id="id_person2" name="id_person2" /></p>


		<p><input type="hidden" id="inviato" name="inviato" value="1" />
			<input type="submit" value="Invia &rarr;" /></p>
	</form>
	
	<div id="log">

		<h2>Ultime operazioni</h2>
		<?php
			$query = "SELECT m.id, t.nome AS tipo, m.targa, a.nome AS nome_resp, a.cognome AS cognome_resp, c.nome AS comando, m.data_in, m.data_out FROM mezzi AS m INNER JOIN tipi_mezzi AS t ON t.id = m.id_tipo INNER JOIN anagrafica AS a ON m.id_resp = a.id INNER JOIN comandi as c ON m.id_comando = c.id WHERE m.data_out = '1970-01-01 00:00:00' ORDER BY m.data DESC LIMIT 10;";
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
				?><td><a href="dettagli_mezzi.php?id=<?php echo $result->fields[0] ?>">Modifica</a></td></tr><?php
			   $result->MoveNext();
			}
		?></table>  
		
	</div>
<?php include("footer.php");  ?>
