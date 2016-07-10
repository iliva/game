<?php
session_start();
class FrontEndController extends BaseController {
 
    public $metaDescription,
           $url         = '/',
           $metaKeywords,
           $code_name, 
           $og_title    = '', 
           $og_desc     = '', 
           $og_image    = '',
           $og_url      = '',
		   $phone      = '',
		   $phone_trim      = '',
		   $menu      = '',
           $Section     = null,
		   $Settings = null,
           $sectionUrl, 
		   $user_info = 0,
		   $string_name = '',
		   $login = false,
		   $page_type = '',
		   $token = '',
		   $miles = '',
		   $currency = 0,
           $lang ; // mobile

    public $_itemList; // для настроек


    public $activeSection; //для меню

    public function init(){
		return false;
    } 


    public function __construct($id,$module=null){
           
            parent::__construct($id,$module);
			
			// currency cookie
			if (isset($_REQUEST['set_currency']) && $_REQUEST['set_currency'] == intval($_REQUEST['set_currency'])) {
				setcookie("banderky_currency", $_REQUEST['set_currency'], time()+3600, "/", '.'.Yii::app()->params['url']);
				$this->currency = $_REQUEST['set_currency'];
			} elseif(isset($_COOKIE['banderky_currency']) && $_COOKIE['banderky_currency'] != ''){
				$this->currency = $_COOKIE['banderky_currency'];
			}
			
            // Set default page title
            $this->pageTitle = Yii::t('common', Yii::app()->name);
			
            if($this->id != 'section' && $this->id != 'site')
                $this->code_name = Base::findControllerAlias($this->id);
            else{
                $this->code_name = Yii::app()->request->getParam('code_name');
            } 

            if($this->code_name != NULL){ 
                $this->Section = Section::model()->published()->withCodeName($this->code_name)->find();
            } else {
            //    $this->Section = Section::model()->published()->withCodeName('home')->find();  
            }
			
			
			$settings_tmp = Settings::model()->findAll();
			if (count($settings_tmp) > 0) {
				$this->Settings = array();
				foreach($settings_tmp as $s) {
					$this->Settings[$s->parameter] = $s->value;
				}
			} 
           
            if($this->code_name != '')
                $this->url = '/'.$this->code_name.'/'; // эта переменная может изменяться в коде
            
            $this->sectionUrl    = $this->url; 

            return true; 
    }
    


    public function findControllerCodeName($controller_name){

        $Section = Section::model()->published()->find('controller = :controller', array(':controller' => $controller_name.'Controller.php'));
        if(!$Section && $Section->code_name == '') return false;
        
        return $Section->code_name;
    } 


	

} 
