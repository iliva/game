<?php
class SettingsController extends BackEndController{ 
 
 	public function init(){
	
		$this->role = Editors::model()->getUserRole(Yii::app()->user->id);
 		$this->activeModule = 'settings';
		$this->moduleName 	= Yii::t('app',$this->activeModule); 
		$this->moduleNameTitle 	= Yii::t('app',$this->activeModule);

 		return true;
 	}
	 
	public function actionIndex(){
  
		$model = new Settings; 
		$post = Yii::app()->request->getPost('Settings');   
		
		if(isset($post) && count($post) > 0 ){
			foreach($post as $parameter => $value){ 
				$Settings = Settings::model()->find('parameter = :parameter', array(':parameter' => $parameter));
				if($Settings){
					$Settings->value = $value;
					$Settings->save();
				} 
			}  
		}
		$settingsItems = Settings::model()->findAll();
		$this->render('index',array( 'settingsItems' => $settingsItems , 'model' => $model ));
	}
 

 
}