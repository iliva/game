<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',

	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
         'enctype'=>'multipart/form-data',
         'class' => 'admin-form',
     ),

	
)); ?>
	
	<div class="model-description">
		<p class="note"><?=Yii::t('app','required');?></p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? Yii::t('app','add') : Yii::t('app','save')) ?></button> 
 
 	<div class="row-top"> 
		
 		<div class="row-elements">

			<div class="block">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name', 
							array( 'size' => 60,
									'maxlength' => 255, 
									'value' 	=> $model->name, 
								)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
						
			<div class="block">
				<?php echo $form->labelEx($model,'code_name'); ?>
				<?php echo $form->textField($model,'code_name', 
							array( 'size' => 60,
									'maxlength' => 255, 
									'value' 	=> $model->code_name, 
								)); ?>
				<?php echo $form->error($model,'code_name'); ?>
			</div> 	

		</div>
 
	</div>	 
 

	<div class="clear"></div> 
 
	<?php $this->widget('application.components.widgets.AdminAuthor', array( 'model' => $model )); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->