<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>版本服务器配置</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/");?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
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
		echo $form->hiddenField($repository, 'id', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'name', array('class' => 'form-control'));
		echo $form->textAreaRow($repository, 'description', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'root_path', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'git_config_path', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'apache_group_file', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'apache_user_file', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'url_host', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'apache_bin', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'ssh_port', array('class' => 'form-control'));
		echo $form->textFieldRow($repository, 'url_port', array('class' => 'form-control'));
		echo $form->dropDownListRow(
				$repository,
				'url_schema',
				array(
					'http' => 'http', 'https' => 'https'
				     ),
				array('class' => 'form-control')

				);

		echo $form->textFieldRow($repository, 'htpasswd_bin', array('class' => 'form-control'));
		?>
		<div class="form-actions" style="margin-top:20px">
		<?php
		$this->widget(
				'booster.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => '保存', 'type' => 'primary')
			     );
		?>
		</div>
		<?php
		$this->endWidget();
		unset($form);
		?>
		</div>
		</div>
