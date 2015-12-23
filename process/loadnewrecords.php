<?
require_once '../config.php';
require_once '../funciones.php';

$id_publicacion = $_POST['id_publicacion'];
$time = $_POST['time'];

$categorias = array();
$paginas = array();

$sql = 'SELECT * FROM categorias';
$r = eS($sql);
while($f = fetch($r)){
	$categorias[$f['id_categoria']] = $f['nombre'];
}
$sql = 'SELECT * FROM pagina';
$r = eS($sql);
while($f = fetch($r)){
	$paginas[$f['id_pagina']] = $f;
}


	
$sql = 'SELECT * FROM anuncios WHERE id_publicacion = '.$id_publicacion.' AND fecha_creacion >= '.$time.' AND fecha_eliminado = 0 ORDER BY id_anuncio';
$r = eS($sql);

echo '<div id="ajax_newrows">';
while($f = fetch($r)){
	$p = $paginas[$f['id_pagina']];
	include '../adrow.php';
}
echo '</div>';

foreach($paginas as $pag){
	$sip = spaceInPage($id_publicacion, $pag['id_pagina']);
	echo '<div class="ajax_sip" idpag="'.$pag['id_pagina'].'">'.($sip>0).'</div>';
}
	
$sql = 'SELECT * FROM anuncios WHERE id_publicacion = '.$id_publicacion.' AND fecha_eliminado >= '.$time;
$r = eS($sql);
	
$deleted = '';
while($f = fetch($r)){
	if($deleted != '')$deleted .=',';
	$deleted .= $f['id_anuncio'];
}
echo '<div id="ajax_deleted">'.$deleted.'</div>';


?>