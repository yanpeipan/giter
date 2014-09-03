<ol class="breadcrumb">
  	<li class="active" ><?php echo AdminModule::t("用户更新"); ?></li>
</ol>

<div class="form">
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-update-form',
    'htmlOptions'=>array('class'=>'well'),
)); ?>
<div class="row" style="margin:150px auto;width:300px;">
	<?php echo $form->textFieldRow($model, 'username', array('class'=>'form-control focused','readonly'=>"readonly")); ?><br />
	<?php echo $form->passwordFieldRow($model, 'oldPassword', array('class'=>'form-control focused','style'=>';float:right',)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>AdminModule::t("重置密码"),
		    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
		    'size'=>'xs', // null, 'large', 'small' or 'mini'
			'htmlOptions'=>array(
							'onclick'=>'ini_pwd()',
							'style'=>'display:inline-block',
						),
		    		)); ?>
	<br />
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'form-control focused')); ?><br />
	<?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class'=>'form-control focused')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','type'=>'primary','htmlOptions'=>array('class'=>'form-control','style'=>'margin-top:20px'),'label'=>AdminModule::t("Save"))); ?>
	<?php $this->endWidget(); ?>
</div>

<script>
    function checkValue(dom){
        if($(dom).val()==''){
            $(dom).next().html("不能为空");
            return false;
        }else{
           $(dom).next().html("");
           var pwd = $(dom).val();
           var id = $("input[name='user_id']").val();
           $.ajax({
                url:"<?php echo Yii::app()->createUrl('admin/user/ajax_pwd');?>",
                type:'GET',
                data:'id='+id+'&pwd='+pwd,
                success:function(result){
                    if(result!=1){
                        $(dom).next().html("输入的原密码不正确！");
                    }
                } 
            });
        }
    }
    
    function pw_ok(dom){
       var pwd = $(dom).val();
       var id = $("input[name='user_id']").val();
       $.ajax({
            url:"<?php echo Yii::app()->createUrl('admin/user/ajax_pwd');?>",
            type:'GET',
            data:'id='+id+'&pwd='+pwd,
            success:function(result){
                if(result==1){
                    alert("输入的原密码不正确！");
                }
            }
        });
    }
    
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
    
    function ini_pwd(){
        var id = <?php echo $_GET['id']?>;
        $.ajax({
            url:"<?php echo Yii::app()->createUrl('admin/user/ajax_inipwd');?>",
            type:'GET',
            data:'id='+id,
            success:function(result){
                if(result==1){
                    alert("初始化后密码:123456");
                }
            }
        });
    }
</script>
