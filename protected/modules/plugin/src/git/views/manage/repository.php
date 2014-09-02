<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>Repository Config</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/");?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-setting"><i class="fa fa-wrench"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
			<a href="form-dropzone.html#" class="btn-close"><i class="fa fa-times"></i></a>
		</div>
	</div>
	<div class="box-content">
		<?php 
		$form = $this->beginWidget(
			'booster.widgets.TbActiveForm',
			array(
				'id' => 'Repositories',
				'type' => 'horizontal',
				'htmlOptions' => array('class' => 'well'), // for inset effect 
				'enableAjaxValidation'=>true,
				 'enableClientValidation'=>true,
				 'action' => Yii::app()->createUrl('plugin/git/editRepostory')
				)
			);
		echo $form->hiddenField($repository, 'id');
		echo $form->textFieldGroup($repository, 'name');
		echo $form->textAreaGroup($repository, 'description');
		echo $form->textFieldGroup($repository, 'root_path');
		echo $form->textFieldGroup($repository, 'git_config_path');
		echo $form->textFieldGroup($repository, 'apache_group_file');
		echo $form->textFieldGroup($repository, 'apache_user_file');
		echo $form->textFieldGroup($repository, 'ipper');
		echo $form->textFieldGroup($repository, 'apache_bin');
		echo $form->textFieldGroup($repository, 'ssh_port');
		echo $form->textFieldGroup($repository, 'url_port');
		echo $form->textFieldGroup($repository, 'htpasswd_bin');

		$this->widget(
			'booster.widgets.TbButton',
			array('buttonType' => 'submit', 'label' => 'save')
			);

		$this->endWidget();
		unset($form);
		?>
	</div>
</div>