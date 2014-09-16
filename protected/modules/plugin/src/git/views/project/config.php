<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>配置项目</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/");?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
		</div>
	</div>
	<div class="box-content">
		<?php 
		$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'create_project_form',
			'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnType'=>true,
				),
			'action' =>  '#',
			'focus' => array($project, 'name'),
			'type'=>'horizontal',
			'htmlOptions'=>array(
				'class' => 'form-horizontal',
				'enctype'=>'multipart/form-data',
				),
			)
		); 
		?>
		<!--  Project Name Input-->
		<?php echo $form->textFieldRow($project, 'name', array('class' => 'form-control'));?>
		<!-- Project type -->
		<?php 
		echo $form->dropDownListRow(
			$project,
			'type',
			$project->types,
			array(
				'select' => 'local',
				'onChange' => 'js:var types='.json_encode($project->hasDomainTypes). ';var switcher=types.hasOwnProperty($(this).val());$("#Projects_domain_group").toggle(switcher)',
				'class' => 'form-control',
				'disabled' => 'disabled',
				)
		);
		?>
		<!--  Project Domain Input-->
		<?php var_dump($project->type); var_dump($project->hasDomainTypes);?>
		<div id='Projects_domain_group' <?php echo in_array($project->type, $project->hasDomainTypes) ? '' : 'style="display:none;"'?>>
		<?php
		echo $form->textFieldRow(
			$project, 
			'domain', 
			array(
				'class' => 'form-control',
				'append' => '.red16.com',
				'appendOptions' => array(
					'class' => 'input-group-addon'
					)
				)
			);
			?>
		<?php
		echo $form->textFieldRow(
			$project, 
			'root', 
			array(
				'class' => 'form-control',
				//'prepend' => $project->rootPrepend,
				'appendOptions' => array(
					'class' => 'input-group-addon'
					)
				)
			);
			?>
		<?php
		echo $form->textFieldRow(
			$project, 
			'index', 
			array(
				'class' => 'form-control',
				'appendOptions' => array(
					'class' => 'input-group-addon'
					)
				)
			);
			?>
		</div>

		<div class="form-actions" style="margin-top:20px">
			<?php 
			$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => '创建', 'type' => 'primary')
				);
			?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>
