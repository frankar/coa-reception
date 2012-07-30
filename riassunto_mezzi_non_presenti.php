<?php include("header.php"); ?>
<link rel="stylesheet" href="js/DataTables-1.9.1/media/css/demo_table.css" type="text/css" media="screen" title="no title" charset="utf-8">
<script type="text/javascript" language="javascript" src="js/DataTables-1.9.1/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tab_anag').dataTable({
			"aaSorting": [],
			"bPaginate": false,
	        "bFilter": true,
	        "bAutoWidth": false,
	        "oLanguage": {
	            "sLengthMenu": "Mostra _MENU_ righe per pagina",
	            "sZeroRecords": "Nessun record trovato !",
	            "sInfo": "Stai vedendo dalla riga _START_ alla riga _END_ di _TOTAL_ totali",
	            "sInfoEmpty": "Stai vedendo dalla riga 0 alla riga 0 di 0 totali",
	            "sInfoFiltered": "(filtered from _MAX_ total records)",
				"sSearch": "Cerca nella tabella:",
				"oPaginate": {
					"sNext": "Prossima pagina",
					"sPrevious": "Pagina precedente",
					"sFirst": "Prima pagina",
					"sLast": "Last page"
				}
	        }
		});
		$('#tab_anag_filter input').focus();

		$("#excel #genera_csv").click(function(e) {
			var query = $("#query_excel").val();
			$.ajax({
				type: "POST",
				url: "query_select.php",
				data: "inviato=1&f=csv&query=" + query,
				dataType: "html",
				success: function(msg) {
					$('#txt_excel').show().val(msg).select()
					.focus(function() {
						$(this).select();
					});
					$("#excel p.red").remove();
					$("#excel").append('<p class="red">Ora puoi copiare i dati CSV con CTRL+C per poi incollarli in un\'altra applicazione</p>')
										
				},
				error: function() {
					alert("Chiamata fallita, si prega di riprovare...");
				}
			});
			e.preventDefault();
		});
		$("#excel #genera_tsv").click(function(e) {
			var query = $("#query_excel").val();
			$.ajax({
				type: "POST",
				url: "query_select.php",
				data: "inviato=1&f=tsv&query=" + query,
				dataType: "html",
				success: function(msg) {
					$('#txt_excel').show().val(msg).select()
					.focus(function() {
						$(this).select();
					});
					$("#excel p.red").remove();
					$("#excel").append('<p class="red">Ora puoi copiare i dati TSV con CTRL+C per poi incollarli in un\'altra applicazione</p>')
										
				},
				error: function() {
					alert("Chiamata fallita, si prega di riprovare...");
				}
			});
			e.preventDefault();
		});
		
	} );
</script>
<?php include("menu.php"); ?>
	<?php
		$sql = "SELECT m.id, t.nome AS tipo, m.targa, a.id AS id_resp, a.nome AS nome_resp, a.cognome AS cognome_resp, c.nome AS comando, m.data_in, m.data_out FROM mezzi AS m INNER JOIN tipi_mezzi AS t ON t.id = m.id_tipo INNER JOIN anagrafica AS a ON m.id_resp = a.id INNER JOIN comandi as c ON m.id_comando = c.id WHERE m.data_out <> '1970-01-01 00:00:00' ORDER BY m.data_out DESC";
	?>

	<h2>Esportazione Mezzi Usciti dal COA per Excel</h2>
	<div id="excel">
		<p><input type="button" name="genera_csv" value="Genera dati CSV" id="genera_csv">
			<input type="button" name="genera_tsv" value="Genera dati TSV" id="genera_tsv">
			<input type="hidden" name="query_excel" value="<?php echo base64_encode($sql) ?>" id="query_excel"></p>
		<p><textarea class="hidden" id="txt_excel"></textarea></p>
	</div>
	

	<h2>Tabella anagrafica Mezzi Usciti dal COA</h2>
	<div class="dataTables_wrapper">
	<table class="pretty-table display" id="tab_anag" style="width:100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Tipo</th>
				<th>Targa</th>
				<th>Resp</th>
				<th>Comando</th>
				<th>Ingresso</th>
				<th>Uscita</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$rs = $db->Execute($sql);
		while (!$rs->EOF) {
			$r_ass = $rs->GetRowAssoc();
			//print_r($r_ass);
			?>
			<tr>
				<td><?php echo $r_ass['ID'] ?></td>
				<td><?php echo $r_ass['TIPO'] ?></td>
				<td><a href="dettagli_mezzi.php?id=<?php echo $r_ass['ID']?>"><?php echo $r_ass['TARGA'] ?></a></td>
				<td><a href="dettagli.php?id=<?php echo $r_ass['ID_RESP'] ?>"><?php echo $r_ass['COGNOME_RESP']." ".$r_ass['NOME_RESP'] ?></a></td>
				<td><?php echo $r_ass['COMANDO'] ?></td>
				<td><?php echo $r_ass['DATA_IN'] ?></td>
				<td><?php echo $r_ass['DATA_OUT'] ?></td>
			</tr>
			<?php
			$rs->MoveNext();
		}
		?>
		</tbody>
	</table>
	</div>
	<br />
<?php include("footer.php");  ?>
