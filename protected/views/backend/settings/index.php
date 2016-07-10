<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'servise-form',
		//'enableAjaxValidation'=>false, 
		
		'htmlOptions'=>array(
	          //'enctype'=>'multipart/form-data',
	          'class' => 'admin-form',
	     )  
		
	)); 
	?>   	 
	<br>
	<button class="btn btn-primary"><?=Yii::t('app','save');?></button>

 	<?php if($settingsItems): ?>
		<div class="row-top">
			<div class="block">	
				<?php echo $form->label($model ,'manager_email'); ?> 
				<?php echo CHtml::textField('Settings['.$settingsItems[0]->parameter.']', $settingsItems[0]->value); ?>

				<?php echo $form->label($model ,'title'); ?> 
				<?php echo CHtml::textField('Settings['.$settingsItems[1]->parameter.']', $settingsItems[1]->value, 
				array( 'style'=>'width: 500px')); ?>
				
				<?php echo $form->label($model ,'meta_description'); ?> 
				<?php echo CHtml::textArea('Settings['.$settingsItems[2]->parameter.']', $settingsItems[2]->value, 
					array('style'=>'width: 500px', 'rows'=>4)); ?>								
				
				<?php echo $form->label($model ,'meta_keywords'); ?> 
				<?php echo CHtml::textArea('Settings['.$settingsItems[3]->parameter.']', $settingsItems[3]->value, 
					array('style'=>'width: 500px', 'rows'=>4)); ?>	
					
				<?php echo $form->label($model ,'novaposhta'); ?> 
				<?php echo CHtml::textField('Settings['.$settingsItems[4]->parameter.']', $settingsItems[4]->value, 
				array( 'style'=>'width: 500px')); ?>				
				
			</div> 						

		</div>  

			
		<?php endif; ?>   
			   
  
	<div class="clear"></div>
			
		
	<?php $this->endWidget(); ?> 
</div><!-- form -->