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

	// Arrivo
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
			$id_mezzo = sanitize($_POST['id']);
			$tipo = $_POST['tipo'];
			$targa = sanitize($_POST['targa']);
			$resp = $_POST['resp'];
			$comando = $_POST['comando'];

			$data_in = $_POST['arrivo'];	
			$a_data_ora = explode(' ',$data_in);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);
			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$d_in = date("Y-m-d H:i:s",$data_timestamp);

			$data_out = $_POST['rientro'];
			$a_data_ora = explode(' ',$data_out);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);
			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$d_out = date("Y-m-d H:i:s",$data_timestamp);
			
			if ($targa == '') {
				$message = 'Targa non valida';
				$message_class = "ko";
			} else {
				// controllo che la targa sia unica
				$sql = "SELECT count(*) FROM mezzi WHERE data_out = '1970-01-01 00:00:00' AND id != '".$id_mezzo."' AND targa = '".$_POST['targa']."';";
				$rs_cod = $db->Execute($sql);
				if ($rs_cod->fields[0] > 0) {
					$message_class = "ko";
					$message = 'La targa '.$targa.' &egrave; gi&agrave; presente in archivio !';				
				} else {
					$sql = "UPDATE mezzi SET ";
					$sql .= "id_tipo = '".$tipo."', ";
					$sql .= "targa = ".$db->qstr($targa).", ";
					$sql .= "id_resp = '".$resp."', ";
					$sql .= "id_comando = '".$comando."', ";
					$sql .= "data_in = '".$d_in."', ";
					$sql .= "data_out = '".$d_out."' ";
					$sql .= "WHERE id = '".$_POST['id']."';";

					if ($db->Execute($sql) === false) {
						$message = 'error modifing: '.$db->ErrorMsg().'<br />Sql: '.$sql;
						$message_class = "ko";
					} else {
						$message = "Il mezzo VF ".$targa." &egrave; stato modificato correttamente";
					}
				}
			}
		};
		
		if ($message <> '') {
			$soundfile = ($message_class == "ko") ? "error" : "ok";
			?>
			<div id="message" class="<?php echo $message_class ?>"><?php echo $message ?>
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
					  window.location.href = "dettagli_mezzi.php?id=<?php echo $_POST['id'] ?>";
					}, 2000);
				</script>
				<?php
			}
			?>
			<p><a href="dettagli_mezzi.php?id=<?php echo $_POST['id']?>">Torna ai dettagli del mezzo</a></p>
			<?php
		}
		
		if (isset($_GET['id'])) {
			$sql = "SELECT m.* FROM `mezzi` AS m WHERE m.id = ".$_GET['id'];
			$rs = $db->Execute($sql);
			$r = $rs->GetRowAssoc();
			?>
			<div class="dettagli">
				<h3>Dati Mezzo</h3>
				<form id="form_dettagli" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" accept-charset="utf-8">
					<p><button id="modify">Abilita modifiche</button></p>
					<p>ID:
						<span id="id"><?php echo $r['ID'] ?></span>
						<input type="hidden" name="id" value="<?php echo $r['ID'] ?>" id="id" />
						<input type="hidden" name="modifica" value="1" id="modifica" />
						</p>



					<p><label for="tipo">Tipo Mezzo</label>
						<select name="tipo" id="tipo" size="1">
							<?php
							$sql = "SELECT * FROM tipi_mezzi ORDER BY nome;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['ID_TIPO'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
								$rsl->MoveNext();
							}
							?>
						</select>
					</p>

					<p class="evidente"><label for="targa">Targa</label>
						<input type="text" name="targa" value="<?php echo $r['TARGA'] ?>" id="targa" /></p>

					<p><label for="resp">Responsabile</label>
						<select name="resp" id="resp" size="1">
							<?php
							$sql = "SELECT * FROM anagrafica WHERE data_out = '1970-01-01 00:00:00' ORDER BY cognome;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['ID_RESP'] == $rsl->fields[0]) ? 'selected="selected"':'' ?>value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[3] ?> <?php echo $rsl->fields[2] ?> Tel.<?php echo $rsl->fields[4] ?></option><?php
								$rsl->MoveNext();
							}
							?>
						</select>
					</p>


					<p><label for="comando">Comando</label>
						<select name="comando" id="comando" size="1">
							<?php
							$sql = "SELECT id,nome,esteso FROM comandi;";
							$rsl = $db->Execute($sql);
							while (!$rsl->EOF) {
								?><option <?php echo ($r['ID_COMANDO'] == $rsl->fields[0]) ? 'selected="selected"':'' ?> value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?><?php echo ($rsl->fields[2] != '' ? ' - '.$rsl->fields[2] : '') ?></option><?php
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
					<p class="evidente"><label for="rientro">Data e ora rientro:</label>
						<?php
							$datetime = strtotime($r['DATA_OUT']);
						?>
						<input type="text" name="rientro" id="rientro" value="<?php echo date("d/m/Y H:i",$datetime) ?>" />
						<button class="time" value="1" id="rientro_setdt">Ora Attuale</button>
					</p>

					<p><input type="submit" value="Salva &rarr;" /></p>
				</form>
			</div>
			<?php
		}
	?>
<?php include("footer.php");  ?>
