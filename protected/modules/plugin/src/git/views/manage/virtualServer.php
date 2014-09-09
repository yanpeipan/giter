<div class="box">
  <div class="box-header">
    <h2><i class="fa fa-edit"></i>虚拟主机配置</h2>
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
?>
<fieldset>
<?php
echo $form->hiddenField($virtualserver, 'id');
echo $form->textFieldRow($virtualserver, 'name', array('class' => 'form-control'));
echo $form->textAreaRow($virtualserver, 'description', array('style' => 'width: 100%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 126px;'));
echo $form->textFieldRow($virtualserver, 'htdocs_path', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'nginx_config_path', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'ngixn_bin', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'ipper', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'ssh_port', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'url_port', array('class' => 'form-control'));
echo $form->textFieldRow($virtualserver, 'url_host', array('class' => 'form-control'));
echo $form->dropDownListRow(
  $virtualserver,
  'url_schema',
  array(
    'http' => 'http', 'https' => 'https'
  ),
  array('class' => 'form-control')

);
?>
</fieldset>
<div class="form-actions" style="margin-top:20px">
<?php
$this->widget(
  'bootstrap.widgets.TbButton',
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
