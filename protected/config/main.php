<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/yii-bootstrap');
$db=include dirname(__FILE__)."/db.php";
$common_config = array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
  'name'=>'项目管理',
  'language'=>'zh_cn',
  'defaultController'=>'admin/user',
  'theme'=>'genius',

  // preloading 'log' component
  'preload'=>array('log', 'yii-bootstrap'),

  // autoloading model and component classes
  'import'=>array(
    'application.models.*',
    'application.modules.*',
    'application.extensions.*',
    'application.components.*',
    'ext.pinyin.*',
    'application.extensions.swfupload.*',
    'application.vendors.*',
    'ext.Curl',
    'ext.ApiTest.*',
    'ext.punica.puti.*',
    'ext.yii-bootstrap.*',
    'ext.cdnimg.*',
    'ext.clientfilter.*',
    'ext.checkcfg.*',
    'ext.letter.*',
    'ext.curl.*',
    'ext.ip.*',
    'ext.FilterByMongo.*',
    'ext.upyun.*',
    'ext.highcharts.*',
    'application.extensions.KEmail.KEmail',
    'application.modules.admin.extensions.*',
    //'application.modules.srbac.controllers.SBaseController',
    'ext.file.*',
    ),


  'modules'=>array(
    'admin'=>array(
      'defaultController'=>'video',
      'class'=>'application.modules.admin.AdminModule',
      ),
      //'install',
    /*
    'srbac' => array(
      'userclass'=>'Admin', 
      'userid'=>'id', 
      'username'=>'username',
      'debug'=>TRUE, 
      'delimeter'=>'@',
      'pageSize'=>20, 
      'superUser' =>'admin', 
      'css'=>'srbac.css', 
      'layout'=>'application.views.layouts.column2',
      'notAuthorizedView'=>'srbac.views.authitem.unauthorized',      
      'alwaysAllowed'=>array(''),
      'userActions'=>array('Show','View','List'),
      'listBoxNumberOfLines' => 15, 
      'imagesPath' => 'srbac.images', 
      'imagesPack'=>'noia',  
      'iconText'=>true, 
      'header'=>'srbac.views.authitem.header',         
      'footer'=>'srbac.views.authitem.footer',           
      'showHeader'=>true, 
      'showFooter'=>true, 
      'alwaysAllowedPath'=>'srbac.components',

      ),
      */
  'plugin',
  ),

  // application components
  'components'=>array (   
    //start
    'oauth2' => array(
      'class' => 'PDOOAuth2',
      ),
    //end
    //start
    'curl'=>array(
      'class'=>'Curl',
      'options'=>array(
        'login'=>array(
          'username'=>'gw.16tree.com',
          'password'=>'xxoo',
          ),
        ),
      ),
    //end
    //start
    'user'=>array(
      'allowAutoLogin' => true,
      'loginUrl' => '/admin/user',
      'class'=>'WebUser',
      ),
    'file'=>array(
      'class'=>'application.extensions.file.CFile',
      ),
    //end
    //start
    'email'=>array(  
      'class'=>'KEmail',  
      'host_name'=>'smtp.exmail.qq.com',  //Hostname or IP of smtp server  
      'user'=>'master@sotu.tv',
      'password'=>'feedback2013',	
      'authentication_mechanism'=>'LOGIN',
      ),
    //end
    //start
    'authManager'=>array(
      //'class'=>'CPhpAuthManager',
      //'authFile'=>dirname(__FILE__).'/authFile',
      'class' => 'CDbAuthManager',
      'connectionID' => 'db',
      //'itemTable'=>'tz_items',
      //'assignmentTable'=>'tz_assignments',
      //'itemChildTable'=>'tz_itemchildren',
      ),	   
    //end

    // uncomment the following to enable URLs in path-format
    //start
    'urlManager'=>array( 
      'urlFormat'=>'path',
      'showScriptName'=>false,
      'rules'=>array(
        '/api' => '/apis',
        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
        ),
      ),
    //end  
    //==============================DB config===============================================
    'db'=> is_array($db) ? $db : array(),
    //=================================End DB config======================================================

    //start
    'errorHandler'=>array(
      // use 'site/error' action to display errors
      'errorAction'=>'site/error',
      ),
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array( 
          'class'=>'CFileLogRoute',
          'levels'=>'error, warning, trace',
          ),
        ),
    ), //end
    //end
    //bootstrap
    'bootstrap' => array(
      'class' => 'ext.yii-bootstrap.components.Bootstrap',
      'responsiveCss' => false,
      'yiiCss'=>false,
      'coreCss'=>false,
      'jqueryCss'=>false,
      'enableJS'=>false,
      ),
    'booster' => array(
      'class' => 'ext.booster.components.Booster',
      ),
    'filecache'=>array(
      'class'=>'system.caching.CFileCache',    
      //我们使用CFileCache实现缓存,缓存文件存放在runtime文件夹中
      'directoryLevel'=>'2',   //缓存文件的目录深度
      //'cachePath'=>'/tmp/cachetest',
      ),
    'session'=>array(
      'autoStart'=>false,
      'sessionName'=>'Site Access',
      'cookieMode'=>'only',
      //'savePath'=>'/path/new/',
      ),
    ),


  // using 
'params'=>array(
    // this is used in contact page
  'adminEmail'=>'webmaster@example.com', 
    'imgUrl' => 'http://vo.tuziv.com/',//'http://img1tuzi.b0.upaiyun.com/',//封面路径配置
    'yunimg'=> 'http://img1tuzi.b0.upaiyun.com/',
    'user' => 'root',
    'pubkeyfile' => dirname(__FILE__) . '/.ssh/id_rsa.pub',
    'pemkeyfile' => dirname(__FILE__) . '/.ssh/id_rsa.pem',
    ),
);

if(defined("YII_DEBUG") && true == YII_DEBUG){
  ini_set("display_errors",1);
  ini_set("error_reporting", E_ALL);
  ini_set('html_errors', 1);
  $common_config = CMap::mergeArray(
    $common_config,
    array(
      'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
          'class'=>'system.gii.GiiModule', 
          'password'=>false,
          // If removed, Gii defaults to localhost only. Edit carefully to taste.
          'ipFilters'=>false,
          //'ipFilters'=>array('127.0.0.1','::1'),
          ),
        ),
      )
    );
}
return $common_config;
