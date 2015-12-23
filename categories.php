<?
require_once 'config.php';
require_once 'funciones.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link type="text/css" rel="stylesheet" href="css/style.css" media="all" />
</head>
<body>
<a href="./">Back</a>
<?	
	$categorias = array();
	$sql = 'SELECT * FROM categorias';
	$r = eS($sql);
	while($f = fetch($r)){
		$categorias[] = $f;
	}
?>
<table>
	<thead>
        <tr>
            <th>Categories</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<? foreach($categorias as $c){ ?>
	<tr>
    	<td><?=$c['nombre']?></td>
        <td><a href="process/delete.php?type=categories&id=<?=$c['id_categoria']?>">Delete</a></td>
    </tr>
    <? } ?>
    </tbody>
</table>

<form action="process/new_cat.php" method="post" class="createform">
	<h3>New Category</h3>
	<table>
    	<tr><td>Name</td><td><input type="text" name="nombre" class="input" /></td></tr>
        <tr><td></td><td><input type="submit" value="Create"></td></tr>
    </table>
</form>
</body>
</html>