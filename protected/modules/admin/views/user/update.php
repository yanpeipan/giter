<ol class="breadcrumb">
  	<li class="active" ><?php echo AdminModule::t("用户更新"); ?></li>
</ol>

<div class="form">
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'user-update-form',
			'htmlOptions'=>array('class'=>'well'),
			)); 
?>

<div class="row" style="margin:150px auto;width:300px;">
	<?php echo $form->textFieldRow($model, 'username', array('class'=>'form-control focused','readonly'=>"readonly")); ?><br />
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'form-control focused', 'value'=>$model->decrypt)); ?><br />
	<?php echo $form->dropDownListRow($model, 'is_super_admin', $model->adminLevel, array('class'=>'form-control focused')); ?><br />
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','type'=>'primary','htmlOptions'=>array('class'=>'form-control','style'=>'margin-top:20px'),'label'=>AdminModule::t("Save"))); ?>
	<?php $this->endWidget(); ?>
</div>
