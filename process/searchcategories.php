<?
require_once '../config.php';
require_once '../funciones.php';

	$categorias = array();
	$sql = 'SELECT * FROM categorias';
	$r = eS($sql);
	
	$json = '';
	while($f = fetch($r)){
		if($json != '')$json.=',';
		$json.='{"label":"'.$f['nombre'].'", "value":"'.$f['id_categoria'].'"}';
	}
	$json = '{"categorias":['.$json.']}';
	
	echo $json;
?>