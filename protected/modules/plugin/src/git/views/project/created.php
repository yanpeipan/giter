<div class="box">
  <div class="box-header">
  <h2><i class="fa fa-edit"></i>项目列表</h2>
    <div class="box-icon">
      <a href="<?php echo Yii::app()->createUrl("plugin/git/createProject");?>" class="btn-adding"><i class="fa fa-plus"></i></a>
      <a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
    </div>
  </div>
  <div class="box-content">
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'type'=>'striped bordered condensed',
  'dataProvider'=>$projects->search(),	
  'pager' => array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
  'cssFile'=>'',
  'ajaxUpdate'=> false, 
  'columns'=>array(
    array(
      'type'=>'raw', 
      'name' => 'id',
      //'value'=>'CHtml::checkBox($data->id,0,array("rel"=>"orders[]",));', 
    ),
    array(
      'type'=>'raw', 
      'name' => 'name',
      //'header' => 'Project Name',
    ),
    array(
      'type'=>'raw', 
      'name' => 'type',
      //'header' => 'Project Name',
    ),

    array(
      'type'=>'raw', 
      'name' => 'remote_url',
    ),
    array(
      'type'=>'raw', 
      'name' => 'ctime',
      //'header' => 'Create At'
    ),
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'htmlOptions' => array('class' => 'col-md-2'),
      'template'=>'{manage} {publish} {visit} {delete}',
      'header'=>'操作',
      'buttons'=>array(
        'delete'=>array(
          'label'=>'删除',
          'icon' => 'danger',
          'url' =>'Yii::app()->createUrl("plugin/git/deleteProject/id/$data->id")',

        ),

        'publish'=>array(
          'label'=>"发布",
          'icon'=>'success',
	  'visible' => 'in_array($data->type, ' . var_export($projects->hasDomainTypes, True) . ') ? true : false;',
          'url'=>'Yii::app()->createUrl("plugin/git/publish/id/$data->id/domain/$data->domain")',
        ),	
        'manage'=>array(
          'label'=>'成员',
          'icon'=>'info',
          'url'=>'Yii::app()->createUrl("plugin/git/members/id/$data->id")'
        ),
        'visit'=>array(
          'options' => array(
            'title' => '访问',
          ),
	  'visible' => 'in_array($data->type, ' . var_export($projects->hasDomainTypes, True) . ') ? true : false;',
          'label'=>'访问',
          'icon'=>'',
          'url'=>'$data->domainurl'
        ),
      ),
    )
  )
)
);
?>
</div>
</div>
