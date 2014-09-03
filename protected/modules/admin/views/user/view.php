<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{font-size:12px;line-height:20px;padding:3px;overflow:hidden;height:20px;}
.summary{text-align:right;}
td{padding:0;}
.pagination{margin:5px 0;}
</style>
<ol class="breadcrumb">
  	<li class="active" >用户列表</li>
</ol>
<div class="search-forms" style="margin-bottom: 5px;">
<div class="wide form">
	<!---->
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'添加管理员',
		    'type'=>'inverse', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
		    'size'=>'small', // null, 'large', 'small' or 'mini'
		   // 'htmlOptions'=>array('style'=>'position:absolute;'),
		    'url'=>array('/admin/user/add'),
		)); ?>
		<div style="margin-bottom:10px;"></div>
</div>
</div>
<div class="box" style="position: relative">
	<div class="box-content">


<?php
    if($is_super_admin!=0){
    $this->widget('bootstrap.widgets.TbGridView',array(
    'dataProvider'=>$model->search(),
    'type'=>'striped bordered condensed',
    'pager' => array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
    'columns'=>array(
       'id',
       'username',
       array(
           'name'=>'管理员类型',
           'value'=>'$data->isadmin',
       ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',//{addusers}
            'htmlOptions'=>array('style'=>'width:130px;'),
            'buttons'=>array(
                 'update'=>array(
                    'label'=>'修改',
                    'icon' => 'danger',
                    'options'=>array('style'=>'padding-right:10px;'),
                                   
                ),
                 'delete'=>array(
                    'label'=>'删除',
                    'icon' => 'inverse',
                    'options'=>array('style'=>'padding-right:10px;'),
                ),
                
            ),
        ),
    ),

));
    }else{
       $this->widget('zii.widgets.grid.CGridView',array(
        'dataProvider'=>$model->search(),
        'columns'=>array(
           'id',
           'username',
           array(
            'name'=>'管理员类型',
           	'value'=>'$data->isadmin',
       		),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array(
                    'delete' => array(
                        'visible' =>'', // assumes model has canDelete attribute
                        ),
                        
                    ),
                ),
            ),
        
        )); 
    }

?>
</div>
</div>
<script>
    function a(){
       //alert(111);
         $("tbody > tr td:nth-child(5)").each(function(){
          var dom = this;
          var htm = $(dom).html().toLowerCase();
          //htm = 'sohu,tudou';
          //alert(htm.indexOf('sohu')+','+htm.indexOf('youku'));
          if(htm.indexOf('sohu')==-1&&htm.indexOf('youku')==-1){
              var don = $(dom).parent()[0];
              var _don = $(don).children().last()[0];
              $(_don).children().last().html("");
          }
        });
   }
</script>