<?php 

include("config.inc.php");

//creazione di un array vuoto
$return_arr = array();

$term = strtolower($_GET["term"]);

//connessione al a mysql
$conn = mysql_connect($dbhost, $dbuser, $dbpass)
or die ('Impossibile connettersi a Mysql');
//selezione ddb
mysql_select_db($dbname);
//se connesso
if ($conn)
{

$fetch = mysql_query("SELECT a.id, a.nome, a.cognome, a.tel, a.tenda, q.sigla as qual FROM anagrafica AS a LEFT JOIN qualifica AS q ON a.idqual = q.id WHERE (LOWER(a.nome) REGEXP '".$term."' OR LOWER(a.cognome) REGEXP '".$term."' OR LOWER(a.tel) REGEXP '".$term."') AND data_out = '1970-01-01 00:00:00'");
//loop dei dati
while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
	$row_array['value'] = $row['qual']." - ".$row['nome']." ".$row['cognome']." Tel: ".$row['tel']." Tenda: ".$row['tenda'];
	$row_array['nome'] = $row_array['value'];
	$row_array['id_person'] = $row['id'];
	array_push($return_arr,$row_array);
}

}
//chiudo la connessione a mysql
mysql_close($conn);

//restituisco l'array in formato json
echo json_encode($return_arr);

?>