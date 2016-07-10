<div class="form">

	<div class="model-description">
		<p class="note"><?=Yii::t('app','required');?></p>
	</div>
	

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'pictures-form',

		'enableAjaxValidation'=>false, 
		
		'htmlOptions'=>array(
			 'enctype'=>'multipart/form-data',
			 'class' => 'admin-form',
		 ),

		
	)); ?>
	
	<?php echo $form->errorSummary($model); ?>


 	<div class="row-top"> 
		<div class="row-elements">
		
			<?
				$category_id = (int)Yii::app()->request->getParam('category_id', 0); 
				echo $form->hiddenField($model,'category_id',array('value' => $category_id));			
			?>	
			
			<div class="block admin-image">
				<?php echo $form->labelEx($model,'file_image'); ?>

				<?php if($this->getAction()->id == 'update' && !empty($model->file_image) && file_exists($_SERVER['DOCUMENT_ROOT'].$model->image_small['path'].$model->file_image) ):
				               
			        echo '<a href="'.$model->image_big['path'].$model->file_image.'">'.CHtml::image($model->image_small['path'].$model->file_image).'</a><br />';   
				    	
			    	echo $form->checkBox($model, 'image_delete');
			        echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />';
			        echo $form->error($model,'image_delete'); 
				    endif; ?>

			   	<?php echo $form->fileField($model,'image'); ?>
			</div>	
			
			<div class="block">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name'); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
			
			
			<div class="block">
				<?php echo $form->labelEx($model,'active'); ?>
				<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>  

		</div>
	</div>	 
 

	<div class="clear"></div> 
	<br>
    <button class="btn btn-primary"><?php echo ($model->isNewRecord ? Yii::t('app','add') : Yii::t('app','save')) ?></button> 
	
	<?php $this->widget('application.components.widgets.AdminAuthor', array( 'model' => $model )); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->