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
		$sql = "SELECT a.id, q.sigla as qual, a.nome, a.cognome, c.nome AS comando, a.tel, m.nome AS mansione,a.tenda,a.data_in,a.data_out_pres FROM anagrafica AS a INNER JOIN comandi AS c ON a.comando = c.id INNER JOIN mansioni as m ON a.idmansione = m.id INNER JOIN qualifica as q ON a.idqual = q.id WHERE a.data_out = '1970-01-01 00:00:00' ORDER BY a.data_in DESC";
	?>
	
	<h2>Esportazione anagrafica presenti per Excel</h2>
	<div id="excel">
		<p><input type="button" name="genera_csv" value="Genera dati CSV" id="genera_csv">
			<input type="button" name="genera_tsv" value="Genera dati TSV" id="genera_tsv">
			<input type="hidden" name="query_excel" value="<?php echo base64_encode($sql) ?>" id="query_excel"></p>
		<p><textarea class="hidden" id="txt_excel"></textarea></p>
	</div>

	<h2>Tabella anagrafica PRESENTI</h2>
	<div class="dataTables_wrapper">
	<table class="pretty-table display" id="tab_anag" style="width:100%">
		<thead>
			<tr>
				<th>Qual</th>
				<th>Nome</th>
				<th>Cognome</th>
				<th>Comando</th>
				<th>Telefono</th>
				<th>Mansione</th>
				<th>Tenda</th>
				<th>Ingresso</th>
				<th>Uscita Presunta</th>
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
				<td><a href="dettagli.php?id=<?php echo $r_ass['ID'] ?>"><?php echo $r_ass['QUAL'] ?></a></td>
				<td><a href="dettagli.php?id=<?php echo $r_ass['ID'] ?>"><?php echo $r_ass['NOME'] ?></a></td>
				<td><a href="dettagli.php?id=<?php echo $r_ass['ID'] ?>"><?php echo $r_ass['COGNOME'] ?></a></td>
				<td><?php echo $r_ass['COMANDO'] ?></td>
				<td><?php echo $r_ass['TEL'] ?></td>
				<td><?php echo $r_ass['MANSIONE'] ?></td>
				<td><?php echo $r_ass['TENDA'] ?></td>
				<td><?php echo $r_ass['DATA_IN'] ?></td>
				<td><?php echo $r_ass['DATA_OUT_PRES'] == '1970-01-01 00:00:00' ? 'n.d.': $r_ass['DATA_OUT_PRES'] ?></td>
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
