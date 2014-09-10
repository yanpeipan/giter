<div class="container">
		<div class="row">
					<div id="content" class="col-sm-12 full">
			<div class="row">
				<div class="login-box">
					
					<div class="header">
						<?php echo Yii::app()->name;?>	
					</div>
					<?php /** @var BootActiveForm $form */
					$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					    'id'=>'verticalForm',
					    'htmlOptions'=>array('class'=>'form-horizontal login'),
					)); ?>
						<fieldset class="col-sm-12">
							<div class="form-group">
							  	<div class="controls row">
									<div class="input-group col-sm-12">	
										<?php echo $form->textFieldRow($model, 'username', array('label'=>false,'class'=>'form-control','placeholder'=>"用户名",'append'=>'<i class="fa fa-user"></i>','appendOptions'=>array('class'=>'input-group-addon'))); ?>
									</div>	
							  	</div>
							</div>
							<div class="form-group">
							  	<div class="controls row">
									<div class="input-group col-sm-12">	
										<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'form-control','placeholder'=>"密码",'append'=>'<i class="fa fa-key"></i>','appendOptions'=>array('class'=>'input-group-addon'))); ?>
										
									</div>	
							  	</div>
							</div>
							<div class="confirm">
								<input type="checkbox" name="remember"/>
								<label for="remember">记住我</label>
							</div>	
							<div class="row">
								<button type="submit" class="btn btn-lg btn-primary col-xs-12">登录</button>
							</div>
						</fieldset>	
					<?php $this->endWidget(); ?>
					<div class="clearfix"></div>				
				</div>
			</div><!--/row-->
		</div>	
				</div><!--/row-->		
		
	</div><!--/container-->
