<?
require_once("../includes/lib.php");

global $CFG;

beginSession('R');

$link=conectaBD();
$tok = strtok ($_GET['vconf']," ");
$idconf=$tok;
$tok = strtok (" ");
$idstatus=$tok;
$Query_actualiza= "UPDATE config SET status="."'".$idstatus."'
			   WHERE id="."'".$idconf."'";

$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la configuracion ".mysql_errno($actualiza_registro));

header('Location: '.$CFG->wwwroot.'/admin/admin.php?opc=C');
?>
