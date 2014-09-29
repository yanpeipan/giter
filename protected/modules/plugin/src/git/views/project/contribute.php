<div class="box">
  <div class="box-header">
    <h2><i class="fa fa-edit"></i>参与的项目</h2>
    <div class="box-icon">
      <a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
    </div>
  </div>
  <div class="box-content">
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'type'=>'striped bordered condensed',
  'dataProvider'=>$projects->search(True),	
  'pager' => array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
  'cssFile'=>'',
  'ajaxUpdate'=> false, 
  'columns'=>array(
    array(
      'type'=>'raw', 
      'name' => 'id',
      'value'=>'CHtml::checkBox($data->id,0,array("rel"=>"orders[]",));', 
    ),
    array(
      'type'=>'raw', 
      'name' => 'name',
    ),
    array(
      'type'=>'raw', 
      'name' => 'type',
    ),
    array(
      'type'=>'raw', 
      'name' => 'cloneUrl',
    ),
    array(
      'type'=>'raw', 
      'name' => 'ctime',
    ),
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'htmlOptions' => array('class' => 'col-md-2'),
      'template'=>'{publish} {visit}',
      'header'=>'操作',
      'buttons'=>array(
        'publish'=>array(
          'label'=>"发布",
          'icon'=>'success',
          'url'=>'Yii::app()->createUrl("plugin/git/publish/id/$data->id/domain/$data->domain")',
	  'visible' => 'in_array($data->type, ' . var_export($projects->hasDomainTypes, True) . ') ? true : false;',
        ),
        'visit'=>array(
	  'visible' => false,
	  'options' => array(
		  'title' => '访问',
		  'target'=>'_blank',
		  ),
          'label'=>'访问',
          'icon'=>'',
          'url'=>'$data->domainurl',
	  'visible' => 'in_array($data->type, ' . var_export($projects->hasDomainTypes, True) . ') ? true : false;',
        ),

      ),
    )
  )
)
);
?>
</div>
</div>
