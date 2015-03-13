<?php

/**
 * This is the model class for table "{{projects}}".
 *
 * The followings are the available columns in table '{{projects}}':
 * @property string $id
 * @property string $name
 * @property integer $uid
 * @property string $ctime
 * @property string $remote_url
 * @property string $domain
 * @property string $status
 */
class Projects extends CActiveRecord
{

  public $mid;
  public $type;
  public $repositories = ['local' => 'local', 'github' => 'github'];

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{projects}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name, type', 'required'),
      array('name', 'unique'),
      array('name', 'match', 'pattern'=>'/^[\d\w]+$/'),
      //array('domain',  'domainValidator'),
      array('description', 'type', 'type'=> 'string'),
      array('uid', 'numerical', 'integerOnly'=>true),
      array('name, domain, status, repository', 'length', 'max'=>45),
      array('remote_url', 'length', 'max'=>255),
      array('root', 'length', 'max'=>4096),
      array('index', 'length', 'max'=>255,),
      array('index', 'default', 'value'=>'index.php'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, type, uid, ctime, remote_url, domain, status', 'safe', 'on'=>'search'),
    );
  }

  /**
   * domain Validator
   */
  public function domainValidator($attributes, $params)
  {
    //special chars validator
    if ( $this -> domain !== trim(escapeshellarg($this -> domain), "'")) {
      $this->addError('domain', 'contains special chars');
    } 
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'name' => '名称',
      'uid' => 'Uid',
      'ctime' => '创建时间',
      'remote_url' => '访问地址',
      'domain' => '域名',
      'status' => '状态',
      'root' => '相对根目录',
      'index' => 'Index文件',
      'type' => '类型',
      'repository' => '版本库',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   *
   * Typical usecase:
   * - Initialize the model fields with values from filter form.
   * - Execute this method to get CActiveDataProvider instance which will filter
   * models according to data in model fields.
   * - Pass data provider to CGridView, CListView or any similar widget.
   *
   * @return CActiveDataProvider the data provider that can return the models
   * based on the search/filter conditions.
   */
  public function search($mid = False)
  {
    // @todo Please modify the following code to remove attributes that should not be searched.

    $criteria=new CDbCriteria;
    $criteria->alias = 'projects';

    $criteria->compare('projects.id',$this->id,true);
    $criteria->compare('name',$this->name,true);
    $criteria->compare('projects.uid',$this->uid);
    $criteria->compare('ctime',$this->ctime,true);
    $criteria->compare('remote_url',$this->remote_url,true);
    $criteria->compare('domain',$this->domain,true);
    $criteria->compare('status',$this->status,true);

    if ($mid) {
      $criteria->compare('{{projects_members}}.uid',$this->mid,true);
      $criteria->join='left join {{projects_members}} on {{projects_members}}.pid=projects.id';
    }
    $criteria->order = 'ctime desc';

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Projects the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function getTypes($type = 'All')
  {
    switch($type){
    case 'needVirtualServer':
      return array('PHP-web' => 'PHP-web');
      break;
    default:
      return array('Android' => 'Android', 'iOS' => 'iOS', 'PHP-web' => 'PHP-web');
    }
  }

  public function gethasDomainTypes()
  {
    return array('PHP-web' => 'PHP-web');
  }

  public function getRootPrepend()
  {
    $virtualServer = $this->getVirtualServerInfo();
    return $virtualServer['htdocs_path'];
  }

  /**
   * Returns the url of domain, for example: http://yourname.test.com
   * @return String
   */
  public function getDomainUrl()
  {
    if ($this->needVirtualServer()) {
      $server = $this->getVirtualServerInfo();
      return "{$server->url_schema}://{$this->name}.{$server->url_host}". ($server->url_port == 80 ? '' : ":{$server->url_port}");
    } else {
      return '#';
    }
  }

  /**
   * git clone url
   * @return string
   */
  public function getCloneUrl()
  {
    if ($this->repository == 'local' ) {
	    $server = $this->getRepositoryServerInfo();
	    return "{$server->url_schema}://{$server->url_host}" . ($server->url_port == 80 ? '' : ":{$server->url_port}") . "/{$this ->name}.git"; 
    } else {
	return $this->remote_url;
    }
  }


  public function needVirtualServer()
  {
    if (isset($this->type) && in_array($this->type, $this->hasDomainTypes)) {
      return true;
    }
    return false;
  }

  public function getRepositoryServerInfo($id = null)
  {
    if (is_numeric($id)) {
      $repository = Repositories::model()->findByPk($id);
    } else {
      $repository = Repositories::model()->findByAttributes(array(), 'name<>""', array('order'=>'id asc', 'limit'=>1 ));
    }
    if (is_null($repository)) {
      throw new Exception("Cannot find any Repositories", 1);
    } else {
      return $repository;
    }
  }

  public function getVirtualServerInfo($id = null)
  {
    if (is_numeric($id)) {
      $virtualServer = VirtualServers::model()->findByPk($id);
    } else {
      $virtualServer = VirtualServers::model()->findByAttributes(array(), 'name<>""', array('order'=>'id asc', 'limit'=>1 ));
    }
    if (is_null($virtualServer)) {
      throw new Exception("Cannot find any VirtualServers", 1);
    } else {
      return $virtualServer;
    }
  }

  public function publish($domain, $id = null)
  {

    $server = $this -> getVirtualServerInfo($id); 
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    $domain =  escapeshellarg($domain);
    $command =<<<"EOD"
      domain={$domain}
      htdocs={$server->htdocs_path}
EOD;
$command .=<<<'EOT'
        project_dir=${htdocs}${domain}
        if [ ! -d ${project_dir} ];then
            error_exit "Cannot found htdocs"
        fi
        cd ${project_dir}
  git pull
  chown -R www-data. ${project_dir}
EOT;

$stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
//$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);


  }
  /**
   * Clone Repository
   */
  private function cloneRepository($domain)
  {
    $server = $this -> getVirtualServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    $command =<<<"EOD"
      htdocs={$server->htdocs_path}
      origin={$this->CloneUrl}
      location={$domain}
EOD;
$command  .= PHP_EOL;
$command .=<<<'EOT'
      cd ${htdocs} ||  error_exit "Cannot change directory"
      git clone ${origin} ${location}
EOT;
$stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
if (empty($result)) {
  return true;
}
return false;
  }

  public function gitRemoteShow()
  {
    $server = $this -> getVirtualServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);	
  }

  /**
   * Create Virtual Host
   */
  private function createVirtualServer($secondLevelDomain, $id, $relatePath='', $index='index.php')
  {
    $server = $this -> getVirtualServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    $server_name = "{$secondLevelDomain}.{$server->url_host}";
    $root = "{$server->htdocs_path}{$secondLevelDomain}/{$relatePath}";
    $access_log = "/var/web-logs/{$secondLevelDomain}.{$server->url_host}-access.log";
    $fastcgi_pass = '127.0.0.1:9000';
    $index = $index;

    $config ="
       server
       {
        listen 80;
        server_name  $server_name;
        root  $root;
        access_log  $access_log access;

        location ~ .*\.(php|php5)?$
        {
            try_files \$uri =404;
            fastcgi_pass  $fastcgi_pass;
            fastcgi_index $index;
            include fastcgi_params;
            fastcgi_param  SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
        }

        location / {
            if (-f \$request_filename/index.html){
                rewrite (.*) \$1/index.html break;
            }
            if (-f \$request_filename/index.php){
                rewrite (.*) \$1/index.php;
            }
            if (!-f \$request_filename){
                rewrite (.*) /{$index};
            }
            index {$index};
        }

        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
        {
            expires      30d;
        }

        location ~ .*\.(js|css)?$
        {
            expires      12h;
        }
  }";


    $tmp = tempnam(sys_get_temp_dir(), '');
    file_put_contents($tmp, $config);
    $filename = $server->nginx_config_path . "{$secondLevelDomain}.{$server->url_host}" . '.conf';
    $result = ssh2_scp_send($ssh, $tmp, $filename, 0777);

    $command =<<<"EOD"
    {$server->ngixn_bin} -s reload
EOD;
$stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
  }

  public function destroyVirtualServer()
  {
    $server = $this->getVirtualServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
    $filename = $server->nginx_config_path . "{$this->name}.{$server->url_host}" . '.conf';
    $sftp = ssh2_sftp($ssh);
    ssh2_sftp_unlink ($sftp, $filename);
    $command =<<<"EOD"
      domain={$this->name}
      htdocs={$server->htdocs_path}
EOD;
$command .= PHP_EOL;
$command .=<<<'EOT'
        cd ${htdocs}/&& rm -rf ${domain} 
EOT;
$command .= PHP_EOL;
$command .=<<<"EOD"

{$server->ngixn_bin} -s reload
EOD;
$stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
  }

  private function createLocalRepository($id) {
    $server = $this -> getRepositoryServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
    //append .git to domain
    $domain = strpos($id, '.git') ? $id : $id . '.git';

    //create Project Command
    $command =<<<"EOD"
      domain={$domain}
      repositoriesRoot={$server->root_path}
      apache2={$server->apache_bin}
EOD;


$command .= PHP_EOL;

$command .=<<<'EOT'
        if [ ! -d ${repositoriesRoot} ];then
            mkdir  ${repositoriesRoot} || error_exit "Cannot create  Root"
        fi
        dir=${repositoriesRoot}"/"${domain}
        cd ${repositoriesRoot} ||  error_exit "Cannot change directory"

        git init --bare ${domain} && cd ${dir} && git update-server-info
        cp ${dir}/hooks/post-update.sample  ${dir}/hooks/post-update
        chown -R www-data. ${dir}
  server nginx restart
EOT;

$stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
$this -> remote_url = $this->CloneUrl;

return true;

  } 

  private function deleteGithubRepository($id) {
    $client = new \Github\Client();
    $client->authenticate('', \Github\Client::AUTH_URL_TOKEN);
    $client->api('repo')->remove('inmi-panel', $id); 
  }

  private function createGithubRepository($id) {
    $client = new \Github\Client();
    $client->authenticate('', \Github\Client::AUTH_URL_TOKEN);
    $repo = $client->api('repo')->create($id, '', '', true);
    $this-> remote_url = $repo['clone_url'];
    if (isset(Yii::app()->user->github_name) && !empty(Yii::app()->user->github_name)) {
	    $this->addcollaborators($id, Yii::app()->user->github_name);
    }
  }
  private function addCollaborators($id, $username) {
	if (empty($username)) {
		return false;	
	}
	$client = new \Github\Client();
	$client->authenticate('', \Github\Client::AUTH_URL_TOKEN);
	$client->api('repo')->collaborators()->add('inmi-panel', $id, $username);
  }
  /**
   * Create Repository
   */
  private function createRepository($id)
  {
    if ($this->repository == 'github') {
      $this->createGithubRepository($id);
    } else {
      $this->createLocalRepository($id);
    }
    return true;
  }

  public function destroyRepository($id) {
	if ($this->repository == 'local') {
		$this->destroyLocalRepository($id);
	}elseif($this->repository == 'github'){
		$this->deleteGithubRepository($id);
	}
  }

  public function destroyLocalRepository($id)
  {
    $server = $this -> getRepositoryServerInfo();
    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    $domain = strpos($id, '.git') ? $id : $id . '.git';
    $command =<<<"EOD"
      domain={$domain}
      repositoriesRoot={$server->root_path}
EOD;
$command .= PHP_EOL;
$command .=<<<'EOT'
            cd ${repositoriesRoot} && rm -rf  ${domain}
EOT;
ssh2_exec($ssh, $command);
  }

  /**
   *  Create Project
   */
  public function create()
  {
    if (is_numeric($this->id) && $this -> createRepository($this->name)) {
      $usr = Yii::app() -> user -> name;
      $psw = Admin::decrypt(Yii::app() ->user -> encrypt);
      //$this -> htpasswd($usr, $psw);
      if ($this->needVirtualServer()) {
        $this  -> createVirtualServer($this->name, $this->id, $this->root, $this->index);
        $this -> cloneRepository($this->name);
      }
      return true;
    } else {
      $this -> addError('domain', 'exception when create repository');
    }
  }

  public function modify()
  {
    if ($this->needVirtualServer()) {
      $this  -> createVirtualServer($this->name, $this->id, $this->root, $this->index);
    } 
  }

  public function destory()
  {
	  $this->destroyRepository($this->name);
	  if ($this->needVirtualServer()) {
		  $this->destroyVirtualServer();
	  }
  }

  public function online()
  {
    if($this->needVirtualServer()) {
      set_time_limit(0);

      $server = $this -> getVirtualServerInfo();
      $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
      ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
      $command = "git_upload {$this->name}";
      $stream = ssh2_exec($ssh, $command);
      $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
      stream_set_blocking($stream, true);
      $result = stream_get_contents($stream);
    }
  }
    public function deleteMember($usr, $project = null)
    {
                    $server = $this -> getRepositoryServerInfo();
                    $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
                    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
                    if ($project) {
                    	$command = "sed -i \"/^{$project}:/s/ $usr / /g;s/ $usr$/ /g\" {$server->apache_group_file}";
                    }else{
                    	$command = "sed -i \"s/ $usr / /g;s/ $usr$/ /g\" {$server->apache_group_file}";
                    }
                    $stream = ssh2_exec($ssh, $command);
                    $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
         //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
    }

    public function addMember($usr, $psw, $project)
    {
        $server = $this -> getRepositoryServerInfo();
        $ssh = ssh2_connect($server->url_host, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
        $command  =<<<"EOD"
        sed -i "/^{$project}:/{s/ $usr / /g;s/$/&$usr /g;s/[ ]\{2,\}/ /g}"  {$server->apache_group_file}
EOD;
	echo $command;
       $stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
         //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
//$this -> htpasswd($usr, $psw);
    }

  public function test()
  {
    echo Yii::app()->Controller->renderPartial('shell/addApacheGroup', array('apache_group_file' => '/group', 'group_name' => "/group_name"), true);
  }
} 
