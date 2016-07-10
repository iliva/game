//очистка кеша сайта
$(document).on('click','#clearCache', function(){

	$.ajax({
	        type: "POST",
	        url: base_path+"/support/ajax/clearCache",
	        dataType : 'json',  
	        success: function(data){ 
	               
	            if(data.success == 1){
	                 alert('Вы очистили кеш');
	            }
	        }
  	});   // end ajax  

	return false;
});

var base_path = '';
if (typeof window.base == "undefined") window.base = {};

// массовая загрузка фото
function massUpload(responseJSON){ 

	$('.waiting').show();
	$('.add_photos').hide();
	$('button.btn-primary').hide();
	
	if(responseJSON.success == true) {

		var id = $('.add_photos').attr('id');
		var model = $('.add_photos').attr('attr');
		var file = responseJSON.filename;
	
		$.ajax({  
		   type: "POST",  
		   url: base_path+"/support/ajax/saveUploaded",  
		   cache: false,
		   dataType: 'json',
		   data: 'id='+id+'&model='+model+'&file='+file,  
		   success: function(data) {
				var i = new Image();
				i.src = data.src; 
				i.onload = function() {
					$('.photos_list').prepend(data.html);
					setTimeout(function(){ 
						$('.waiting').hide();
						$('.add_photos').show();
						$('button.btn-primary').show();
					}, 2000);
					massPhotosActions();
				}		   
		   }
		}); 

	}
	
}

function massPhotosActions(){
	// сохранение порядка фото
	$('.add_photos_form .photos_list input').blur(function(){
		
		var id = $(this).attr('name');
		var order_num = $(this).attr('value');
		$.ajax({  
		   type: "POST",  
		   url: base_path+"/support/ajax/saveOrderNum",  
		   cache: false,
		   dataType: 'json',
		   data: 'id='+id+'&order_num='+order_num,  
		   success: function(data) {	   
		   }
		}); 			
	});
	
	// удаление фото
	$('.add_photos_form .photos_list .item span').click(function(){
		$(this).parents('.item').fadeOut();
		var id = $(this).parents('.item').find('input').attr('name');
		$.ajax({  
		   type: "POST",  
		   url: base_path+"/support/ajax/deletePhoto",  
		   cache: false,
		   dataType: 'json',
		   data: 'id='+id,  
		   success: function(data) {
		   }
		}); 
	});
}

$(document).ready(function() { 

	// загрузка фото
	
	$('.add_photos_form button').bind('click',function(){
		location.reload();
	});
	
	massPhotosActions();
	
	if($("#loginForm").length > 0 ){  

	    $("#loginForm").validate({
	        rules: {
	            username: {
	                required: true,
	                minlength: 4
	            },
	            password: {
	                required: true,
	                minlength: 6
	            }  
	        },
	        messages: {
	            username: {
	                required: "Fill me please",
	                minlength: "My name is bigger"
	            },
	            password: {
	                required: "Please provide a password",
	                minlength: "My password is more that 6 chars"
	            }
	        }   
	    }); 
	}  



	
	//------------- To top plugin  -------------//
	$().UItoTop({ 
		//containerID: 'toTop', // fading element id
		//containerHoverID: 'toTopHover', // fading element hover id
		//scrollSpeed: 1200,
		easingType: 'easeOutQuart' 
	});


	//------------- Uniform  -------------//
	//add class .nostyle if not want uniform to style field
	$("input, textarea, select").not('.nostyle').uniform();

	//remove loadstate class from body and show the page
	setTimeout(function(){  

		$("html").removeClass("loadstate");
	},500); 
	

	// чекбокс "не обмежено"
	if($('input#Promo_inf').is(':checked')) {
		$('input#Promo_uses').attr('disabled','disabled').val('');
	}	
	$('input#Promo_inf').click(function(){
	   if($(this).is(':checked')) {
		  $('input#Promo_uses').attr('disabled','disabled').val('');
	   } else {
		  $('input#Promo_uses').removeAttr('disabled').val(1);
	   }
	});	
});

//generate random number for charts
randNum = function(){
	return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
}


$(window).load(function() {
	var wheight = $(window).height();
	$('#sidebar.scrolled').css('height', wheight-63+'px');
}); 

 

//------------- Check all checkboxes  -------------//	
$(document).on('click',"#checkAll", function() {
	
	var thiz 			= $(this);
	var checkedStatus 	= thiz.hasClass('checked');  

	var checkedStatus = $(this).closest('span').hasClass('checked');
	
	$("table tr .chChildren:checkbox").each(function() {		
		this.checked = checkedStatus;
		if (checkedStatus == this.checked) { 			 
			$(this).closest('.checker > span').removeClass('checked');
		}

		if (this.checked) {			 
			$(this).closest('.checker > span').addClass('checked');
		}
	});
 
}); 
 

