<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php 
	  //Yii::app()->bootstrap->register();
      Yii::app()->clientScript->registerCssFile('/css/main.css');

	  Yii::app()->clientScript->registerCoreScript('jquery');
      Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-selectbox.js'); 

    ?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body data-spy="scroll"  data-target=".bs-docs-sidebar">
<?php 
	$this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
    'brand'=>Yii::app()->name,
    'brandUrl'=>'#',
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
            	array('label'=>'基本设置', 'url'=>array('/admin/system/view'),'visible'=>!Yii::app()->user->isGuest,'active'=>'background'==$this->id||'system'==$this->id),
            	array('label'=>'点播管理', 'url'=>array('/admin/video/view/app/video'), 'visible'=>!Yii::app()->user->isGuest,'active'=>'video'==$this->id||'source'==$this->id||'channel'==$this->id||'report'==$this->id||'categorycover'==$this->id||'actor'==$this->id),
        		array('label'=>'直播管理', 'url'=>array('/admin/live/index/'), 'visible'=>!Yii::app()->user->isGuest,'active'=>'live'==$this->id),
        		array('label'=>'运营统计', 'url'=>array('/admin/statas/index/'), 'visible'=>!Yii::app()->user->isGuest,'active'=>'statas'==$this->id),             		
        		array('label'=>'用户管理', 'url'=>array('/admin/user/view'), 'visible'=>!Yii::app()->user->isGuest),
			    array('label'=>'权限管理', 'url'=>array(''), 'visible'=>!Yii::app()->user->isGuest&&Yii::app()->user->checkAccess('admin'),),    
		        array('label'=>'扩展', 'url'=>array('/admin/background/index/app/extend'), 'visible'=>!Yii::app()->user->isGuest,'active'=>'weather'==$this->id),
		  		array('label'=>'插件', 'url'=>array('/admin/plugin/view'),'visible'=>!Yii::app()->user->isGuest,'active'=>'plugin'==$this->id),
		    ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>Yii::app()->user->name, 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest,'items'=>array(
                    array('label'=>'退出', 'url'=>array('/admin/user/logout')),
                    array('label'=>'修改密码', 'url'=>array('/admin/user/changepassword')),
                )),
                //array('label'=>'登录','url'=>Yii::app()->getModule('user')->loginUrl,'visible'=>Yii::app()->user->isGuest),
            ),
        ),
    ),
)); ?>
<hr>
    <?php echo $content;?>
    
    
</body>
</html>




















