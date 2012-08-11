<?php
include("config.inc.php");

$tables = array("opzioni","tipi_mezzi","mansioni","qualifica","comandi","anagrafica","mezzi");

$tables_drop = array_reverse($tables);

$r = 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'."\n\n";

$r .= "USE `".$dbname."`;"."\n\n";

foreach($tables_drop as $table) {
	$r.= 'DROP TABLE IF EXISTS `'.$table.'`;'."\n\n";
}

foreach($tables as $table) {
  $result = mysql_query('SELECT * FROM '.$table);
  $num_fields = mysql_num_fields($result);
  
  $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
  $r.= $row2[1].";\n\n";
  
  for ($i = 0; $i < $num_fields; $i++) 
  {
    while($row = mysql_fetch_row($result))
    {
      $r.= 'INSERT INTO '.$table.' VALUES(';
      for($j=0; $j<$num_fields; $j++) 
      {
        $row[$j] = addslashes($row[$j]);
        $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
        if (isset($row[$j])) { $r.= '"'.$row[$j].'"' ; } else { $r.= '""'; }
        if ($j<($num_fields-1)) { $r.= ','; }
      }
      $r.= ");\n";
    }
  }
  $r.="\n\n\n";
}

if (isset($_POST['dir_back'])) {
	$dir_back = $_POST['dir_back'];
	// echo $dir_back;
	// exit;
	//save file
	$handle = fopen($dir_back.$dbname.date("_Y-m-d_H-i").'.sql','w+');
	fwrite($handle,$r);
	fclose($handle);
	$sql = "UPDATE `opzioni` SET `valore` = '".date("Y-m-d H:i:s")."' WHERE `opzioni`.`key` ='backup_last';";
	$db->Execute($sql);
	
} else {
	header('Content-disposition: attachment; filename='.$dbname.date("_Y-m-d_H-i").'.sql');
	header('Content-type: text/plain');
	echo $r;	
}
?>