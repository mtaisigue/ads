// JavaScript Document
$(function(){
	$('.sendonchange').change(function(){
		$(this).parents('form').submit();
	});
	
	CategoriesAutocomplete();
	AjaxForm()
});

function CategoriesAutocomplete(){
	$.ajax({
		url: 'process/searchcategories.php',
		async: false,
		success: function(json){
			json = $.parseJSON(json);
			$(".categoria").autocomplete({
				minLength: 0,
				source: json.categorias,
				focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
				select: function(event, ui) {
					$(this).val(ui.item.label);
					$(this).parent().find('.id_categoria').val(ui.item.value);
					return false;
				}
			});
			$(".categoria").focusout(function(){
				v = $(this).parent().find('.id_categoria').val();
				if(v == '')$(this).val('');
			})
			$(".categoria").keydown(function(e){
				$(this).parent().find('.id_categoria').val('');
				if (event.which == 8 || event.which == 46) {
					$(this).val('');
				}
			});
		}
	});
}

var cform;
function AjaxForm(){
	$('form.ajax_submit').submit(function(event){
		if($(cform).attr('ajax') != 'done'){
			event.preventDefault();
			cform = $(this);		

			$('.msg_error', cform).css('display','none');
			var post = 'ajax=1';
			var action = '';
			
			for(x=0; x < $('.input', this).length; x++){
				$('.input:eq('+x+')', this).css('border-color','');
				$('.input:eq('+x+')', this).css('background','');
				
				if($('.input:eq('+x+')', this).hasClass('checkbx')){
					if($('.input:eq('+x+')', this).is(':checked')){
						post += '&';
						post += $('.input:eq('+x+')', this).attr('name') + '=' + $('.input:eq('+x+')', this).val();
					}
				}else{
					if($('.input:eq('+x+')', this).hasClass('required') && $('.input:eq('+x+')', this).val() == ''){
						$('.input:eq('+x+')', this).css('border-color','#F00');
						$('.input:eq('+x+')', this).css('background','#ffe1e1');
					}else{
						if(post != '')post += '&';
						post += $('.input:eq('+x+')', this).attr('name') + '=' + $('.input:eq('+x+')', this).val();
					}
				}
			}
			action = $(cform).attr('action');
			
			$.ajax({
				type: "POST",
				url: action,
				async: false,
				data: post,
				success: function(msg){
					mess = msg;

					if(msg != ''){
						ShowAlert(msg, cform);
						prevent = 1;
					}else if($('input[name="location"]', cform).val() == undefined){
						prevent = 1;
						msg = 'Data Saved';
						$('#main_msgerror').css('display','none');
						$('#main_msgerror').html(msg);
						$('#main_msgerror').fadeIn('slow');
						
						
						if(cform.parent().hasClass('blank')){
							RefreshForm();
							$('.empresa', cform).val('');
							$('.tamano option:first', cform).attr('selected','selected');
						}
					}else{
						prevent = 0;
						$('.msg_error', cform).css('display','none');
						htt = HTTP+( $('input[name="location"]', cform).val() );
						$(cform).attr('action', htt);
					}
				}
			});
			if(prevent == 0){
				$(cform).attr('ajax','done');
				$(cform).submit();
			}
		}
	});
}


function ShowAlert(msg, cform){
	$('.msg_error', cform).css('display','none');
	$('.msg_error', cform).html(msg);
	$('.msg_error', cform).fadeIn('slow');
}