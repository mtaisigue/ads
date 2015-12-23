<?
require_once '../config.php';
require_once '../funciones.php';

$type = @$_GET['type'];
$id = @$_GET['id'];

if($type == 'ad'){
	$sql = 'UPDATE anuncios SET fecha_eliminado = '.time().' WHERE id_anuncio = '.$id;
	if(!$usuario_isadmin)
		$sql .= ' AND id_usuario = '.$id_usuario;
	eS($sql);
	$sql = 'SELECT * FROM anuncios WHERE id_anuncio = '.$id;
	$r = eS($sql);
	$f = fetch($r);
	 
	$location = 'index.php?p='.$f['id_publicacion'];
}else if($usuario_isadmin){
	if($type == 'runsheet'){
		$sql = 'DELETE FROM publicaciones WHERE id_publicacion = '.$id;
		eS($sql);
		$sql = 'DELETE FROM anuncios WHERE id_publicacion = '.$id;
		eS($sql);
		$location = $type.'s.php';
	}else if($type == 'categories'){
		$sql = 'DELETE FROM categorias WHERE id_categoria = '.$id;	
		eS($sql);
		$location = $type.'.php';
	}
}

header('location:../'.$location);

?>