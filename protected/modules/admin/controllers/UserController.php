<?php
class UserController extends AdminBaseController
{
    public $layout = "//layouts/column2";
    public $defaultAction = 'login';
    
    //public $is_admin;
    
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl',
            );
    }

    public function accessRules()
    {
        return array(
            array(
                'deny',
                'actions'=>array(
                    'view', 
                    'update',
                    'ajax_username',
                    'ajax_pwd',
                    'Load_user_model',
                    'delete',
                    'authadmin',
                    'add',
                    'authadmin',
                    'ajax_username',
                    'ajax_inipwd',
                    'ajax_pwd',
                    ),
                'users'=>array('?'),
                ),
            );
    }
    
   /* public function init(){
        parent::init();
        $is_admin_level = $this->Load_user_model(Yii::app()->user->id);
        $this->is_admin = $is_admin_level->is_super_admin;
    }*/
    /**
     * 管理员登录
     */
    public function actionLogin()
    {
        $this->layout = "//layouts/main"; 
        $model=new Admin;
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if(isset($_POST['Admin']))
        {
            $model->attributes=$_POST['Admin'];
            if($model->validate() && $model->login()){
            	$record = $this->record_login_time();
                $auth = $this->_authcode($model->username.'&*'.Yii::app()->user->id,'ENCODE','tuziadmin');
                $cookie = new CHttpCookie(sha1('vo_auth'), $auth);
                $cookie->path = '/';
                $cookie->domain = trim(Yii::app()->params['domain'],'http://');		//获取域名
                $cookie->expire =time()+60*30*60;
                Yii::app()->request->cookies[sha1('vo_auth')]=$cookie;
                
                $cookie = Yii::app()->request->getCookies();
                if(isset(Yii::app()->authManager)){
                	$auth = Yii::app()->authManager;
                	if(Yii::app()->user->checkAccess('plugin@GitIndex')){
                		$this->redirect(Yii::app()->createUrl('plugin/git/index'));
                	}elseif(Yii::app()->user->checkAccess('srbac@AuthitemFrontpage')){
                		$this->redirect(Yii::app()->createUrl('srbac/authitem/frontpage'));
                	}
                }
                
                Yii::app()->end();
            }
        }
        $this->render('login',array('model'=>$model));
    }

    /**
     * 登出
     */
    public function actionLogout()
    {
    	Yii::app()->user->identityCookie = 1;
      $domain = trim(Yii::app()->params['domain'],'http://');
      setcookie(sha1('vo_auth'),'',time()-10000000,'/',$domain);
      echo "<script>delcoo();</script>";
      $cookie = Yii::app()->request->getCookies();
      unset($cookie[sha1('vo_auth')]);
      Yii::app()->user->logout();
      Yii::app()->homeUrl = '/admin/user';
      $this->redirect(Yii::app()->homeUrl);
  }
    /**
	* admin list view
	*/
    public function actionView(){
    	$this->layout = "//layouts/column2";
        $user_model = $this->Load_user_model(Yii::app()->user->id);
        if($user_model->is_super_admin==3){
            $model = new Admin;
            $this->render('view', array('model'=>$model,'is_super_admin'=>1));
        }else{
            $this->render('view', array('model'=>$user_model,'is_super_admin'=>0));
        }
        
    }
    
     /**
     * 更新播放源信息
     * @param id
     */
     public function actionUpdate($id)
     {
       $this->layout = "//layouts/column2";
       $model = new UserUpdate;
       $model->username = Admin::model()->findbyPk($_GET['id'])->username;
       if(isset($_REQUEST['UserUpdate']))
       {
           $model->attributes=$_POST['UserUpdate'];
           if($model->validate())
           {
               $new = Admin::model()->findbyPk($_GET['id']);
               $new->username = $model->username;
               $new->password = $model->password;
               $new ->encrypt = Admin::encrypt($model->password);
               if($new->save()){
               	if(class_exists('Projects')){
              	$project = new Projects();
              	$sql = 'select username from {{admin}} where id=:id';
              	$username = Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>Yii::app()->user->id))->queryScalar();
              	$project->htpasswd($username, $model->password);
              }
                $this->redirect(Yii::app()->createUrl("/admin/user/view"));
                die;
            }
        }
    }

    $this->render('update', array(
        'model'=>$model,
        ));
} 

public function actiondelete($id){
    $model = $this->Load_user_model($id);
    if($model->delete()){
    	if(class_exists('Projects')){
    		$project = new Projects();
    		$project -> deleteMember($model->username, '');
    	}
    }
    $this->redirect(array('/admin/user/view'));
}

