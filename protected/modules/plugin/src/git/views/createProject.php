<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>Create Project</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/");?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-setting"><i class="fa fa-wrench"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
			<a href="form-dropzone.html#" class="btn-close"><i class="fa fa-times"></i></a>
		</div>
	</div>
	<div class="box-content">
		<?php 
		$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'create_project_form',
			'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'action' =>  '#',
			'type'=>'horizontal',
			'htmlOptions'=>array(
				'class' => 'form-horizontal',
				'enctype'=>'multipart/form-data',
				),
			)
		); 
		?>
		<!--  Project Name Input-->
		<?php echo $form->textFieldRow($model, 'name');?>
		<!-- Project type -->
		<?php 
		echo $form->dropDownListRow(
			$model,
			'type',
			array( 'local' => 'local', 'php-web' => 'php-web',),
			array(
				'select' => 'local',
				'onChange' => 'js:$("#Projects_domain_group").toggle()',
				
				)
		);
		?>
		<!--  Project Domain Input-->
		<div id='Projects_domain_group' style="display:none;">
		<?php
		echo $form->textFieldRow(
			$model, 
			'domain', 
			array(
				'append' => '.red16.com', 
				)
			);
			?>
		</div>

		<div class="form-actions">
			<?php echo CHtml::submitButton('Submit'); ?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>