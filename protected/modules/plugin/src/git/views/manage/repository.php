<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>版本服务器配置</h2>
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
			'bootstrap.widgets.TbActiveForm',
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
		echo $form->textFieldRow($repository, 'name');
		echo $form->textAreaRow($repository, 'description');
		echo $form->textFieldRow($repository, 'root_path');
		echo $form->textFieldRow($repository, 'git_config_path');
		echo $form->textFieldRow($repository, 'apache_group_file');
		echo $form->textFieldRow($repository, 'apache_user_file');
		echo $form->textFieldRow($repository, 'ipper');
		echo $form->textFieldRow($repository, 'apache_bin');
		echo $form->textFieldRow($repository, 'ssh_port');
		echo $form->textFieldRow($repository, 'url_port');
		echo $form->textFieldRow($repository, 'htpasswd_bin');

		$this->widget(
			'booster.widgets.TbButton',
			array('buttonType' => 'submit', 'label' => '保存')
			);

		$this->endWidget();
		unset($form);
		?>
	</div>
</div>
