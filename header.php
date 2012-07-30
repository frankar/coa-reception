<?php
include("config.inc.php");
  ?><!DOCTYPE html>
<html>
<head>
	<title>Reception</title>
	<link rel="stylesheet" href="css/master.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="css/menu.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="jquery-ui-1.8.13.custom/css/ui-lightness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script src="jquery-ui-1.8.13.custom/js/jquery-1.5.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery-ui-timepicker-addon.js" type="text/javascript" charset="utf-8"></script>
	<?php
	if (isset($options['check_ver'])) {
		$last = strtotime($options['check_ver']);
		$next = strtotime("+".$new_ver_period, $last);
		if (strtotime("now") > $next) {
			?>
			<script type="text/javascript" charset="utf-8">
				$(function(){
					$.ajax({
						type: "POST",
						url: "check_ver.php",
						data: "",
						dataType: "html",
						success: function(msg)
						{
							if (msg != '0') {
								var x=window.confirm("E' disponibile una nuova versione dell'applicazione: Ver."+msg+".\nVolete scaricarla ora ?");
								if (x) 
								window.location.href='https://github.com/frankar/coa-reception/zipball/master';
							}
						},
						error: function()
						{
							alert("Controllo versione non riuscito");
						}
					});

				});
			</script>
			<?php
		} 		
	} else {
		$sql = "
		INSERT INTO `opzioni` (
			`key`, 
			titolo, 
			descrizione, 
			valore
			) 
		VALUES (
			'check_ver', 
			'Ultima ricerca versione', 
			'Data ultimo controllo di nuova versione disponibile (normalmente non serve modificare questo valore)', 
			'1970-01-01 00:00:00'
			);";

		$result = $db->Execute($sql);
		if ($result === false) die("failed: inserimento check_ver".$db->ErrorMsg());

	}
	if (isset($options['backup_last'])) {
		if (isset($options['backup_path'])) {
			if (isset($options['backup_freq'])) {
				if($options['backup_freq'] <> '0' and $options['backup_path'] <> '') {
					$last = strtotime($options['backup_last']);
					$next = strtotime("+".$options['backup_freq'], $last);
					if (strtotime("now") > $next) {
						?>
						<script type="text/javascript" charset="utf-8">
							$(function(){
								$.ajax({
									type: "POST",
									url: "backup_dati.php",
									data: "dir_back=<?php echo urlencode($options['backup_path']) ?>",
									dataType: "html",
									success: function(msg)
									{
										// alert("Backup effettuato "+msg);
									},
									error: function()
									{
										alert("Controllo versione non riuscito");
									}
								});

							});
						</script>
						<?php
					} 							
				}
			} else {
				$sql = "
				INSERT INTO `opzioni` (
					`key`, 
					titolo, 
					descrizione, 
					valore
					) 
				VALUES (
					'backup_freq', 
					'Frequenza Backup Automatico Dati', 
					'Specificare ogni quanto tempo effettuare il backup automatico. (es: \"30 min\", \"1 hour\", \"3 day\", \"1 week\"). Specificare 0 per disabilitare il backup automatico', 
					'0'
					);";

				$result = $db->Execute($sql);
				if ($result === false) die("failed: inserimento check_ver".$db->ErrorMsg());

			}
		} else {
			$sql = "
			INSERT INTO `opzioni` (
				`key`, 
				titolo, 
				descrizione, 
				valore
				) 
			VALUES (
				'backup_path', 
				'Percorso di backup', 
				'Specificare il percorso dove effettuare il backup automatico dei dati (es. \"\\\\server\\backupcoa\\\" opp. \"d:\\backup\\\"), lasciare il campo vuoto per non effettuare il backup giornaliero automatico.', 
				''
				);";

			$result = $db->Execute($sql);
			if ($result === false) die("failed: inserimento backup_path".$db->ErrorMsg());
		}
	} else {
		$sql = "
		INSERT INTO `opzioni` (
			`key` ,
			`titolo` ,
			`descrizione` ,
			`valore`
		)
		VALUES (
			'backup_last', 
			'Ultimo backup', 
			'Data ultimo backup effettuato (normalmente non serve modificare questo valore)',
			'1970-01-01 00:00:00'
		);";
		
		$result = $db->Execute($sql);
		if ($result === false) die("failed: inserimento check_ver".$db->ErrorMsg());
	}
	
	?>
	
	
	