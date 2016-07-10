<?php
/*
дл€ работы плагина:

jquery.form.js, 
jquery.imgareaselect.min.js, 
crop-image.js

bootstrap.css - убрать img width: 100%

class Image: 
	public function resizeThumbnailImage
	public function addBg
	public function scaleMin
	
AjaxController:
	utf-8 без boom
	public function actionImageCropUpload
	public function actionImageCropThumbnail
	public function actionImageDelete
	public function actionImageAddFields	
	
¬ьюшка:
	виджет вставить за пределы тега формы,
	в форму добавить поле: $form->hiddenField($model,'file_image',array('class' => 'upload_image'));

 ласс:
	rules - нет image_delete, image
	beforeSave - нет удалений изображений
	afterSave - $this->deleteTmpImages($this->file_image);
	afterDelete - удаление изображений вынести отдельно в public function deleteImages + deleteTmpImages	
	
иконка /img/messagebox_info.png	
	
*/
class UploadImage extends CWidget {

	public $original;
	public $crop;
	public $scale;
	public $file_image;
	public $modelName;
	

	public function run() { 

		
        $this->render('uploadImage', array('modelName' => $this->modelName,
											'file_image' => $this->file_image,
											'original' => $this->original,
											'crop' => $this->crop,
											'scale' => $this->scale));
    }
} 