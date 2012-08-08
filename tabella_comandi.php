<?php include("header.php"); ?>
<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/comandi.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Tabella Comandi</h2>
<?php
$message = '';
$message_class = "ok";

if (isset($_REQUEST['action'])) {
	if($_REQUEST['action'] == 'modifica_comando') {
		$id_target = sanitize($_POST['id_comando']);
		$sigla = sanitize($_POST['nome_comando']);
		$esteso = sanitize($_POST['nome_es_comando']);
		$mail = sanitize($_POST['mail_comando']);
		$dir = sanitize($_POST['dir']);
		$sql = "UPDATE `comandi` SET 
			`nome` = ".$db->qstr($sigla).", 
			`esteso` = ".$db->qstr($esteso).", 
			`mail` = ".$db->qstr($mail).",
			`id_dir` = ".$db->qstr($dir)."
			WHERE `comandi`.`id` = ".$id_target.";";
		if ($db->Execute($sql) === false) {
			$message = 'error modifing: '.$db->ErrorMsg().'<br />';
			$message_class = "ko";
			break;
		} else {
			$message = "Modifica Comando avvenuta con successo";
		}
	} elseif($_REQUEST['action'] == 'del') {
		$id_target = sanitize($_GET['id']);
		$sql = "SELECT COUNT(*) FROM anagrafica WHERE comando = '".$id_target."';";
		$rs = $db->Execute($sql);
		if ($rs->fields[0] > 0) {
			$message = 'Impossibile eliminare il comando in quanto <strong>'.$rs->fields[0]."</strong> ".($rs->fields[0] > 1 ? "persone sono associate" : "persona e' associata")." a questo comando !";
			$message_class = "ko";
			
		} else {
			$sql = "SELECT COUNT(*) FROM mezzi WHERE id_comando = '".$id_target."';";
			$rs = $db->Execute($sql);
			if ($rs->fields[0] > 0) {
				$message = 'Impossibile eliminare il comando in quanto <strong>'.$rs->fields[0]."</strong> ".($rs->fields[0] > 1 ? "mezzi sono associati" : "mezzo e' associato")." a questo comando !";
				$message_class = "ko";
			} else {
				$sql = "DELETE FROM `comandi` WHERE `comandi`.`id` = '".$id_target."';";
				if ($db->Execute($sql) === false) {
					$message = 'error deleting: '.$db->ErrorMsg().'<br />';
					$message_class = "ko";
					break;
				} else {
					$message = "Eliminazione Comando avvenuta con successo";
				}
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
	
}  

?>
	
<div id="mod_comando">
	<h2>Modifica Comando</h2>
	<form id="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
		<p class="evidente"><label for="id_comando">ID (sola lettura)</label>
		<input readonly="readonly" type="text" name="id_comando" id="id_comando" value="" />

		<p class="evidente"><label for="nome_comando">Sigla</label>
		<input type="text" name="nome_comando" id="nome_comando" value="" />

		<p class="evidente"><label for="nome_es_comando">Nome</label>
		<input style="width:25em" type="text" name="nome_es_comando" id="nome_es_comando" value="" />

		<p class="evidente"><label for="mail_comando">E.mail</label>
		<input style="width:25em" type="text" name="mail_comando" id="mail_comando" value="" />
		
		<p class="evidente"><label for="qual">Direzione</label>
			<select name="dir" id="dir" size="1">
				<?php
				$sql = "SELECT id, nome, esteso FROM comandi WHERE id = id_dir ORDER BY nome;";
				$rsl = $db->Execute($sql);
				if ($rsl === false) {
					die("failed ".$db->ErrorMsg());
				}
				while (!$rsl->EOF) {
					?><option value="<?php echo $rsl->fields[0] ?>"><?php echo $rsl->fields[1] ?></option><?php
					$rsl->MoveNext();
				}
				?>
			</select> <span class="button" id="add_dir">Aggiungi Direzione</span></p>
		<input type="hidden" name="action" value="modifica_comando" /></p>
		<p><input type="submit" value="Modifica Comando &rarr;" /></p>

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
		<p><span class="button">Aggiungi Direzione</span></p>

	</form>
</div>

<table class="pretty-table">
	<thead>
	<tr>
		<?php
		$sql = "SELECT c.`id` AS Id, c.`nome` AS Sigla, c.`esteso` AS Nome, c.`mail` as Email, d.nome AS Direzione, d.id FROM `comandi` AS c LEFT JOIN comandi AS d ON c.id_dir = d.id ORDER BY Sigla";
		$rs = $db->Execute($sql);
		if ($rs === false) {
			die("failed ".$db->ErrorMsg());
		} 
		
		for ($i=1; $i < $rs->FieldCount() -1 ; $i++) {
			$fld = $rs->FetchField($i)->name;
			?><th><?php echo $fld ?></th><?php
		}
		?><th>Azioni</th>
		
	</tr></thead><tbody>
	<?php
	while (!$rs->EOF) {
		?>
		<tr id="r_<?php echo $rs->Fields(0) ?>">
			<?php
			for ($i=1; $i < $rs->FieldCount() -1; $i++) {
				?><td class="f_<?php echo $rs->FetchField($i)->name ?>"><?php echo $rs->Fields($i); ?></td><?php
			}
			?><td><a class="mod_com" itemid="<?php echo $rs->Fields(0) ?>" itemdir="<?php echo $rs->Fields(5) ?>" href="#" title="Modifica"><img src="css/edit-icon.png" alt="Modifica" /></a>
				<a onClick="return confirmSubmit()" href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?action=del&amp;id=<?php echo $rs->Fields(0) ?>" title="Elimina"><img src="css/del-icon.png" alt="Elimina" /></a></td>
		</tr>
		<?php
		$rs->MoveNext();
	}
	?></tbody>
</table>

<?php include("footer.php");  ?>