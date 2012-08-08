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

			$all = array();
			$ad = array();
			?>
			<table class="riepilogo">
				<caption><h3>Riepilogo mail per i Comandi del giorno <?php echo $data_mail ?></h3></caption>
				<tbody>
					<?php
					$sql = "SELECT c.id, c.nome, c.esteso, c.mail, c.id_dir, d.esteso, d.mail FROM comandi AS c INNER JOIN comandi AS d ON c.id_dir = d.id INNER JOIN anagrafica AS a ON a.comando = c.id WHERE (a.data_in >= ".$db->qstr($d_mail)." and a.data_in < ".$db->qstr($d_mail)." + interval 1 day) OR (a.data_out >= ".$db->qstr($d_mail)." and a.data_out < ".$db->qstr($d_mail)." + interval 1 day) GROUP BY comando ";
					$rs = $db->Execute($sql);
					while (!$rs->EOF) {
						
						// se non c'e' ancora inserisco il comando nell'array all
						if(!array_key_exists($rs->fields[0],$all)) {
							$all[$rs->fields[0]] = array(
								'sigla' => $rs->fields[1],
								'nome' => $rs->fields[2],
								'mail' => $rs->fields[3]
							);
						}

						// inserisco la direzione
						if(!array_key_exists($rs->fields[4],$ad)) {
							$ad[$rs->fields[4]]['nome'] = $rs->fields[5];
							$ad[$rs->fields[4]]['mail'] = $rs->fields[6];
						}
						$ad[$rs->fields[4]]['com'][$rs->fields[0]] = array(
							'sigla' => $rs->fields[1],
							'nome' => $rs->fields[2]
							);
						
						?>
						<tr>
							<td colspan="2"><div class="nome_com">Comando di <?php echo $rs->fields[2], ' (', $rs->fields[1], ')'?></div>
							<div class="mail_com"><?php echo $rs->fields[3] ?></div>
							</td>
						</tr>
						<tr>
							<td class="iu">
								<?php
								$sql2 = "SELECT q.sigla as qual, a.nome, a.cognome FROM anagrafica as a INNER JOIN qualifica AS q ON a.idqual = q.id WHERE (a.data_in >= ".$db->qstr($d_mail)." AND a.data_in < ".$db->qstr($d_mail)." + interval 1 day) AND (comando = '".$rs->fields[0]."') ORDER BY cognome";
								$rs2 = $db->Execute($sql2);
								if ($rs2->RecordCount() > 0) {
									
									// se non c'e' ancora preparo l'array I per gli ingressi
									// if(!array_key_exists('I',$all[$rs->fields[0]])) {
									// 	$all[$rs->fields[0]]['I'] = array();
									// }
									
									?>
									<table class="pretty-table">
										<caption>Ingressi</caption>
										<thead>
										<tr>
											<?php
											for ($i=0; $i < $rs2->FieldCount(); $i++) {
												$fld = $rs2->FetchField($i)->name;
												?><th><?php echo $fld ?></th><?php
											}
											?>
										</tr></thead><tbody>
										<?php
										while (!$rs2->EOF) {
											?>
											<tr>
												<?php
												$a_person = array();
												for ($i=0; $i < $rs2->FieldCount(); $i++) {
													$a_person[$rs2->FetchField($i)->name] = $rs2->Fields($i);
													?><td><?php echo $rs2->Fields($i) ?></td><?php
												}
												$all[$rs->fields[0]]['I'][] = $a_person;
												$ad[$rs->fields[4]]['com'][$rs->fields[0]]['I'][] = $a_person;
												?>
											</tr>
											<?php
											$rs2->MoveNext();
										}
										?></tbody>
									</table>
									<?php
								} else {
									?><p>Nessun Ingresso</p><?php
								}?>
							</td>
							<td class="iu">
								<?php
								$sql2 = "SELECT q.sigla as qual, a.nome, a.cognome FROM anagrafica as a INNER JOIN qualifica AS q ON a.idqual = q.id WHERE (a.data_out >= ".$db->qstr($d_mail)." AND a.data_out < ".$db->qstr($d_mail)." + interval 1 day) AND (comando = '".$rs->fields[0]."') ORDER BY cognome";
								$rs2 = $db->Execute($sql2);
								if ($rs2->RecordCount() > 0) {
									
									// se non c'e' ancora preparo l'array I per gli ingressi
									// if(!array_key_exists('I',$all[$rs->fields[0]])) {
									// 	$all[$rs->fields[0]]['U'] = array();
									// }
									
									?>
									<table class="pretty-table">
										<caption>Uscite</caption>
										<thead>
										<tr>
											<?php
											for ($i=0; $i < $rs2->FieldCount(); $i++) {
												$fld = $rs2->FetchField($i)->name;
												?><th><?php echo $fld ?></th><?php
											}
											?>
										</tr></thead><tbody>
										<?php
										while (!$rs2->EOF) {
											?>
											<tr>
												<?php
												// preparo l'array per i dettagli di una persona
												$a_person = array();
												for ($i=0; $i < $rs2->FieldCount(); $i++) {
													// inserisco un dettaglio
													$a_person[$rs2->FetchField($i)->name] = $rs2->Fields($i);
													?><td><?php echo $rs2->Fields($i) ?></td><?php
												}
												// aggiungo la persona all'array generale
												$all[$rs->fields[0]]['U'][] = $a_person;
												$ad[$rs->fields[4]]['com'][$rs->fields[0]]['U'][] = $a_person;
												?>
											</tr>
											<?php
											$rs2->MoveNext();
										}
										?></tbody>
									</table>
									<?php
								} else {
									?><p>Nessuna Uscita</p><?php
								}?>
							</td>
						</tr>
						<?php
						$rs->MoveNext();
					}
					?>
				</tbody>
			</table>

