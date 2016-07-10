<?php

class AjaxController extends CController {

    public function actionCheckAlias(){

        $result = array('susses' => 0);
    
        $model        = Yii::app()->request->getParam('model'); 
        $this_alias   = Yii::app()->request->getParam('this_alias');  
		$id_note   = Yii::app()->request->getParam('id_note'); 
	
        $item = BaseModel::getModel($model)->find('code_name = :this_alias',array(':this_alias' => $this_alias)); 

        if($item){
            $result = array('susses'  => 0, 'id'      => $item->id);
        }
        else{
            $result = array('susses' => 1);
        }
		if($id_note == $item->id) {
			$result = array('susses' => 1);
		}


        header('Content-type: application/json'); 
        echo json_encode($result); 
    }
	
	public function actionUpload()
	{

        Yii::import("ext.EAjaxUpload.qqFileUploader");
 
        $folder = Yii::getPathOfAlias('webroot').Photos::PATH_TMP;
        $allowedExtensions = array("jpg","jpeg","gif","png");
        $sizeLimit = 100 * 1024 * 1024;
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		
        echo $return;// it's array
	}
	
	public function actionSaveUploaded() {

		$Photos = new Photos();
		if($_REQUEST['model'] == 'products') {
			$Photos->product_id = $_REQUEST['id'];
			$image_size = Products::model()->image_add_size;
			$thumb_size = Products::model()->thumb_add_size;		
		}		
		$Photos->save();
		$tmp_image = Yii::getPathOfAlias('webroot').Photos::PATH_TMP.$_REQUEST['file'];
		$Image = new Image(); 
		$Photos->file_image = basename($Image->load($tmp_image)->save($_SERVER['DOCUMENT_ROOT'].Photos::PATH_ORIG.$Photos->id));
		$Image->crop($image_size)->save($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->id);					  
        $Image->crop($thumb_size)->save($_SERVER['DOCUMENT_ROOT'].Photos::PATH_THUMB.$Photos->id);		
		$Photos->save();
		
		$src = Photos::PATH_THUMB.$Photos->file_image;
		$html = "<div class='item'><img src='".$src."'><input type='text' value='".$Photos->order_num."' name='".$Photos->id."'><span class='icomoon-icon-cancel-2'></span></div>";
		
		unlink($_SERVER['DOCUMENT_ROOT'].Photos::PATH_TMP.$_REQUEST['file']);

		$result = array('html' => $html,'src' => $src);		
		
        header('Content-type: application/json'); 
        echo json_encode($result); 	
	}
	
	public function actionSaveOrderNum() {

		$Photos = Photos::model()->findByPk($_REQUEST['id']);
		$Photos->order_num = $_REQUEST['order_num'];
		$Photos->update(array('order_num'));
		
        header('Content-type: application/json'); 
        echo json_encode($result); 	
	}
	
	public function actionDeletePhoto() {

		Photos::model()->findByPk($_REQUEST['id'])->delete();
		
        header('Content-type: application/json'); 
        echo json_encode($result); 	
	}	

	
    public function actionGetAlias(){

        $result = array('susses'    => 1,
                        'alias'    => UrlTransliterate::cleanString($_POST['name'],'_')
                    );
   
        header('Content-type: application/json'); 
        echo json_encode($result); 
    }

    public function actionClearCache(){

        $array = array('success' => 0);   

        $status = Yii::app()->cacheFile->flush();
        if($status){
            $array = array('success' => 1); 
        }

        header('Content-type: application/json'); 
        echo json_encode($array);
    }
  
