<ol class="breadcrumb">
  	<li class="active" >添加头像</li>
</ol>

<div class="box">
	<div class="box-content">
	<div class="form" style="margin-left:35px;">
	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'avatar-add-form',
	    'enableAjaxValidation'=>false,
	    'action' => '/plugin/user/aaSubmit',
	    'htmlOptions'=>array(
	        'enctype'=>'multipart/form-data',
	    ),
	)); ?>
	
	<table class="tuzi_cms_video">
		<tr>
			<td>头像</td>
			<td>
				<?php echo $form->hiddenField($model,"url");?>
				<p id="img_add"></p>
		        <?php 
		        $this->widget('ext.EAjaxUpload.EAjaxUpload',
		        array(
		        'id'=>'uploadFiles',
		        'config'=>array(
		               'action'=>'/admin/video/upload',
		               'allowedExtensions'=>array("jpg","jpeg","gif","png"),//array("jpg","jpeg","gif","exe","mov" and etc...
		               'sizeLimit'=>10*1024*1024,// maximum file size in bytes
		               'minSizeLimit'=>1*1024,// minimum file size in bytes
		               'onComplete'=>"js:function(id, fileName, responseJSON){ $('#UserAvatar_url').val(responseJSON.filename_url); 
		                                                                       $('#img_add').html('<img width=338 height=474 src=/'+responseJSON.filename+'>');
		               }",
		              )
		        ));
		         ?>(标准图是338X474)
			</td>
		</tr>
		<tr>
			<td> 图片id</td>
			<td><?php echo $form->textField($model,'imgId');?></td>
		</tr>
		<tr>
					<td colspan="2">
						<?php $this->widget('bootstrap.widgets.TbButton', array(
						    'label'=>'保存',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'small', // null, 'large', 'small' or 'mini'
						    'htmlOptions'=>array('class'=>'form-control','onclick'=>"add_actor()"),
						)); ?>
					</td>
				</tr>
	</table>
	<?php $this->endWidget(); ?> 
	</div><!-- form -->
	
	
</div>
</div>
<script>
	function add_actor(){
		if($('#UserAvatar_url').val()==''){
			alert('请上传头像');
			return false;
		}
		if($('#UserAvatar_imgId').val()==''){
			alert('图像ID不能为空');
			return false;
		}
		$('#avatar-add-form').submit();
	}
</script>
