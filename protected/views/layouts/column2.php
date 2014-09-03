<?php $this->beginContent('//layouts/main'); ?>
<div style="margin-top:20px;margin-left:0px;width: 100%;min-width: 1200px" class="container">
	<div class="row" style="margin-left: 0px; padding: 0 50px;min-width: 1200px">
	<div  class="span2" style="margin-left: 0px;min-width: 1200px">
		<?php 
		if('video'==$this->id||'source'==$this->id||'channel'==$this->id||'report'==$this->id||'categorycover'==$this->id||'chtype'==$this->id||'actor'==$this->id){
			
			
			//echo Yii::app()->controller->id;die;
			$this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'tabs',
		    //'stacked'=>true,
		    'htmlOptions'=>array('class'=>''),
		    'items'=>array(
		        array(
			        'label'=>'视频列表',
			        //'icon'=>'chevron-right',		         
			        'url'=>array('/admin/video/view'),
			        'active'=>'video'==$this->id&&($this->action->id=='view'||$this->action->id=='update'||$this->action->id=='videoinfo'||$this->action->id=='addtotopic'||$this->action->id=='addtv'||$this->action->id=='mergevideo')
				 ),
			 	array(
				 	'label'=>'专题管理',
				 	//'icon'=>'chevron-right',
				 	'url'  =>array('/admin/video/topic'),
				 	'active'=>'video'==$this->id&&($this->action->id=='topic'||$this->action->id=='topicadd'||$this->action->id=='topicvideo'||$this->action->id=='topicupdate')
				),
			    array(
				    'label'=>'分类管理',
				    //'icon'=>'chevron-right',
				    'url'  =>array('/admin/video/catview'),
				    'active'=>'video'==$this->id&&($this->action->id=='catview'||$this->action->id=='catadd'||$this->action->id=='catupdate')
				),
                array(
                    'label'=>'地区管理',
                    //'icon'=>'chevron-right',
                    'url'  =>array('/admin/video/areaview'),
                    'active'=>'video'==$this->id&&($this->action->id=='areaview'||$this->action->id=='areaadd'||$this->action->id=='areaupdate')
                ),
                
                array(
                    'label'=>'标签管理',
                    //'icon'=>'chevron-right',
                    'url'  =>array('/admin/video/typeview'),
                    'active'=>'video'==$this->id&&($this->action->id=='typeview'||$this->action->id=='typeadd'||$this->action->id=='typeupdate')
                ),
                  array(
                    'label'=>'死链报告',
                    //'icon'=>'chevron-right',
                    'url'  =>array('/admin/report/index'),
                    'active'=>'report'==$this->id&&$this->action->id=='index',
                ),
                 array(
                    'label'=>'接入源管理',
                    //'icon'=>'chevron-right',
                    'url'  =>array('/admin/source/index_config'),
                    'active'=>'source'==$this->id && ($this->action->id=='index_config'||$this->action->id=='cfg_add'||$this->action->id=='cfg_update'),
                    'visible'=>!Yii::app()->user->isGuest&&Yii::app()->user->checkAccess('Admin@VideoAjax_look')!=false,
                ),
				array(
                    'label'=>'二级推荐管理',
                    'url' =>array('/admin/channel/view'),
                    'active'=>('channel'==$this->id&&($this->action->id=='view'||$this->action->id=='update'||$this->action->id=='add'||$this->action->id=='channelvideo'||$this->action->id=='addvideo'||$this->action->id=='addtochannel'))||('chtype'==$this->id&&($this->action->id=='index'||'add'))
                ),
                 array(
					'label'	=> '分类封面管理',
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/categorycover/index'),
					'active'=> 'categorycover'==$this->id,
				),				
				array(
					'label' => '演员管理',
					'url'	=> array('/admin/actor/index'),
					'active'=> 'actor'==$this->id,
				),

			),
		));
		}elseif('weather'==$this->id){
			$this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'tabs',
		    //'stacked'=>true,
		    'htmlOptions'=>array('class'=>''),
		    'items'=>array(			
				
				array(
					'label'	=> '天气封面管理',
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/weather/index'),
					'active'=> 'weather'==$this->id&&($this->action->id=='index'||$this->action->id=='update'),
				),
				array(
					'label'	=> 'shell脚本监控',
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/shell/index'),
					'active'=> 'shell'==$this->id&&($this->action->id=='index'||$this->action->id=='update'),
				),

			),
			));
		} elseif ("user"==$this->id){
			$this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'tabs',
		    //'stacked'=>true,
		    'htmlOptions'=>array('class'=>''),
		    'items'=>array(			
				array(
					'label'	=> AdminModule::t('管理员列表'),
					'url'	=> array('/admin/user/view'),
					'active'=> 'user'==$this->id&&($this->action->id=='view'||$this->action->id=='update'),
				),
			),
			));
		} elseif ("live" == $this->id)
		{
			$this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'tabs',
		    //'stacked'=>true,
		    'htmlOptions'=>array('class'=>''),
		    'items'=>array(			
				array(
					'label'	=> AdminModule::t('直播列表'),
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/live/index'),
					//'tudan'==$this->id,
					'active'=> 'live'==$this->id&&($this->action->id=='index'||$this->action->id=='update'),
				),
				array(
					'label'	=> AdminModule::t('分类管理'),
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/live/category'),
					'active'=> 'live'==$this->id&&($this->action->id=='category'||$this->action->id==''),
				),

			),
			));
		}elseif("stata" == $this->id){

		}elseif("system"== $this->id || 'background'==$this->id){
			$this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'tabs',
		    //'stacked'=>true,
		    'htmlOptions'=>array('class'=>''),
		    'items'=>array(			
				array(
					'label'	=> AdminModule::t('基本设置'),
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/system/view'),
					//'tudan'==$this->id,
					'active'=> 'system'==$this->id&&($this->action->id=='view'||$this->action->id==' '),
				),
				array(
					'label'	=> AdminModule::t('修改设置'),
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/system/update'),
					'active'=> 'system'==$this->id&&($this->action->id=='update'||$this->action->id==''),
				),
				array(
					'label'	=> '终端封面背景',
					//'icon'=>'chevron-right',
					'url'	=> array('/admin/background/index'),
					//'tudan'==$this->id,
					'active'=> 'background'==$this->id&&($this->action->id=='index'||$this->action->id=='update'),
				),

			),
			));			
		}elseif("plugin" == $this->id){
			$this->widget('bootstrap.widgets.TbMenu', array(
			    'type'=>'tabs',
			    //'stacked'=>true,
			    'htmlOptions'=>array('class'=>''),
			    'items'=>array(			
					array(
						'label'	=> AdminModule::t('插件列表'),
						//'icon'=>'chevron-right',
						'url'	=> array('/admin/plugin/view'),
						//'tudan'==$this->id,
						'active'=> 'plugin'==$this->id&&($this->action->id=='view'||$this->action->id==' '),
					),
					array(
						'label'	=> AdminModule::t('下载插件'),
						//'icon'=>'chevron-right',
						'url'	=> array('/admin/plugin/download'),
						'active'=> 'plugin'==$this->id&&($this->action->id=='download'||$this->action->id==''),
					),
	
				),
			));	
			
		}
		 ?>
		<!-- sidebar -->
	</div>
	<div class="" style="width:100%;float:left;position:relative;clear:both;margin-left:0px;margin-top:10px;">
			<?php echo $content; ?>
		<!-- content -->
	</div>
	</div>
</div>
<?php $this->endContent(); ?>
