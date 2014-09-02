<ol class="breadcrumb">
  	<li class="active" >添加活跃用户</li>
</ol>

<div class="box">
	<div class="box-content">
	<div class="form" style="margin-left:35px;">
	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'hotuser-add-form',
	    'enableAjaxValidation'=>false,
	    'action' => '/plugin/user/ahsubmit',
	    'htmlOptions'=>array(
	        'enctype'=>'multipart/form-data',
	    ),
	)); ?>
	<table class="tuzi_cms_video">
		<tr>
			<td>id</td>
			<td></td>
		</tr>
		<tr>
			<td> id</td>
			<td>name</td>
		</tr>
	</table>
	<?php $this->endWidget(); ?> 
	</div><!-- form -->
	
	
</div>
</div>