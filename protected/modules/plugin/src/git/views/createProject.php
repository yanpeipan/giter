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
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'create_project_form',
			'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'action' =>  '#',
			'htmlOptions'=>array(
				'class' => 'form-horizontal',
				'enctype'=>'multipart/form-data',
				),
			)
		); 
		?>
		<!--  Project Name Input-->
		<div class="form-group">
			<?php echo $form->labelEx($model,'name', array(
				'class' => 'control-label'
				)
			); 
			?>
			<div class="controls">
				<div class="input-group">
					<?php echo $form->textField($model,'name', array('class' => 'focused')); ?>
				</div>
				<span class="help-block"><?php echo $form->error($model,'name'); ?></span>
			</div>
		</div>

		<!--  Project Domain Input-->
		<div class="form-group">
			<?php echo $form->labelEx($model,'domain', array(
				'class' => 'control-label'
				)
			); 
			?>
			<div class="controls">
				<div class="input-group">
					<?php echo $form->textField($model,'domain'); ?>
					<span class="input-group-addon">.red16.com</span>
				</div>
				<span class="help-block"><?php echo $form->error($model,'domain'); ?></span>
			</div>
		</div>

		<div class="form-actions">
			<?php echo CHtml::submitButton('Submit'); ?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>