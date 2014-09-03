<?
/**
 * 同户注册类
 */
class RegisterBase{
	private $email;
	
	private $password;
	private $repassword;
	
	private $client_id;
	private $client_secret;
	
	private $is_from;
	
    const HTTP_SUCCESS      = 200;
	
	public function __construct($email, $password, $repassword, $client_id, $client_secret, $is_from){
		$this->email         = $email;
		$this->password      = $password;
		$this->repassword    = $repassword;
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->is_from       = $is_from;
	}
	
   public function doRegister(){
        $this->checkAppIfCanRegistry();

        $this->checkEmail();

        $this->checkPassword();
        
        $uuid = $this->getUuidForNewUser();
        $result = array();
        $result['status'] = 0;

        //uc register
        if($uuid){
            if($this->is_from == ''){
                $result['error_no'] = 3009;
                $result['error_msg'] = "Need is_from";
                $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
            }
            $status = uc_user_register($uuid , $this->password , $this->email);
            if($status>0){
                $result['status'] = 1;//success
                $result['uuid']   = $uuid;
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $agent = $this->_agentInfo($user_agent);
                $sql = "UPDATE `16tree_user`.`uc_members` SET agent='$agent' WHERE username=:uuid";
                Yii::app()->db_user->createCommand($sql)->bindValue(":uuid",$uuid)->execute();
                $sql = "UPDATE `16tree_user`.`uc_members` SET is_from=:from WHERE username=:uuid";
                Yii::app()->db_user->createCommand($sql)->bindValue(":from",$this->is_from)->bindValue(":uuid",$uuid)->execute();
            }else{
                $result['status']   = 0;//failed
                $result['error_no'] = $status;
            }
        }else{
            $result['error_no'] = "-999";
        }
        $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
    }
    
    //check Email
    public function checkEmail(){
        $result = array('status'=>0);
        if(empty($this->email)){
            $result['error_no'] = 3001;
            $result['error_msg'] = "Need Email";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $result['error_no'] = 3002;
            $result['error_msg'] = "Email Format is Wrong!";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        
        //check if had registed
        $sql = "SELECT count('x') cnt FROM {{members}} WHERE email=:email";
        $cmd = Yii::app()->db_user->createCommand($sql);
        $cmd->bindValue(":email",$this->email);
        $row = $cmd->queryRow();
        if($row && $row['cnt']>0){
            $result['error_no'] = 3003;
            $result['error_msg'] = "Email is Exists!";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
    }
    
    //check password
    public function checkPassword(){
        $result = array('status'=>0);
        if(empty($this->password) || empty($this->repassword)){
            $result['error_no'] = 3004;
            $result['error_msg'] = "Need Password";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        
        if($this->password != $this->repassword){
            $result['error_no'] = 3005;
            $result['error_msg'] = "Two Passwords must be equal";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        
        if(strlen($this->password)<6){
            $result['error_no'] = 3006;
            $result['error_msg'] = "Password's length must be greater than or equal to 6";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
    }
    
    public function checkAppIfCanRegistry(){
        $result = array('status'=>0);
        if(empty($this->client_id) || empty($this->client_secret)){
            $result['error_no'] = 3007;
            $result['error_msg'] = "Need client_id && client_secret";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        $sql = "SELECT app_id FROM {{app}} WHERE client_id=:client_id AND client_secret=:client_secret";
        $row = Yii::app()->db_dev->createCommand($sql)->bindValue(":client_id",$this->client_id)->bindValue(":client_secret",$this->client_secret)->queryRow();
        if(!$row){
            $result['error_no'] = 3008;
            $result['error_msg'] = "client_id && client_secret are invalid";
            $this->_sendResponse(self::HTTP_SUCCESS,json_encode($result));
        }
        return $row;
    }
    
    public function getUuidForNewUser(){
        $sql = "SELECT id,is_use,sort
                FROM `16tree_user`.`uc_userid`
                WHERE is_use=0 AND id>100000
                ORDER BY sort DESC LIMIT 1";
        $cmd = Yii::app()->db_user->createCommand($sql);
        $row = $cmd->queryRow();
        if($row){
            $this->setIsUse($row['id']);
        }
        return $row ? $row['id'] : false;
    }
    
    public function setIsUse($uuid){
        $sql = "UPDATE `16tree_user`.`uc_userid` SET is_use=1 WHERE id=:id";
        Yii::app()->db_user->createCommand($sql)->bindValue(":id",$uuid)->execute();
    }
    public function AddUserAmount($username){
        $num = 100;
        //get uid
        $user_sql = "SELECT uid
                FROM `16tree_user`.`uc_members`
                WHERE username = :uname";
        $row = Yii::app()->db_user->createCommand($user_sql)->bindValue(":uname",$username)->queryRow();
        //add punica_num
        $pay_sql = "INSERT INTO `16tree_pay`.`p_user_balance`
                    SET user_id=:uid,punica_num=:num";
        $cmd = Yii::app()->db_pay->createCommand($pay_sql)->bindValue(":uid",$row['uid'])->bindValue(":num",$num)->execute();
    }
    private function _agentInfo($agent){
        $agent_1 = explode(';',$agent);
        if(isset($agent_1[2])){
            return trim($agent_1[2]);
        }else{
            $agent_2 = explode('/',$agent_1[0]);
            return trim($agent_2[0]);
        }
        //return $agent_1;
        //return $agent_1[2];
    }
	
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
 
 

?>