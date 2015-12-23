<?
require_once '../config.php';
require_once '../funciones.php';

$id_publicacion = $_POST['id_publicacion'];
$id_pagina = $_POST['id_pagina'];
$id_tamano = $_POST['id_tamano'];
$empresa = trim($_POST['empresa']);
$fecha_creacion = time();
$cat1 = numorzero($_POST['id_categoria1']);
$cat2 = numorzero($_POST['id_categoria2']);
$size = array(
		'1' => '0.25',
		'2' => '0.5',
		'3' => '1',
		'4' => '0.5',
		'5' => '0.5',
		'6' => '1',
		'7' => '1',
		'8' => '1',
	);
	
$msg = '';
$sql = 'SELECT * FROM pagina WHERE id_pagina = '.$id_pagina;
$r = eS($sql);
$pag = fetch($r);

	if($catname = exceedCat($cat1, $id_publicacion)){
		$msg = 'This Sheet has too many ads of '.$catname.'. ';
	}
	if($catname = exceedCat($cat2, $id_publicacion)){
		$msg = 'This Sheet has too many ads of '.$catname.'. ';
	}

if($pag['nombre'] == ''){
	$id_tamano = 3;
}else{
	if($id_tamano == ''){
		$msg = 'You need to specify the ad\'s size. ';
	}else if($empresa == ''){
		$msg = 'You need to specify the Registrar\'s Name. ';
	}
}


$newspace = spaceInPage($id_publicacion, $id_pagina) - $size[$id_tamano];
if($newspace < 0){
	$msg = 'There is not enough space in the chosen page';
}


if($msg != '')
	echo $msg;
else{
	if($_POST['id_anuncio'] != 0){
		$id_anuncio = $_POST['id_anuncio'];
		$sql = 'UPDATE anuncios SET 
		id_usuario='.$id_usuario.', id_publicacion='.$id_publicacion.', id_pagina='.$id_pagina.',
		id_tamano='.$id_tamano.', empresa="'.$empresa.'", fecha_creacion = '.$fecha_creacion.',
		id_categoria1 = '.$cat1.', id_categoria2 = '.$cat2.' 
		WHERE id_anuncio ='.$id_anuncio;
		if(!$usuario_isadmin){
			$sql .= ' AND id_usuario = '.$id_usuario;
		}
		eS($sql);
	}else{
		$sql = 'INSERT INTO	anuncios(id_usuario, id_publicacion, id_pagina, id_tamano, empresa, fecha_creacion, id_categoria1, id_categoria2) 
				VALUES('.$id_usuario.','.$id_publicacion.','.$id_pagina.','.$id_tamano.',"'.$empresa.'",'.$fecha_creacion.','.$cat1.','.$cat2.')';
		eS($sql);
	}
}
?>