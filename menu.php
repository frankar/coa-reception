<?php
$pageOn = basename($_SERVER['PHP_SELF']);
include 'menu.class.php';
//include 'tidy_menu.class.php';

$menu = menu::factory()
			->add('Home', 'index.php')
			->add('Gestione Personale', '#', menu::factory()
				->add('Accoglienza', 'reception.php')
				->add('Rientri', 'partenze.php')
				->add('Riassunto Presenti', 'riassunto.php')
				->add('Riassunto Non Presenti', 'riassunto_assenti.php'))
			->add('Gestione Automezzi', '#', menu::factory()
				->add('Ingresso Mezzi', 'reception_mezzi.php')
				->add('Uscita Mezzi', 'partenze_mezzi.php')
				->add('Cambia Responsabile Mezzo', 'cambia_resp_mezzo.php')
				->add('Riassunto Mezzi Presenti', 'riassunto_mezzi.php')
				->add('Riassunto Mezzi Usciti', 'riassunto_mezzi_non_presenti.php'))
			->add('Opzioni', '#', menu::factory()
				->add('Parametri', 'opzioni.php')
				->add('Backup Dati', 'backup_dati.php'))
			->add('Help', 'help.php');

?>
</head>
<body>
	<div id="wrapper">
		<div id="head">
			<h1><a href="index.php"><img src="css/logovvf.gif" alt="Logo VVF" /></a> Gestione Reception VV.F. - <?php echo isset($options['nome_coa']) ? $options['nome_coa'] : "COA" ?></h1>
		</div>

		<nav>
			<div class="cssmenu">
				<?php 
					$menu->current = $pageOn;
					echo $menu;
				?>
			</div>
		</nav>

		<div id="content">
