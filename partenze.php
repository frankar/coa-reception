<?php include("header.php"); ?>
<script src="js/autocomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="js/partenze.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		$("input[name='opz_rientro']").change(function(){
			if ($("input[name='opz_rientro']:checked").val() == 'c') {
				$("#form1 #nome").parent().show('slow');
			} else {
				$("#form1 #nome").parent().hide('slow');
				
			}
		});
	});
</script>
<?php include("menu.php"); ?>
	<h2>Gestione Rientri ai Comandi</h2>
	<?php
	$message = '';
	$message_class = "ok";
	$stop = 0;
	


	// E' stata passata l'ora di rientro
	if (isset($_POST['rientro'])) {
		$rientro = $_POST['rientro'];
		if (strlen($rientro) <> 16) {
			$message = "Formato data non corretto !";
			$message_class = "ko";
		} else {
			$a_data_ora = explode(' ',$rientro);
			$a_data = explode('/',$a_data_ora[0]);
			$a_ora = explode(':',$a_data_ora[1]);

			$data_timestamp = mktime($a_ora[0],$a_ora[1],0,$a_data[1],$a_data[0],$a_data[2]);
			$ora_out = date("Y-m-d H:i:s",$data_timestamp);
		}
	} else {
		$rientro = '';
	}
	
	// E' stato inviato il form principale
	if (isset($_POST['inviato'])) {
		$inviato = $_POST['inviato'];
	} else {
		$inviato = '';
	}
	
	// E' stato indicato l'id della persona che rientra
	if (isset($_POST['id_person'])) {
		if(is_numeric($_POST['id_person'])) {
			$id_person = $_POST['id_person'];
		} else {
			$id_person = 0;
		}
	} else {
		$id_person = 0;
	}
	
	// E' stato richiesto il cambio di responsabile
	if (isset($_POST['cambio_resp'])) {
		$cambio_resp = $_POST['cambio_resp'];
		if($cambio_resp == 1) {
			// E' stato selezionata un'opzione di rientro
			if (isset($_POST['opz_rientro'])) {
				$opz_rientro = $_POST['opz_rientro'];
			} else {
				$opz_rientro = '';
			}

			// E' stata indicato il vecchio responsabile
			if (isset($_POST['old_resp'])) {
				$old_resp = $_POST['old_resp'];
			} else {
				$old_resp = '';
			}
			
		} else {
			$cambio_resp = 0;
		}
	} else {
		$cambio_resp = 0;
	}
	
	if ($cambio_resp == "1") {
		if ($opz_rientro == 'r') {
			// Rientrano anche i mezzi del responsabile
			if ($old_resp > 0) {
				$query = "UPDATE mezzi SET data_out = '".$ora_out."' WHERE id_resp = '".$old_resp."';";
				if ($db->Execute($query) === false) {
					$message .= 'error modifing: '.$db->ErrorMsg().'<br />';
					$message_class = "ko";
				} else {
					$message = "Uscita dei mezzi associati al responsabile effettuata con successo";
					$query = "UPDATE anagrafica SET data_out = '".$ora_out."' WHERE id= '".$old_resp."';";
					$db->Execute($query);
					$message .= " e uscita nominativo aggiornata correttamente";
				}
			} else {
				$message = "Errore nel passaggio del parametro del vecchio responsabile";
				$message_class = "ko";
			}
		} elseif ($opz_rientro == 'c') {
			// Cambio responsabile
			if($id_person > 0 and $old_resp > 0) {
				$query = "UPDATE mezzi SET id_resp = '".$id_person."' WHERE id_resp = '".$old_resp."';";
				if ($db->Execute($query) === false) {
					$message = 'error modifing: '.$db->ErrorMsg().'<br />';
					$message_class = "ko";
				} else {
					$message = "Cambio responsabile effettuata con successo";
					$query = "UPDATE anagrafica SET data_out = '".$ora_out."' WHERE id= '".$old_resp."';";
					$db->Execute($query);
					$message .= " e uscita nominativo aggiornata correttamente";
				}
				
			} else {
				$message = "Non e' stato selezionato correttamente il vecchio responsabile o il nuovo responsabile";
				$message_class = "ko";
			}
		} else {
			$message = "Errore nel parametro di scelta rientro";
			$message_class = "ko";
		}
	}
	elseif ($inviato == 1) {
		if ($message_class == "ok") {
			// E' stato passato un id persona valido
			if ($id_person > 0) {
				$query = "SELECT count(*) FROM `mezzi` WHERE data_out = '1970-01-01 00:00:00' AND `id_resp` = '".$id_person."';";
				$rsn = $db->Execute($query);
				$nmezzi = $rsn->fields[0];
				if ($nmezzi > 0) {
					// Questo nominativo e' responsabile di uno o piu' mezzi
					uscita_resp($id_person, $nmezzi, $rientro);
					$stop = 1;
				} else {
					// posso fare l'uscita del nominativo
					$query = "UPDATE anagrafica SET data_out = '".$ora_out."' WHERE id= '".$id_person."';";
					$db->Execute($query);
					$message = "Uscita aggiornata correttamente";
				}
			} else {
				$message = "Non e' stato selezionato un nominativo valido";
				$message_class = "ko";
			}
		}
	} 
	
	// Prima apertura pagina, nessun parametro passato
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
	if ($stop == 0) {
		?>
		<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">

			<p class="evidente"><label for="nome">Nome:</label>
				<input type="text" name="nome" id="nome" />
				<input type="hidden" id="id_person" name="id_person" /></p>
			<p class="evidente"><label for="rientro">Data e ora rientro:</label>
				<input type="text" name="rientro" id="rientro" />
				<button value="1" id="rientro_setdt">Ora Attuale</button></p>
			<p><input type="hidden" id="inviato" name="inviato" value="1" />
			<input type="submit" value="Conferma Partenza &rarr;" /></p>
		</form>

		<div id="log">
			<h2>Ultime operazioni</h2>
			<?php
				$result = $db->Execute("SELECT id,nome,cognome,tel,data_in,data_out_pres,data_out FROM `anagrafica` ORDER BY `data_out` DESC LIMIT 10");
				if ($result === false) die("failed");
				?><table class="pretty-table">
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Nome</th>
						<th scope="col">Cognome</th>
						<th scope="col">Telefono</th>
						<th scope="col">Data Ingresso</th>
						<th scope="col">Data Uscita Presunta</th>
						<th scope="col">Data Uscita</th>
						<th scope="col">Azioni</th>
					</tr><?php
				while (!$result->EOF) {
					?><tr><?php
					for ($i=0, $max=$result->FieldCount(); $i < $max; $i++) {
						?><td><?php echo $result->fields[$i] ?></td><?php
					}
					?><td><a href="annulla_uscita.php?id=<?php echo $result->fields[0] ?>">Annulla</a></td></tr><?php
				   $result->MoveNext();
				}
			?></table>  
		</div>
		<?php
	}
	
	?>
