<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{font-size:12px;line-height:20px;padding:3px;overflow:hidden;height:20px;}
.summary{text-align:right;}
td{padding:0;}
.pagination{margin:5px 0;}
</style>
<ol class="breadcrumb">

  	<li class="active" >用户头像列表</li>
</ol>
<div class="search-forms">
	
	<div class="wide form">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
	    'label'=>'添加头像',
	    'type'=>'inverse', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	    'size'=>'small', // null, 'large', 'small' or 'mini'
	    'url'=>array('/plugin/user/AddAvatar'),
	    'htmlOptions' => array('style' => 'margin-bottom:20px;'),
	)); ?>
	</div>
</div>
<div class="box">
	<div class="box-content">
		<?php
		$this->widget('bootstrap.widgets.TbGridView', 
			array(
				 'id'=>'offline-grid',
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>$model->search(),
			    'pager' => array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
			    'cssFile'=>'',
			    'columns' => array(
				        'id',
				        array(
				        	'type' => 'raw',
				           'header' => '头像图片',
				           'name' => 'cfg_comment',
				           'value'=>'CHtml::link(CHtml::image(Yii::app()->params["imgUrl"].$data->url, !empty($data->url)?"点击查看大图":"暂无头像",    array("style" => "width:50px;height:30px;")),Yii::app()->params["imgUrl"].$data->url, array("target" => "_blank",))',  
		        		),
		        		array(
				           'header' => '图片ID',
				           'name' => 'ctime',
				           'value'=>'!empty($data["imgId"]) ? $data["imgId"] :"0"',  
		        		),
				       array(
				        	'header' => '操作',
				            'class'=>'bootstrap.widgets.TbButtonColumn',
				            'template'=>'{delete}',
				            'htmlOptions'=>array('style'=>'width:80px;'),
				            'buttons'=>array(
								'delete'=>array(
									'label'=>'删除',
				                    'icon' => 'danger',
				                    'options'=>array('style'=>'padding-right:10px;'),
									'url'=>'Yii::app()->createUrl("/plugin/user/delAvatar?id=$data[id]")',
								),
				            )
						), 
				),
			    'ajaxUpdate'=> true,
			)
			)
		?>
	</div>	
</div>