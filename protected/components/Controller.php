<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    public $name='guochao';
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/column1';
    /** 
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    protected $db=''; 
    
    public $is_admin;
    
    public function init(){
    
        parent::init();
        $this->db=Yii::app()->db;
		$cookie = Yii::app()->request->getCookies();
        if(!(isset($cookie[sha1('vo_auth')]) && !empty($cookie[sha1('vo_auth')]->value))){
        	if('user'!==$this->getId()){
        		//$this->redirect(Yii::app()->user->loginUrl);
        	}
        }else{
            $name_id   = $this->_authcode($cookie[sha1('vo_auth')]->value,'DECODE','tuziadmin');
            $nameAndId = explode('&*', $name_id);
            Yii::app()->user->id   = $nameAndId[1];
            Yii::app()->user->name = $nameAndId[0];
        }
		/*
		Yii::app()->bootstrap->responsiveCss= true;
		Yii::app()->bootstrap->yiiCss= true;
		Yii::app()->bootstrap->coreCss= true;
		Yii::app()->bootstrap->jqueryCss= true; 
		Yii::app()->bootstrap->enableJS= true;
		Yii::app()->bootstrap->init();*/
		   
    }
	
    /**
     * 给用户分配uuid
     */
    /**
     * 给用户分配uuid
     */

    protected function getUserId4Username(){
        $sql = "SELECT id FROM {{userid}} WHERE id>100000 AND is_use=0 ORDER BY sort DESC";
        $command = Yii::app()->db_user->createCommand($sql);
        $row = $command->queryRow();
        $id = $row['id'];
        $sql_update = "UPDATE {{userid}} SET is_use=1 WHERE id={$id}";
        $command_update = Yii::app()->db_user->createCommand($sql_update);
        $command_update->execute();
        return $id;
    }
    
    protected function getAreaNameByIp($ip){     
        Yii::import("ext.ip.Convert");
        $convert = new Convert();
        $ipdatafile="protected/extensions/ip/tinyipdata.dat";
        $area_name = $convert->convert_ip_full($ip,$ipdatafile);
        return iconv('GBK', 'UTF-8', $area_name);
    }
    protected function getParam($param){
        $val=Yii::app()->request->getParam($param);
        return $val;
    }
   
    
    protected function pr($op){
        echo '<pre>';
        print_r($op);die;
    }
    
    
    //market use
    protected function getTypeName($data,$row){
        if ($data->is_show == 0){
            return $data->is_show = '否';
        }elseif($data->is_show == 1){
            return $data->is_show = '是';
        }
        else{
                return '没有数据';
        }
    }
    
    /** 写日志
     * 
     * @param   $pro            string  项目名   本项目下默认为CP
     * @param   $uname          string  操作者
     * @param   $method         string  功能-----》操作的方法名
     * @param   $action         string  操作类型     ADD:增加     UPDATE:修改  DEL:删除
     * @param   $table_name     string 操作的表名
     * @param   $t_id           string  操作数据的ID
     * 
     **/
    
    protected function EditLog($uname,$method,$action,$table_name,$t_id){
        /*if(empty($method) || empty($action) || empty($table_name) || empty($t_id)){
            return FALSE;
        }
        $pro='CP';
        $date_time=date('Y-m-d',time());
        $time = time();
        $sql = "INSERT INTO pt_cnt_edit_list_new SET 
                uname=:uname,
                pro=:pro,
                method=:method,
                action=:action,
                table_name=:table_name,
                t_id=:t_id,
                date_time=:date_time,
                time=:time";
        Yii::app()->db->createCommand($sql)
        ->bindValues(array( ':uname'=>$uname,
                            ':pro'=>$pro,
                            ':method'=>$method,
                            ':action'=>$action,
                            ':table_name'=>$table_name,
                            ':t_id'=>$t_id,
                            ':date_time'=>$date_time,
                            ':time'=>$time))
        ->execute();
        if($table_name=='v_list'){
            $this->ActionLog($action,$t_id,$uname);
        }*/
        return TRUE;
    }
    /** 写pt_action表数据
     * 
     * @param   $action         string  1:add 2:update 3:del
     * @param   $table_name     string  操作的表名
     * @param   $vid            string  操作数据的ID
     * @param   $is_del         string  0:否 1：删除    默认0
     * @param   $user_name      string 操作的表名
     * 
     **/
    function ActionLog($action,$vid,$user_name){
        if(!empty($action) && !empty($vid) && !empty($user_name)){
            if($action=='ADD'){
                $a = 1;
            }else if($action=='UPDATE'){
                $a = 2;
            }else if($action=='DEL'){
                $a = 3;
            }
            $sql = "INSERT INTO {{admin_action}} SET 
                    action=:action,
                    table_name=:table_name,
                    vid=:vid,
                    is_del=0,
                    user_name=:user_name";
            $res = Yii::app()->db->createCommand($sql)
            ->bindValues(array( ':action'=>$a,
                                ':table_name'=>'v_list',
                                ':vid'=>$vid,
                                ':user_name'=>$user_name,))
            ->execute();
            return TRUE;
        }
        
    }
 function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
    
        $key = md5($key ? $key : '123456789');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
    
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
    
        $result = '';
        $box = range(0, 255);
    
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
    
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
    
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
    
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                    return '';
                }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    
    }       

  const HTTP_BAD_REQUEST  = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_SUCCESS      = 200;
    
    protected function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);
        
        if($body != '')
        {
            // send the body
            echo $body;
        }
        Yii::app()->end();
    }
    
    
    protected function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
