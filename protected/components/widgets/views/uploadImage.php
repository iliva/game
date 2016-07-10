<style>
	.upload_image_block {padding: 10px; margin: 10px; position: relative}
	.upload_image_block img {border: 1px solid gray;}
	.upload_image_block .crop {display: none; position: relative}
	.upload_image_block .save_crop {display: none;}
	.upload_image_block .info {display: none; background: url(/img/messagebox_info.png) 0 2px no-repeat; padding: 0 0 20px 25px}
	.upload_image_block .show_images li {float: left; display: block; padding: 5px; }
	.upload_image_block .fields {background-color: white; text-align: center}
	.upload_image_block .original_info { position: absolute; padding: 0px 5px; background-color: #e3dede; font-size: 8px; top: 30px; left: 2px}
	.upload_image_block .result_info { position: absolute; padding: 0px 5px; background-color: #e3dede; font-size: 8px; top: 2px; left: 2px; z-index: 2}
	.upload_image_block .loading { background: url(/img/gallery-dark-loading.gif) 0 0px no-repeat; padding: 0 0 20px 50px; line-height: 32px; font-weight: bold; color: red; font-size: 20px}	
</style>
	

<div class="upload_image_block">

	<p>Картинка:
		<? if($file_image != '') { 
			echo '<span class="image_links">
				(<a href="#" name="'.$modelName.'" class="delete_image" >удалить</a>)
				| (<a href="'.$original['path'].$file_image.'" class="look_original" target="_blank">посмотреть оригинал</a>)
				</span>'; 
		} ?>
	</p>

	<form name="photo" id="upload_form" enctype="multipart/form-data" action="/support/ajax/imageCropUpload" method="post">
		<? for($i = 0; $i< count($crop); $i++) { ?>
			<input type="hidden" name="crop[<?=$i;?>][0]" value="<?=$crop[$i]['size'][0];?>">
			<input type="hidden" name="crop[<?=$i;?>][1]" value="<?=$crop[$i]['size'][1];?>">
			<input type="hidden" name="crop[<?=$i;?>][2]" value="<?=$crop[$i]['path'];?>">
		<? } ?>	
		<? for($i = 0; $i< count($scale); $i++) { ?>
			<input type="hidden" name="scale[<?=$i;?>][0]" value="<?=$scale[$i]['size'][0];?>">
			<input type="hidden" name="scale[<?=$i;?>][1]" value="<?=$scale[$i]['size'][1];?>">
			<input type="hidden" name="scale[<?=$i;?>][2]" value="<?=$scale[$i]['path'];?>">
		<? } ?>
		<input type="hidden" name="original_path" value="<?=$original['path'];?>">
		<input type="file" name="image" size="30"/> 
		<input type="submit" name="upload" value="Загрузить"/>
	</form>
	<div class="loading" style="display: none;">Загрузка...</div>
	
	<? if($file_image != '') { $display=''; } else { $display = 'style="display: none;"';}
		foreach($crop as $crop_image) { ?>
			<ul class="show_images" <?=$display;?>>
				<li>
					<?=$crop_image['title'];?><br>
					<img src="<?=$crop_image['path'].$file_image;?>">
				</li>
			</ul>
		<? } ?>
	

	<div class="info">Для выделения области обрезки: кликнуть и потянуть мышкой на оригинале изображении</div>
	<? foreach($crop as $crop) { 
		if($crop['size'][0] > $crop['size'][1]) { $width = $crop['size'][0]; $height = $width;}
		else { $height = $crop['size'][1]; $width = $height;}

	?>
		<div class="crop">
			<ul><li><?=$crop['title'];?> <a href="#" class="add_fields">(добавить белые поля)</a></li></ul>
			<div class="images_wrapper">
				<div class="original_info">Оригинал</div>
				<img src="" path="<?=$crop['path'];?>" style="float: left; margin-right: 10px;" class="crop_image" alt="Create Thumbnail" />
				<div style="float:left; position:relative; overflow:hidden; width:<?php echo $crop['size'][0];?>px; height:<?php echo $crop['size'][1];?>px;">
					<div class="result_info">Результат</div>
					<img src="" style="position: relative;" alt="Thumbnail Preview" />
				</div>
				<br style="clear:both;"/>
				<form name="thumbnail" class="thumbnail_form" action="/support/ajax/imageCropThumbnail" method="post">
					<input type="hidden" name="crop_path" value="<?=$crop['path'];?>">
					<input type="hidden" name="original_path" value="<?=$original['path'];?>">
					<input type="hidden" name="crop_width" value="<?=$crop['size'][0];?>">
					<input type="hidden" name="crop_height" value="<?=$crop['size'][1];?>">
					<input type="hidden" name="file_name" value="" />
					<input type="hidden" name="koef" value="">
					<input type="hidden" name="x1" value="" class="x1" />
					<input type="hidden" name="y1" value="" class="y1" />
					<input type="hidden" name="x2" value="" class="x2" />
					<input type="hidden" name="y2" value="" class="y2" />
					<input type="hidden" name="w" value="" class="w" />
					<input type="hidden" name="h" value="" class="h" />
				</form>
			</div>
		</div>
	<? } ?>
	
</div>

