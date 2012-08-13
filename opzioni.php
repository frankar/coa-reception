<?php include("header.php"); ?>
<?php include("menu.php"); ?>
	<h2>Opzioni dell'applicazione</h2>
	
	<?php
	if (isset($_POST['inviato'])) {
		$a_opt = $_POST;
		$last = array_pop($a_opt);
		# print_r($a_opt);
		foreach(array_keys($a_opt) as $k) {
			$sql = "UPDATE `opzioni` SET `valore` = '".$a_opt[$k]."' WHERE `opzioni`.`key` = '".$k."';";
			$db->Execute($sql);
		}
	}
	?>
	
	<form id="form_opzioni" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" accept-charset="utf-8">
	
	<?php
	$sql = "SELECT * FROM `opzioni`";
	$result = $db->Execute($sql);
	if ($result === false) die("failed");
	while (!$result->EOF) {
		$r_ass = $result->GetRowAssoc();
		?>
		<p<?php echo ($r_ass['CLASSE'] == '') ? '' : " class=".$r_ass['CLASSE'] ?>><label for="opz-<?php echo $r_ass['ID'] ?>"><?php echo sanitize($r_ass['TITOLO']) ?>:<span><?php echo sanitize($r_ass['DESCRIZIONE']) ?></span></label>
			<input value="<?php echo sanitize($r_ass['VALORE']) ?>" type="text" name="<?php echo sanitize($r_ass['KEY']) ?>" id="opz-<?php echo $r_ass['ID'] ?>" /></p>
			
		<?php
		$result->MoveNext();
	}
	
	?>

		<p class="evidente">
			<input type="hidden" name="inviato" value="1" id="inviato">
			<input type="submit" value="Conferma &rarr;" /></p>
	</form>
<?php include("footer.php");  ?>
