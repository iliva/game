<?php
class Pictures extends BaseModel{
	
	public 	$transfer_type = true, // есть перевод
			$select_order = array('order_num', 'added_time DESC'), 
			$act = array('delete', 'active', 'de-active'),
			$search = array(),
			$modelName = 'Pictures',
			
			// для загрузки картинок
			$image_orig 		= array('path'=>'/img/pictures/original/'),
			$image_big 			= array('path'=>'/img/pictures/big/','size'=>array(1000,660),'title'=>'Велике зображення'), 
			$image_small 		= array('path'=>'/img/pictures/small/','size'=>array(300,198),'title'=>'Маленьке зображення'),
			$image_delete = 0,
			$image;

			
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'pictures';
	}


	public function rules()	{
		return array(
			array('order_num, active', 'numerical', 'integerOnly'=>true),
			array('file_image, category_id, added_time, edited_time, modified_by, created_by, name', 'default'),
		);
	}

    protected function beforeValidate() {
		parent::beforeValidate();
			if($this->isNewRecord) {
				$this->added_time = date('Y-m-d H:i:s');
				$this->created_by = Yii::app()->user->id;
			}
			
			$this->edited_time = date('Y-m-d H:i:s'); 
			$this->modified_by = Yii::app()->user->id;

		return true;
	} 	


	public function attributeLabels(){
		return array(
			'id' 				=> 'ID',
			'category_id' 		=> 'категорія',
			'file_image' 		=> 'зображення',
			'order_num'			=> 'порядок',
			'active'			=> 'актив',
		);
	}

	public function relations(){

		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
					'category' => array(self::BELONGS_TO,'Pictures_categories','category_id'),
					);
	}
	
	protected function beforeSave(){

		parent::beforeSave(); 
		
		if($this->image_delete == 1 && $this->file_image != ''){ 
			$this->fileDelete($this->image_big['path'].$this->file_image);
			$this->fileDelete($this->image_small['path'].$this->file_image);
			$this->file_image = '';
		} 	

		return true;
	}
	
	protected function afterSave(){ 

  		parent::afterSave();  
		
  		if(@$_FILES[__CLASS__]['tmp_name']['image'] != NULL){
		
          $doc = CUploadedFile::getInstance($this,'image');
          if($doc){
            Yii::import('application.components.Image');

            $tmp_image = $doc->getTempName();  
            $Image = new Image();  
            $this->file_image = basename($Image->load($tmp_image)->save($_SERVER['DOCUMENT_ROOT'].$this->image_orig['path'].$this->id));
			
			$Image->crop($this->image_big['size'])->save($_SERVER['DOCUMENT_ROOT'].$this->image_big['path'].$this->id);
			$Image->crop($this->image_small['size'])->save($_SERVER['DOCUMENT_ROOT'].$this->image_small['path'].$this->id);
             
            unset($_FILES[__CLASS__]['tmp_name']['image']);
			
			$this->update(array('file_image'));
          }			  
        }   


		return true;
  	}
	
	protected function afterDelete(){

  		parent::afterDelete();    
											
  		if($this->file_image != ''){ 
			$this->fileDelete($this->image_orig['path'].$this->file_image);
			$this->fileDelete($this->image_big['path'].$this->file_image);
			$this->fileDelete($this->image_small['path'].$this->file_image);	
  			$this->file_image = '';
  		}   
		return true;
  	}	
	
  	public function getUrl(){

  		return '/'.Base::findControllerAlias('C_pictures').'/'; 
  	}		
	

			
}