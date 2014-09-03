<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>Add Member</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/members", array('id' => $members->pid));?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
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
				'id' => 'addMember',
				'type' => 'horizontal',
				'htmlOptions' => array('class' => 'well'), // for inset effect 
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				//'action' => Yii::app()->createUrl('')
				)
			);
		echo $form->select2Group(
			$members,
			'uid',
			array(
				'wrapperHtmlOptions' => array(
					),
				'options' => array(
					),
				'widgetOptions' => array(
					'asDropDownList' => true,
					'data' => array($usernames),
					'options' => array(
						)
					),
				)
			);
		?>

		<div class="form-actions">
			<?php echo $form->hiddenField($members, 'pid', array('value' => $members->pid));?>
			<?php 
			$this->widget(
				'booster.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => 'save')
				);
			?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>