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
		$form=$this->beginWidget('booster.widgets.TbActiveForm', array(
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
		<?php echo $form->textFieldGroup($model, 'name');?>
		<!-- Project type -->
		<?php 
		echo $form->dropDownListGroup(
			$model,
			'type',
			array(
				'select' => 'local',
				'onchange' => 'js:alert();',
				'widgetOptions' => array(
					'htmlOptions' => array(
						'select' => 'local',
						'onChange' => 'js:$("#Projects_domain_group").toggle()',
						),
					'select' => 'local',
					'asDropDownList' => true,
					'data' => array( 'local' => 'local', 'php-web' => 'php-web',),
					),
				)
		);?>
		<!--  Project Domain Input-->
		<?php
		echo $form->textFieldGroup(
			$model, 
			'domain', 
			array(
				'append' => '.red16.com', 
				'groupOptions' => array(
					'id' => 'Projects_domain_group',
					'style' => 'display: none'	
					),
				)
			);
			?>

		<div class="form-actions">
			<?php echo CHtml::submitButton('Submit'); ?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>