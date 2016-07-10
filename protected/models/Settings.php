<?php  
 
class Settings extends BaseModel {

	public 	$transfer_type = false,
			$search = array(),
			$select_order = array();
			
	private $_itemList = array();

	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'settings';
	}

	public function rules(){
		return array(
			array('id, parameter, value', 'safe'), 
		);
	} 

	public function attributeLabels()	{
		return array(
			'id' 				=> 'ID', 
			'manager_email' 	=> 'E-mail менеджера',
			'title' 			=> 'Заголовок сайту',
			'meta_description'	=> 'Опис',
			'meta_keywords'		=> 'Ключеві слова',
			'facebook' 			=> 'facebook',
			'twitter' 			=> 'twitter',
			'skype' 			=> 'skype',
			'novaposhta' 		=> 'Нова Пошта, грн',
		);
	}

	public function getItemByKey($key) { 

		if(!empty($this->_itemList[$key]))
			return $this->_itemList[$key]; 
		
		$Settings = Settings::model()->find('parameter = :key', array(':key' => $key));

		if($Settings){
			$this->_itemList[$key] = $Settings->value;
		}
 
		return $this->_itemList[$key];
	}
}