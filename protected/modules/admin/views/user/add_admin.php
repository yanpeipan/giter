<ol class="breadcrumb">
  	<li class="active" >添加用户</li>
</ol>
<div class="box">
	<div class="box-content" style="position: relative;">
<div class="row" style="margin:150px auto;width:300px;">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-add-form',
    'htmlOptions'=>array('class'=>'well'),
    'enableClientValidation'=>false,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>true,
    ),
    'htmlOptions'=>array(
        'class'=>'user-add-form',
    ),
)); ?>
	<?php echo $form->textFieldRow($model, 'username', array('class'=>'form-control focused')); ?><br />
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'form-control focused')); ?><br />
	<?php echo $form->dropDownListRow($model, 'is_super_admin', $model->adminLevel, array('class'=>'form-control focused')); ?><br />
	<?php echo $form->textFieldRow($model, 'tencent_exmail', array('class'=>'form-control focused')); ?><br />
	<?php echo $form->textFieldRow($model, 'github_name', array('class'=>'form-control focused')); ?><br />
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','type'=>'primary','size'=>"sm",'htmlOptions'=>array('class'=>'form-control'), 'label'=>AdminModule::t('Save'))); ?>
	<input type="hidden" name="user_id" value="<?php echo $model->id; ?>">
<?php $this->endWidget(); ?>
</div>
</div>
</div>
<script>
    function user_ok(dom){
       var name = $(dom).val();
       var id = $("input[name='user_id']").val();
       $.ajax({
            url:"<?php echo Yii::app()->createUrl('admin/user/ajax_username');?>",
            type:'GET',
            data:'name='+name+'&id='+id,
            success:function(result){
                if(result==1){
                    alert("用户名已存在！");
                }
            }
        });
    }
    
    
</script>
