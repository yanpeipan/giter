<div class="box">
	<div class="box-header">
		<h2><i class="fa fa-edit"></i>成员</h2>
		<div class="box-icon">
			<a href="<?php echo Yii::app()->createUrl("plugin/git/addMember", array('id' => $members->pid));?>" class="btn-adding"><i class="fa fa-plus"></i></a>
			<a href="<?php echo Yii::app()->createUrl("plugin/git/");?>" class="btn-adding"><i class="fa fa-chevron-left"></i></a>
			<a href="form-dropzone.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
		</div>
	</div>
	<div class="box-content">
		<?php
		$this->widget('bootstrap.widgets.TbExtendedGridView', array(
			'type'=>'striped bordered condensed',
			'dataProvider'=>$members->search(),	
			//'pager' => array('class'=>'bootstrap.widgets.TbPager','displayFirstAndLast'=>true,'htmlOptions'=>array('class'=>'pagination')),
			'columns'=>array(
				array(
					'name' => 'id',
					'header' => 'ID'
					),
				array(
					'name' => 'username',
					'header' => 'User Name'
					),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'template'=>'{delete}',
					'header'=>'操作',
					'buttons'=>array(
						'delete'=>array(
							'label'=>'删除',
							'icon' => 'danger',
							'url' =>'Yii::app()->createUrl("plugin/git/deleteMember/id/$data->id/uid/$data->uid/pid/$data->pid")',
							),
						),
					)
				)
			)
		);
		?>
	</div>
</div>
