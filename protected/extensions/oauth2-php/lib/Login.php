<?php
class Login{
	
	/**
	 * @param $username
	 * @param $password
	 */
	public function doLogin($username, $password){
		$result = $this->checkUserCredentials($username, $password);
		if($result){
			//获取用户信息
			$uid = $result['uid'];
			
			//头像昵称
            $sql = "SELECT avatar_url,nick_name FROM {{members_info}} WHERE uid=:uid";
            $cmd = Yii::app()->db_user->createCommand($sql);
            $user = $cmd->bindValue(':uid', $uid)->queryRow();
            if(!empty($user['avatar_url']))
            {
                //获取cdn_img_switch状态
                $CdnImg = new CdnImg;
                $cnd_img_switch = $CdnImg->CdnImgSwitch(0);
                if($cnd_img_switch==1)
                {
                	//http://avatar-img.b0.upaiyun.com
                    $img = Yii::app()->params['yun_img'].$user['avatar_url'].'!171x181';
                }else{
                    $img = '';
                }
            }else{
                $img = '';
            }

			$json_array = array(
				'status'=>'1',
				'uid'=>strval($uid),
				'uuid'=>strval($result['uuid']),
				'email'=>strval($result['email']),
				'avatar_url'=>strval($img),
				'nick_name'=>strval($user['nick_name']),
				'bindstatus'=>$this->getBindStatus($uid),
			);
		}else{
			$json_array = array(
				'status'=>'0',
				'error_msg'=>'username or password is error!',
			);
		}
		return $json_array;
	}
	
	/**
	 * 检查密码是否正确
	 */
	protected function checkUserCredentials($username, $password){
	   $result = false;
		
       $sql = "SELECT uid,password,salt,username AS uuid,email FROM {{members}} WHERE email=:email";
       $cmd = Yii::app()->db_user->createCommand($sql);
       $cmd->bindValue(":email",$username);
       $row = $cmd->queryRow();
       if($row){
           $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
           if($row['password'] == md5($passwordmd5.$row['salt'])){
				$result = array(
					'uid'=>$row['uid'],
					'uuid'=>$row['uuid'],
					'email'=>$row['email'],
				);
           }
       }else{
           $sql = "SELECT uid,password,salt,username AS uuid,email FROM {{members}} WHERE username=:username";
           $cmd = Yii::app()->db_user->createCommand($sql);
           $cmd->bindValue(":username",$username);
           $row = $cmd->queryRow();
           if($row){
               $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
               if($row['password'] == md5($passwordmd5.$row['salt'])){
					$result = array(
						'uid'=>$row['uid'],
						'uuid'=>$row['uuid'],
						'email'=>$row['email'],
					);
               }
           }
       }
	   
	   return $result;
	}
	
	/**
	 * 微博绑定状态
	 * @param $uid 
	 */
	private function getBindStatus($uid){
		$array = array(
			'qq'=>'0',
			'sina'=>'0',
			'renren'=>'0',
			'qqzone'=>'0',
			'douban'=>'0',
		);
		
		$sql = "SELECT bind_sina,bind_qq FROM {{third_users}} WHERE uid=:uid";
		$cmd = Yii::app()->db_user->createCommand($sql);
		$row = $cmd->bindValue(':uid', $uid)->queryRow();
		
		if($row){
			$array['qq'] = strval($row['bind_qq']);
			$array['sina'] = strval($row['bind_sina']);
		}

		return $array;
	}
}


?>