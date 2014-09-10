<?php
if(Yii::app()->user->checkAccess('plugin@GitCreateProject')) {
	$this->renderPartial('project/created', array('projects' => $projects));
}
?>
<?php
$this->renderPartial('project/contribute', array('projects' => $contributedProject));
?>
