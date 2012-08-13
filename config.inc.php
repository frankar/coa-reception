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
	$dbpass = '123456';
	$dbname = 'coa';

	include("adodb5/adodb.inc.php");
    $db = NewADOConnection('mysql');
    $db->Connect($dbhost, $dbuser, $dbpass, $dbname);

	$opzioni = array(
	  array('id'=>1,'key'=>'nome_coa','titolo'=>'Nome C.O.A.','descrizione'=>'Inserire il nome del Centro Operativo Avanzato','classe'=>'','valore'=>'C.O.A. VENETO'),
	  array('id'=>2,'key'=>'mail_coa','titolo'=>'E.mail C.O.A.','descrizione'=>'Inserire l\'indirizzo e-mail del COA. Servira\' come mittente per le mail in uscita dall\'applicativo','classe'=>'','valore'=>'coa.veneto@vigilfuoco.it'),
	  array('id'=>3,'key'=>'mail_cc','titolo'=>'Indirizzi e.mail C.C.','descrizione'=>'Inserire gli indirizzi e-mail (separati da virgola) a cui inviare in copia carbone le comunicazioni del COA','classe'=>'','valore'=>'comando.cratere@vigilfuoco.it'),
	  array('id'=>4,'key'=>'audio','titolo'=>'Messaggi Audio','descrizione'=>'Impostare 1 per abilitare i messaggi audio, 0 per non attivarli','classe'=>'','valore'=>1),
	  array('id'=>5,'key'=>'media_days','titolo'=>'Media giorni permanenza','descrizione'=>'Indicare il numero medio di giorni di permanenza (numero intero, es: 7)','classe'=>'','valore'=>7),
	  array('id'=>6,'key'=>'check_ver','titolo'=>'Ultima ricerca versione','descrizione'=>'Data ultimo controllo di nuova versione disponibile (normalmente non serve modificare questo valore)','classe'=>'','valore'=>'2012-08-03 22:23:23'),
	  array('id'=>7,'key'=>'backup_freq','titolo'=>'Frequenza Backup Automatico Dati','descrizione'=>'Specificare ogni quanto tempo effettuare il backup automatico. (es: \"30 min\", \"1 hour\", \"3 day\", \"1 week\"). Specificare 0 per disabilitare il backup automatico','classe'=>'','valore'=>0),
	  array('id'=>8,'key'=>'backup_last','titolo'=>'Ultimo backup','descrizione'=>'Data ultimo backup effettuato (normalmente non serve modificare questo valore)','classe'=>'','valore'=>'1970-01-01 00:00:00'),
	  array('id'=>9,'key'=>'backup_path','titolo'=>'Percorso di backup','descrizione'=>'Specificare il percorso dove effettuare il backup automatico dei dati (es. \"\\\\\\\\server\\\\backupcoa\\\\\\\" opp. \"d:\\\\backup\\\\\\\"), lasciare il campo vuoto per non effettuare il backup giornaliero automatico','classe'=>'','valore'=>''),
	  array('id'=>10,'key'=>'check_tel','titolo'=>'Controlla Numero di Telefono','descrizione'=>'Inserire 0 per disabilitare sia l\'unicita\' che l\'obbligatorieta\' del numero di telefono del personale, inserire 1 per controllare solo l\'obbligatorieta\' del numero di telefono, inserire 2 per abilitare il controllo dell\' unicita\' e obbligatorieta\' del numero di telefono','classe'=>'','valore'=>1)
	);
	
	if (isset($_GET['setup'])) {

		# $sql = "INSERT INTO `coa2`.`opzioni` (`id`, `key`, `titolo`, `descrizione`, `classe`, `valore`) VALUES (NULL, \'no_tel\', \'Controlla Numero di Telefono\', \'Inserire 1 per abilitare il controllo dell\'\'unicita\'\' e obbligatorieta\'\' del numero di telefono\', \'\', \'1\');";
		
		# Controllo la tabella opzioni:
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = 'opzioni'";
		$rs = $db->Execute($sql);
		if ((int)$rs->fields[0] < 1) {
			$sql = "
			CREATE TABLE IF NOT EXISTS `opzioni` (
			  `id` int(4) NOT NULL AUTO_INCREMENT,
			  `key` varchar(40) NOT NULL,
			  `titolo` varchar(40) NOT NULL,
			  `descrizione` varchar(300) NOT NULL,
			  `classe` varchar(20) NOT NULL,
			  `valore` varchar(200) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";

			$result = $db->Execute($sql);
			if ($result === false) {
				die("failed: creazione tabella opzioni".$db->ErrorMsg());
			} else {
				foreach ($opzioni as $o) {
					$sql2 = "INSERT INTO `opzioni` (
						`key`, 
						`titolo`, 
						`descrizione`, 
						`classe`, 
						`valore`) 
					VALUES (
						".$db->qstr($o['key']).", 
						".$db->qstr($o['titolo']).", 
						".$db->qstr($o['descrizione']).", 
						".$db->qstr($o['classe']).", 
						".$db->qstr($o['valore'])."
					);";
					$result = $db->Execute($sql2);
					if ($result === false) die("failed ".$db->ErrorMsg());
				}
			};
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
			CREATE TABLE `comandi` (
			  `id` int(8) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(50) NOT NULL,
			  `id_dir` int(8) DEFAULT NULL,
			  `esteso` varchar(100) NOT NULL,
			  `mail` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id_dir` (`id_dir`),
			  CONSTRAINT `comandi_ibfk_1` FOREIGN KEY (`id_dir`) REFERENCES `comandi` (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
			";
			$result = $db->Execute($sql);
			if ($result === false) die("failed: creazione tabella comandi".$db->ErrorMsg());
		}
		// controllo le colonne aggiunte di recente
		$a_cols = array(
			'id_dir'=>'INT( 8 ) NOT NULL',
			'nome'=>'varchar(50) NOT NULL',
			'esteso'=>'varchar(100) NOT NULL',
			'mail'=>'varchar(50) NOT NULL'
		);
		foreach ($a_cols as $key => $value) {
			$sql = "SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME= '".$key."' AND TABLE_NAME = 'comandi' AND TABLE_SCHEMA='".$dbname."';";
			$rs = $db->Execute($sql);
			if ($rs->RecordCount() == 0) {
				$sql2 = "ALTER TABLE `".$dbname."`.`comandi` ADD `".$key."` ".$value.";";
				$db->Execute($sql2);
			}
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

	$sql = "SELECT `key`, `valore` FROM `opzioni`";
	$result = $db->Execute($sql);
	if ($result === false) die("Errore sul database: Prova a seguire questo link: <a href='index.php?setup=1'>Inizializza Opzioni</a>");
	$options = array();
	while (!$result->EOF) {
		$r_ass = $result->GetRowAssoc();
		$options[$r_ass['KEY']] = $r_ass['VALORE'];
		$result->MoveNext();
	}
	
	
	$a_keys = array_keys($options);
	// print_r($a_keys);
	// exit;
	
	foreach($opzioni as $o) {
		if (array_search($o['key'], $a_keys) === FALSE) {
			// echo $o['key']."<br />";
			// print_r( $a_keys );
			// exit;
			$sql2 = "INSERT INTO `opzioni` (
				`key`, 
				`titolo`, 
				`descrizione`, 
				`classe`, 
				`valore`) 
			VALUES (
				".$db->qstr($o['key']).", 
				".$db->qstr($o['titolo']).", 
				".$db->qstr($o['descrizione']).", 
				".$db->qstr($o['classe']).", 
				".$db->qstr($o['valore'])."
			);";
			$result = $db->Execute($sql2);
			if ($result === false) die("failed ".$db->ErrorMsg());
			
			// inserisco in $options
			$options[$o['key']] = $o['valore'];
		}
	}
	unset($opzioni);

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