$(document).on('blur', '.nameToAlias', function(){ 
	
 	var thiz    = $(this);
    var name 	= thiz.val();
  	if(name.length > 0 ){

	    $.ajax({          
	        type 		: "POST",
	        url 		: "/support/ajax/GetAlias",
	        dataType 	: 'json',
	        async 	: false,
	        data 		: ({name 	: name  }),
	        success: function(data){  
				var alias = $('.alias').val();
				//$('.Message').html('');
				if(alias.length > 0 ){
					if(alias != data.alias){ 
						$('.Message').html('<i>Сгенерирован новый алиас - "'+data.alias+'"</i><br>');
					} 
				}	
				else{
					$('.alias').val(data.alias).blur(); 
				}
			} 
	    });
  	}  
});

// проверка алиаса
/*
$(document).on('blur', '.alias', function(){

	var model = $('#content').attr('module');
	var thiz        = $(this);
    var this_alias  = thiz.val();
	var id_note	= thiz.attr('id_note');

    if(this_alias.length > 0 ){
		
		$.ajax({          
	          type 		: "POST",
	          url 		: "/support/ajax/checkAlias",
	          dataType 	: 'json',
	          async 	: false,
	          data 		: ({this_alias 	: this_alias,
							id_note 	: id_note,
	          				model 		: model}),
	          success: function(data){   
	             
	           // $('.Message').html('');

	            if(data.susses == 1){
	            	//$('.Message').html('<i>Такой алиас - "'+this_alias+'", не найден</i>');
	            }
	            else{
	            	$('.Message').html('<i>Найденно совпадение алиаса - "'+this_alias+'", в записи №:'+data.id+'</i>'); 
	            } 
              	
	          } 
	    });

    } 

});

*/
 


//jQuery.noConflict();

function createArr(obj){ 
	
	var _array = new Array();

	$.each(obj, function(i) {
	 	 _array[i] = obj[i].value; 
	});
	return _array;
}

$(document).on('click','a.tab-c[tab="#tab2"]', function(){
      
        var subject     = $('input[name="subject"]').val();
       
        var description = tinyMCE.get('description').getContent();

        var news        	= createArr($('#news option:selected'));  
        var final_list		= createArr($('#final_list option'));
        var mailerEnters	= $('#mailerEnters').val();
		  

        $.ajax({
            type: "POST",
            url: "/support/ajax/PrevSendMail",
            dataType : 'json', 
            data: ({  subject       : subject,
                      description   : description,
                      news          : news,
                      final_list    : final_list,
                      mailerEnters  : mailerEnters,

                    }),

            success: function(data){
                
                var header =  $('.mail_block.header').html();
                var footer =  $('.mail_block.footer').html();

            	$('#previewBlock').html(data.html);    
 

            }
        }); // end ajax   
}); 
 

 
$(document).on('click','.add-subscribers', function(){

	var mailerUsers  = $('#mailerUsers option:selected');
	var mailerGroups = $('#mailerGroups option:selected');
	var mailerEnters = $('#mailerEnters');

	$('#final_list').html('');

	var final_list   = $('#final_list option');

	if(mailerGroups.length > 0){
 

		var mailerGroupsArray = [];
		$.each(mailerGroups, function(i){
			mailerGroupsArray[i] = mailerGroups.eq(i).val();
		});

		$.ajax({
          	type: "POST",
          	url: "/support/ajax/getMails",
          	dataType : 'json', 
          	data: ({  mailerGroups  :  mailerGroupsArray }), 
          	success: function(data){ 
               
             	if(data.success == 1){ 
                  	 
             		$.each(data.mailsArray ,function(i){

             			var thiz = data.mailsArray[i];

             			if(final_list.length > 0){

             				$.each(final_list, function(ii){

             					var item = final_list.eq(ii);

             					if(item.val() !== thiz){
             						$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
             					}

             				}); // end each final_list
 
	                  	} else {

	                  		$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
	                  	}

             		}); // end each data.mailsArray  
                  	
              	}		
         	}
  		});   // end ajax
	}
 
  
	if(mailerUsers.length > 0){

		$.each(mailerUsers, function(i){

			var thiz = mailerUsers.eq(i).val();

			 

			if($('#final_list option').length > 0){
				$.each($('#final_list option'), function(ii){

					var item = $('#final_list').eq(ii);
 

					if(item.val() !== thiz){
						$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
					}

				}); // end each final_list
			} else {

          		$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
          	}	
		}); // end each mailerUsers
	}

	return false;
});

