<?php
class WebUser extends CWebUser  
{
	public function getEncrypt()
	{
		$model = Admin::model() -> findByPk(Yii::app()->user->id);
		if (is_null($model)) {
			throw new Exception("Cannot found user", 1);
		}
		return $model ? $model ->  encrypt : null;
	}

	public function getUsername()
	{
		$model = Admin::model() -> findByPk(Yii::app()->user->id);
		return $model ? $model ->  username : null;
	}

	public function getIs_super_admin()
	{
		$model = Admin::model() -> findByPk(Yii::app()->user->id);
		return $model ? $model->is_super_admin  : null;
	}

	public function getGithub_name()
	{
		$model = Admin::model() -> findByPk(Yii::app()->user->id);
		return $model ? $model->github_name : null;
	}
}
