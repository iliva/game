<?php

class SiteController extends FrontEndController {

	public function urlRules(){
		return array(
			array('site/index',
				'pattern'=>''
			),
			array('site/index',
				'pattern'=>'{this}'
			),						
		);
	}


	public function actionIndex(){


		$this->render('index', array(
		));
		
	}

	
	public function actionError(){

		$this->layout = 'error';
		if($error=Yii::app()->errorHandler->error){
			$this->render('error', $error);
		}
	}


}