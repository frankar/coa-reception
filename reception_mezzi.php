<?php include("header.php"); ?>
<script src="js/autocomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
<script src="js/reception.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Ingresso Mezzi</h2>
	
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
		$tipo = $_POST['tipo_mezzo'];
		
		
		$targa = $_POST['targa'];
		$resp = $_POST['id_person'];
		$comando = $_POST['comando_mezzo'];
		$tipo = $_POST['tipo_mezzo'];
		
		$data_in = $_POST['data_in'];
		if (strlen ($data_in) <> 16) {
			$data_in = "01/01/1970";
		}
		$a_data_ora = explode(' ',$data_in);
		$a_data = explode('/',$a_data_ora[0]);
		$a_ora = explode(':',$a_data_ora[1]);
		
		$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
		$d_in = date("Y-m-d H:i:s",$data_timestamp);
	
		$message_class = "ok";
		if ($targa == "") {
			$message_class = "ko";
			$message = "La targa e' un campo obbligatorio";
		} else {

			$query = "SELECT count(*) FROM mezzi WHERE data_out = '1970-01-01 00:00:00' AND targa = '".$targa."';";
			
			$rs_targa = $db->Execute($query);
			if ($rs_targa->fields[0] < 1) {
					$sql = "INSERT INTO mezzi (
					targa,
					id_resp,
					id_comando,
					id_tipo,
					data_in,
					data_out,
					data
					) VALUES (
					'".$targa."',
					'".$resp."',
					'".$comando."',
					'".$tipo."',
					".$db->DBTimeStamp($d_in).",
					'1970-01-01 00:00:00',
					NOW()
					)";

					if ($db->Execute($sql) === false) {
						$message = 'error inserting: '.$db->ErrorMsg().'<br />';
						$message_class = "ko";
					} else {
						$message = "Inserimento avvenuto con successo";
					}
			} else {
				$message = "Errore: Targa gia' presente in archivio !";
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
	
	<div id="new_mezzo">
		<h2>Nuovo Mezzo</h2>
		<form id="form3" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
		


		</form>
	</div>

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

			<p class="evidente"><label for="nome_es_comando">Nome:</label>
			<input type="text" name="nome_es_comando" id="nome_es_comando" />

			<p class="evidente"><label for="mail_comando">E.mail:</label>
			<input type="text" name="mail_comando" id="mail_comando" />

			<p class="evidente"><label for="dir">Direzione di appartenenza</label>
				<select name="dir" id="dir" size="1">
					<?php
					$sql = "SELECT id, nome, esteso FROM comandi where id = id_dir ORDER BY nome;";
					$rsl = $db->Execute($sql);
					$r = $rsl->GetRowAssoc();
					while (!$rsl->EOF) {
						?><option value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
						$rsl->MoveNext();
					}
					?>
				</select> <span class="button2" id="add_dir">Aggiungi Direzione</span></p>
			
			<input type="hidden" name="inviato_comando" value="1" /></p>
			<p><span class="button">Aggiungi Nome Comando</span></p>

		</form>
	</div>

	<div id="new_direzione">
		<h2>Nuova Direzione</h2>
		<form id="form6" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			<p class="evidente"><label for="nome_direzione">Sigla:</label>
			<input type="text" name="nome_direzione" id="nome_direzione" />

			<p class="evidente"><label for="nome_es_direzione">Nome:</label>
			<input type="text" name="nome_es_direzione" id="nome_es_direzione" />

			<p class="evidente"><label for="mail_direzione">E.mail:</label>
			<input type="text" name="mail_direzione" id="mail_direzione" />
			
			<input type="hidden" name="inviato_direzione" value="1" /></p>
			<p><span class="button">Aggiungi Nome Direzione</span></p>

		</form>
	</div>
	
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">

		<p class="evidente"><label for="tipo_mezzo">Tipo Automezzo</label>
		<select name="tipo_mezzo" id="tipo_mezzo" size="1">
		<?php
			$sql = "SELECT * FROM tipi_mezzi ORDER BY nome;";
			$rsl = $db->Execute($sql);
			$r = $rsl->GetRowAssoc();
			while (!$rsl->EOF) {
				?><option <?php echo ($r['NOME'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
				$rsl->MoveNext();
			}
		?>
		</select> <span class="button2" id="add_tipo_mezzo">Aggiungi tipo Mezzo</span></p>
	
		<p class="evidente"><label for="targa">Targa:</label>
		<input type="text" name="targa" id="targa" /></p>
		
		<p class="evidente"><label for="comando_mezzo">Comando  di provenienza:</label>
		<select name="comando_mezzo" id="comando_mezzo" size="1">
		<?php
			$sql = "SELECT id, nome, esteso FROM comandi ORDER BY nome;";
			$rsl = $db->Execute($sql);
			$r = $rsl->GetRowAssoc();
			while (!$rsl->EOF) {
				?><option value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?><?php echo ($rsl->fields[2] != '' ? ' - '.$rsl->fields[2] : '') ?></option><?php
				$rsl->MoveNext();
			}
		?>
		</select> <span class="button3" id="add_comando">Aggiungi Comando</span></p>

		<p class="evidente"><label for="nome">Responsabile:</label>
		<input type="text" name="nome" id="nome" />
		<input type="hidden" id="id_person" name="id_person" /></p>

		<p class="evidente"><label for="data_in">Data di Ingresso</label>
			<input type="text" name="data_in" id="data_in" />
			<button value="1" id="data_in_setdt">Ora Attuale</button></p>


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
				?><td><a title="Modifica" href="dettagli_mezzi.php?id=<?php echo $result->fields[0] ?>"><img src="css/edit-icon.png" alt="Modifica" /></a></td></tr><?php
			   $result->MoveNext();
			}
		?></table>  
		
	</div>
<?php include("footer.php");  ?>
