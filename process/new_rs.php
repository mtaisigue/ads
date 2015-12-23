<?
require_once '../config.php';
require_once '../funciones.php';

if($usuario_isadmin)
if(@$_POST['nombre'] != ''){
	$sql = 'INSERT INTO publicaciones (nombre, fecha_creacion) VALUES("'.$_POST['nombre'].'",'.time().')';
	eS($sql);
}

header('location:../runsheets.php');
?>