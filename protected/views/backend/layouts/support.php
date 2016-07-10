<!doctype html> 
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js " lang="en"> <!--<![endif]-->
	<head>
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="application-name" content="admin" />
		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>   
		<meta http-equiv="content-script-type" content="text/javascript">
		<link href="<?=Yii::app()->baseUrl?>/css/support/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="<?=Yii::app()->baseUrl?>/css/support/icons.css" rel="stylesheet" type="text/css" />
		<link href="<?=Yii::app()->baseUrl?>/plugins/forms/uniform/uniform.default.css" type="text/css" rel="stylesheet" />
		<link href="<?=Yii::app()->baseUrl?>/plugins/forms/ibutton/jquery.ibutton.css" type="text/css" rel="stylesheet">

		<link href="<?=Yii::app()->baseUrl?>/css/support/main.css" rel="stylesheet" type="text/css" /> 
		<link href="<?=Yii::app()->baseUrl?>/css/support/custom.css" rel="stylesheet" type="text/css" /> 
		<link href="<?=Yii::app()->baseUrl?>/css/support/chosen.css" rel="stylesheet" type="text/css" />  
		<link rel="shortcut icon" href="images/favicon.ico" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
		<script src="<?=Yii::app()->baseUrl?>/js/support/jquery-ui.js"></script>
		<script src="<?=Yii::app()->baseUrl?>/js/support/jquery.form.js"></script>
		<script src="<?=Yii::app()->baseUrl?>/js/support/jquery.imgareaselect.min.js"></script>
		<script src="<?=Yii::app()->baseUrl?>/js/support/crop-image.js"></script>
<? /*
		<link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/js/support/colorpicker/css/colorpicker.css" type="text/css" />
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/colorpicker/js/colorpicker.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/colorpicker/js/eye.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/colorpicker/js/layout.js"></script>
		*/ ?>
		
	</head>   
	<body> 
		<?php if(Yii::app()->user->isGuest==false):?> 

			<!-- loading animation -->
			<div id="qLoverlay"></div>
			<div id="qLbar"></div>
			<div id="header" class="span12">

				<div class="navbar">
					<div class="navbar-inner">
						<div class="container-fluid">
							<a href="<?=Yii::app()->baseUrl?>/support/"><span class="brand"  ><?=Yii::app()->name ?></span> </a>
							<div class="nav-no-collapse">
				

								<ul class="nav">  
									<li class="dropdown">

										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<span class="icon16 icomoon-icon-cog"></span> <?=Yii::t('app','settings')?>
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li class="menu">
												<ul>
													<li>                                                    
														<a href="<?=$this->createUrl('support/settings')?>"><span class="icon16 icomoon-icon-equalizer"></span>
														<?=Yii::t('app','site_configuration')?></a>
													</li> 
													<? if($this->role != 'editor') {?>		
													<li>                                                    
														<a href="<?=$this->createUrl('support/language')?>"><span class="icon16 icomoon-icon-pacman"></span><?=Yii::t('app','languages')?></a>
													</li> 	
													<? } ?>	
												</ul>
											</li>
										</ul>
									</li>  
								</ul>


								<ul class="nav pull-left usernav">

									<li class="dropdown">
										<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
											<span class="icon16 icomoon-icon-user"></span>
											<span class="txt"><?=Yii::app()->user->email?></span>
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li class="menu">
												<ul>
													<li>
														<a href="<?=$this->createUrl('support/update/editors/'.Yii::app()->user->id)?>"><span class="icon16 icomoon-icon-pencil"></span>
														<?=Yii::t('app','edit_profile')?></a>
													</li>  
													<? if($this->role != 'editor'): ?>
														<li>
															<a href="<?=$this->createUrl('support/editors/')?>"><span class="icon16 icomoon-icon-users"></span>
															<?=Yii::t('app','edit_editors')?></a>
														</li>
														<li>
															<a href="<?=$this->createUrl('support/create/editors/')?>"><span class="icon16 entypo-icon-add"></span>
															<?=Yii::t('app','add_editor')?></a>
														</li>
													<?php endif;?>	
												</ul>
											</li>
										</ul>
									</li>
									<li><a href="<?=Yii::app()->baseUrl?>/support/logout"><span class="icon16 icomoon-icon-exit"></span> <?=Yii::t('app','logout')?></a></li>
								</ul>
				
							</div><!-- /.nav-collapse -->
						</div>
					</div><!-- /navbar-inner -->
				</div><!-- /navbar -->  
			</div><!-- End #header -->
	  
 


			<div id="wrapper">

				<!--Responsive navigation button-->  
				<div class="resBtn">
					<a href="#"><span class="icon16 minia-icon-list-3"></span></a>
				</div>
				
				<div id="sidebarbg"></div>
			    

				<div id="sidebar">  

					<div class="sidenav">

						<div class="mainnav">
							<ul>

			
									<li <?php if($this->activeModule == 'pictures') echo 'class="active"' ?>>
										<a href="<?=$this->createUrl('support/pictures_categories')?>">
										<span style="margin-left: -1px;" class="icon16 icomoon-icon-gift"></span>
										Pictures
										</a>
									</li> 	
								
							</ul>
						</div>
					</div>  
				</div>


				<div id="content" class="clearfix" module="<?=$this->activeModule;?>">
					<div class="contentwrapper"> 
						<div class="heading"> 
							<h3><?=$this->moduleNameTitle?></h3>    
							<div class="resBtnSearch">
								<a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
							</div> 

							<?php if(isset($this->breadcrumbs)):?>
								<?php $this->widget('zii.widgets.CBreadcrumbs', array('homeLink' => '<a href="/support/" class="crumbs-l">'.Yii::t('app','main_page').'</a> ', 
										'tagName' => 'ul', 
								        'htmlOptions' => array('class'=>'breadcrumb'), 
								        'activeLinkTemplate' => ' &rArr; <a class="crumbs-l" href="{url}">{label}</a>', 
								        'inactiveLinkTemplate'=>' &rArr; <span class="crumbs-l crumbs-l_current">{label}</span>',
								        'separator' => ' ', 
								        'links' => $this->breadcrumbs
								        )); ?>
							<?php endif;?>
						</div> 
						

						 <div class="row-fluid">

							<div class="span12">
								<?=$content ?>   
							</div>                        

						</div>                 
					</div>
				</div>
			</div>  

		<?php else:?>   

			<div class="container-fluid"> 

				<?=$content ?>  

			</div>

		<?php endif;?>

		
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/bootstrap/bootstrap.js"></script> 
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/validate/jquery.validate.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/uniform/jquery.uniform.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/ibutton/jquery.ibutton.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/misc/totop/jquery.ui.totop.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/script.js"></script>

		
	</body>
</html>