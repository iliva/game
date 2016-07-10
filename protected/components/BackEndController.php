<?php


class BackEndController extends BaseController {

    public 	$layout = '/layouts/support',
			$role,
			$breadcrumbs = array();
    
    
    public 	$activeModule,
    		$moduleName,
			$moduleNameTitle,
    		$seo_text; 


    public function init(){  

   		if(Yii::app()->user->id) 
   			$this->role = Editors::model()->getUserRole(Yii::app()->user->id);  

   		$model_name = Yii::app()->request->getParam('model_name');

   		if($model_name != NULL){

   			$this->activeModule = $model_name;
   			$this->moduleName 	= Yii::t('app',$model_name); 
			$this->moduleNameTitle = Yii::t('app',$model_name); 
			
			// добавить название категории к заголовку, если передан параметр category_id
			$category_id 	= (int)Yii::app()->request->getParam('category_id', 0);
			if($category_id > 0)
				$this->moduleNameTitle .= ' :: '.Pictures_categories::model()->findByPk($category_id)->name;	
			
			// добавить в заголовку название текущего элемента
			$id = (int)Yii::app()->request->getParam('id', 0);
			if($id > 0 && $model_name != 'sertificates' && $model_name != 'banners') {
				$model = ucfirst($model_name);
				$item = BaseModel::getModel($model)->findByPk($id);
				
			}
			
   			if(@Yii::app()->controller->actionParams['id'] == NUll){
   				$this->breadcrumbs=array(
					$this->moduleName
				);
   			} else {
				$this->breadcrumbs=array(
					$this->moduleName => array('support/'.$model_name)
				);
   			} 
   		}
	
		
		$this->moduleNameTitle = strip_tags($this->moduleNameTitle);
		
   		return true;	
   }


    public function filters(){ 

		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function accessRules(){

		return array(
			array('allow',
				//'actions'=>array('index','logout','update','create','delete' ),
				'roles'=>array('admin', 'editor', 'superAdmin'),
			),
			array('allow',
				'actions'=>array('login','logout'),
				'roles'=>array('guest'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
 	 
	
	/**
	 * Displays the login page
	 */
	public function actionLogin(){

		//если залогинен и пытается сюда зайти реддиректим на главную админки
		if(Yii::app()->user->isGuest==false){
			$this->redirect('index');	
			exit;
		}

		$model = new LoginForm;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
			
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm'])){ 

			$model->attributes=$_POST['LoginForm'];
 
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				$this->redirect('index');
			}  	
		}

		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout(){

		Yii::app()->user->logout();
		$this->redirect('/support/');
	}    

	public function actionError(){
		if($error=Yii::app()->errorHandler->error){
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


	public function loadModel($className,$id){

		$model = BaseModel::getModel($className)->findByPk($id); 

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
 