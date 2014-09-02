<?php
/**
 * 获取用户信息
 */
class UserApi extends BaseApi{
    public function GetUserInfo()
    {
        $uid = isset($_POST['uid'])?trim($_POST['uid']):'';
        
        $sql = "SELECT uid,username AS uuid,email FROM {{uc_members}} WHERE uid=:uid";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':uid', $uid);
        $row = $cmd->queryRow();
        if($row)
        {
            $sql = "SELECT avatar_url,nick_name,mobile FROM {{uc_members_info}} WHERE uid=:uid";
            $cmd = Yii::app()->db->createCommand($sql);
            $user = $cmd->bindValue(':uid', $uid)->queryRow();
            if(!empty($user['avatar_url']))
            {
                //http://img1.red16.com/avatar_phone/origin/5/52c0b85ed5fe69fe68d56edc2fae2286.jpg
                //http://avatar-img.b0.upaiyun.com/upload/avatar_phone/7/738cca6b072f7304cd987a5e9805ec22.jpg

                //获取cdn_img_switch状态
                $CdnImg = new CdnImg;
                $cnd_img_switch = $CdnImg->CdnImgSwitch(0);
                if($cnd_img_switch==1)
                {
                   
					if(strpos($user['avatar_url'], 'http') !==FALSE){
						$img=$user['avatar_url'];
					}else{
						 $img = Yii::app()->params['yun_img']."/".$user['avatar_url'].'!171x181';
					}
                }else{
                    $img = '';
                }
            }else{
                $img = '';
            }

            $row['uid'] = strval($row['uid']); 
            $row['uuid'] = strval($row['uuid']); 
            $row['email'] = strval($row['email']); 
            $row['avatar_url'] = strval($img);
            $row['nick_name'] = strval($user['nick_name']);
			$row['isvip'] = $this->judgeIsTestUser($uid);
			$row['bindstatus'] = $this->getBindStatus($uid);
			
			$model = isset($user['mobile']) ? strval($user['mobile']) : '';
			$row['mobile'] = $model ? $model : '';

            $json_array = array(
                'status'=>'1',
                'data'=>$row,
            );
        }else{
            $json_array = array(
                'status'=>'0',
            );
        }
        return $json_array;
    }

	/**
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
		
		$sql = "SELECT bind_sina,bind_qq FROM {{uc_third_users}} WHERE uid=:uid";
		$cmd = Yii::app()->db->createCommand($sql);
		$row = $cmd->bindValue(':uid', $uid)->queryRow();
		
		if($row){
			$array['qq'] = strval($row['bind_qq']);
			$array['sina'] = strval($row['bind_sina']);
		}

		return $array;
	}
	
	private function judgeIsTestUser($uid)
	{
		$sql = "SELECT extgroupids FROM {{common_member}} WHERE uid=:uid AND extgroupids LIKE '21' ";
		$cmd = Yii::app()->db_bbs->createCommand($sql);
		$row = $cmd->bindValue(':uid', $uid)->queryRow();
		$is_vip = $row ? '1' : '0';
		
		return $is_vip;
	}

}
?>