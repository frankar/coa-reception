<?php
include("config.inc.php");
$version = file_get_contents('https://raw.github.com/frankar/coa-reception/master/VERSION.txt');
if ($current_ver != trim($version)) {
	echo trim($version);
} else {
	echo "0";
}
$sql = "UPDATE `opzioni` SET `valore` = '".date("Y-m-d H:i:s")."' WHERE `opzioni`.`key` ='check_ver';";
$db->Execute($sql);
?>