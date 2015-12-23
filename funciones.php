<?php
session_start();

function curPageURL(){
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


/*
* Edita la cadena
* @return url valida
*/
function urls_amigables($url) {

	$url = utf8_decode($url);
	
	//Rememplazar caracteres especiales latinos
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	$repl = array('a', 'e', 'i', 'o', 'u', 'n');
	$url = str_replace ($find, $repl, $url);
	
	$find = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
	$repl = array('A', 'E', 'I', 'O', 'U', 'N');
	$url = str_replace ($find, $repl, $url);
	
	$url = strtolower($url);

	// Añadir guiones
	$find = array(' ', '&', '\r\n', '\n', '+'); 
	$url = str_replace ($find, '-', $url);

	// Eliminar y Reemplazar demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);

	return $url;
}



/**
 * Realiza una conexión con servidor MySQL 
 * @return Enlace de conexión
 */
function Conectarse(){
	
	// variables globales definidas fuera de la funcion Conectarse
	global $SERVIDOR_MYSQL ,$USUARIO_MYSQL,$CONTRASENA_MYSQL,$BASE_DATOS_MYSQL;

	$enlace = mysql_connect($SERVIDOR_MYSQL ,$USUARIO_MYSQL,$CONTRASENA_MYSQL);
	if($enlace == false){
		echo "Error conectando con el Servidor de base de datos: " . $SERVIDOR_MYSQL ;
		exit(1);
	}
	$res_seleccion = mysql_select_db($BASE_DATOS_MYSQL ,$enlace);
	if($res_seleccion==false){
		echo "Error seleccionando base de datos: " . $BASE_DATOS_MYSQL;
		exit(1);
	}
	
	return $enlace;
}



/**
 * Ejecuta una consulta SQL en el servidor de MySQL
 *
 * @param $sql Consulta SQL a ejecutar
 * @return Resultado de la consulta
 */
function eS(&$sql){
	$enlace = Conectarse();
	$resultado = mysql_query($sql,$enlace);

	if($resultado==false){
		echo "Error ejecutando consulta: ". $sql;
		exit(1);
	}
	return $resultado;
	
}



/*
* Resumen de mysql_fetch_array()
*/
function fetch($r){
	return mysql_fetch_array($r);
}

/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text	String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
	function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = true) {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
    
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                    // if tag is a closing tag (f.e. </b>)
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                    // if tag is an opening tag (f.e. <b>)
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                
                // if the maximum length is reached, get off the loop
                if($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        
        // if the words shouldn't be cut in the middle...
		if (!$exact) {
            // ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
                // ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		
        // add the defined ending to the text
		$truncate .= $ending;
		
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
		
		return $truncate;
        
	}




	function hasPriv_Screen($id_user,$controller,$action){

		$id_perfil = 0;
		$id_priv = 0;
		$id_mode_priv = 1; // Privilegios sobre: 1 Pantalla, 2 campo, 3 Pantalla y campo

		// si no hay controller o action en la db si tiene acceso
		$has_priv = TRUE;

		// Encontrar el privilegio
		$sql = 'SELECT p.id_priv
				FROM fk_controllers c, fk_controllers_action a, fk_privileges p
				WHERE c.controller =  "'.$controller.'"
				AND a.action =  "'.$action.'"
				AND c.id_controller = a.id_controller
				AND p.id_controller = c.id_controller
				AND p.id_action = a.id_action
				AND p.id_mode_priv ="'.$id_mode_priv.'"';

		$r = eS($sql);
		if($rec=fetch($r)){
			$id_priv=$rec['id_priv'];
		}

		// Si hay priv definido
		if($id_priv!=0){
			// Si existe el privilegio, por default el acceso es false
			$has_priv = FALSE;

			// Encontrar perfil del usuario
			$sql = 'SELECT id_perfil from usuarios
			        where id_usuario = "'.$id_user.'" ';
			$r = eS($sql);

			if($rec=fetch($r)){
				$id_perfil=$rec[0];
			}

			// 1) encontrar priv de excepcion
			$sql = 'SELECT p_usr.permitir_acceso as access
		            FROM fk_privileges_usuarios p_usr 
		            WHERE p_usr.id_usuario = "'.$id_user.'"
		            AND p_usr.id_priv = "'.$id_priv.'"
		            LIMIT 1';
			$r = eS($sql);

			if($rec=fetch($r)){
				$acceso = $rec['access'];
			}else{
				//2) Si no hay registros de excepcion, buscar los del perfil
				// encontrar priv de perfil...

				$sql = 'SELECT p_pf.access as access
		            FROM fk_perfiles_privs p_pf
		            WHERE p_pf.id_perfil = "'.$id_perfil.'"
		            AND p_pf.id_priv = "'.$id_priv.'"
		            LIMIT 1
		            ';
				$r = eS($sql);

				if($rec=fetch($r)){
					$acceso = $rec['access'];
				}
					
			}

		}
		if(isset($acceso)){
			if($acceso!=0){
				$has_priv = true;
			}
		}

		return $has_priv;

	} // hasPriv_Screen
	function leer_privilegios($section,$str){
    
    $sql = 'SELECT * FROM fk_privileges WHERE privilege_desc = "'.$section.'"';

    $sql = eS($sql);
    
    $r = fetch($sql);
    
	if($r != 0){
			
        $sql = 'SELECT * FROM  fk_perfiles_privs WHERE id_priv = '.$r['id_priv'].' AND id_usuario = '.$_SESSION["id_usuario"];
        $sql = eS($sql);
        $r = fetch($sql);
		
         
        $cadena = strstr($r['access'],$str);
		
		if($cadena == '')$cadena = 0;
		else $cadena = 1;
		
	}else $cadena = 0;
		

    
    return ($cadena);          
          
}

function spaceInPage($ippub, $idpage){
	// 1 QUARTER, 2 HALF, 3 PAGE
	$value = array(
		'1' => '0.25',
		'2' => '0.5',
		'3' => '1',
		'4' => '0.5',
		'5' => '0.5',
		'6' => '1',
		'7' => '1',
		'8' => '1',
	);
	$sql = 'SELECT * FROM anuncios WHERE id_publicacion = '.$ippub.' AND id_pagina = '.$idpage.' AND fecha_eliminado = 0';
	$app = eS($sql);
	$sip = 1;
	while($f = fetch($app)){
		$sip -= $value[$f['id_tamano']];
	}
	return($sip);
}

function numorzero($num){
	if(!is_numeric($num))
		$num = 0;
	return $num;
}

function exceedCat($idcat, $idpub){
	$category = 0;
	global $samecat_limit;
	if($idcat != 0){
		$sql = 'SELECT * FROM anuncios WHERE id_publicacion = '.$idpub.' AND (id_categoria1 = '.$idcat.' OR id_categoria2 = '.$idcat.') AND fecha_eliminado = 0';
		$r = eS($sql);
		
		if(mysql_num_rows($r) > $samecat_limit){
			while($f = fetch($r)){ print_r($f); echo '<br/>'; }
			
			echo mysql_num_rows($r).'-'.$samecat_limit.'<br/>';
			$sql = 'SELECT * FROM categorias WHERE id_categoria = '.$idcat;
			$r = eS($sql);
			$f = fetch($r);
			$category = $f['nombre'];
		}
	}
	
	return $category;
}

?>