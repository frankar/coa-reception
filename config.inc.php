<?php

	# Imposto il fuso orario corretto
	date_default_timezone_set('Europe/Rome');

	// Ogni quanto tempo controllare se esistono nuove versioni ? (vedi php strtotime())
	$new_ver_period = "1 day";
	
	// Controllo versione
	$current_ver = trim(file_get_contents("VERSION.txt"));
	

	//dati di accesso a mysql e al db
	$dbhost = 'localhost';
	$dbuser = 'coa';
	$dbpass = 'mCtA7qGNjfppY7BB';
	$dbname = 'coa2';

	include("adodb5/adodb.inc.php");
    $db = NewADOConnection('mysql');
    $db->Connect($dbhost, $dbuser, $dbpass, $dbname);

	if (isset($_GET['setup'])) {

		# Controllo la tabella opzioni:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'opzioni'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `opzioni` (
			  `id` int(4) NOT NULL AUTO_INCREMENT,
			  `key` varchar(40) NOT NULL,
			  `titolo` varchar(40) NOT NULL,
			  `descrizione` varchar(200) NOT NULL,
			  `classe` varchar(20) NOT NULL,
			  `valore` varchar(60) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella opzioni".$db->ErrorMsg());

			$sql = "
			INSERT INTO `opzioni` (`id`, `key`, `titolo`, `descrizione`, `classe`, `valore`) VALUES
			(1, 'nome_coa', 'Nome C.O.A.', 'Inserire il nome del Centro Operativo Avanzato', '', 'C.O.A. VENETO'),
			(2, 'audio', 'Messaggi Audio', 'Impostare 1 per abilitare i messaggi audio, 0 per non attivarli', '', '1'),
			(3, 'media_days', 'Media giorni permanenza', 'Indicare il numero medio di giorni di permanenza (numero intero, es: 7)', '', '7'),
			(4, 'check_ver', 'Ultima ricerca versione', 'Data ultimo controllo di nuova versione disponibile (normalmente non serve modificare questo valore)', '', '1970-01-01 00:00:00'),
			(5, 'backup_path', 'Percorso di backup', 'Specificare il percorso dove effettuare il backup automatico dei dati (es. \"\\\\\\\\\\\\\\\\server\\\\\\\\backupcoa\\\\\\\\\\\\\" opp. \"d:\\\\\\\\backup\\\\\\\\\\\\\"), lasciare il campo vuoto per non effettuare il backup giornaliero automatico.', '', ''),
			(6, 'backup_freq', 'Frequenza Backup Automatico Dati', 'Specificare ogni quanto tempo effettuare il backup automatico. (es: \"30 min\", \"1 hour\", \"3 day\", \"1 week\"). Specificare 0 per disabilitare il backup automatico', '', '0'),
			(7, 'backup_last', 'Ultimo backup', 'Data ultimo backup effettuato (normalmente non serve modificare questo valore)', '', '1970-01-01 00:00:00');
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed ".$db->ErrorMsg());

		}

		# Controllo la tabella tipi_mezzi:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'tipi_mezzi'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `tipi_mezzi` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(20) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella tipi_mezzi".$db->ErrorMsg());

		}

		# Controllo la tabella mansioni:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'mansioni'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `mansioni` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella mansioni".$db->ErrorMsg());

		}

		# Controllo la tabella qualifica:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'qualifica'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `qualifica` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `sigla` varchar(20) NOT NULL,
			  `nome` varchar(20) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella qualifica".$db->ErrorMsg());

		}

		# Controllo la tabella comandi
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'comandi'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `comandi` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";
			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella comandi".$db->ErrorMsg());

		}

		# Controllo la tabella anagrafica:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'anagrafica'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `anagrafica` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `idqual` int(8) NOT NULL,
			  `nome` varchar(50) NOT NULL,
			  `cognome` varchar(50) NOT NULL,
			  `tel` varchar(50) NOT NULL,
			  `comando` int(8) NOT NULL,
			  `tenda` int(3) NOT NULL,
			  `idauto` int(8) NOT NULL,
			  `idmansione` int(8) NOT NULL,
			  `idnote` int(8) NOT NULL,
			  `data_in` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			  `data_out` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			  `data_out_pres` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`),
			  KEY `idmansione` (`idmansione`),
			  KEY `idqual` (`idqual`),
			  KEY `comando` (`comando`),
			  CONSTRAINT `anagrafica_ibfk_1` FOREIGN KEY (`idmansione`) REFERENCES `mansioni` (`id`),
			  CONSTRAINT `anagrafica_ibfk_2` FOREIGN KEY (`idqual`) REFERENCES `qualifica` (`id`),
			  CONSTRAINT `anagrafica_ibfk_3` FOREIGN KEY (`comando`) REFERENCES `comandi` (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";
			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella anagrafica ".$db->ErrorMsg());
		}

		# Controllo la tabella mezzi:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'mezzi'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `mezzi` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `targa` int(8) NOT NULL,
			  `id_resp` int(8) NOT NULL,
			  `id_comando` int(8) NOT NULL,
			  `id_tipo` int(8) NOT NULL,
			  `data_in` datetime NOT NULL,
			  `data_out` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`),
			  KEY `id_resp` (`id_resp`),
			  KEY `id_comando` (`id_comando`),
			  KEY `targa` (`targa`),
			  KEY `id_tipo` (`id_tipo`),
			  CONSTRAINT `mezzi_ibfk_1` FOREIGN KEY (`id_resp`) REFERENCES `anagrafica` (`id`),
			  CONSTRAINT `mezzi_ibfk_2` FOREIGN KEY (`id_comando`) REFERENCES `comandi` (`id`),
			  CONSTRAINT `mezzi_ibfk_3` FOREIGN KEY (`id_tipo`) REFERENCES `tipi_mezzi` (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella mezzi".$db->ErrorMsg());

		}
	}

	$sql = "SELECT * FROM `opzioni`";
	$result = $db->Execute($sql);
	if ($result === false) die("Errore sul database: Prova a seguire questo link: <a href='index.php?setup=1'>Inizializza Opzioni</a>");
	$options = array();
	while (!$result->EOF) {
		$r_ass = $result->GetRowAssoc();
		$options[$r_ass['KEY']] = $r_ass['VALORE'];
		$result->MoveNext();
	}

	//$oggi = date('j'); 

	$mykey = "CoaVenetoKey";

	function sanitize($data){

		//remove spaces from the input

		$data=trim($data);

		//convert special characters to html entities
		//most hacking inputs in XSS are HTML in nature, so converting them to special characters so that they are not harmful

		$data=stripslashes(htmlspecialchars($data));

		return $data;
	}



?>