public function actionadd(){
   $this->layout = "//layouts/column2";
   $model    = new Admin();
        //保存视频信息
   if(isset($_POST['Admin']))
        {  
            $model->attributes = $_POST['Admin'];
            $model->password = substr(md5($_POST['Admin']['password']),8,16);
            $model ->encrypt = Admin::encrypt($_POST['Admin']['password']);
            if($model->validate())
            {
                if($model->save())
                {
                    if(isset(Yii::app()->authManager)){
                    	$auth=Yii::app()->authManager;
                    	switch($model->is_super_admin){
                    		case 3:
                    			$role = 'admin';
                    		break;
                    		case 2:
                    			$role = 'PM';
                    		break;
                    		default:
                    			$role = 'Developer';
                    	}
                    	$auth->assign($role, $model->id);
                    }
                    $this->redirect(Yii::app()->createUrl("/admin/user/view"));
                    Yii::app()->end();
                }
            }
        }
        $this->render('add_admin',array('model'=>$model));
    }
    
    public function actionauthadmin($id){
        $model = $this->Load_user_model($id);
        if(isset($_POST['Admin']))
        {
            $is_super_admin = (int)$_POST['is_super_admin'];
            $sql = "UPDATE {{admin}} SET is_super_admin=:is_super_admin WHERE id=:id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValues(array(':is_super_admin'=>$is_super_admin,':id'=>$id,));
            $result = $command->execute();
            if($result==1)
            {
             $this->redirect(Yii::app()->createUrl("/admin/user/view"));
             die;
         }
     }  
     $this->render('authadmin', array(
        'model'=>$model,
        ));
 }


 public function actionajax_username(){
    $name = isset($_GET['name'])?$_GET['name']:'';
    $id = isset($_GET['id'])?$_GET['id']:0;
    $sql = "SELECT COUNT(*) as cnt FROM pt_admin where id!=:id AND username=:name";
    $cmd = Yii::app()->db->createCommand($sql);
    $cmd->bindValue(":id",$id);
    $cmd->bindValue(":name",$name); 
    $rows = $cmd->queryRow();
    if($rows['cnt']!=0){
        echo $result = 1;
    }
}

public function actionajax_pwd(){
    $id = isset($_GET['id'])?$_GET['id']:0;
    $pwd = isset($_GET['pwd'])?$_GET['pwd']:'';
        $pwd1 = md5($pwd);//bfe75b03ce5d7237f09b48d8d763a746
        $str = substr($pwd1,8,16);
        $model = $this->Load_user_model($id); 
        if($model->password!=$str){
            echo $result = 0;
        }else{
            echo $result = 1;
        }
    }
    
    public function actionajax_inipwd(){
     $id = isset($_GET['id'])?$_GET['id']:0;
     $pwd = '123456';
     $sql = "UPDATE {{admin}} SET password=:password,encrypt=:encrypt WHERE id=:id";
     $command = Yii::app()->db->createCommand($sql);
     $command->bindValues(array(':password'=>substr(md5($pwd),8,16),':id'=>$id, ':encrypt'=>Admin::encrypt($pwd)));
     $result = $command->execute();
     if(class_exists('Projects')){
              	$project = new Projects();
              	$sql = 'select username from {{admin}} where id=:id';
              	$username = Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>Yii::app()->user->id))->queryScalar();
              	$project->htpasswd($username, $pwd);
        }
     echo $result;
 }

        /**
     * 加载模型
     * @param id
     */
        public function Load_user_model($id)
        {
            $model = Admin::model()->findByPk((int)$id);
            if($model==NULL)
            {
                throw new CHttpException(404, '页面不存在');
            }
            return $model;
        }

        public function record_login_time(){
         $sql = "INSERT INTO {{admin_time}} SET user_id=:user_id,user_name=:user_name";
         $command = Yii::app()->db->createCommand($sql);
         $command->bindValues(array(':user_id'=>Yii::app()->user->id,':user_name'=>Yii::app()->user->name));
         return $resu = $command->execute();
     }
   /**
	 * Change password
	 */
   public function actionChangepassword() {
      $model = new UserChangePassword;
      if (Yii::app()->user->id) {

			// ajax validator
         if(isset($_POST['ajax']) && $_POST['ajax']==='changepassword-form')
         {
            echo UActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['UserChangePassword'])) {
           $model->attributes=$_POST['UserChangePassword'];
					//var_dump($model->validate());die;
           if($model->validate()) {
              $new_password = AdminModule::encrypting($model->password);
              $sql  = "UPDATE {{admin}} SET password=:password, encrypt=:encrypt WHERE id=:id";
              $bool = Yii::app()->db->createCommand($sql)->bindValues(array(':password'=>$new_password,':encrypt'=>Admin::encrypt($model->password), ':id'=>Yii::app()->user->id))->execute();
              if(class_exists('Projects')){
              	$project = new Projects();
              	$sql = 'select username from {{admin}} where id=:id';
              	$username = Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>Yii::app()->user->id))->queryScalar();
              	$project->htpasswd($username, $model->password);
              }
              Yii::app()->user->setFlash('profileMessage',AdminModule::t("New password is saved."));
              if(isset(Yii::app()->authManager)){
                	$auth = Yii::app()->authManager;
                	if(Yii::app()->user->checkAccess('plugin@GitIndex')){
                		$this->redirect(Yii::app()->createUrl('plugin/git/index'));
                	}elseif(Yii::app()->user->checkAccess('srbac@AuthitemFrontpage')){
                		$this->redirect(Yii::app()->createUrl('srbac/authitem/frontpage'));
                	}
                }
          }
      }
      $this->render('changepassword',array('model'=>$model));
  }
}
            /**
             * Http basic Authenticate
             */
            public function actionHttpBasicAuthenticate()
            {
                if(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                    $model=new Admin;
                    $model->attributes = array(
                        'password' => $_SERVER['PHP_AUTH_PW'],
                        'username' => $_SERVER['PHP_AUTH_USER'],
                    );
                    if($model->validate() && $model->login()) {
                         Yii::app()->end();
                    }
                }
                header('WWW-Authenticate: Basic realm="Restricted"');
                header('HTTP/1.0 401 Unauthorized');
                Yii::app()->end();
            }
}
