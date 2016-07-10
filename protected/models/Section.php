<?php
class Section extends BaseModel{

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'sections';
	}


	public function rules()	{
		return array(
			array('code_name, controller', 'default'),
		);
	}

  	public function hasController(){

		if (empty($this->controller) || !file_exists('protected/controllers/frontend/'.$this->controller))
			return false;
		
		return true;
	}
}