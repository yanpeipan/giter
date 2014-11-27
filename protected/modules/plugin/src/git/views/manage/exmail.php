<div class="box">
  <div class="box-header">
    <h2><i class="fa fa-edit"></i>QQ企业邮箱配置</h2>
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
echo $form->textFieldRow($configure, 'exmail_client_id', array('class' => 'form-control'));
echo $form->textFieldRow($configure, 'exmail_client_secret', array('class' => 'form-control'));
echo $form->textFieldRow($configure, 'exmail_host', array('class' => 'form-control'));
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
