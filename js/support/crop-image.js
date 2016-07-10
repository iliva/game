	var large_image_width;
	var large_image_height;
	var center;
	var file_name = '';
	var original_width = 0;
	var old_image;
	
	$(document).ready(function () { 
	
		old_image = $('.upload_image').val();
		
		// загрузка изображения
		$('#upload_form input[name=upload]').click(function(){
			$('#upload_form').hide();
			$('.upload_image_block .loading').show();
		});
		$('#upload_form').ajaxForm(function(data) { 
			$('#upload_form input[name=image]').val('');
			$('#upload_form').show();
			$('.upload_image_block .loading').hide();
			if(data.error != '') {
				// ошибка
				alert(data.error);
			} else {
				// удаление прежних фото
				if($('.upload_image').val() != '') {
					$('.delete_image').trigger('click');
				}
				// если были добавлены поля к фоткам - вернуть верстку
				$('.images_wrapper').show();
				$('.add_fields').show();
				$('.thumbnail_form_unactive').addClass('thumbnail_form').removeClass('thumbnail_form_unactive');
				$('.fields_image').remove();				

				// вывод изображения
				file_name = data.file_name;
				original_width = data.width;
				$('.upload_image').val(file_name);
				//console.log(file_name);
				$('.show_images').hide();
				$('.crop').show();
				$('.info').show()
				$('.save_crop').show();
				$('.crop img').each(function(){
					$(this).attr('src',data.image_path);
				});
				$('input[name=file_name]').val(file_name);
				
				$('.crop').each(function(){
					var elm = $(this);
					var path = elm.find('.crop_image').attr('path');
					var i = new Image();
					i.src = path+file_name; 
					i.onload = function() { 
						var large_image_width = i.width;
						var large_image_height = i.height;	
						var koef = original_width/large_image_width;
						elm.find('input[name=koef]').val(koef);
						var crop_width = elm.find('input[name=crop_width]').val();
						var crop_height = elm.find('input[name=crop_height]').val();
						if(crop_width > crop_height) { var type = 0; } else { var type = 1; }
						var devide = crop_height/crop_width;		
						var vertical_center = Math.round((large_image_width - crop_width) / 2);
						var horisontal_center = Math.round((large_image_height - crop_height) / 2);
						
						var img_width_plus_center = large_image_width-0+vertical_center;
						var img_height_plus_center = large_image_height-0+horisontal_center;
						
						elm.find('img').eq(0).attr('src',path+file_name);
						elm.find('img').eq(1).attr('src',path+file_name);
						
						function preview(img, selection) { 

							var scaleX = crop_width / selection.width; 
							var scaleY = crop_height / selection.height; 
	
							elm.find('.crop_image + div > img').css({ 						
								width: Math.round(scaleX * large_image_width) + 'px', 
								height: Math.round(scaleY * large_image_height) + 'px',
								marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
								marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
							});
							elm.find('.x1').val(selection.x1);
							elm.find('.y1').val(selection.y1);
							elm.find('.x2').val(selection.x2);
							elm.find('.y2').val(selection.y2);
							elm.find('.w').val(selection.width);
							elm.find('.h').val(selection.height);	 

							//console.log(selection.x1+' - '+selection.y1+' - '+selection.x2+' - '+selection.y2+' - '+selection.width+' - '+selection.height);	
						} 		
						elm.find('.crop_image').imgAreaSelect({ aspectRatio: '1:'+devide, onSelectChange: preview });
						// расположение по умолчанию для вертикальных изображений
						if(type == 1) {	
							elm.find('.x1').val(vertical_center);
							elm.find('.y1').val(0);
							elm.find('.x2').val(img_width_plus_center);
							elm.find('.y2').val(crop_height);
							elm.find('.w').val(crop_width);
							elm.find('.h').val(crop_height);	
							elm.find('.crop_image + div > img').css({ 
								marginLeft: '-' + vertical_center + 'px', 
							});			
							//console.log(path+' - '+elm.find('.x1').val()+' - '+elm.find('.y1').val()+' - '+elm.find('.x2').val()+' - '+elm.find('.y2').val()+' - '+elm.find('.w').val()+' - '+elm.find('.h').val());	
						}
						// расположение по умолчанию для горизонтальных изображений
						if(type == 0) {	
							elm.find('.x1').val(0);
							elm.find('.y1').val(horisontal_center);
							elm.find('.x2').val(crop_height);
							elm.find('.y2').val(img_height_plus_center);
							elm.find('.w').val(crop_width);
							elm.find('.h').val(crop_height);	
							elm.find('.crop_image + div > img').css({ 
								marginTop: '-' + horisontal_center + 'px', 
							});							
							//console.log(elm.find('.x1').val()+' - '+elm.find('.y1').val()+' - '+elm.find('.x2').val()+' - '+elm.find('.y2').val()+' - '+elm.find('.w').val()+' - '+elm.find('.h').val());						
						}						
					}
				});
			}
		});
	
		// добавить поля
		$('.add_fields').click(function(){
			$(this).hide();
			var parent = $(this).parents('.crop');
			var path = parent.find('input[name=crop_path]').val();
			var file_name = parent.find('input[name=file_name]').val();
			var crop_width = parent.find('input[name=crop_width]').val();
			var crop_height = parent.find('input[name=crop_height]').val();
			
			$.ajax({  
			   type: "POST",  
			   url: "/support/ajax/imageAddFields",  
			   cache: false,
			   dataType: 'json',
			   data: 'crop_height='+crop_height+'&crop_width='+crop_width+'&file_name='+file_name+'&path='+path,  
			   success: function(data) {
					var i = new Image();
					i.src = data.image; 
					i.onload = function() { 
						parent.find('.images_wrapper').hide();
						parent.find('.thumbnail_form').addClass('thumbnail_form_unactive').removeClass('thumbnail_form');
						parent.find('.images_wrapper').before('<img src="'+data.image+'" class="fields_image">');
					}	
			   }
			}); 			
			return false;
		});

		// сохранить обрезку картинок
	
		$('.btn').click(function(){
			$('.imgareaselect-outer').remove();
			$('.imgareaselect-selection').remove();
			$('.imgareaselect-border1').remove();
			$('.imgareaselect-border2').remove();		
			form_length = $('.thumbnail_form').length;
			if($('.crop').is(":visible") && form_length > 0) {
				$('.form').hide();
				var i = 0;
				$('.thumbnail_form').each(function(){
					i++;
					$(this).ajaxForm(function(){
						if(i == form_length) {
							setTimeout(function(){ $('.admin-form').submit(); },500);
						}
					});
					$(this).submit();
					
				});			
				return false;			
			}

		});
		
		
		// удалить картинку
		$('.delete_image').click(function(){
			if(old_image != '') {file = old_image;}
			if(file_name != '') {file = file_name;}
			if(file != '') {
				old_image = '';
				file_name = '';
				$('.upload_image').val('');	
				var name = $(this).attr('name');
				$.ajax({  
				   type: "POST",  
				   url: "/support/ajax/imageDelete",  
				   cache: false,
				   dataType: 'json',
				   data: 'name='+name+'&file='+file,  
				   success: function(data) {
						$('.show_images').hide();	   
				   }
				}); 
			}	
			return false;
		});


	}); 