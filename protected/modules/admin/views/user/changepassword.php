<ol class="breadcrumb">
  	<li class="active" >更改密码</li>
</ol>
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
)); ?>
<div class="row">
	<div class="col-lg-12">
		<div class="box">
			<div class="box-content">
					<div class="form-group">
					  <div class="controls">
						<div class="input-group color col-sm-4">
							<?php echo $form->passwordFieldRow($model, 'oldPassword', array('class'=>'form-control')); ?><br />
						</div>	
					  </div>
					</div>
					
					<div class="form-group">
					  <div class="controls">
						<div class="input-group color col-sm-4">
							<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'form-control')); ?>
						</div>	
					  </div>
					</div>
					
					<div class="form-group">
					  <div class="controls">
						<div class="input-group color col-sm-4">
							<?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class'=>'form-control')); ?>
						</div>	
					  </div>
					</div>
					

					<br/>

					<div class="form-actions">
					  <button type="submit" class="btn btn-primary">保存</button>
					</div>
			</div>
		</div>
	</div><!--/col-->
</div><!--/row-->
<?php $this->endWidget(); ?>
