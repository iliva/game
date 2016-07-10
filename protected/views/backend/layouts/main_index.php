<div class="box"> 

 	<div class="title"> 
	    <h4>
	
	        <span class="icon16 entypo-icon-add"></span>
	        <span><?=CHtml::link(Yii::t('app','add'), array('support/create/'.$model_name.$dop_link), array('class'=>'btn'));?></span> 
	
			<div class="search_form">
				<?php  $search_name = Yii::app()->request->getParam('search_name');  ?>
				<form action="/support/<?=$model_name?>" >  
					<div class="row-fluid">

						<?php 
						if(@$_REQUEST['category_id'] > 0) {
							echo CHtml::hiddenField('category_id', $_REQUEST['category_id']);
						}
						
						echo CHtml::textField('search_name', $search_name, array('size'=>60, 'class' => "text", 'placeholder' => Yii::t('app','search'))); ?> 
						
						<button style="margin-top: -10px;" class="btn btn-danger" href="#"><?=Yii::t('app','search');?></button> 
					</div>  

				</form>	
			</div>	
	       
	        <?php if($list): ?>
				<? 
				$model = ucfirst($this->activeModule);
				$actions = CActiveRecord::model($model)->act;
				if(count($actions)) { ?>
		        <form class="box-form right" action="">
		            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
		                <span class="icon16 icomoon-icon-cog-2"></span>
		                <span class="caret"></span>
		            </a>
		            <ul class="dropdown-menu">
						<?
						foreach($actions as $action){ ?>
							<li><a class="change-elements" type="<?=$action;?>_choose" href="#"><span class="icon-trash"></span><?=$action;?></a></li>
						<? } ?>
		            </ul>
		        </form> 
				<? } ?>
		    <?php endif; ?> 
	    </h4> 
	</div> 

	


	<?php $this->renderPartial('/'.$model_name.'/index', array( 'model_name' => $model_name, 
																'dop_link' 	 => $dop_link,
																'list' 		 => $list,

																)); ?>

</div><!-- End .box -->  
<?php   $this->widget('application.components.widgets.AdminPagination', array( 	'module_name'   => $model_name,
																				'page'			=> $page,
						 											  			'total_pages'	=> $total_pages,
						 											  			'params' 		=> $dop_link));
 