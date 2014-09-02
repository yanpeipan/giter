<?php $this->beginContent('//layouts/main'); ?>
	<div class="content fix">
	    <div class="left_menu">
        <?php 
        $this->widget('zii.widgets.CMenu', array(
            'items'=>array(                                   
                 array(
					'label'=>'sotu排序管理',
					'url' => '/sotu/sotukeyword/index',
				    'active'=>'sotukeyword'==$this->id,
				),
                array(
                    'label'=>'web站点管理',
                    'url' => '/sotu/webmanage/index',
                    'active'=>'webmanage'==$this->id,
                ),
                array(
                    'label'=>'sotu链接管理',
                    'url' => '/sotu/sotulink/index',
                    'active'=>'sotulink'==$this->id,
                ),
                 array(
                    'label'=>'sotu片源管理',
                    'url' => '/sotu/sotuRss/index',
                    'active'=>'sotuRss'==$this->id,
                ),
                 array(
                    'label'=>'sotu专题管理',
                    'url' => '/sotu/channel/view',
                    'active'=>'channel'==$this->id,
                ),
                 array(
                    'label'=>'sotu关键字管理',
                    'url' => '/sotu/sotukw/index',
                    'active'=>'sotukw'==$this->id,
                ),
                /*
				array(
					'label'=>'可视化编辑',
                    'url'  =>array('/rec/index'),
                    'linkOptions'=>array('target'=>'_blank'),
                    'visible'=>!Yii::app()->user->isGuest,
				),*/
				
			

            ),
        
        ));
        ?>
        </div>
	    <div class="content_right">
            <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>