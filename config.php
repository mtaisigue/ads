<?

$USUARIO_MYSQL = "root"; 		// USUARIO
$CONTRASENA_MYSQL = "root";  		// contrase�a
$SERVIDOR_MYSQL = "localhost";	// servidor de bd
$BASE_DATOS_MYSQL = "anuncios";	// Base de datos

$user =& JFactory::getUser();
$id_usuario = $user->get('id');
$usuario_isadmin = 1;

$samecat_limit = 2;
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);

?>