<pre>
	<?php
	// print_r($ad);
	?>
</pre>	
			<?php
			foreach($ad as $k => $v) {
				?>
				<table class="riepilogo">
					<caption><h3>Riepilogo mail per la <?php echo $v['nome'] ?> del giorno <?php echo $data_mail ?></h3></caption>
					<tbody>
						<?php
						foreach($v['com'] as $kc => $vc) {
							?>
							<tr>
								<td colspan="2"><div class="nome_com">Comando di <?php echo $vc['nome'], ' (', $vc['sigla'], ')'?></div></td>
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
											<caption>Ingressi</caption>
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
					<input type="hidden" name="all_com" value="<?php echo base64_encode(gzcompress(serialize($all))) ?>" id="all_com">
					<input type="hidden" name="all_dir" value="<?php echo base64_encode(gzcompress(serialize($ad))) ?>" id="all_dir">
					<input type="submit" value="Conferma invio mail &rarr;"></p>
			</form>
			<?php
			msg($message, $message_class);
			break;
		case 3:
			# Posso inviare le mail ai comandi
			$all = unserialize(gzuncompress(base64_decode($_POST['all_com'])));
			$ad = unserialize(gzuncompress(base64_decode($_POST['all_dir'])));
			
			
			// foreach($all as $k => $v) {
			// 	$ad[$v['dir']['id']]['nome'] = $v['dir']['nome'];
			// 	$ad[$v['dir']['id']]['mail'] = $v['dir']['mail'];
			// 	$ad[$v['dir']['id']][$k]['sigla'] = $v['sigla'];
			// 	$ad[$v['dir']['id']][$k]['nome'] = $v['nome'];
			// 	if (array_key_exists('I',$v)) {
			// 		foreach($v['I'] as $vp) {
			// 			$ad[$v['dir']['id']][$k]['I'][] = array(
			// 				'qual'=> $vp['qual'],
			// 				'nome'=> $vp['nome'],
			// 				'cognome'=> $vp['cognome']
			// 				);
			// 		}
			// 	}
			// 	if (array_key_exists('U',$v)) {
			// 		foreach($v['U'] as $vp) {
			// 			$ad[$v['dir']['id']][$k]['U'][] = array(
			// 				'qual'=> $vp['qual'],
			// 				'nome'=> $vp['nome'],
			// 				'cognome'=> $vp['cognome']
			// 				);
			// 		}
			// 	}
			// }
			
			?>
				<pre>
					<?php print_r($ad) ?>
				</pre>
			<?php
			exit;

			// preparo l'array per l'invio alle direzioni
			$a_direz = array();
			foreach ($all as $k => $v) {
				$testo = "Si comunica che in data odierna ";	

				$oggetto = "Comunicazione ";
				if (array_key_exists('I',$v)) {
					$oggetto .= "Ingressi ";
					$testo .= "e' arrivato presso questo COA il seguente personale dalla Vostra sede:\r\n\r\n";
					foreach ($v['I'] as $p => $detail) {
						$testo .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']." ".$v['nome']."\r\n";
					}
					if (array_key_exists('U',$v)) {
						$oggetto .= "e ";
						$testo .= "\r\ninoltre ";
					}
				}
				if (array_key_exists('U',$v)) {
					$oggetto .= "Uscite dal ";
					$testo .= "e' partito da questo COA verso la Vostra sede il seguente personale:\r\n\r\n";
					foreach ($v['U'] as $p => $detail) {
						$testo .= $detail['qual']." ".$detail['cognome']." ".$detail['nome']." ".$v['nome']."\r\n";
					}
				}

				$oggetto .= "COA del giorno ". $_POST['data']."\r\n";

				//$destinatario = $v['mail'];
				$destinatario = "franco.carinato@gmail.com";

				$intestazioni = "From: ".$options['nome_coa']. " <".$options['mail_coa'].">";
				$intestazioni .= "\r\nReply-To: ".$options['mail_coa'];
				// $intestazioni .= "\r\nCc: ".$options['mail_cc'];
				// $intestazioni .= "\r\nBcc: her@$herdomain";
				$intestazioni .= "\r\nX-Mailer: PHP/" . phpversion();
				$intestazioni .= "\r\n";

				// costruisco il corpo del messaggio
				$corpo = "Mail inviata da: ". $options['nome_coa'];
				$corpo .= " (". $options['mail_coa'] . ")\r\n";
				$corpo .= "Destinatario: ". $v['nome']. " ".$v['sigla']." (".$v['mail'].")\r\n";
				$corpo .= "Oggetto messaggio: " . $oggetto ."\r\n";
				$corpo .= "Testo messaggio: \r\n";
				$corpo .= $testo . "\r\n";
				$corpo .= "\r\n---------------------------------------------\r\n";
				$corpo .= "Email inviata automaticamente tramite programma di gestione personale\r\n";

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
			
		case 4:
			#####
			break;
			
		default:
			# Form iniziale
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
	if ($m <> '') {
		$soundfile = ($c == "ko") ? "error" : "ok";
		?><div id="message" class="<?php echo $class ?>"><?php echo $m ?>
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