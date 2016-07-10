<?php

class BaseModel extends CActiveRecord { 

	protected function tablePrefix(){
		return 'smart_';
	}
	
	public function init() {
        parent::init(); 
        if(isset($this->active)){
        	if($this->isNewRecord)
        		$this->active = 1;	
        }
         
    } 
	 
	public function getModel($m) {	
		$m  = ucfirst($m);
		
		if($m == 'Editors') return Editors::model();	
		if($m == 'Settings') return Settings::model();
		if($m == 'Pictures') return Pictures::model();
		if($m == 'Pictures_categories') return Pictures_categories::model();		
		if($m == 'Language') return Language::model();
		if($m == 'Size') return Size::model();
	}


	public function scopes(){

        return array(
            'published'	=> array(
                'condition'	=> $this->tableAlias.'.active = 1',
            ), 	
            'actual'	=> array(
                'condition'	=> $this->tableAlias.'.number > 0',
            ), 			
        );
    } 
	
    protected $uploadedFile = null;
	public function addUploadedFile($file){
		$this->uploadedFile = $file;
	}
	public function getUploadedFile(){
		return $this->uploadedFile;
	}


	public static function model($className=__CLASS__){
		return parent::model($className);
	}


 	
	public function limit($num=1){

		$this->getDbCriteria()->mergeWith(array(
			'limit'	=> $num
		));
		return $this;
	} 

	
	public function page($page=1, $perPage=10){
		
		$this->getDbCriteria()->mergeWith(array(
			'offset'=>(($page-1)*$perPage),// + 1
			'limit'=>$perPage
		));
		return $this;
	} 

