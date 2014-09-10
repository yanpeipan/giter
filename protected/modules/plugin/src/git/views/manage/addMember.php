<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>添加成员</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/members", array('id' => $members->pid));?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
		</div>
	</div>
	<div class="box-content">
		<?php 
		$form = $this->beginWidget(
			'bootstrap.widgets.TbActiveForm',
			array(
				'id' => 'addMember',
				'type' => 'horizontal',
				'htmlOptions' => array('class' => 'well'), // for inset effect 
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				//'action' => Yii::app()->createUrl('')
				)
			);
		?>
		 <?php echo $form->dropDownListRow($members, 'uid', $usernames, array('class' => 'form-control', 'labelOptions' => array('label' => '用户'))); ?>

		<div class="form-actions" style="margin-top:20px">
			<?php echo $form->hiddenField($members, 'pid', array('value' => $members->pid));?>
			<?php 
			$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => '添加', 'type' => 'primary')
				);
			?>
		</div>

		<?php $this->endWidget(); ?> 
	</div>
</div>
