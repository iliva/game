<?php
$this->breadcrumbs=array(
	Yii::t('app','editors') 
);
?>

<?php 
//echo '<p>'.CHtml::link('Добавить', array('/support/create/editors/'), array('class'=>'btn')).'</p>';

if(empty($list))
	echo '<p>'.Yii::t('app','no_entries').'</p>';
else {
?>

<?php
	echo '<table class="table table-striped table-bordered">';
	echo '<thead>';
	echo '<tr>'; 
		echo '<th width="1%">'.Editors::model()->getAttributeLabel('id').'</th>';
		 
		echo '<th>'.Editors::model()->getAttributeLabel('email').'</th>';
		echo '<th>'.Editors::model()->getAttributeLabel('fullname').'</th>'; 
		echo '<th width="5%">'.Editors::model()->getAttributeLabel('active').'</th>';
		echo '<th>'.Editors::model()->getAttributeLabel('role').'</th>'; 
		
		echo '<th width="2%"></th>';
		echo '<th width="2%"></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($list as $item) {
		echo '<tr>';
		echo '<td>'.$item->id.'</td>';
	  
		echo '<td>'.$item->email.'</td>';
		echo '<td>'.$item->fullname.'</td>'; 
		echo '<td>'.$item->active.'</td>';
		echo '<td>'.$item->getUserRole($item->id).'</td>';

		echo '<td>'.CHtml::link(Yii::t('app','edit'), array('/support/update/editors/'.$item->id)).'</td>';
		echo '<td>'.CHtml::link(Yii::t('app','delete'), array('/support/delete/editors/'.$item->id)).'</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}
?>