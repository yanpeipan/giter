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
    		array('domain',  'domainValidator'),
    		array('uid', 'numerical', 'integerOnly'=>true),
    		array('name, domain, status', 'length', 'max'=>45),
    		array('remote_url', 'length', 'max'=>255),
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
        'type' => '类型',
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

    public function getDomainButtonStyle()
    {
        return $this->domain ? '' : 'display:none';
    }

    /**
     * Returns the url of domain, for example: http://yourname.test.com
     * @return String
     */
    public function getDomainUrl()
    {
        if ($this->needVirtualServer()) {
            $server = $this->getVirtualServerInfo();
            return "{$server->url_schema}://{$this->domain}.{$server->url_host}". ($server->url_port == 80 ? '' : ":{$server->url_port}");
        } else {
            return '#';
        }
    }

    public function getVirtualServerRoot()
    {
        
    }

    public function needVirtualServer()
    {
        if (isset($this->type) && in_array($this->type, array('php-web'))) {
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

    private function htpasswd($usr, $psw)
    {
    	$server = $this -> getRepositoryServerInfo();
    	$ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    	ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);


    	if (!$psw) {
    		return false;
    	}
    	$command =<<<"EOD"
                htpasswd={$server->htpasswd_bin}
                git={$server->apache_user_file}
                usr={$usr}
                psw={$psw}
                apache={$server->apache_bin}
EOD;
                $command .= PHP_EOL;
                $command.=<<<'EOT'
    	if [ -f  ${git} ];then
	    	/usr/bin/htpasswd -b ${git} ${usr} ${psw}
	 else
	    	/usr/bin/htpasswd -c ${git} ${usr} ${psw}
    	fi
                ${apache} restart
EOT;
	$stream = ssh2_exec($ssh, $command);
    	$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
             //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    	stream_set_blocking($stream, true);
    	$result = stream_get_contents($stream);
    	if (empty($result)) {
    		return true;
    	}

    	return false;
    }

    public function addGroup($group)
    {
        $server = $this -> getRepositoryServerInfo();
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
       $command =<<<"EOD"
       group_file={$server->apache_group_file}
       group_name={$group}
EOD;
        $command .= PHP_EOL;
        $command .=<<<'EOT'
       if [ -f ${group_file} -a -s ${group_file} ];then
            sed -i "$ a\\${group_name}: admin " ${group_file}
        else
            echo "${group_name}: admin "> ${group_file} ||  error_exit "Cannot create file"
       fi
EOT;
    $stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
         //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
    }

    public function destroyGroup($group)
    {
        $server = $this -> getRepositoryServerInfo();
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
        $command = "sed -i \"/^${group}:/d\" {$server->apache_group_file}";
        $stream = ssh2_exec($ssh, $command);
    }

    public function addMember($usr, $psw, $project)
    {
        $server = $this -> getRepositoryServerInfo();
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

        $command  =<<<"EOD"
        sed -i "/^{$project}:/{s/ $usr / /g;s/$/&$usr /g;s/[ ]\{2,\}/ /g}"  {$server->apache_group_file}
EOD;
       $stream = ssh2_exec($ssh, $command);
$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
         //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
$this -> htpasswd($usr, $psw);
    }

    public function deleteMember($usr, $project)
    {
                    $server = $this -> getRepositoryServerInfo();
                    $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
                    ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
                    $command = "sed -i \"/^{$project}:/s/ $usr / /g\" {$server->apache_group_file}";
                    $stream = ssh2_exec($ssh, $command);
                    $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
         //$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
stream_set_blocking($stream, true);
$result = stream_get_contents($stream);
    }

    public function publish($domain, $id = null)
    {

        $server = $this -> getVirtualServerInfo($id); 
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
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
        cd ${htdocs}/${domain} && git pull origin master
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
    	$ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    	ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    	$command =<<<"EOD"
    	htdocs={$server->htdocs_path}
    	origin={$this->remote_url}
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
    	$ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    	ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);	
    }

    /**
     * Create Virtual Host
     */
    private function createVirtualServer($domain)
    {
    	$server = $this -> getVirtualServerInfo();
    	$ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    	ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

    	$config =<<<"EOD"
    server
        {
                listen 80;
                server_name {$this->domain}.{$server->url_host};
                root {$server->htdocs_path}{$domain}/;
                access_log  /var/web-logs/{$domain}.{$server->url_host}-access.log  access;
EOD;
	$config .= PHP_EOL;
	$config .=<<<'EOT'
                location ~ .*\.(php|php5)?$
                        {
                                try_files $uri =404;
                                fastcgi_pass  127.0.0.1:9000;
                                #fastcgi_pass  unix:/tmp/php-fpm.socket;
                                fastcgi_index index.php;
                                include fastcgi_params;
                                fastcgi_param  SCRIPT_FILENAME $document_root/$fastcgi_script_name;
                        }

                location / {
                        if (-f $request_filename/index.html){
                        rewrite (.*) $1/index.html break;
                        }
                        if (-f $request_filename/index.php){
                        rewrite (.*) $1/index.php;
                        }
                        if (!-f $request_filename){
                        rewrite (.*) /index.php;
                        }
                        index index.php;
                }
                location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
                        {
                                expires      30d;
                        }

                location ~ .*\.(js|css)?$
                        {
                                expires      12h;
                        }
}	
EOT;
	$tmp = tempnam(sys_get_temp_dir(), '');
	file_put_contents($tmp, $config);
	$filename = $server->nginx_config_path . $this ->domain . '.conf';
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
        $server = $this -> getVirtualServerInfo();
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
        $filename = $server->nginx_config_path . $this ->domain . '.conf';
        $sftp = ssh2_sftp($ssh);
        ssh2_sftp_unlink ($sftp, $filename);
        $command =<<<"EOD"
        domain={$this->domain}
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

    /**
     * Create Repository
     */
    private function createRepository($id)
    {
    	$server = $this -> getRepositoryServerInfo();
    	$ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
    	ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);
    	//append .git to domain
    	$domain = strpos($this->id, '.git') ? $this->id : $this->id . '.git';

                $config  =<<<"EOD"
        <Directory "{$server->root_path}{$this->id}.git/">
            Allow from all
            Order Allow,Deny
            <Limit GET PUT POST DELETE PROPPATCH MKCOL COPY MOVE LOCK UNLOCK>
                Require group {$this->domain}
            </Limit>
        </Directory> 
EOD;
        $tmp = tempnam(sys_get_temp_dir(), '');
        file_put_contents($tmp, $config);
        $filename = $server->git_config_path . $this ->domain . '.conf';

        $result = ssh2_scp_send($ssh, $tmp, $filename, 0777);

            $this -> addGroup($this->id);
            $usr = Yii::app() -> user -> name;
            $psw = Admin::decrypt(Yii::app() ->user -> encrypt);
            $this -> addMember($usr, $psw, $this->id);

    	//create Project Command
    	$command =<<<"EOD"
    	domain={$id}
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
                apache2 restart
EOT;

    	$stream = ssh2_exec($ssh, $command);
    	$stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
    	stream_set_blocking($stream, true);
    	$result = stream_get_contents($stream);
    	$this -> remote_url = "http://{$server->ipper}:{$server->url_port}/git/{$this ->id}.git";

    	return true;
    }

    public function destroyRepository($id)
    {
        $server = $this -> getRepositoryServerInfo();
        $ssh = ssh2_connect($server->ipper, $server->ssh_port, array('hostkey'=>'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh, Yii::app()->params['user'], Yii::app()->params['pubkeyfile'],  Yii::app()->params['pemkeyfile']);

                $domain = strpos($this->id, '.git') ? $this->id : $this->id . '.git';
        $filename = $server->git_config_path . $this ->id . '.conf';
        $command =<<<"EOD"
        apache_config_file={$filename}
        domain={$domain}
        repositoriesRoot={$server->root_path}
        apache2={$server->apache_bin}
EOD;
        $command .= PHP_EOL;
        $command .=<<<'EOT'
            rm -rf ${apache_config_file}
            cd ${repositoriesRoot} && rm -rf  ${domain}
EOT;
        echo $command;
        ssh2_exec($ssh, $command);
    }

    /**
     *  Create Project
     */
    public function create()
    {
    	if (is_numeric($this->id) && $this -> createRepository($this->id)) {
    		$usr = Yii::app() -> user -> name;
    		$psw = Admin::decrypt(Yii::app() ->user -> encrypt);
    		$this -> htpasswd($usr, $psw);
                          if ($this->needVirtualServer()) {
                            $this  -> createVirtualServer($this->domain);
                            $this -> cloneRepository();
                          }
    		return true;
    	} else {
    		$this -> addError('domain', 'exception when create repository');
    	}
    }

    public function destory()
    {
        $this->destroyRepository($this->id);
        $this->destroyGroup($this->name);
        if ($this->needVirtualServer()) {
            $this->destroyVirtualServer();
        }
    }

    public function test()
    {
        echo Yii::app()->Controller->renderPartial('shell/addApacheGroup', array('apache_group_file' => '/group', 'group_name' => "/group_name"), true);
    }
} 