	public function orderById(){
 
		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.id'
		));
		return $this;
	}

	public function orderByIdDesc(){
 
		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.id DESC'
		));
		return $this;
	}
	
	public function orderByName(){
 
		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.name DESC'
		));
		return $this;
	}
	
	public function orderByDate(){

		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.date DESC, 
			'.$this->tableAlias.'.order_num, 
			'.$this->tableAlias.'.added_time DESC' 
		));
		return $this;
	}

	public function orderByOrderNum(){

		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.order_num, '.$this->tableAlias.'.id DESC'
		));
		return $this;
	}
	
	public function orderByRand(){

		$this->getDbCriteria()->mergeWith(array(
			'order'	=> 'RAND()'
		));
		return $this;
	}	

	// category_id
	public function withCategory($id = 0)	{
	 
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.category_id = :category_id',
			'params'	=> array(':category_id' => $id)
		));
		return $this;	
	}
	
	
	public function withParent($id = 0)	{
	
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.parent_id = :parent_id',
			'params'	=> array(':parent_id' => $id)
		));
		return $this;	
	}	
	
	public function withOrder($id = 0)	{
	
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.order_id = :order_id',
			'params'	=> array(':order_id' => $id)
		));
		return $this;	
	}	
	
	public function withCodeName($codeName){


		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.code_name = :code_name',
			'params'	=> array(':code_name' => $codeName)
		));
		return $this;
	}
	
	
	public function hasNo($id){


		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.id != :id',
			'params'	=> array(':id' => $id)
		));
		return $this;
	}	

  	public function findByParent($parent_id,$lang_id=1){

		$result = $this->find(array('condition' => 'parent_id = :parent_id
									AND language_id = :language_id',
									'params' 	=> array(':parent_id' => $parent_id,
									 					':language_id'=> $lang_id))); 

		return $result;
	}
	
	public function searchName($search_name,$model_name){
	
		$params = array();
		$condition = array();
				
		if($search_name != ''){
		
			// поиск по таблице переводов
			if($this->transfer_type){
			
				$className_transfer  = ucfirst($model_name).'Transfer';
				$class_transfer	= new $className_transfer;
				
				if(count($class_transfer->search) > 0) {
					foreach($class_transfer->search as $search_item) {
						$condition[] = 'transfer.'.$search_item.' LIKE :search_item';
						$params  += array(':search_item' => '%'.$search_name.'%'); 
					}
				}

				$this->getDbCriteria()->mergeWith(array(
					'with'	=> array(
						'transfer'=>array(
							'joinType'=>'INNER JOIN',
							'condition' => implode(' OR ', $condition),
							'params' 	=> $params
						),
					)
				)); 
				
				// поиск по текущей таблице
			} else {

				if(count($this->search) > 0) {
					foreach($this->search as $search_item) {
						$condition[] = $this->tableAlias.'.'.$search_item.' LIKE :'.$search_item;
						$params  += array(':'.$search_item => '%'.$search_name.'%'); 
					}
				}

				$this->getDbCriteria()->mergeWith(array(
					'condition'	=> implode(' OR ', $condition),
					'params'	=> $params,
				));
			
			}
		}	

		return $this;	
	}
	
	protected function fileDelete($filename){    
  		$file = $_SERVER['DOCUMENT_ROOT'].$filename; 
		if(file_exists($file)){
        	@chmod ($file, 0777);
        	unlink($file); 
        } 
        return true;
  	}  

  	protected function beforeDelete(){
		parent::beforeDelete();    
 		return true;	
 	} 

 	protected function beforeSave(){
		parent::beforeSave();    
 		return true;	
 	} 

	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 

		return true;
  	}

	public function withProduct($id = 0)	{
	
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.product_id = :product_id',
			'params'	=> array(':product_id' => $id)
		));
		return $this;	
	}	

	
	public function meta($Section){

		if ($Section->transfer->page_title == '') {
			$Section->transfer->page_title = $Section->transfer->name;
		}
		if ($Section->transfer->meta_description == '') {
			$Section->transfer->meta_description = $Section->transfer->name;
		}
		if ($Section->transfer->meta_keywords == '') {
			$Section->transfer->meta_keywords = $Section->transfer->name;
		}
		
 		$meta[]	= $Section->transfer->page_title;
		$meta[]	= $Section->transfer->meta_description;
		$meta[] = $Section->transfer->meta_keywords; 
		return $meta;
	}	
	
	public function sendMail($from_email, $to_email, $subject, $message){ 

	   $success = 0;
       $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
		try {
			$mailer->Host   	= Yii::app()->params['mailhost']; // config/main.php params
			//$mailer->IsSMTP();
			$mailer->From       = $from_email;
			$mailer->FromName   = Yii::app()->name;

			$mailer->ClearAddresses();
			$mailer->ClearAttachments();
 
			$mailer->AddAddress($to_email);
			$mailer->IsHTML(true); 

			$mailer->CharSet    = 'UTF-8'; 
			$mailer->Subject    = $subject;    
			$mailer->Body       = $message;    
			$mailer->Send();
			$success = 1;	
		}catch (phpmailerException $e){
			//echo $e; exit;
		}  
		catch (Exception $e){
			//echo  $e; exit;
		} 
		return $success;
	}	
	
		public function saveData(){ 
		
		$pictures = array();
		$picturesQuery = Pictures::model()->published()->orderByRand()->findAll(); 
			
		foreach($picturesQuery as $item) {
			$pictures[] = array(
				'id' => $item->id,
				'category_id' => $item->category_id,					
				'image' => $item->file_image,
				'name' => $item->name,
			);
		}

		$categories = array();
		$categoriesQuery = Pictures_categories::model()->published()->orderByOrderNum()->findAll(); 
			
		foreach($categoriesQuery as $item) {
			$categories[] = array(
				'id' => $item->id,
				'name' => $item->name,
				'image' => $item->file_image,
			);
		}		
		
		
		$data = json_encode($pictures);
		$fp = fopen('json/pictures.json', 'w');  
		fwrite($fp, $data);
		fclose($fp);

		$data = json_encode($categories);
		$fp = fopen('json/categories.json', 'w');  
		fwrite($fp, $data);
		fclose($fp);		
		
	}


}