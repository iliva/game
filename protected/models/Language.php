<?php
class Language extends BaseModel{
	

	public 	$transfer_type = false,
			$select_order = array('id'),
			$act = array('delete'),
			$search = array('name');

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'language';
	}


	public function rules()	{
		return array(
			//array('code_name', 'required'),
			array('name, code_name', 'required'),
			array('added_time, edited_time, modified_by, created_by', 'default'),
		);
	}

	protected function beforeSave(){

		parent::beforeSave(); 

 		//if($this->id == 1 && $this->code_name == 'ua' && $this->active == 0)
  		//	return false;

  		return true;
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
			'name' 				=> 'назва',
			'code_name' 		=> 'код',
		);
	}

	public function getActiveLanguageId(){
		$Language = Language::model()->find('code_name = :code_name', array(':code_name' => Yii::app()->language));
		return $Language->id;
	}
    
    public function getDefaultLanguageId(){ 

		$Language = Language::model()->find('default = :default', array(':default' => Yii::app()->language)); 
		return $Language->id;
	}

	public function getActiveLanguage(){ 

		$Language = Language::model()->find('code_name = :code_name', array(':code_name' => Yii::app()->language)); 
		return $Language;
	}

	public function getLanguageList($row='name'){

		$items = Language::model()->orderById()->findAll();

		return CHtml::listData($items, 'id', $row );
	}  

  	protected function beforeDelete(){

 		parent::beforeDelete(); 
 		
 		if($this->id == 1 || $this->code_name == 'ua')
  			return false; 

 		return true;	
 	}

}