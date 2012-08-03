<?php include("header.php"); ?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$('#form_dettagli input, #form_dettagli select, #form_dettagli textarea, #form_dettagli button.time').attr("disabled", true);
	$('#form_dettagli input:submit').hide();
	$('#modify').click(function(event) {
		event.preventDefault();
		$('#form_dettagli input:submit').show();
		$('#form_dettagli :disabled').removeAttr('disabled');
		$(this).hide();
	})

	// Uscita
	$('#arrivo').datetimepicker({
		dateFormat: "dd/mm/yy",
		dayNames: ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"],
		dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
		monthNames: ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"],
		timeText: "Orario",
		hourText: "Ora",
		minuteText: "Minuti",
		currentText: "Adesso",
		closeText: "Fatto"
	});
	$('#arrivo_setdt').click(function(e){
		$("#arrivo").datetimepicker('setDate', (new Date()) );
		e.preventDefault();
	});

	// Rientro presunto
	$( "#rientro_pres" ).datepicker({
		dateFormat: "dd/mm/yy",
		dayNames: ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"],
		dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
		monthNames: ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"]	    
	});

	// Rientro
	$('#rientro').datetimepicker({
		dateFormat: "dd/mm/yy",
		dayNames: ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"],
		dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
		monthNames: ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"],
		timeText: "Orario",
		hourText: "Ora",
		minuteText: "Minuti",
		currentText: "Adesso",
		closeText: "Fatto"
	});
	$('#rientro_setdt').click(function(e){
		$("#rientro").datetimepicker('setDate', (new Date()) );
		e.preventDefault();
	});
});
</script>
<?php include("menu.php"); ?>

	<h2>Dettagli</h2>
	
	<?php
		$message = '';
		$message_class = "ok";
	
		if(isset($_POST['modifica'])) {
			$id_pers = sanitize($_POST['id']);
			$idqual = $_POST['qualifica'];
			$nome = sanitize($_POST['nome']);
			$cognome = sanitize($_POST['cognome']);
			$tel = sanitize($_POST['tel']);
			$tenda = sanitize($_POST['tenda']);
			$comando = $_POST['comando'];
			$idmansione = $_POST['mansione'];

			$data_in = $_POST['arrivo'];	
			$a_data_ora = explode(' ',$data_in);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);
			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$d_in = date("Y-m-d H:i:s",$data_timestamp);
			
			$data_out_pres = $_POST['rientro_pres'];
			$a_data = explode('/',$data_out_pres);
			$data_timestamp = mktime(0,0,0,$a_data[1],$a_data[0],$a_data[2]);
			$d_out_pres = date("Y-m-d H:i:s",$data_timestamp);

			$data_out = $_POST['rientro'];
			$a_data_ora = explode(' ',$data_out);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);
			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$d_out = date("Y-m-d H:i:s",$data_timestamp);
			



			if ($tel == '') {
				$message_class = "ko";
				$message = "Il numero telefonico e' un campo obbligatorio";
			} else {
				$sql = "SELECT count(*) FROM `anagrafica` WHERE data_out = '1970-01-01 00:00:00' AND id != '".$id_pers."' AND tel = '".$tel."';";
				$rs_cod = $db->Execute($sql);
				if ($rs_cod->fields[0] < 1) {

					$sql = "UPDATE anagrafica SET ";
					$sql .= "idqual = '".$idqual."', ";
					$sql .= "nome = ".$db->qstr($nome).", ";
					$sql .= "cognome = ".$db->qstr($cognome).", ";
					$sql .= "tel = ".$db->qstr($tel).", ";
					$sql .= "tenda = '".$tenda."', ";
					$sql .= "comando = '".$comando."', ";
					$sql .= "idmansione = '".$idmansione."', ";
					$sql .= "data_in = ".$db->DBTimeStamp($d_in).", ";
					$sql .= "data_out_pres = ".$db->DBTimeStamp($d_out_pres).", ";
					$sql .= "data_out = ".$db->DBTimeStamp($d_out)." ";
					$sql .= "WHERE id = '".$id_pers."';";

					if ($db->Execute($sql) === false) {
						$message = 'error modifing: '.$db->ErrorMsg().'<br />';
						$message_class = "ko";
					} else {
						$message = "Il nominativo ".$nome." ".$cognome." &egrave; stato modificato correttamente";
					}

				} else {
					$message_class = "ko";
					$message = "Errore: Record gia' presente in archivio, verifica l'unicita' del numero telefonico !";
				}
			}
		};
		
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
			</div>
			<?php
			if ($message_class == "ok") {
				?>
				<script type="text/javascript" charset="utf-8">
					setTimeout(function() {
					  window.location.href = "<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?id=<?php echo $_POST['id'] ?>";
					}, 3000);
				</script>
				<?php
			}
			?>
			<p><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?id=<?php echo $_POST['id']?>">Torna ai dettagli di <?php echo $nome." ".$cognome ?></a></p>
			<?php
		}
		
		if (isset($_GET['id'])) {
			$sql = "SELECT a.* FROM `anagrafica` AS a WHERE a.id = ".$_GET['id'];
			$rs = $db->Execute($sql);
			$r = $rs->GetRowAssoc();
			?>
			<div class="dettagli">
				<h3>Dettagli della persona selezionata</h3>
				<form id="form_dettagli" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" accept-charset="utf-8">
					<p><button id="modify">Abilita modifiche</button></p>
					<p>ID:
						<span id="id"><?php echo $r['ID'] ?></span>
						<input type="hidden" name="id" value="<?php echo $r['ID'] ?>" id="id" />
						<input type="hidden" name="modifica" value="1" id="modifica" />
						</p>

					<p class="evidente"><label for="qualifica">Qualifica</label>
						<select name="qualifica" id="qualifica" size="1">
							<?php
							$sql = "SELECT * FROM qualifica ORDER BY SIGLA;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['IDQUAL'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
								$rsl->MoveNext();
							}
							?>
						</select></p>

					<p class="evidente"><label for="nome">Nome</label>
						<input type="text" name="nome" value="<?php echo $r['NOME'] ?>" id="nome" /></p>
						
					<p class="evidente"><label for="cognome">Cognome</label>
						<input type="text" name="cognome" value="<?php echo $r['COGNOME'] ?>" id="cognome" /></p>

					<p class="evidente"><label for="tel">Telefono</label>
						<input type="text" name="tel" value="<?php echo $r['TEL'] ?>" id="tel" /></p>

					<p class="evidente"><label for="tenda">Tenda</label>
						<input type="text" name="tenda" value="<?php echo $r['TENDA'] ?>" id="tenda" /></p>

					<p class="evidente"><label for="comando">Comando</label>
						<select name="comando" id="comando" size="1">
							<?php
							$sql = "SELECT * FROM comandi;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['COMANDO'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
								$rsl->MoveNext();
							}
							?>
						</select>
					</p>
					<p class="evidente"><label for="mansione">Mansione</label>
						<select name="mansione" id="mansione" size="1">
							<?php
							$sql = "SELECT * FROM mansioni;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['IDMANSIONE'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
								$rsl->MoveNext();
							}
							?>
						</select>
					</p>
					<p class="evidente"><label for="arrivo">Data e ora arrivo:</label>
						<?php
							$datetime = strtotime($r['DATA_IN']);
						?>
						<input type="text" name="arrivo" id="arrivo" value="<?php echo date("d/m/Y H:i",$datetime) ?>" />
						<button class="time" value="1" id="arrivo_setdt">Ora Attuale</button>
					</p>
					<p class="evidente"><label for="rientro_pres">Data rientro presunta:</label>
						<?php
							$datetime = strtotime($r['DATA_OUT_PRES']);
						?>
						<input type="text" name="rientro_pres" id="rientro_pres" value="<?php echo date("d/m/Y",$datetime) ?>" />
					</p>
					<p class="evidente"><label for="rientro">Data e ora rientro:</label>
						<?php
							$datetime = strtotime($r['DATA_OUT']);
						?>
						<input type="text" name="rientro" id="rientro" value="<?php echo date("d/m/Y H:i",$datetime) ?>" />
						<button class="time" value="1" id="rientro_setdt">Ora Attuale</button>
					</p>

					<p class="evidente"><input type="submit" value="Salva &rarr;" /></p>
				</form>
			</div>
			<?php
		}
	?>
<?php include("footer.php");  ?>
