<?
/**
 * 同户注册类
 */
class Register{
	private $email;
	
	private $password;
	private $repassword;
	
	private $is_from;
	
	/**
	 * @param $email
	 * @param $password
	 * @param $repassword
	 * @param $is_from
	 */
   public function doRegister($email, $password, $repassword, $is_from){
		$this->email         = $email;
		$this->password      = $password;
		$this->repassword    = $repassword;
		$this->is_from       = $is_from;	
		
        $res = $this->checkEmail();
		if($res!=='1'){
			return $res;
		}
		
        $res = $this->checkPassword();
		if($res!=='1'){
			return $res;
		}
		
        $uuid = $this->getUuidForNewUser();
        $result = array();
        $result['status'] = 0;

        //uc register
        if($uuid){
            if($this->is_from == ''){
                $result['error_no'] = 3009;
                $result['error_msg'] = "Need is_from";
                return $result;
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
        return $result;
    }
    
    //check Email
    public function checkEmail(){
        $result = array('status'=>0);
        if(empty($this->email)){
            $result['error_no'] = 3001;
            $result['error_msg'] = "Need Email";
            return $result;
        }
        
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $result['error_no'] = 3002;
            $result['error_msg'] = "Email Format is Wrong!";
            return $result;
        }
        
        //check if had registed
        $sql = "SELECT count('x') cnt FROM {{members}} WHERE email=:email";
        $cmd = Yii::app()->db_user->createCommand($sql);
        $cmd->bindValue(":email",$this->email);
        $row = $cmd->queryRow();
		
        if($row && $row['cnt']>0){
            $result['error_no'] = 3003;
            $result['error_msg'] = "Email is Exists!";
            return $result;
        }
		
		$result = '1';
		return $result;
    }
    
    //check password
    public function checkPassword(){
        $result = array('status'=>0);
        if(empty($this->password) || empty($this->repassword)){
            $result['error_no'] = 3004;
            $result['error_msg'] = "Need Password";
            return $result;
        }
        
        if($this->password != $this->repassword){
            $result['error_no'] = 3005;
            $result['error_msg'] = "Two Passwords must be equal";
            return $result;
        }
        
        if(strlen($this->password)<6){
            $result['error_no'] = 3006;
            $result['error_msg'] = "Password's length must be greater than or equal to 6";
            return $result;
        }
		
		$result = '1';
		return $result;
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
}
 
 

?>