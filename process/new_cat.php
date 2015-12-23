<?
require_once '../config.php';
require_once '../funciones.php';

if($usuario_isadmin)
if(@$_POST['nombre'] != ''){
	$sql = 'INSERT INTO categorias (nombre) VALUES("'.$_POST['nombre'].'")';
	eS($sql);
}
	
header('location:../categories.php');
?>