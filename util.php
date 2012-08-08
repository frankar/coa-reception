<?php include("header.php"); ?>
<?php include("menu.php"); ?>
  <h2>Link di manutenzione dati, l'utilizzo puo' provocare la perdita di dati</h2>
<?php
$message = '';
$message_class = "ok";

if (isset($_REQUEST['action'])) {
	if($_REQUEST['action'] == 'pop') {

		$coms = array(array("dir.Abruzzo","dir.Abruzzo","Direzione Abruzzo"),
		array("dir.Basilicata","dir.Basilicata","Direzione Basilicata"),
		array("dir.Calabria","dir.Calabria","Direzione Calabria"),
		array("dir.Campania","dir.Campania","Direzione Campania"),
		array("dir.EmiliaRomagna","dir.EmiliaRomagna","Direzione Emilia Romagna"),
		array("dir.FriuliVeneziaGiulia","dir.FriuliVeneziaGiulia","Direzione Friuli Venezia Giulia"),
		array("dir.Lazio","dir.Lazio","Direzione Lazio"),
		array("dir.Liguria","dir.Liguria","Direzione Liguria"),
		array("dir.Lombardia","dir.Lombardia","Direzione Lombardia"),
		array("dir.Marche","dir.Marche","Direzione Marche"),
		array("dir.Molise","dir.Molise","Direzione Molise"),
		array("dir.Piemonte","dir.Piemonte","Direzione Piemonte"),
		array("dir.Puglia","dir.Puglia","Direzione Puglia"),
		array("dir.Sardegna","dir.Sardegna","Direzione Sardegna"),
		array("dir.Sicilia","dir.Sicilia","Direzione Sicilia"),
		array("dir.Toscana","dir.Toscana","Direzione Toscana"),
		array("dir.TrentinoAltoAdige","dir.TrentinoAltoAdige","Direzione Trentino Alto Adige"),
		array("dir.Umbria","dir.Umbria","Direzione Umbria"),
		array("dir.ValledAosta","dir.ValledAosta","Direzione Valle d'Aosta"),
		array("dir.Veneto","dir.Veneto","Direzione Veneto"),
		array("AG","dir.Sicilia","Agrigento"),
		array("AL","dir.Piemonte","Alessandria"),
		array("AN","dir.Marche","Ancona"),
		array("AO","dir.ValledAosta","Aosta"),
		array("AP","dir.Marche","Ascoli piceno"),
		array("AQ","dir.Abruzzo","L'aquila"),
		array("AR","dir.Toscana","Arezzo"),
		array("AT","dir.Piemonte","Asti"),
		array("AV","dir.Campania","Avellino"),
		array("BA","dir.Puglia","Bari"),
		array("BG","dir.Lombardia","Bergamo"),
		array("BI","dir.Piemonte","Biella"),
		array("BL","dir.Veneto","Belluno"),
		array("BN","dir.Campania","Benevento"),
		array("BO","dir.EmiliaRomagna","Bologna"),
		array("BR","dir.Puglia","Brindisi"),
		array("BS","dir.Lombardia","Brescia"),
		array("BT","dir.Puglia","Barletta-Andria-Trani"),
		array("BZ","dir.TrentinoAltoAdige","Bolzano"),
		array("CA","dir.Sardegna","Cagliari"),
		array("CB","dir.Molise","Campobasso"),
		array("CE","dir.Campania","Caserta"),
		array("CH","dir.Abruzzo","Chieti"),
		array("CI","dir.Sardegna","Carbonia-Iglesias"),
		array("CL","dir.Sicilia","Caltanissetta"),
		array("CN","dir.Piemonte","Cuneo"),
		array("CO","dir.Lombardia","Como"),
		array("CR","dir.Lombardia","Cremona"),
		array("CS","dir.Calabria","Cosenza"),
		array("CT","dir.Sicilia","Catania"),
		array("CZ","dir.Calabria","Catanzaro"),
		array("EN","dir.Sicilia","Enna"),
		array("FE","dir.EmiliaRomagna","Ferrara"),
		array("FG","dir.Puglia","Foggia"),
		array("FI","dir.Toscana","Firenze"),
		array("FM","dir.Marche","Fermo"),
		array("FO","dir.EmiliaRomagna","Forlì cesena"),
		array("FR","dir.Lazio","Frosinone"),
		array("GE","dir.Liguria","Genova"),
		array("GO","dir.FriuliVeneziaGiulia","Gorizia"),
		array("GR","dir.Toscana","Grosseto"),
		array("IM","dir.Liguria","Imperia"),
		array("IS","dir.Molise","Isernia"),
		array("KR","dir.Calabria","Crotone"),
		array("LC","dir.Lombardia","Lecco"),
		array("LE","dir.Puglia","Lecce"),
		array("LI","dir.Toscana","Livorno"),
		array("LO","dir.Lombardia","Lodi"),
		array("LT","dir.Lazio","Latina"),
		array("LU","dir.Toscana","Lucca"),
		array("MB","dir.Lombardia","Monza"),
		array("MC","dir.Marche","Macerata"),
		array("ME","dir.Sicilia","Messina"),
		array("MI","dir.Lombardia","Milano"),
		array("MN","dir.Lombardia","Mantova"),
		array("MO","dir.EmiliaRomagna","Modena"),
		array("MS","dir.Toscana","Massa carrara"),
		array("MT","dir.Basilicata","Matera"),
		array("NA","dir.Campania","Napoli"),
		array("NO","dir.Piemonte","Novara"),
		array("NU","dir.Sardegna","Nuoro"),
		array("OG","dir.Sardegna","Ogliastra"),
		array("OR","dir.Sardegna","Oristano"),
		array("OT","dir.Sardegna","Olbia-Tempio"),
		array("PA","dir.Sicilia","Palermo"),
		array("PC","dir.EmiliaRomagna","Piacenza"),
		array("PD","dir.Veneto","Padova"),
		array("PE","dir.Abruzzo","Pescara"),
		array("PG","dir.Umbria","Perugia"),
		array("PI","dir.Toscana","Pisa"),
		array("PN","dir.FriuliVeneziaGiulia","Pordenone"),
		array("PO","dir.Toscana","Prato"),
		array("PR","dir.EmiliaRomagna","Parma"),
		array("PS","dir.Marche","Pesaro urbino"),
		array("PT","dir.Toscana","Pistoia"),
		array("PV","dir.Lombardia","Pavia"),
		array("PZ","dir.Basilicata","Potenza"),
		array("RA","dir.EmiliaRomagna","Ravenna"),
		array("RC","dir.Calabria","Reggio calabria"),
		array("RE","dir.EmiliaRomagna","Reggio emilia"),
		array("RG","dir.Sicilia","Ragusa"),
		array("RI","dir.Lazio","Rieti"),
		array("RM","dir.Lazio","Roma"),
		array("RN","dir.EmiliaRomagna","Rimini"),
		array("RO","dir.Veneto","Rovigo"),
		array("SA","dir.Campania","Salerno"),
		array("SI","dir.Toscana","Siena"),
		array("SO","dir.Lombardia","Sondrio"),
		array("SP","dir.Liguria","La spezia"),
		array("SR","dir.Sicilia","Siracusa"),
		array("SS","dir.Sardegna","Sassari"),
		array("SV","dir.Liguria","Savona"),
		array("TA","dir.Puglia","Taranto"),
		array("TE","dir.Abruzzo","Teramo"),
		array("TN","dir.TrentinoAltoAdige","Trento"),
		array("TO","dir.Piemonte","Torino"),
		array("TP","dir.Sicilia","Trapani"),
		array("TR","dir.Umbria","Terni"),
		array("TS","dir.FriuliVeneziaGiulia","Trieste"),
		array("TV","dir.Veneto","Treviso"),
		array("UD","dir.FriuliVeneziaGiulia","Udine"),
		array("VA","dir.Lombardia","Varese"),
		array("VB","dir.Piemonte","Verbania"),
		array("VC","dir.Piemonte","Vercelli"),
		array("VE","dir.Veneto","Venezia"),
		array("VI","dir.Veneto","Vicenza"),
		array("VR","dir.Veneto","Verona"),
		array("VS","dir.Sardegna","Medio Campidano"),
		array("VT","dir.Lazio","Viterbo"),
		array("VV","dir.Calabria","Vibo valentia"));
		
		foreach ($coms as $v) {
			$sql = "update comandi set nome = '".strtoupper($v[0])."' WHERE UPPER(nome) = '".strtoupper($v[2])."';";
			$db->Execute($sql);
		}
		foreach ($coms as $v) {
			$sql = "SELECT * FROM comandi WHERE UPPER(nome) = '".strtoupper($v[0])."';";
			if (strlen($v[0]) > 2) {
				$email = strtolower(str_replace(' ','',$v[1]))."@vigilfuoco.it";
			} else {
				$email = "comando.".strtolower(str_replace(' ','',$v[2]))."@vigilfuoco.it";
			}
			$rs = $db->Execute($sql);
			if ($rs->RecordCount() > 0) {
				$sql2 = "UPDATE comandi SET esteso = ".$db->qstr($v[2]).", mail = ".$db->qstr($email)." WHERE UPPER(nome) = '".strtoupper($v[0])."';"; 
			} else {
				$sql2 = "INSERT INTO comandi (nome,esteso,mail) VALUES (".$db->qstr(strtoupper($v[0])).", ".$db->qstr($v[2]).", ".$db->qstr($email).");"; 
			}
			if ($db->Execute($sql2) === false) {
				$message = 'error inserting or modifing: '.$db->ErrorMsg().'<br />';
				$message_class = "ko";
				break;
			} else {
				$message = "Popolazione tabella comandi avvenuta con successo";
			}
		}
		
		// Inserisco le direzioni
		foreach ($coms as $v) {
			$d = $v[1];
			$sql = "SELECT id FROM comandi WHERE UPPER(nome) = '".strtoupper($v[1])."';";
			$rs = $db->Execute($sql);
			$dir = $rs->fields[0];
			$sql = "UPDATE comandi SET id_dir = '".$dir."' WHERE UPPER(nome) = '".strtoupper($v[0])."';";
			$db->Execute($sql);
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
$sql = "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_NAME = 'comandi' AND TABLE_SCHEMA = '".$dbname."';";
$rs = $db->Execute($sql);
if ($rs->fields[0] < 5) {
	?>
		<p>La tabella comandi contiene pochi campi, prava a <a href="index.php?setup=1">reimpostare il DB</a></p>
	<?php
	exit;
}
?>

<ul>
	<li>
		<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?action=pop">Popolazione della tabella Comandi in base alla lista Comandi d'Italia (util.php)</a>		
	</li>
	<li>
		<a href="index.php?setup=1">Controlla struttura dati</a>		
	</li>
</ul>

<?php include("footer.php");  ?>