$(document).on('click','.del-subscribers', function(){
	
	$('#final_list option:selected').remove();
	return false;
});


$(document).on('click','.del-file', function(){

    var thiz  	= $(this);
    var type  	= thiz.attr('type');
    var id_file = thiz.attr('id-file');
    var id_model= thiz.attr('id-model');

    $.ajax({
          type: "POST",
          url: base_path+"/support/ajax/delPhoto",
          dataType : 'json', 
          data: ({  id_photo  : id_file, 
          			id_model  : id_model, 
                    type      : type, 
                  }),

          success: function(data){ 
               
              if(data.success == 1){

                  thiz.closest('tr').remove();
              }
         }
  });   // end ajax
});


$(document).on('click','#sendMailButton', function(){
 

    var subject       = $('input[name="subject"]').val(); 
    var description   = tinyMCE.get('description').getContent();

    var news        	= createArr($('#news option:selected'));  
    var final_list		= createArr($('#final_list option'));
    var mailerEnters	= $('#mailerEnters').val();

    $.ajax({
            type: "POST",
            url: "/support/ajax/sendMail",
            dataType : 'json', 
            data: ({  subject       : subject,
                      description   : description,
                      news          : news,
                      final_list    : final_list,
                      mailerEnters  : mailerEnters,
                    }),

            success: function(data){ 
                 
                  if(data.susses == 1)
                    alert('Все отправленно');
                  else
                    alert(data.errArr);
           }
    });   // end ajax

});



$(document).on('click', '.change-elements', function(){

	var thiz 	= $(this);
	var action 	= thiz.attr('type');  
	var checked = $('.chChildren:checked');
	var model = $('#content').attr('module');
	
	if(checked.length > 0 ){

		if(action == 'delete_choose'){
			if (!confirm("Вы уверены что хотите удалить выбранные элементы?")) {
				return false;
			}
		}

		var idsArray = [];
		$.each(checked, function(i){

			idsArray[i] = $(this).val()*1; 
		});// end each

		$.ajax({
	        type: "POST",
	        url: base_path+"/support/ajax/changeElements",
	        dataType : 'json', 
	        data: ({idsArray : idsArray, 
	        		action   : action,
	          		model  	 : model}), 
	        success: function(data){ 
	               
	            if(data.success == 1){
	                location.reload();
	            }
	        }
	  });   // end ajax  
	}// end if checked count  

	$('.box-form.right').removeClass('open');
	return false;
});


$(document).ready(function(){
	
	var popup;
	$.fn.FM = function (options) {
		options = $.extend({
			id: '',
			popup: {
				width: 600,
				height: 400
			}
		}, options);

		return this.each(function () {
			var el = $(this);

			el.click(function () {
				if (popup !== undefined)
					popup.close();


				//console.log(this,$(this).attr('href'), this.href);  

				var redirect_uri, url = redirect_uri = $(this).attr('href');  

				url += url.indexOf('?') >= 0 ? '&' : '?';
				if (url.indexOf('redirect_uri=') === -1)
					url += 'redirect_uri=' + encodeURIComponent(redirect_uri) + '&';
				url += 'js';

				var centerWidth = (window.screen.width - options.popup.width) / 2,
					centerHeight = (window.screen.height - options.popup.height) / 2;


				popup = window.open(url, "yii_eauth_popup", "width=" + options.popup.width + ",height=" + options.popup.height + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
				popup.focus();

				var timer = setInterval(checkChild, 500);

				function checkChild() {
				    if (popup.closed) {				       

				        var somevariable = popup.returnValue;
						
						if(typeof somevariable != 'undefined'){
							$('#uniform-News_video .filename').text(somevariable);
					        $('#News_hidedVideoName').val(somevariable);
							//console.log(somevariable); 

							alert('Вы выбрали файл: '+somevariable);
						} 
						
				        clearInterval(timer);
				    }
				}

				
				return false;
			});
		});
	};  

	$('#file-manager').FM(); 
});

var popup;

$(document).on('click', '#file-manager', function(){


	//return false;
});

var input_count = 0;
$(document).on('click', '.add_miles', function(){

	$('.multy_miles_block').append('<input name="multy_miles['+input_count+']" type="text" class="text"><br>');
	input_count++;
	return false;
});