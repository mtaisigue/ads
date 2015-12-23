<? 

$txtdisabled = '';
if($f != 0)
	if((!$usuario_isadmin) && ($id_usuario != @$f['id_usuario'])){
		$txtdisabled = 'disabled="disabled"';
	}
		
?>

<div class="row pag<?=$p['id_pagina']?> <? if(!$f){?>blank<? }else{ ?>anuncio<?=$f['id_anuncio']?><? } ?>" <? if(!$f && $sip==0){?>style="display: none;"<? } ?>>
	    <form class="ajax_submit" method="post" action="process/savead.php">
		        <input type="hidden" name="id_publicacion" value="<?=$id_publicacion?>" class="input id_publicacion" />
        		<input type="hidden" name="id_pagina" value="<?=$p['id_pagina']?>" class="input id_pagina" />
                
                <? if($f){?>
                	<input type="hidden" name="id_anuncio" value="<?=$f['id_anuncio']?>" class="input id_anuncio" />
				<? } ?>
        		<div class="msg_error"></div>
                <div class="clear"></div>
            	<div class="col1"><?=$p['nombre']?></div>
                <div class="col2"><? if($p['nombre'] != ''){ ?><input type="text" name="empresa" class="input empresa" value="<?=@$f['empresa']?>" <?=$txtdisabled?> /> <? } ?></div>
                <div class="col3">
                <? if($p['nombre'] != ''){ ?>
                	<select name="id_tamano" class="input tamano" <?=$txtdisabled?> >
                        <option value="">...</option>
                        <option value="1" <?=@$f['id_tamano']==1?'selected="selected"':''?> >Quarter Page</option>
                        <option value="2" <?=@$f['id_tamano']==2?'selected="selected"':''?> >Half Page</option>
                        
                        <option value="4" <?=@$f['id_tamano']==4?'selected="selected"':''?> >Top Half Page</option>
                        <option value="5" <?=@$f['id_tamano']==5?'selected="selected"':''?> >Bottom Half Page</option>
                        <option value="3" <?=@$f['id_tamano']==3?'selected="selected"':''?> >Full Page</option>
                        
                        <option value="6" <?=@$f['id_tamano']==6?'selected="selected"':''?> >Full Page Exclusive</option>
                        
                        <option value="7" <?=@$f['id_tamano']==7?'selected="selected"':''?> >Editorial</option>
                        <option value="8" <?=@$f['id_tamano']==8?'selected="selected"':''?> >Editorial Exclusive</option>
                    </select>

				<? } ?>
				</div>
                <div class="col4">
                    <div class="cat_container" style="float:left;">
                    <input class="input categoria" value="<?=@$categorias[@$f['id_categoria1']]?>" <?=$txtdisabled?> />
                    <input name="id_categoria1" type="hidden" class="id_categoria input">
                    </div>
                    <div class="cat_container" style="float:left;">
                    <input class="input categoria" value="<?=@$categorias[@$f['id_categoria2']]?>" <?=$txtdisabled?> />
                    <input name="id_categoria2" type="hidden" class="id_categoria input">
                    </div>
            	</div>
                <div class="col5">
                	<? if( (($id_usuario == $f['id_usuario']) || $usuario_isadmin) && $f != 0){?>
                    	<a href="process/delete.php?type=ad&id=<?=$f['id_anuncio']?>">Delete</a>
                    <? } ?>
                    <? if( ($id_usuario == $f['id_usuario']) || $usuario_isadmin || $f == 0){?>
	                	<input type="submit" value="Save" />
					<? } ?>
                </div>
                <div class="clear"></div>
			
	    </form>
	</div>