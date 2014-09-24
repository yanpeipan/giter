<?php

PluginManager::import('git.lib.*');
Yii::import('application.modules.admin.models.*');
/**
 * Git Controller
 * 
 * @author yanpeipan <yanpeipan_82@qq.com>
 * @version 2014.8.3
 */
class GitController extends PluginBaseController
{
	public $layout = '//layouts/column2';
	public $errors;

	public function filters()
	{
        		// return the  filter configuration for this controller, e.g.:
		return array(
			'accessControl',
			);
	}

	public function accessRules()
	{
		return array
		(
			array('deny', 'actions'=>array('*'), 'users'=>array('?'),),
			);
	}	
	/**
	 * Index
	 */
	public function actionIndex()
	{
		$params =  Yii::app() -> request -> getParam('Projects', array());
		$projects = new Projects();
		$contributedProject = new Projects();

		if(Yii::app()->user->is_super_admin < 3) {
			$projects->uid = Yii::app() ->user->id;
		}
		$contributedProject->mid = Yii::app()->user->id;


		if (!empty($params)) {
			$model -> attributes = $params;
			if (!$model -> validate()) {
				Yii::app() -> end();
			}
		}

		$this -> render('index',  array('projects' => $projects, 'contributedProject' => $contributedProject));
	}

	/**
	 *  Create Project
	 */
	public function actionCreateProject()
	{
		if(Yii::app()->user->is_super_admin < 2)
		{
			Yii::app()->user->setFlash('danger', 'You do not have permission to perform this operation.');
			Yii::app()->getController()->actionIndex();
			Yii::app()->end();
		}
		$model = new Projects;

		// Ajax Validate
		if(isset($_POST['ajax']) && $_POST['ajax']==='create_project_form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} 

		//Form Submit
		$params = Yii::app() -> request -> getParam('Projects', array());

		if (!empty($params)) {
			$model -> attributes = $params;
			if ($model -> save() && $model -> create()) {

				$model -> uid = Yii::app()->user->getId();
				$model  -> save();
				
				$this -> redirect('index');
				Yii::app() -> end();
			} else {
				
			}
		}

		//render view
		$this -> render('project/create', array('project' => $model));
	}

	/**
	 *  Publish
	 */
	public function actionPublish()
	{
		$id = Yii::app() -> request -> getParam('id', null);
		if (is_numeric($id)) {
			$model = Projects::model()->findByPk($id);
			if($model){
				$model ->publish($model->name);
			}
		}
		$this->redirect('/plugin/git/index');
	}

	/**
	 * Delete Project
	 */
	public function actionDeleteProject()
	{
		if(Yii::app()->user->is_super_admin < 2)
		{
			$this->redirect('/plugin/git/');
		}
		$id = Yii::app() -> request -> getparam('id', null);
		if (is_numeric($id)) {
			$project = Projects::model()->findByPk($id);
			if ($project && (Yii::app()->user->is_super_admin == 3) || (isset($project->uid) && $project->uid == Yii::app()->user->id)) {
				$project->deleteByPk($id);
				$project->destory();
			}
		}
		$this->redirect('/plugin/git/index');
	}

	/**
	 * Update Project
	 */
	public function actionUpdateProject()
	{
		$this->redirect('/plugin/git/index');
	}

	/**
	 * Members
	 */
	public function actionMembers()
	{
		$id = Yii::app() -> request -> getParam('id', null);
		if (is_numeric($id)) {
			$project = new Projects();
			$members = new ProjectsMembers();
			$members -> pid = $id;
			$this -> render('manage/members',  array('project' => $project, 'members' => $members));
		} else {
		}
	}
	
	/**
	 * Members
	 */
	public function actionConfig()
	{
		$id = Yii::app() -> request -> getParam('id', null);

		// Ajax Validate
		if(isset($_POST['ajax']) && $_POST['ajax']==='config_project_form')
		{
			$model = new Projects;
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} 

		if (isset($_POST['Projects'], $_POST['Projects']['id'])) {
			$params = Yii::app()->request->getParam('Projects');
			$model = Projects::model()->findByPk($params['id']);
			$model -> attributes = $params;
			if($model && $model->save()){
				$model -> modify();
				$this->redirect('/plugin/git/');
				Yii::app()->end();
			}else{
				$this -> render('project/config',  array('project' => $model));
			}

			}
		if (is_numeric($id)) {
			$project = Projects::model()->findByPk($id);
			$this -> render('project/config',  array('project' => $project));
		}
	}

	public function actionAddMember()
	{
		$params = Yii::app() -> request -> getParam('ProjectsMembers', array());
		$members = new ProjectsMembers();
		$members -> pid = Yii::app() -> request -> getParam('id');

		if (isset($_POST['ajax']) && $_POST['ajax'] == 'addMember') {
			echo CActiveForm::validate($members);
			Yii::app()->end();
		}

		if (!empty($params)) {
			$members -> attributes = $params;
			if ($members -> save()) {
				$project = Projects::model()->findByPk($members->pid);
				$user = Admin::model()->findByPk($members->uid);
				$project -> addMember($user->username, Admin::decrypt($user->encrypt), $project->id);
				$this->redirect(Yii::app() -> createUrl('/plugin/git/members/', array('id' => $members->pid)));
			} 
		} 
		$usernames = CHtml::listData(Admin::model()->findAll(), 'id', 'username');
		$this ->render('manage/addMember', array('members' => $members, 'usernames' => $usernames));
	}

	/**
	 *  Delete Member
	 */
	public function actionDeleteMember()
	{
		$id = Yii::app() -> request -> getParam('id');
		$members  = new ProjectsMembers();
		if ($id) {
			$member = $members -> findByPk($id);
			if ($member) {
				$project = Projects::model()->findByPk($member->pid);
				$admin = Admin::model()->findByPk($member->uid);
				if ($project && $project->uid == Yii::app()->user->id) {
					$member->delete();
					if ($admin->username != Yii::app()->user->username) {
						$project -> deleteMember($admin->username, $project->id);
					}
				}
			}
		}
		$this->redirect('/plugin/git/index');
	}

	/**
	 * Manage
	 */
	public function actionManage()
	{
		if(Yii::app()->user->is_super_admin < 3)
		{
			$this->redirect('/plugin/git/');
		}
		$repositories = new Repositories();
		$virtualServers = new VirtualServers();
		$repository = $repositories->findByAttributes(array(), 'name<>""', array('order'=>'id asc', 'limit'=>1 ));
		$virtualserver = $virtualServers->findByAttributes(array(), 'name<>""', array('order'=>'id asc', 'limit'=>1 ));
		if (!is_null($repository) && !is_null($virtualserver)) {
			$this ->render('manage/index', array('repository' => $repository, 'virtualserver' => $virtualserver));
		}
	}

	public function actionEditVirtualServer()
	{
		if(Yii::app()->user->is_super_admin < 3)
		{
			$this->redirect('/plugin/git/');
		}
		$virtualServer = new VirtualServers();

		if(isset($_POST['ajax']) && $_POST['ajax']==='VirtualServers')
		{
			echo CActiveForm::validate($virtualServer);
			Yii::app()->end();
		}	

		if(isset($_POST['VirtualServers']))
		{
			if(isset($_POST['VirtualServers']['id']))
			{
				$virtualServer = VirtualServers::model()->findByPk($_POST['VirtualServers']['id']);
			}

			$virtualServer->attributes=$_POST['VirtualServers'];
			if($virtualServer->save()) {
				$this->redirect(Yii::app()->createUrl('plugin/git/manage'));
			} else {
				$this->redirect(Yii::app()->createUrl('plugin/git/manage'));
			}
		}
	}

	public function actionEditRepostory()
	{
		if(Yii::app()->user->is_super_admin < 3)
		{
			$this->redirect('/plugin/git/');
		}
		$repositories = new Repositories();

		if(isset($_POST['ajax']) && $_POST['ajax']==='Repositories')
		{
			echo CActiveForm::validate($repositories);
			Yii::app()->end();
		}	

		if(isset($_POST['Repositories']))
		{
			if(isset($_POST['Repositories']['id']))
			{
				$repositories = Repositories::model()->findByPk($_POST['Repositories']['id']);
			}

			$repositories->attributes=$_POST['Repositories'];
			if($repositories->save()) {
				$this->redirect(Yii::app()->createUrl('plugin/git/manage'));
			} 
		}	
	}
	
	public function actionOnline()
	{
		$id = Yii::app()->request->getParam('id');
		if(is_numeric($id)) {
			$project = Projects::model()->findByPk($id);
			if ($project && ($project->uid == Yii::app()->user->id || Yii::app()->user->is_super_admin == 3)) {
				$project->online();
			}
		}
		$this->redirect('/plugin/git/');
	}

	public function actionTest()
	{
		$project = new Shell();

		$project -> test();
		return;
		$new =new  Admin();
		$new->username = 'test';
		$new->password =  '123456';
		$new ->encrypt = Admin::encrypt(123456);
	}
}
?>
