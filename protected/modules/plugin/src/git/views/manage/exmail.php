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
$this->widget('bootstrap.widgets.TbGridView', array(
			'dataProvider'=>$configure->search(),
			'columns'=>array(
				'name',          // display the 'title' attribute
				array(
					'class' => 'editable.EditableColumn',
					'name' => 'value',
					'editable' => array(    //editable section
						'url'        => $this->createUrl('site/updateUser'),
						'placement'  => 'right',
						)   
				     )
				)
			)
	     );
?>
  <div class="form-actions" style="margin-top:20px">
  </div>
    </div>
    </div>
