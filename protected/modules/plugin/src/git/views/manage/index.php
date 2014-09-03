<?php //$this->renderPartial('manage/gitServer', array('virtualserver' => $gitServer)); ?>
<?php $this->renderPartial('manage/virtualServer', array('virtualserver' => $virtualserver)); ?>
<?php $this->renderPartial('manage/repository', array('repository' => $repository)); ?>