<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>Virtual Server Config</h2>
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
				'id' => 'VirtualServers',
				'type' => 'horizontal',
				'htmlOptions' => array('class' => 'well'), // for inset effect 
				 'enableAjaxValidation'=>true,
				 'enableClientValidation'=>true,
				 'action' => Yii::app()->createUrl('plugin/git/editvirtualserver')
				)
			);
		echo $form->hiddenField($virtualserver, 'id');
		echo $form->textFieldGroup($virtualserver, 'name');
		echo $form->textAreaGroup($virtualserver, 'description');
		echo $form->textFieldGroup($virtualserver, 'htdocs_path');
		echo $form->textFieldGroup($virtualserver, 'nginx_config_path');
		echo $form->textFieldGroup($virtualserver, 'ngixn_bin');
		echo $form->textFieldGroup($virtualserver, 'ipper');
		echo $form->textFieldGroup($virtualserver, 'ssh_port');
		echo $form->textFieldGroup($virtualserver, 'url_port');
		echo $form->textFieldGroup($virtualserver, 'url_host');
		echo $form->select2Group(
			$virtualserver,
			'url_schema',
			array(
				'wrapperHtmlOptions' => array(
					),
				'options' => array(
					),
				'widgetOptions' => array(
					'asDropDownList' => true,
					'data' => array('http' => 'http', 'https' => 'https'),
					'options' => array(
						)
					),
				)
			);

		$this->widget(
			'booster.widgets.TbButton',
			array('buttonType' => 'submit', 'label' => 'save')
			);

		$this->endWidget();
		unset($form);
		?>	</div>
</div>