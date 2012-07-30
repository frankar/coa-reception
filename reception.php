<?php include("header.php"); ?>
<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
<script src="js/reception.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Reception</h2>
	
	
	<?php
	$message = '';
	$message_class = "ok";
	
	// controllo le request

	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];
	} else {
		$inviato = '';
	}
	
	if($inviato == '1') {
		$nome = sanitize($_POST['nome']);
		$cognome = sanitize($_POST['cognome']);
		$tel = sanitize($_POST['tel']);
		$qual = $_POST['qual'];
		$tenda = sanitize($_POST['tenda']);
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
				`data_out_pres`,
				`data_out`
				) VALUES (
				".$db->qstr($nome).",
				".$db->qstr($cognome).",
				".$db->qstr($tel).",
				".$qual.",
				".$comando.",
				".$db->qstr($tenda).",
				'".$mansione."',
				NOW(),
				".$db->DBTimeStamp($d_in).",
				".$db->DBTimeStamp($d_out_pres).",
				'1970-01-01 00:00:00'
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
	
	<div id="new_qual">
		<h2>Nuova Qualifica</h2>
		<form id="form2" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			<p class="evidente"><label for="sigla_qual">Sigla:</label>
			<input type="text" name="sigla_qual" id="sigla_qual" />
			<input type="hidden" name="inviato_qual" value="1" /></p>
			<p class="evidente"><label for="nome_qual">Nome:</label>
			<input type="text" name="nome_qual" id="nome_qual" />
			<p><span class="button">Aggiungi</span></p>

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

	<div id="new_mansione">
		<h2>Nuova mansione</h2>
		<form id="form6" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			<p class="evidente"><label for="nome_mansione">Nome:</label>
			<input type="text" name="nome_mansione" id="nome_mansione" />
			<input type="hidden" name="inviato_mansione" value="1" /></p>
			<p><span class="button">Aggiungi Mansione</span></p>

		</form>
	</div>

	
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
			
		<p class="evidente"><label for="qual">Qualifica</label>
			<select name="qual" id="qual" size="1">
				<?php
				$sql = "SELECT * FROM qualifica ORDER BY sigla;";
				$rsl = $db->Execute($sql);
				$r = $rsl->GetRowAssoc();
				while (!$rsl->EOF) {
					?><option <?php echo ($r['SIGLA'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
					$rsl->MoveNext();
				}
				?>
			</select> <span class="button" id="add_qual">Aggiungi Qualifica</span></p>

		<p class="evidente"><label for="nome">Nome:</label>
			<input type="text" name="nome" id="nome" style="width:20em;" />
			<input type="hidden" id="id_person" name="id_person" /></p>
		<p class="evidente"><label for="cognome">Cognome:</label>
			<input type="text" name="cognome" id="cognome"  style="width:20em;" /></p>
		<p class="evidente"><label for="tel">Telefono:</label>
			<input type="text" name="tel" id="tel" /></p>
		<p class="evidente"><label for="tenda">Posto Tenda Assegnato:</label>
			<input class="corto" type="text" name="tenda" id="tenda" /></p>
		<p class="evidente"><label for="qual">Comando di provenienza</label>
			<select name="com" id="com" size="1">
				<?php
				$sql = "SELECT * FROM comandi ORDER BY nome;";
				$rsl = $db->Execute($sql);
				$r = $rsl->GetRowAssoc();
				while (!$rsl->EOF) {
					?><option <?php echo ($r['NOME'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
					$rsl->MoveNext();
				}
				?>
			</select> <span class="button" id="add_com">Aggiungi Comando Provenienza</span></p>
		<p class="evidente"><label for="data_in">Data di Ingresso</label>
			<input type="text" name="data_in" id="data_in" />
			<button value="1" id="data_in_setdt">Ora Attuale</button></p>

		<p class="evidente"><label for="data_out_pres">Data di Uscita Presunta</label>
			<input type="text" name="data_out_pres" id="data_out_pres" value="<?php 
			$presunta  = mktime(0, 0, 0, date("m")  , date("d")+intval($options['media_days']), date("Y"));
			echo date("d/m/Y",$presunta);
			?>" /></p>

		<p class="evidente"><label for="mansione">Mansione</label>
			<select name="mansione" id="mansione" size="1">
				<?php
				$sql = "SELECT * FROM mansioni ORDER BY nome;";
				$rsl = $db->Execute($sql);
				$r = $rsl->GetRowAssoc();
				while (!$rsl->EOF) {
					?><option <?php echo ($r['NOME'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
					$rsl->MoveNext();
				}
				?>
			</select> <span class="button" id="add_mansione">Aggiungi Mansione</span></p>

		<p><input type="hidden" id="inviato" name="inviato" value="1" />
			<input type="submit" value="Invia &rarr;" />
		</p>
	</form>
	
	<div id="log">

		<h2>Ultime operazioni</h2>
		<?php
			$result = $db->Execute("SELECT a.id,a.nome,a.cognome,a.tel,c.nome,a.tenda,a.data_in,a.data_out_pres FROM `anagrafica` AS a INNER JOIN `comandi` AS c ON a.comando = c.id ORDER BY a.data DESC LIMIT 10");

			if ($result === false) die("failed");
			?><table class="pretty-table">
				<tr><th scope="col">ID</th>
					<th scope="col">Nome</th>
					<th scope="col">Cognome</th>
					<th scope="col">Tel</th>
					<th scope="col">Comando</th>
					<th scope="col">Tenda</th>
					<th scope="col">Ingresso</th>
					<th scope="col">Uscita Presunta</th>
					<th scope="col">Azioni</th>
					</tr><?php
			while (!$result->EOF) {
				?><tr><?php
				for ($i=0, $max=$result->FieldCount(); $i < $max; $i++) {
					?><td><?php echo $result->fields[$i] ?></td><?php
				}
				?><td><a href="dettagli.php?id=<?php echo $result->fields[0] ?>">Modifica</a></td></tr><?php
			   $result->MoveNext();
			}
		?></table>  
		
	</div>
<?php include("footer.php");  ?>
