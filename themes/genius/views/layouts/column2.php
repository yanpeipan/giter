<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="row">

		<!-- start: Main Menu -->
		<div id="sidebar-left" class="col-lg-2 col-sm-1 ">

			<div class="sidebar-nav nav-collapse collapse navbar-collapse">
				<ul class="nav main-menu">
					<?php 
					if(Yii::app()->params['MAINMENU']):
						foreach(Yii::app()->params['MAINMENU'] as $menu):?>
					<?php if(AdminBaseController::menuVisible($menu['cfg_value'])):?>
						<li <?php if(strpos($menu['cfg_value'], Yii::app()->controller->id)):?>class="active"<?php endif;?>>
							<a  <?php if(isset(Yii::app()->params['SUBMENU'][$menu['id']])):?>class="dropmenu"<?php endif;?> href="<?php echo Yii::app()->createUrl($menu['cfg_value']);?>"><i class="fa <?php echo isset(Yii::app()->params['ICONS'][$menu['cfg_comment']]) ? Yii::app()->params['ICONS'][$menu['cfg_comment']] : 'fa-ellipsis-h';?>"></i><span class="hidden-sm text"> <?php echo $menu['cfg_comment'];?></span> <?php if(isset(Yii::app()->params['SUBMENU'][$menu['id']])):?><span class="chevron closed"></span><?php endif;?></a>	
							<?php if(isset(Yii::app()->params['SUBMENU'][$menu['id']])):?>
								<ul>
									<?php foreach(Yii::app()->params['SUBMENU'][$menu['id']] as $k1=>$v1):?>
										<?php if(AdminBaseController::menuVisible($menu['cfg_value'])):?>
											<li <?php if(strpos($k1, $this->getAction()->getId()) && strpos($k1, Yii::app()->controller->id)):?>class="active"<?php endif;?>><a class="submenu" href="<?php echo Yii::app()->createUrl($k1);?>"><i class="fa fa-chevron-right"></i><span class="hidden-sm text"> <?php echo $v1;?></span></a></li>
										<?php endif;?>
									<?php endforeach;?>
								</ul>
							<?php endif;?>
						</li>
					<? endif;?>
				<?php endforeach;?>
			<? endif;?>
		</ul>
	</div>
	<a href="#" id="main-menu-min" class="full visible-md visible-lg"><i class="fa fa-angle-double-left"></i></a>
</div>
<!-- end: Main Menu -->

<!-- start: Content -->
<div id="content" class="col-lg-10 col-sm-11 ">

	<?php 
	$this->widget('bootstrap.widgets.TbAlert', array(
				'block'=>true, // display a larger alert block?
				'fade'=>true, // use transitions?
				'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
				'htmlOptions'=>array('displayTime'=>0),
				'alerts'=>array( // configurations per alert type
					'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
   					'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
   					'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
   					'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
   					'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
   					)
				)
	);
	?>
	<?php echo $content;?>	
</div>
<!-- end: Content -->

</div><!--/row-->		

</div><!--/container-->
<?php $this->endContent(); ?>
