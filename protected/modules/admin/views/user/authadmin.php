<h1>管理员授权</h1>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-update-form',
    'enableClientValidation'=>false,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>true,
    ),
    'htmlOptions'=>array(
        'class'=>'user-update-form',
    ),
)); ?>

<table>
    <tr class="row">
        <td>
            <?php echo $form->labelEx($model,'username'); ?>
        </td>
        <td>
            <?php echo $form->textField($model,'username',array('onblur'=>'user_ok(this)')); ?>
        </td>
        <td>
            <?php echo $form->error($model,'username'); ?>
        </td>
    </tr>
    
    <tr class="row">
         <td>
                                    管理员权限
        </td>
        <td>
            <?php echo CHtml::dropDownList('is_super_admin',$model->is_super_admin, Yii::app()->params['is_super_admin']); ?>
        </td>
        <td>
            <?php echo $form->error($model,'is_super_admin'); ?>
        </td>
    </tr>
    
    <tr class="row buttons">
        <td><?php echo CHtml::submitButton('授权'); ?></td><td></td><td></td>
    </tr>
</table>
<input type="hidden" name="user_id" value="<?php echo $model->id; ?>">
<?php $this->endWidget(); ?>