<?php include("footer.php");  ?>
<?php

function uscita_resp($id_person,$nmezzi,$rientro) {
	global $db;
	$query = "SELECT q.sigla, a.cognome, a.nome, a.tel FROM anagrafica AS a INNER JOIN qualifica AS q ON q.id = a.idqual WHERE a.id = '".$id_person."';";
	$rs = $db->Execute($query);
	$r = $rs->GetRowAssoc();
	?>
	<div class="attention">
		<h3>Attenzione !</h3>
		<p class="evidente"><?php echo $r['SIGLA']." ".$r['COGNOME']." ".$r['NOME']." Tel. ".$r['TEL'] ?> risulta responsabile dei seguenti mezzi:</p>
		<?php
		$query = "SELECT t.nome as tipo, m.targa, c.nome, m.data_in FROM `mezzi` AS m INNER JOIN tipi_mezzi AS t ON t.id = m.id_tipo INNER JOIN comandi as c ON c.id = m.id_comando WHERE `id_resp` = '".$id_person."';";
		$rs = $db->Execute($query);
		// Memorizzo il responsabile perche' poi potrebbe cambiare
		$old_resp = $id_person;
		?>
		<table class="pretty-table">
			<thead>
				<tr>
					<th>Tipo</th>
					<th>Targa</th>
					<th>Comando</th>
					<th>Data Ingresso</th>
				</tr>
			</thead>
			<tbody>
		<?php
		while (!$rs->EOF) {
			?><tr>
				<?php for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) {
					?><td><?php echo $rs->fields[$i] ?></td><?php
				} ?>
			  </tr>
			<?php
			$rs->MoveNext();
		}
		?>
			</tbody>
		</table>
	</div>
	
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
		<fieldset class="evidente">
			<legend>Selezionare una delle seguenti opzioni</legend>
			<input type="radio" name="opz_rientro" value="r" id="opz_rientro_r" checked="checked">
			<label for="opz_rientro_r"><?php echo ($nmezzi > 1 ? 'Rientrano anche i mezzi':'Rientra anche il mezzo') ?></label>
			<br />
			<input type="radio" name="opz_rientro" value="c" id="opz_rientro_c">
			<label for="opz_rientro_c">Cambiare il responsabile di quest<?php echo ($nmezzi > 1 ? 'i':'o') ?> mezz<?php echo ($nmezzi > 1 ? 'i':'o') ?></label>
		</fieldset>

		<p class="evidente" style="display:none"><label for="nome">Nuovo responsabile</label>
			<input type="text" name="nome" id="nome" /></p>

		<p>
			<input type="hidden" name="rientro" value="<?php echo $rientro ?>" id="rientro">
			<input type="hidden" id="cambio_resp" name="cambio_resp" value="1" />
			<input type="hidden" id="id_person" name="id_person" value="" />
			<input type="hidden" id="old_resp" name="old_resp" value="<?php echo $old_resp ?>" />
			<input type="submit" value="Procedi &rarr;"></p>
	</form>
	
	<?php
	
}
?>