<style>
.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td{font-size:12px;line-height:20px;padding:3px;overflow:hidden;height:20px;}
.summary{text-align:right;}
td{padding:0;}
.pagination{margin:5px 0;}
</style>
<ol class="breadcrumb">
  	<li class="active" >本地插件</li>
</ol>

<?php 
$items = array();
if($tab)foreach($tabs as $url=>$label){
	$items[]=array(
		'label' => $label,
		'url'   => $this->createUrl($url),
		'active' => strpos($url, $tab)>0 ? true : false
	);
}
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>$items
)); ?>
<div class="box" style="border-top: none;"><div class="box-content">
<?php
$data = array(); 
if(is_array($result) && isset($result['data'])){
	foreach($result['data'] as $v){
		if(is_array($v)){
			$author = isset($v['config']['author']) ? "开发者:".$v['config']['author'] :'';
			$email = isset($v['config']['email']) ? "联系方式:".$v['config']['email'] :'';
			$dependencies = isset($v['config']['dependencies']) ? $v['config']['dependencies'] :'';
			$v['config']['description'] = mb_substr($v['config']['description'], 0,"255");
			$v['config']['description'] .= "<br/>".$author.", ".$email;
			if(!empty($dependencies)){
				$v['config']['description'] .= "<br/>"."依赖插件:".$dependencies;
			}
			if(!empty($v['config']['needed'])){
				$v['config']['description'] .= "<br/>"."<font color='#f00' id='needed'>缺失依赖插件:</font>".$v['config']['needed'];
			}
			//增加操作类型
			$btn_setup = $this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'安装',
			    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
			    'htmlOptions'=>array('style'=>'margin-right:10px;','onclick'=>'plugin_action(this,"setup")'),
			    'size'=>'xs', 
			),true);
			$btn_unsetup = $this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'卸载',
			    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
			    'htmlOptions'=>array('style'=>'margin-right:10px;','onclick'=>'plugin_action(this,"unsetup")'),
			    'size'=>'xs', 
			),true);
			$btn_delete = $this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'删除',
			    'type'=>'inverse', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
			    'htmlOptions'=>array('style'=>'','onclick'=>'plugin_action(this,"delete")'),
			    'size'=>'xs', 
			),true);
			$btn_update = $this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'更新',
			    'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
			    'htmlOptions'=>array('style'=>'margin-right:10px;','onclick'=>'plugin_action(this,"update")'),
			    'size'=>'xs', 
			),true);
			$v['config']['_action_'] = '';
			if($v['setup']){
				$v['config']['_action_'] = $btn_unsetup;
				if($v['update']){
					$v['config']['_action_'] .= $btn_update;
				}
			}else{
				$v['config']['_action_'] = $btn_setup.$btn_delete;
			}
			$data[]=$v['config'];
		}
			
	}
	//列表的button
	
	
	
	$buttons = array('class'=>'bootstrap.widgets.TbButtonColumn','template'=>'{setup}{delete}','htmlOptions'=>array('style'=>'width:15%;'),
        'header'=>'操作',
        'buttons'=>array()
    );
	$buttons = array(
                 'setup'=>array(
                    'label'=>'安装',
                    'icon' => 'primary',
                    'options'=>array('style'=>'padding-right:10px;','onclick'=>'plugin_setup(this)'),
                    'url'  =>'',
                                   
                ),
                 'delete'=>array(
                    'label'=>'删除',
                    'icon' => 'inverse',
                    'options'=>array('style'=>'padding-right:10px;','onclick'=>'plugin_delete(this)'),
                    'url'  =>'',
                                   
                ),
            );
}

$gridDataProvider = new TArrayDataProvider($data);
$gridDataProvider->setTotalItemCount(isset($result['total']) ? $result['total'] :0);
$gridDataProvider->getPagination()->pageSize = isset($result['pageSize']) ? $result['pageSize'] :0;
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'pager' => array(
    	'class'=>'bootstrap.widgets.TbPager',
    	'displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
    'columns'=>array(
        array('name'=>'id', 'header'=>'id','htmlOptions'=>array('style'=>'width:15%','class'=>'pluginid')),
        array('name'=>'name', 'header'=>'名称','htmlOptions'=>array('style'=>'width:15%')),
        array('name'=>'type', 'header'=>'类型','htmlOptions'=>array('style'=>'width:5%')),
        array('name'=>'version', 'header'=>'版本','htmlOptions'=>array('style'=>'width:5%')),
        array('name'=>'description', 'header'=>'描述','type'=>'raw','htmlOptions'=>array('style'=>'width:45%')),
        array('name'=>'_action_','header'=>'操作','type'=>'raw','htmlOptions'=>array('style'=>'width:15%')),

    ),
)); ?>
</div></div>
<script>
$(document).ready(function(){
});
//setup a plugin
function plugin_action(o,action)
{
	if('delete'==action){
		var f = confirm("确定要删除吗？");
		if(!f)return false;
	}
	if('unsetup'==action){
		var f = confirm("确定要卸载吗？");
		if(!f)return false;
	}
	var tr = $(o).parent().parent();
	var id = tr.find("td.pluginid").text();
	$.post('/admin/pluginManager/ajax',{pluginid:id,action:action},function(json){
		if(1==json.status){
			noty({text: json.msg,type:'success'});
			setTimeout(function(){location.reload();},1000);
		}else{
			noty({text: json.msg,type:'error'});
		}
	},'json');
}
</script>