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
			'bootstrap.widgets.TbActiveForm',
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
		echo $form->textFieldRow($virtualserver, 'name');
		echo $form->textFieldRow($virtualserver, 'description');
		echo $form->textFieldRow($virtualserver, 'htdocs_path');
		echo $form->textFieldRow($virtualserver, 'nginx_config_path');
		echo $form->textFieldRow($virtualserver, 'ngixn_bin');
		echo $form->textFieldRow($virtualserver, 'ipper');
		echo $form->textFieldRow($virtualserver, 'ssh_port');
		echo $form->textFieldRow($virtualserver, 'url_port');
		echo $form->textFieldRow($virtualserver, 'url_host');
		echo $form->dropDownListRow(
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
			'bootstrap.widgets.TbButton',
			array('buttonType' => 'submit', 'label' => 'save')
			);

		$this->endWidget();
		unset($form);
		?>	</div>
</div>