    public function actionChangeElements(){

        $array      = array('success' => 0);
        $model      = Yii::app()->getRequest()->getPost('model'); 
        $action     = Yii::app()->getRequest()->getPost('action'); 
        $idsArray   = Yii::app()->getRequest()->getPost('idsArray'); 
        $model      = ucfirst($model);

        $modelItems = CActiveRecord::model($model)->findAllByPk($idsArray);
        if($modelItems){ 
            foreach($modelItems as $Model){

                switch ($action) {
                    case 'active_choose':
                        $Model->active = 1;
                        $Model->update(array('active')); 
                        break; 
                    case 'de-active_choose':
                        $Model->active = 0;
                        $Model->update(array('active')); 
                        break; 
                    case 'delete_choose':
                        $Model->delete();
                        break; 
                }

            } // end foreach

            $array = array('success' => 1);
        } 

        header('Content-type: application/json'); 
        echo json_encode($array);
    }  
	
	
    public function actionImageCropUpload(){	

		$original_path     		= Yii::app()->getRequest()->getPost('original_path');
		$scale	    			= Yii::app()->getRequest()->getPost('scale');	
		$crop	    			= Yii::app()->getRequest()->getPost('crop');	
		$image_name 			= strtotime(date('Y-m-d H:i:s'));
		$max_file 				= "8";		
		$error 					= "";
		
		$Image = new Image();  

		// информация об изображении
		$userfile_name = $_FILES['image']['name'];
		$userfile_tmp = $_FILES['image']['tmp_name'];
		$userfile_size = $_FILES['image']['size'];
		$userfile_type = $_FILES['image']['type'];
		$filename = basename($_FILES['image']['name']);
		$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
		
		// ошибки
		if(!in_array($file_ext, array('jpg','jpeg','png'))) {
			$error= "Неверное расширение файла";
		}
		elseif((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
			if ($userfile_size > ($max_file*1048576)) {
				
				$error .= "Допускаются изображения не больше ".$max_file."MB";
			}
		}else{
			$error = "Выберите файл изображения";
		}
		// Загрузка изображения
		if (strlen($error)==0){
			
			if (isset($_FILES['image']['name'])){
				// загрузка оригинала
				$Image->load($userfile_tmp)->save($_SERVER['DOCUMENT_ROOT'].$original_path.$image_name);
				$ext = $Image->getExt();
				$width = $Image->getWidth();
				// crop
				
				if(count($crop) > 0) {
				foreach ($crop as $crop){
					$file = $_SERVER['DOCUMENT_ROOT'].$crop[2].$image_name;
					$Image->scaleMin(array($crop[0],$crop[1]))->save($file);
				}}					

				// scale 
				if(count($scale) > 0) {
				foreach ($scale as $scale){
					$Image->scale(array($scale[0],$scale[1]))->save($_SERVER['DOCUMENT_ROOT'].$scale[2].$image_name);
				}}		
				
			}
		}	
		
		$array = array(	'error' => $error,
						'width' => $width,
						'file_name' => $image_name.'.'.$ext,
						);
        header('Content-type: application/json'); 
        echo json_encode($array);		
	}
	
    public function actionImageCropThumbnail(){	
		
		$koef      	  		  = Yii::app()->getRequest()->getPost('koef');
		$crop_path      	  = Yii::app()->getRequest()->getPost('crop_path');
		$original_path     	  = Yii::app()->getRequest()->getPost('original_path');
		$file_name      	  = Yii::app()->getRequest()->getPost('file_name');
		$crop_width      	  = Yii::app()->getRequest()->getPost('crop_width');
		$x1      			  = Yii::app()->getRequest()->getPost('x1')*$koef;
		$y1      			  = Yii::app()->getRequest()->getPost('y1')*$koef;
		$x2      			  = Yii::app()->getRequest()->getPost('x2')*$koef;
		$y2      			  = Yii::app()->getRequest()->getPost('y2')*$koef;
		$w      			  = Yii::app()->getRequest()->getPost('w')*$koef;
		$h      			  = Yii::app()->getRequest()->getPost('h')*$koef;
		
		$location = $_SERVER['DOCUMENT_ROOT'].$crop_path.$file_name;
		$large_image_location = $_SERVER['DOCUMENT_ROOT'].$original_path.$file_name;
		//Scale the image to the thumb_width set above
		$scale = $crop_width/$w;
		$Image = new Image(); 
		$Image->resizeThumbnailImage($location, $large_image_location,$w,$h,$x1,$y1,$scale);
		
		$array = array('image'=>$crop_path.$file_name);
        header('Content-type: application/json'); 
        echo json_encode($array);		
	}	
	
	public function actionImageAddFields(){
	
		$path = Yii::app()->getRequest()->getPost('path');
		$file_name = Yii::app()->getRequest()->getPost('file_name');
		$crop_width = Yii::app()->getRequest()->getPost('crop_width');
		$crop_height = Yii::app()->getRequest()->getPost('crop_height');

		$crop_image = $_SERVER['DOCUMENT_ROOT'].$path.$file_name;
		$crop_image_tmp = $_SERVER['DOCUMENT_ROOT'].$path.'tmp_'.$file_name;
		
		$Image = new Image(); 
		$Image->load($crop_image)->scale(array($crop_width,$crop_height));
		$Image->addBg($crop_image, $crop_width, $crop_height);
		copy($crop_image, $crop_image_tmp);
		
		$array = array('image'=>$path.'tmp_'.$file_name);
        header('Content-type: application/json'); 
        echo json_encode($array);		
	}
    public function actionImageDelete(){	

		$model_name = Yii::app()->getRequest()->getPost('name');
		$file = Yii::app()->getRequest()->getPost('file');
		$class = new $model_name();
		$class->deleteImages($file);

		$array = array();
        header('Content-type: application/json'); 
        echo json_encode($array);		
	}		
} 
 