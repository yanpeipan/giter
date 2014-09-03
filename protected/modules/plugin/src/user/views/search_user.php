<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{font-size:12px;line-height:20px;padding:3px;overflow:hidden;height:20px;}
.summary{text-align:right;}
td{padding:0;}
.pagination{margin:5px 0;}
</style>
<ol class="breadcrumb">
  	<li class="active" >用户列表</li>
</ol>


<div style="margin:5px 0px;">
<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl('plugin/user/index'),
    'method'=>'get',
)); ?>
    <span class="key">用户名:</span>
    <?php echo $form->textField($model,'loginname',array('class'=>'focused','placeholder'=>"例如:张三")); ?>
    
    <span class="key">昵称:</span>
    <?php echo $form->textField($model,'nickname',array('class'=>'focused','placeholder'=>"")); ?>
    
	<?php $this->widget('bootstrap.widgets.TbButton', array(
	    'label'=>'搜索',
	    'buttonType'=>'submit',
	    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	    'size'=>'xs', // null, 'large', 'small' or 'mini'
	    'htmlOptions'=>array('style'=>'margin-left:10px'),
	)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
	    'label'=>'清空',
	    'size'=>'xs', // null, 'large', 'small' or 'mini'
	    'url'=>array('/plugin/user/index'),
	)); ?>
    
<?php $this->endWidget(); ?>
</div>

<div class="box">
	<div class="box-content">
		
		<div style="position: absolute;">
		<?php
			$total = $model->count($model->search()->criteria);
			if(!empty($total)){
				$this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'button', 'type'=>'primary','size'=>'xs','label'=>'全选','htmlOptions'=>array("checkall_value"=>0,'onclick'=>'userAllchecked(this)','style'=>'cursor:pointer;'),));
				$this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'button', 'type'=>'primary','size'=>'xs','label'=>'设置为活跃用户','htmlOptions'=>array("checkall_value"=>0,'onclick'=>'addHotUser(this)','style'=>'cursor:pointer;margin-left:5px;'),));       		
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
			    		array(
					        'type'=>'raw', 
					        'value'=>'CHtml::checkBox($data->id,0,array("rel"=>"orders[]",));',  
					        'htmlOptions'=>array('style'=>'width:2%;'),
					    ),
				        array(
				        	'name'=>'id',
				        	'htmlOptions'=>array('style'=>'width:5%;','class'=>'id'),
						),
				        array(
				           'name' => 'loginname',
		        		),
		        		array(
				           'name' => 'nickname',
		        		),
		        		array(
				           'name' => 'email',
		        		),
		        		array(
				           'header' => '粉丝',
				           'value'=> '$data->fensi',
				           'htmlOptions'=>array('style'=>'width:5%;'),
		        		),
		        		array(
				           'header' => '播放',
				           'value'=> '$data->history',
				           'htmlOptions'=>array('style'=>'width:5%;'),
		        		),
		        		array(
				           'header' => '收藏',
				           'value'=> '$data->collect',
				           'htmlOptions'=>array('style'=>'width:5%;'),
		        		),
		        		array(
				           'header' => '注册地址',
				           'value'=> '$data->address',
		        		),
		        		array(
				           'name' => 'regtime',
				           'value'=>'date("Y-m-d",$data->regtime)',
		        		),
		        		array(
				           'name' => 'mobile',
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
function addHotUser() {
	var obj=new Array();
    var v_id;
    var ids = [];
    var vid;
    $("input[rel='orders[]']").each(function(){
        if($(this).attr("checked")=='checked') {
            v_id = $(this).attr('name');	         
          //  $(this).parent().parent().remove();
            var ob= $(this).parent().parent();
            obj.push(ob);
            ids.push(v_id);
        }
    }); 

    if(ids==''){
    	alert('请至少选择一个数据')
        return false;
    }
	if(confirm('确定要添加为活跃用户？')){
	    $.ajax({
	        url:"<?php echo Yii::app()->createUrl('/plugin/user/addHot');?>",
	        type:'GET',
	        data:'id='+ids,
	        dataType:'json',
	        success:function(result){
	            if(result.error == 0){	                 
	      			//alert();
	      			noty({"text":result.content,"layout":"topCenter","type":"alert","animateOpen": {"opacity": "show"}});
	      			
	            } else {
	            	noty({"text":result.content,"layout":"topCenter","type":"alert","animateOpen": {"opacity": "show"}});
	            }
	        }
	     }); 
	   }
}	
</script>