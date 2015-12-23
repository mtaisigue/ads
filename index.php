<?
require_once 'config.php';
require_once 'funciones.php';
$loadtime = time();

	if(@$_REQUEST['p'] != ''){
		$id_publicacion = $_REQUEST['p'];
	}
	
	$categorias = array();
	$paginas = array();
	$publicaciones = array();
	$sql = 'SELECT * FROM publicaciones';
	$r = eS($sql);
	while($f = fetch($r)){
		$publicaciones[] = $f;
	}
	$sql = 'SELECT * FROM categorias';
	$r = eS($sql);
	while($f = fetch($r)){
		$categorias[$f['id_categoria']] = $f['nombre'];
	}
	$sql = 'SELECT * FROM pagina';
	$r = eS($sql);
	while($f = fetch($r)){
		$paginas[] = $f;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link type="text/css" rel="stylesheet" href="css/style.css" media="all" />
<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.8.17.custom.css" media="all" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui.js" type="text/javascript"></script>
<? if(isset($id_publicacion)){ ?>
<script src="js/refresh.js" type="text/javascript"></script>
<? } ?>
<script src="js/script.js" type="text/javascript"></script>
</head>
<body>
<input type="hidden" name="loadtime" value="<?=$loadtime?>" id="loadtime" />
<div id="sheets">
	<form action="" method="GET">
	<select name="p" class="input sendonchange">
    	<option value="">...</option>
      <? foreach($publicaciones as $p){ ?>
        	<option value="<?=$p['id_publicacion']?>" <?=$id_publicacion==$p['id_publicacion']?'selected="selected"':'' ?>><?=$p['nombre']?></option>
        <? }?>
    </select>
    </form>
	<? if($usuario_isadmin){ ?>
    <a href="./runsheets.php">Manage Run Sheets</a>
    <a href="./categories.php">Manage Categories</a>
    <? } ?>
</div>

<? if(isset($id_publicacion)){ ?>
		<div class="table" id="main_table">
        	<div class="msg_error" id="main_msgerror"></div>
            <div class="clear"></div>
			<div class="row">
            	<div class="col1">Page</div>
                <div class="col2">Name Registrar</div>
                <div class="col3">Size</div>
                <div class="col4">Categories</div>
                <div class="col5"></div>
				<div class="clear"></div>
			</div>
	<?
	foreach($paginas as $p){
		
		$sql = 'SELECT * FROM anuncios WHERE id_publicacion = '.$id_publicacion.' AND id_pagina = '.$p['id_pagina'].' AND fecha_eliminado = 0 ORDER BY id_anuncio';
		$anuncios = eS($sql);
		$sip = spaceInPage($id_publicacion,$p['id_pagina']);
		$f = 0;
		include 'adrow.php'; // Fila Vacia
		while($f = fetch($anuncios)){
	        include 'adrow.php';	
		}// END WHILE
	}// END FOREACH ?>
    </div>
<? }// END IF ?>

<div id="hiddencontainer" style="display:none;">
</div>
</body>
</html>