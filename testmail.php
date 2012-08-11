<?php

$mittente = "coa.veneto@vigilfuoco.it";

$destinatario = "coa.modena@vigilfuoco.it";

$oggetto = "Prova Messaggio";

$testo = "Testo prova messaggio";


if (isset($_GET['action'])) {
	$intestazioni = "From: ".$mittente. " <".$mittente.">";
	$intestazioni .= "\r\nReply-To: ".$mittente."\r\n";
	if (mail($destinatario, $oggetto, $testo, $intestazioni)) {
		echo "============> E.mail inviata correttamente";
	} else {
		echo "============> Errore nell'invio della mail";
	}
}

?>

<p>Questo script fa un test di invio mail con questi parametri:</p>
<ul>
	<li>Mittente: <?php echo $mittente ?></li>
	<li>Destinatario: <?php echo $destinatario ?></li>
	<li>Oggetto: <?php echo $oggetto ?></li>
	<li>Testo: <?php echo $testo ?></li>
</ul>

<p><a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?action=1">Conferma Invio Mail</a></p>
