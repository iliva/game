<?php
class SupportController extends BackEndController{ 


	
    public function actionIndex($model_name='',$page=1){
	
 	   	if(!$model_name) {
			$model_name = 'pictures_categories'; // первая страница админки
    		$this->render('index');
    	}
   		$this->activeModule = $model_name;
    	
   		$className  = ucfirst($model_name);
   		$class 		= new $className();
   		$on_page 	= 20;
		
   		$criteria 		 = new CDbCriteria;
		

   		//--------------------------------------------------------------
   		//поиск
   		$search_name = Yii::app()->request->getParam('search_name');
   
		//--------------------------------------------------------------
		// сортировать по
			
		$order_string = '';
		if(count($class->select_order) > 0) {
			foreach($class->select_order as $order_item) {
				$order_string .= ' t.'.$order_item.', ';
			}
		} 
		$criteria->order = $order_string.' t.id DESC';
		
		if(Yii::app()->request->getParam('sort') != '') {
			$sort = Yii::app()->request->getParam('sort');
			$criteria->order = 't.'.$sort;
		}
				
   		$list 		= BaseModel::getModel($className)->searchName($search_name,$model_name)->page($page,$on_page)->findAll($criteria);
   		$count_all 	= BaseModel::getModel($className)->searchName($search_name,$model_name)->count($criteria);
 
 
   		//----------------------------------------------------------------------------------
		// если передан параметр category_id
    	
   		$category_id 	= (int)Yii::app()->request->getParam('category_id', 0); 
		if($category_id > 0){
			$list 		= BaseModel::getModel($className)->withCategory($category_id)->searchName($search_name,$model_name)->page($page,$on_page)->findAll($criteria);
			$count_all 	= BaseModel::getModel($className)->withCategory($category_id)->searchName($search_name,$model_name)->count($criteria);
		} 
			
			
   		//-------------------------------------------------------------------------------------------
			
   		$total_pages= ceil($count_all/$on_page);
    		 	 
		$dop_link = ''; 
		if($_SERVER['QUERY_STRING'] != ''){
			if($dop_link != ''){
				$dop_link .= '&'.$_SERVER['QUERY_STRING'];
			} else {
				$dop_link = '/?'.$_SERVER['QUERY_STRING'];
			}
		} 
		BaseModel::saveData();
   		$this->render('/layouts/main_index',array(	  'list' 			=> $list,
    												  'page'			=> $page, 
									 				  'total_pages'		=> $total_pages,
									 				  'model_name'		=> $model_name,
								 				  	  'dop_link'    	=> $dop_link )); 


    }

    public function actionCreate($model_name){

    	$params = '';
		if($_SERVER['QUERY_STRING'] != '')
			$params = '/?'.$_SERVER['QUERY_STRING'];


    	$this->activeModule = $model_name;

    	$className  = ucfirst($model_name);
   		$model 		= new $className();
	
 		$post = Yii::app()->request->getPost($className);
		if($post){

			$model->attributes=$_POST[$className]; 
			
			if($model->save()) { 
			
				BaseModel::saveData();
				$this->redirect(array('support/'.$model_name.$params));
			}			
		}

		//CRM::push_service_list();
		
		$this->render('create',array('model'			=> $model,
									 'model_name'		=> $model_name,
									 'model_transfer'	=> @$model_transfer,
								 	 'dop_link'    		=> $params
								));

	}

	 
	public function actionUpdate($model_name,$id){

		$this->activeModule = $model_name;

		$className  		= ucfirst($model_name); 
		$model 				= $this->loadModel($className,$id); 
		
 	
		
	    $params = '';
		if($_SERVER['QUERY_STRING'] != '')
			$params = '/?'.$_SERVER['QUERY_STRING']; 

    	 
		$post = Yii::app()->request->getPost($className);
		if(isset($post)){

			$model->attributes 	= $post;   

			if(isset($model->blocked)){
				$model->blocked = 0;
			}  
			if($model->save()) {  

				if($model->transfer_type){ // если у модуля есть переводы

					$postTransfer = Yii::app()->request->getPost($transferClassName);

					if($postTransfer){   
						
	 					$postTransfer = $this->getTransfersAttr($postTransfer);  


						if(Language::model()->getLanguageList()){

							foreach(Language::model()->getLanguageList() as $lang_id=>$lang_name){

								$model_transfer 				= $this->loadTransferModel($transferClassName, $model->id, $lang_id);  
			 					$model_transfer->attributes 	= $postTransfer[$lang_id]; 
 								
 								//var_dump($model_transfer->id, $model_transfer->name, $model_transfer->parent_id  );
						
			 					$model_transfer->save(); 

							}
						} 
					}
				} // end transfer
				BaseModel::saveData();
				$this->redirect(array('support/'.$model_name.$params));
			} 	
		}

		$this->render('update',array(
									'model'				=> $model,
									'model_name'		=> $model_name,
									'model_transfer'	=> @$model_transfer,
								 	'dop_link'    		=> $params
								));
	}
	


	public function actionDelete($model_name,$id){

			$className  = ucfirst($model_name); 
			$this->loadModel($className, $id)->delete();
			
		
			$params = '';
			if($_SERVER['QUERY_STRING'] != '')
				$params = '/?'.$_SERVER['QUERY_STRING'];
				
	
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('support/'.$model_name.$params));

	}
 
	
		
   private function getTransfersAttr($post){

    	$attrs = array();

    	if(count($post) > 0){
    		foreach($post as $attr=>$valuesArray){

    			if(count($valuesArray) > 0){

    				foreach($valuesArray as $lang_id=>$value){

    					$attrs[$lang_id][$attr] = $value;
    				}
    			} 
    		}
    	}

    	return $attrs;
    }	
}