// Script que agrega los registros nuevos al form

$(function(){
	setInterval(RefreshForm, 10000);
});

function RefreshForm(){
	timestamp = parseInt(new Date().getTime().toString().substring(0, 10));
	loadtime = $('#loadtime').val();
	
	idpublicacion = $('.blank:eq(0)').find('.id_publicacion').val();
	data = 'ajax=1&id_publicacion='+idpublicacion+'&time='+loadtime;
	nuevas = 0;
	$.ajax({
		url: "process/loadnewrecords.php",
//		async: false,
		type: 'POST',
		data: data,
		success: function(html){
			$('#hiddencontainer').html(html);
				
			newrowsq = $('#hiddencontainer #ajax_newrows .row').length;
			for(y=0; y< newrowsq; y++){
				nuevas++;
				
				current_row = $('#hiddencontainer #ajax_newrows .row:eq('+y+')');
				current_row_id = $('.id_anuncio', current_row).val();
				
				isupdate = $('#main_table .anuncio'+current_row_id).length;// Chequiar si existe $('#main_table .anuncio'+current_row_id)
				if(isupdate){
					$('#main_table .anuncio'+current_row_id).html(current_row.html());
					current_row.replaceWith('');
				}else{
					idpagina = $('.id_pagina',current_row).val();
					$('#main_table .pag'+idpagina+':last').after(current_row.parent().html());//Ubicar regs nuevos
				}
			}
			
			/** Mostrar u ocultar blank segun el espacio de la pagina **/
			
			for(xsip=0; xsip< $('#hiddencontainer .ajax_sip').length; xsip++){
				sip = parseInt($('#hiddencontainer .ajax_sip:eq('+xsip+')').html());
				idpagina = $('#hiddencontainer .ajax_sip:eq('+xsip+')').attr('idpag');
				if(sip){//Mostrar Blank
					$('#main_table .pag'+idpagina+'.blank').css('display','');
				}else{//Ocultar
					$('#main_table .pag'+idpagina+'.blank').css('display','none');
				}
			}
				
			// Eliminar las filas eliminadas
			deletedids = $('#hiddencontainer #ajax_deleted').html();
			deletedids = deletedids.split(',');
			for(y=0; y<deletedids.length; y++){
				$('#main_table .anuncio'+deletedids[y]).replaceWith('');
			}
		}
		
	});// FIN AJAX
	if(nuevas > 0){
		$('form.ajax_submit').unbind('submit');
		AjaxForm();
	}
	$('#loadtime').val(timestamp);
	
}
/* 
		Leer los registros que se crearon despues de $_SESSION['loadtime']
		
		Por cada pagina
			Si hay registros nuevos			
				Por cada registro nuevo hacer:
					Cargarlo en un div oculto
					Buscar el ID
					Cargarlo en la ultima posicion de la pagina
			Fin Si
			
			Si la pagina tiene espacio
				Mostrar la fila vacia
			Sino
				Ocultar fila vacia
			
			Eliminar las filas que se han eliminado
		
		Actualizar $_SESSION['loadtime']
	*/