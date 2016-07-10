<div style="padding: 20px">
<?php
 
if($this->model->created_by > 0) 
	echo 'Created by: '.Editors::model()->findByPk($this->model->created_by)->fullname.', '.$this->model->added_time.'<br>'; 
	
if($this->model->modified_by > 0 && $this->model->modified_by != $this->model->created_by) 
	echo 'Edited by: '.Editors::model()->findByPk($this->model->modified_by)->fullname.', '.$this->model->edited_time.'<br>'; 

?>
</div>	