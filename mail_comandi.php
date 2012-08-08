<?php include("header.php"); ?>
<script src="js/mail_com.js" type="text/javascript" charset="utf-8"></script>
<?php include("menu.php"); ?>
	<h2>Invio Mail Movimenti Giornalieri ai Comandi</h2>
	
<?php
$message = '';
$message_class = "ok";

if (isset($_POST['inviato'])) {
	switch ($_POST['inviato']) {
		case 1:
			# Ho inserito la data
			$data_mail = $_POST['data'];
			$a_data = explode('/',$data_mail);
			$data_timestamp = mktime(0,0,0,$a_data[1],$a_data[0],$a_data[2]);
			$d_mail = date("Y-m-d H:i:s",$data_timestamp);

			$sql = "SELECT c.id, c.nome, c.esteso, c.mail FROM comandi AS c INNER JOIN anagrafica AS a ON a.comando = c.id WHERE (a.data_in >= ".$db->qstr($d_mail)." and a.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (a.data_out >= ".$db->qstr($d_mail)." and a.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY comando ";
			$sql .= "UNION ";
			$sql .= "SELECT c.id, c.nome, c.esteso, c.mail FROM comandi AS c INNER JOIN mezzi AS m ON m.id_comando = c.id WHERE (m.data_in >= ".$db->qstr($d_mail)." and m.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (m.data_out >= ".$db->qstr($d_mail)." and m.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY id_comando ";

			$rs = $db->Execute($sql);

			if ($rs->RecordCount() > 0) {
				//$comandi = 
				?>
				<h3>Controllare i dati dei seguenti comandi</h3>
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
					<?php
					while (!$rs->EOF) {
						?>
						<fieldset style="margin-top:1em" id="com_<?php echo $rs->fields[0] ?>">
							<legend>Comando <?php echo $rs->fields[1] ?></legend>
							<p><label for="sigla_<?php echo $rs->fields[0] ?>">Sigla</label>
								<input style="width:50%" type="text" name="sigla_<?php echo $rs->fields[0] ?>" value="<?php echo $rs->fields[1] ?>" id="sigla_<?php echo $rs->fields[0] ?>"></p>
							<p><label for="nome_<?php echo $rs->fields[0] ?>">Nome</label>
								<input style="width:50%" type="text" name="nome_<?php echo $rs->fields[0] ?>" value="<?php echo $rs->fields[2] ?>" id="nome_<?php echo $rs->fields[0] ?>"></p>
							<p><label for="email_<?php echo $rs->fields[0] ?>">E.mail</label>
								<input style="width:50%" type="text" name="email_<?php echo $rs->fields[0] ?>" value="<?php echo $rs->fields[3] ?>" id="email_<?php echo $rs->fields[0] ?>"></p>
						</fieldset>
						<?php
						$rs->MoveNext();
					}
					?>

					<p><input type="hidden" id="inviato2" name="inviato" value="2" />
						<input type="hidden" id="data" name="data" value="<?php echo $data_mail ?>" />
						<input type="submit" value="Continua &rarr;"></p>
				</form>
				<?php
			} else {
				$message = "Non sono stati registrati movimenti nella data selezionata";
				$message_class = "ko";
			}
			msg($message, $message_class);
			break;
		case 2:
			# Eventaulmente modifico i dati dei comandi
			$data_mail = $_POST['data'];
			$a_data = explode('/',$data_mail);
			$data_timestamp = mktime(0,0,0,$a_data[1],$a_data[0],$a_data[2]);
			$d_mail = date("Y-m-d H:i:s",$data_timestamp);

			// modifico i dati dei comandi
			$sql = "SELECT c.id FROM comandi AS c INNER JOIN anagrafica AS a ON a.comando = c.id WHERE (a.data_in >= ".$db->qstr($d_mail)." and a.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (a.data_out >= ".$db->qstr($d_mail)." and a.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY comando ";
			$sql .= "UNION ";
			$sql .= "SELECT c.id FROM comandi AS c INNER JOIN mezzi AS m ON m.id_comando = c.id WHERE (m.data_in >= ".$db->qstr($d_mail)." and m.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (m.data_out >= ".$db->qstr($d_mail)." and m.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY id_comando ";
			$rs = $db->Execute($sql);
			while (!$rs->EOF) {
				$sql = "UPDATE comandi SET nome = '".$_POST['sigla_'.$rs->fields[0]]."', esteso = '".$_POST['nome_'.$rs->fields[0]]."', mail = '".$_POST['email_'.$rs->fields[0]]."' WHERE id = '".$rs->fields[0]."';";
				$db->Execute($sql);
				$rs->MoveNext();
			}

			//$all = array();
			$ad = array();
			$sql = "SELECT c.id, c.nome, c.esteso, c.mail, c.id_dir, d.esteso, d.mail FROM comandi AS c INNER JOIN comandi AS d ON c.id_dir = d.id INNER JOIN anagrafica AS a ON a.comando = c.id WHERE (a.data_in >= ".$db->qstr($d_mail)." and a.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (a.data_out >= ".$db->qstr($d_mail)." and a.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY comando ";
			$rs = $db->Execute($sql);
			while (!$rs->EOF) {
				
				// inserisco la direzione
				if(!array_key_exists($rs->fields[4],$ad)) {
					$ad[$rs->fields[4]]['nome'] = $rs->fields[5];
					$ad[$rs->fields[4]]['mail'] = $rs->fields[6];
				}
				$ad[$rs->fields[4]]['com'][$rs->fields[0]] = array(
					'sigla' => $rs->fields[1],
					'nome' => $rs->fields[2],
					'mail' => $rs->fields[3]
					);
				
					// INGRESSI
				$sql2 = "SELECT q.sigla as qual, a.nome, a.cognome FROM anagrafica as a INNER JOIN qualifica AS q ON a.idqual = q.id WHERE (a.data_in >= ".$db->qstr($d_mail)." AND a.data_in < ".$db->qstr($d_mail)." + interval 1 day) AND (comando = '".$rs->fields[0]."') ORDER BY cognome";
				$rs2 = $db->Execute($sql2);
				if ($rs2->RecordCount() > 0) {
						while (!$rs2->EOF) {
								$a_person = array();
								for ($i=0; $i < $rs2->FieldCount(); $i++) {
									$a_person[$rs2->FetchField($i)->name] = $rs2->Fields($i);
								}
								$ad[$rs->fields[4]]['com'][$rs->fields[0]]['I'][] = $a_person;
							$rs2->MoveNext();
						}
				}

				// USCITE
				$sql2 = "SELECT q.sigla as qual, a.nome, a.cognome FROM anagrafica as a INNER JOIN qualifica AS q ON a.idqual = q.id WHERE (a.data_out >= ".$db->qstr($d_mail)." AND a.data_out < ".$db->qstr($d_mail)." + interval 1 day) AND (comando = '".$rs->fields[0]."') ORDER BY cognome";
				$rs2 = $db->Execute($sql2);
				if ($rs2->RecordCount() > 0) {
						while (!$rs2->EOF) {
								// preparo l'array per i dettagli di una persona
								$a_person = array();
								for ($i=0; $i < $rs2->FieldCount(); $i++) {
									// inserisco un dettaglio
									$a_person[$rs2->FetchField($i)->name] = $rs2->Fields($i);
								}
								// aggiungo la persona all'array generale
								$ad[$rs->fields[4]]['com'][$rs->fields[0]]['U'][] = $a_person;
							$rs2->MoveNext();
						}
				}
				
				$rs->MoveNext();
			}

			// Genero la tabella 
			foreach($ad as $k => $v) {
				?>
				<table class="riepilogo">
					<caption>
						<h3>Riepilogo mail per la <?php echo $v['nome'] ?> del giorno <?php echo $data_mail ?></h3>
						<div class="mail_com"><?php echo $v['mail'] ?></div>
					</caption>
					<tbody>
						<?php
						foreach($v['com'] as $kc => $vc) {
							?>
							<tr>
								<td colspan="2">
									<div class="nome_com">Comando di <?php echo $vc['nome'], ' (', $vc['sigla'], ')'?></div>
									<div class="mail_com"><?php echo $vc['mail'] ?></div>
								</td>
							</tr>
							<tr>
								<?php
								foreach(array('I','U') as $t) {
									?>
									<td class="iu">
										<?php
										if (array_key_exists($t,$vc)) {
										?>
										<table class="pretty-table">
											<caption><?php echo ($t == 'I') ? " Ingressi" : "Uscite"; ?></caption>
											<thead>
											<tr>
												<?php
												foreach(array_keys($vc[$t][0]) as $k ) {
													?><th><?php echo $k ?></th><?php
												}
												?>
											</tr></thead><tbody>
											<?php
											foreach($vc[$t] as $p) {
												?>
												<tr>
													<?php
													foreach($p as $detail) {
														?>
															<td><?php echo $detail ?></td>
														<?php
													}
													?>
												</tr>
												<?php
											}
											?></tbody>
										</table>
										<?php	
										} else {
											?><p>Nessun<?php
											echo ($t == 'I') ? " Ingresso" : "a Uscita";
											?></p><?php
										}
										?>
									</td>
									<?php
								}
								?>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>	
				<?php
			}
			
			?>
			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
				<p class="evidente">
					<input type="hidden" id="data" name="data" value="<?php echo $data_mail ?>" />
					<input type="hidden" id="inviato3" name="inviato" value="3" />
					<input type="hidden" name="all_dir" value="<?php echo base64_encode(gzcompress(serialize($ad))) ?>" id="all_dir">
					<input type="submit" value="Conferma invio mail &rarr;"></p>
			</form>
			<?php
			msg($message, $message_class);
			break;
			
		case 3:
			# Posso inviare le mail ai comandi
			$ad = unserialize(gzuncompress(base64_decode($_POST['all_dir'])));			

			foreach ($ad as $k => $v) {
				$testo = "Si comunica che in data odierna ";	
				$oggetto = "PROVA Comunicazione ";
				
				// Controllo se a livello direzione ci sono stati ingressi o uscite
				$in = FALSE;
				$out = FALSE;
				foreach ($v['com'] as $vc) {
					if (array_key_exists('I',$vc)) $in = TRUE;
					if (array_key_exists('U',$vc)) $out = TRUE;
					
					// Mail ai comandi
					$txtcom = "Si comunica che in data odierna ";	
					$objcom = "PROVA Comunicazione ";
					
					if (array_key_exists('I',$vc)) {
						$objcom .= "Ingressi ";
						$txtcom .= "e' ARRIVATO presso questo C.O.A. il seguente personale dal Vostro Comando:\r\n";
						foreach ($vc['I'] as $p => $detail) {
							$txtcom .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']."\r\n";
						}
						if (array_key_exists('U',$vc)) {
							$objcom .= "e ";
							$txtcom .= "\r\ninoltre ";
						}
						
					}
					if (array_key_exists('U',$vc)) {
						$objcom .= "Uscite dal ";
						$txtcom .= "e' PARTITO da questo C.O.A. verso il vostro Comando il seguente personale:\r\n";
						foreach ($vc['U'] as $p => $detail) {
							$txtcom .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']."\r\n";
						}
					}

					$objcom .= "COA del giorno ". $_POST['data']."\r\n";

					$destinatario = $vc['mail'];
					//$destinatario = "test.test@vigilfuoco.it";
					
					

					$intestazioni = "From: ".$options['nome_coa']. " <".$options['mail_coa'].">";
					$intestazioni .= "\r\nReply-To: ".$options['mail_coa'];
					//$intestazioni .= "\r\nCc: ".$options['mail_cc'];
					$intestazioni .= "\r\nBcc: ".$options['mail_coa'];
					$intestazioni .= "\r\nX-Mailer: PHP/" . phpversion();
					$intestazioni .= "\r\n";

					// costruisco il corpo del messaggio
					$corpo = "Mail inviata da: ". $options['nome_coa'];
					$corpo .= " (". $options['mail_coa'] . ")\r\n";
					$corpo .= "Destinatario: ". $vc['nome']. " ".$vc['sigla']." (".$vc['mail'].")\r\n";
					$corpo .= "Oggetto messaggio: " . $objcom ."\r\n";
					$corpo .= "Testo messaggio: \r\n";
					$corpo .= $txtcom . "\r\n";
					$corpo .= "\r\n---------------------------------------------\r\n";
					$corpo .= "Email inviata automaticamente tramite programma di gestione reception COA\r\n";

					// se l'invio va a buon fine do un messaggio positivo 
					if (mail($destinatario, $objcom, $corpo, $intestazioni)) {
						$message .= "E.mail inviata correttamente al Comando di ".$vc['nome']."<br />";
					// altrimenti do un mesaggio di mancato invio
					} else {
						$message .= "Errore nell'invio della mail a :".$vc['nome']."<br />";
						$message_class = 'ko';
					}
					// fine invio ai comandi
				}
				
				// Invio alle direzioni
				if ($in) {
					$oggetto .= "Ingressi ";
					$testo .= "e' ARRIVATO presso questo C.O.A. il seguente personale dai Vostri Comandi:\r\n";
					foreach ($v['com'] as $vc) {
						if (array_key_exists('I',$vc)) {
							$testo .= "\r\nComando: ".$vc['nome']." (".$vc['sigla'].")\r\n";
							foreach ($vc['I'] as $p => $detail) {
								$testo .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']."\r\n";
							}
						}
					}
					if ($out) {
						$oggetto .= "e ";
						$testo .= "\r\ninoltre ";
					}
				}
				if ($out) {
					$oggetto .= "Uscite dal ";
					$testo .= "e' PARTITO da questo C.O.A. verso i Comandi il seguente personale:\r\n";
					foreach ($v['com'] as $vc) {
						if (array_key_exists('U',$vc)) {
							$testo .= "\r\nComando: ".$vc['nome']." (".$vc['sigla'].")\r\n";
							foreach ($vc['U'] as $p => $detail) {
								$testo .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']."\r\n";
							}
						}
					}
				}

				$oggetto .= "COA del giorno ". $_POST['data']."\r\n";

				$destinatario = $v['mail'];
				$destinatario = "test.test@vigilfuoco.it";
				
				

				$intestazioni = "From: ".$options['nome_coa']. " <".$options['mail_coa'].">";
				$intestazioni .= "\r\nReply-To: ".$options['mail_coa'];
				$intestazioni .= "\r\nCc: ".$options['mail_cc'];
				$intestazioni .= "\r\nBcc: ".$options['mail_coa'];
				$intestazioni .= "\r\nX-Mailer: PHP/" . phpversion();
				$intestazioni .= "\r\n";

				// costruisco il corpo del messaggio
				$corpo = "Mail inviata da: ". $options['nome_coa'];
				$corpo .= " (". $options['mail_coa'] . ")\r\n";
				$corpo .= "Destinatario: ". $v['nome']." (".$v['mail'].")\r\n";
				$corpo .= "Oggetto messaggio: " . $oggetto ."\r\n";
				$corpo .= $testo . "\r\n";
				$corpo .= "\r\n---------------------------------------------\r\n";
				$corpo .= "Email inviata automaticamente tramite programma di gestione reception COA\r\n";

				// se l'invio va a buon fine do un messaggio positivo 
				if (mail($destinatario, $oggetto, $corpo, $intestazioni)) {
					$message .= "E.mail inviata correttamente a: ".$v['nome']."<br />";
				// altrimenti do un mesaggio di mancato invio
				} else {
					$message .= "Errore nell'invio della mail a :".$v['nome']."<br />";
					$message_class = 'ko';
				}
			}
			msg($message, $message_class);
			break;
						
	} // fine switch
} else {
	?>
	<form id="form_day" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
		<p><label for="data">Giorno di riferimento <span>selezionare il giorno a cui si riferisce la comunicazione</span></label>
			<input type="text" name="data" id="data" value="<?php echo date('d/m/Y') ?>"/></p>
		<p><input type="hidden" id="inviato" name="inviato" value="1" />
		<input type="submit" value="Continua &rarr;" /></p>
	</form>
	<?php
}
 
function msg($m, $c) {
	global $options;
	if ($m <> '') {
		$soundfile = ($c == "ko") ? "error" : "ok";
		?><div id="message" class="<?php echo $c ?>"><?php echo $m ?>
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
<?php include("footer.php");  ?>