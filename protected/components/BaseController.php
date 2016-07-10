<?php
class BaseController extends CController{
    
    // флеш-нотис пользователю
    public function setNotice($message){
    	
        return Yii::app()->user->setFlash('notice', $message);		
    }
    
    // флеш-ошибка пользователю
    public function setError($message){

        return Yii::app()->user->setFlash('error', $message);		
    } 

    public function createUrl($route,$params=array(),$ampersand='&') {

        $url = parent::createUrl($route,$params,$ampersand);
        return $url;
    } 

 
    
}