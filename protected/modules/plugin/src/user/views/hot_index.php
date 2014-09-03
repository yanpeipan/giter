<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{font-size:12px;line-height:20px;padding:3px;overflow:hidden;height:20px;}
.summary{text-align:right;}
td{padding:0;}
.pagination{margin:5px 0;}
</style>
<ol class="breadcrumb">

  	<li class="active" >活跃用户列表</li>
</ol>
<div class="search-forms">
	
	<div class="wide form">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
	    'label'=>'添加活跃用户',
	    'type'=>'inverse', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	    'size'=>'small', // null, 'large', 'small' or 'mini'
	    'url'=>array('/plugin/user/index'),
	    'htmlOptions' => array('style' => 'margin-bottom:20px;'),
	)); ?>
	</div>
</div>
<div class="box">
	<div class="box-content">
		
		<div style="position: absolute;">
		<?php
			$total = $model->count($model->search()->criteria);
			if(!empty($total)){
				$this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'button', 'type'=>'primary','size'=>'xs','label'=>'全选','htmlOptions'=>array("checkall_value"=>0,'onclick'=>'userAllchecked(this)','style'=>'cursor:pointer;'),));       		
			}
		?>
		</div>
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
				        	'header' => '别名',
				        	'name' => 'nick_name',
				            //'value'=>'!empty($data["cfg_value"]) ? $data["cfg_value"] :"0"',
				        ),
				        array(
				           'header' => '名字',
				           'name' => 'username',
				           //'value'=>'!empty($data["cfg_comment"]) ? $data["cfg_comment"] :"0"',  
		        		),
		        		array(
				           'header' => '排序',
				           'name' => 'orderid',
				           //'value'=>'!empty($data["ctime"]) ? date("Y-m-d", $data["ctime"]) :"0"',  
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
									'url'=>'Yii::app()->createUrl("/plugin/user/hotdelete?id=$data[id]")',
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

<script>
	
function userAllchecked(_don) {
    this.don = _don;
    if(0==$(this.don).attr("checkall_value")) {
        $("input[rel='orders[]']").each(function(){
              $(this).attr("checked", true);
              //$(this).attr("check_value",1);
           });
        $(this.don).attr("checkall_value",1);
    }else if(1==$(this.don).attr("checkall_value")){
         $("input[rel='orders[]']").each(function() {
              $(this).attr("checked", false); 
              //$(this).attr("check_value",0);
         });
         $(this.don).attr("checkall_value",0);
    }
}	
	
</script>