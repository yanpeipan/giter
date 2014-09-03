<?php

class SiteController extends Controller
{
    public $layout = "//layouts/column1";
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
   public function actionIndex()
	{
        $this->render('index1');
		//$this->redirect(Yii::app()->createUrl('/admin/user/login'));	
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
	}
	
	public function actionD()
	{
		$this->layout = false;
        $this->render('index');
		//$this->redirect(Yii::app()->createUrl('/admin/user/login'));	
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
	}
	
	public function actionPhone(){
		$this->layout = false;
        $this->render('phone');
	}
	
	public function actionDetail(){
		$this->layout = false;
		$brand = array(
			1=>'清华同方',
			2=>'天敏',
			3=>'迈乐',
			4=>'小米',
			5=>'我播',
			6=>'海美迪',
			7=>'开博尔',
			8=>'美如画',
			9=>'第五元素',
			10=>'长虹',
			11=>'海信'
		);
		$vnum = $this->getRom();
        $this->render('detail',array('brand'=>$brand,'vnum'=>$vnum));
	}
    

    public function getRom(){
    	$pro_name = isset($_REQUEST['p'])?$_REQUEST['p']:'Tuzi';
        $sql  = "SELECT id,filename,vnum FROM {{rom}} WHERE pro_name=:pro_name ORDER BY id DESC limit 1";
        $cmd  = Yii::app()->db_rom->createCommand($sql);
        $cmd->bindValue(':pro_name',$pro_name);
        $row = $cmd->queryRow();
        return $row['vnum'];
    }
    
    public function actionAbout()
    {
        $this->render('about');
    }
    
    public function actionHelp()
    {
        $this->render('help');
    }
    public function actionShare(){
        $this->layout='/site';
        $this->render('share');
    }
	/**
	 * error
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

    /**
     * 注册
     */
    public function actionRegister()
    {
        $json_array = array(
            'status'      =>0,
            'error_email' =>0,
            'error_pwd'   =>0,
        );
        
        $salt  = self::productSalt(6);
        
        $model = new User('register');
        if($_POST)
        {
            $email = isset($_POST['User']['email'])?$_POST['User']['email']:'';
            $pwd   = isset($_POST['User']['pwd'])?$_POST['User']['pwd']:'';
            
            $model->email    = $email;
            $model->password = $pwd;
            $model->salt     = $salt;
            $model->time     = time();

            if($model->validate())
            {
                //密码加密
                $password = md5(md5($pwd).$salt);
                
                //给用户分配uuid
                $uuid = $this->getUserId4Username();

                //保存到uc_member表(16tree)
                $values_members = array(
                    ':username'=>$uuid,
                    ':email'   =>$email,
                    ':password'=>$password,
                    ':salt'    =>$salt,
                    ':is_from' =>3,
                );
                $fields_members = array(
                    'username=:username',
                    'email=:email',
                    'password=:password',
                    'salt=:salt',
                    'is_from=:is_from'
                );
                $sql = "INSERT INTO {{members}} SET ".join(',',$fields_members);
                $cmd = Yii::app()->db_user->createCommand($sql);
                $cmd->bindValues($values_members);   
                
                if($cmd->execute())
                {
                    $uid = Yii::app()->db_user->getLastInsertID();
                    
                    //保存信息到user表
                    $values_user = array(
                        ':uid'=>$uid,
                        ':email'=>$email,
                        ':password'=>$password,
                        ':salt'=>$salt,
                    );
                    $sql_user = "INSERT INTO {{user}} SET uid=:uid,email=:email,password=:password,salt=:salt";
                    $cmd_user = Yii::app()->db->createCommand($sql_user);
                    $cmd_user->bindValues($values_user);
                    $cmd_user->execute();
                    
                    $json_array = array(
                        'status'=>1,
                    );
                }
            }else{
                $error_email = $model->hasErrors('email')?0:1;
                $error_pwd   = $model->hasErrors('password')?0:1;
                $json_array  = array(
                    'error_email'=>$error_email,
                    'error_pwd'  =>$error_pwd,
                ); 
            }
        }
        echo json_encode($json_array);
    }


    /**
     * 生成salt
     * @param length
     * @param numeric
     */
    private function productSalt($length, $numeric=0)
    {
          PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
          if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
          }else{
              $hash = '';
              $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
              $max = strlen($chars) - 1;
              for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
              }
          }
          return strtolower($hash);
    }

	/**
	 * 登录
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * 登出
     */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
      
}