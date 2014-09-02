<?php

class UserInfo{
    
    
    /**
     * 获取用户基本信息
     */
     public function getUserInfo($uid,$size='middle'){
         
             //$key_name = "getUserinfo_".$uid."_".$size;
             //$key_name = "userinfo_".$uid;
             //$res = RedisHandler::kv_get($key_name);
             //var_dump($res);
             //if(!$result = RedisHandler::hash_get($key_name)){         
               $fields = array('address','tel','QQ','MSN');
               $result = UserInfo::_getUserInfo($fields, $uid,$size);  
               //$result['stat'] = 333;             
               //RedisHandler::hash_set($key_name,$result);
             //}
             return $result;               
    }
    
    
    /**
     * 删除用户
     */
    public function delUserInfo($uid){
        if($uid=intval($uid)){
            $member = Yii::app()->db_user->createCommand();
            $result = $member->update('{{members_info}}',array('is_del'=>1),'uid=:uid',array(':uid'=>$uid));
            return $result;
        }
        else return false;
    }
    
    /**
     * 修改用户资料
     */
    public function updateUserInfo($info,$uid){
       
       //var_dump($info);die;
        if($uid=intval($uid)){
            
            $nick_name = $info['nick_name'];
            $real_name = $info['real_name'];
            $province = $info['province'];
            $city = $info['city'];
            $location = $info['location'];
            $sex = $info['sex'];
            $bir_year = $info['bir_year'];
            $bir_month = $info['bir_month'];
            $bir_day = $info['bir_day'];
            $blog_url = $info['blog_url'];
            $QQ = $info['QQ'];
            $MSN = $info['MSN'];
            $abstruct = $info['abstruct'];
            $address = $info['address'];
            $postal_code = $info['postal_code'];
            $tel = $info['tel'];
            $domain_name = $info['domain_name'];
            $comment_area = $info['comment_area'];
            $private_area = $info['private_area'];
            $honor_area = $info['honor_area'];
            $mobile = $info['mobile'];
                     
            $member = Yii::app()->db_user->createCommand();
            $result = $member->update('{{members_info}}',array('nick_name'=>$nick_name,
                                                             'real_name'=>$real_name,
                                                             'province'=>$province,
                                                             'city'=>$city,
                                                             'location'=>$location,
                                                             'sex'=>$sex,'bir_year'=>$bir_year,'bir_month'=>$bir_month,'bir_day'=>$bir_day,
                                                             'blog_url'=>$blog_url,'QQ'=>$QQ,'MSN'=>$MSN,'abstruct'=>$abstruct,
                                                             'address'=>$address,'postal_code'=>$postal_code,'tel'=>$tel,'domain_name'=>$domain_name,
                                                             'comment_area'=>$comment_area,'private_area'=>$private_area,'honor_area'=>$honor_area,'mobile'=>$mobile),'uid=:uid',array(':uid'=>$uid));

          
            return $result;
        } else{
            return false;
        }        
    }

    /**
     * 修改用户积分
     */
    public function updateScore($score,$uid){
        
        if($uid = intval($uid)){
            $member = Yii::app()->db_user->createCommand();
            $result = $member->update('{{members_info}}',array('score'=>$score),'uid=:uid',array(':uid'=>$uid));        
            return $result;
        }else{
            return false;
        }
    }
    
    /**
     * 查看昵称是否唯一
     */
    public function CheckUname($uid,$nick_name){
 
       $sql = "SELECT count(*) cnt FROM {{members_info}} WHERE nick_name=:nick_name AND uid<>:uid";
       $result = Yii::app()->db_user->createCommand($sql)->bindValue(":nick_name",$nick_name)->bindValue(":uid",$uid)->queryRow();
       if($result)return $result['cnt'];
       return 0;
   }
    
    /**
     * 获取 余额
     */
    public function getBalance($uid){
        if($uid = intval($uid)){

            $sql = "SELECT punica_num FROM {{user_balance}} WHERE user_id=:uid";
            $cmd = Yii::app()->db_pay->createCommand($sql);
            $cmd->bindValue(':uid',$uid);
            $result = $cmd->queryRow();
            return $result;            
        } else {
            return false;
        }
        
         /*$sql = "SELECT COUNT(*) AS cnt
                 FROM m_user_action 
                 WHERE field_id = :album_id AND action = :action AND `type` = :type";
         $cmd = Yii::app()->db->createCommand($sql);
         
         $cmd->bindValues(array(':album_id'=>$id,':action'=>'LIKE',':type'=>'ALBUM'));
         $row = $cmd->queryRow();*/
  
    }
    
    
    
    /**
     * 通过多个uid 获取 用户信息
     */
    public function getMultiInfo($info,$size='middle'){
        if(is_array($info)){
            $result = array();
            foreach($info as $key=>$value){
                if($value = intval($value)){
                    $fields = array('address','tel','QQ','MSN');
                    $result[$value] = UserInfo::_getUserInfo($fields, $value, $size);                    
                 } else {
                     continue;
                 }
            } 
             return $result;      
        } else {
            return false;
        }
    }
    
    /**
     * 获取 粉丝/关注资料
     */
    public function getSilkInfo($info,$size='middle'){
        if(is_array($info)){
            $result = array();
            foreach($info as $key=>$value){
                if($value = intval($value)){

                    $fields = array('address','tel','QQ','MSN');
                    $result[$value] = UserInfo::_getUserInfo($fields, $value,$size);
                    $result[$value]['followers_count'] = UserInfo::followers_count($value);  //粉丝数量
                    $result[$value]['followed_count'] = UserInfo::followed_count($value);  //关注数量
                 } else {
                     continue;
                 }
            } 
             return $result;      
        } else {
            return false;
        }
    }
    
    ////////////////////////以下为最基本方法
     /**
     * 用户基本信息
     */    
    static function _getUserInfo($fields,$uid,$size='middle'){
        if($uid=intval($uid)){
            //$key_name = "_getUserInfo_".$uid."_".$fields."_".$middle;
            //if(!$result = RedisHandler::kv_get($key_name)){
                if(is_array($fields)){
                    $str = ',';
                    foreach($fields as $key=>$value){
                        $str .= $value.',';
                    }
                    $str = substr($str,0,strlen($str)-1);
                }
                
                $sql = "SELECT nick_name,location,sex,bir_year,bir_month,bir_day,blog_url".$str."
                        FROM {{members_info}} 
                        WHERE uid=:uid AND is_del=0";
                        
                $cmd = Yii::app()->db_user->createCommand($sql);
                $cmd->bindValue(':uid',$uid);
                $result = $cmd->queryRow();
                
                $result['email'] = UserInfo::email($uid);
                $result['face'] = UserInfo::avatar($uid,$size);
                //RedisHandler::kv_set($key_name,$result);
           // }          
            return $result;
        } else {
            return false;
        } 
    }
    
    /**
     * 获取头像
     */
    static public function avatar($uid,$size="middle"){
        
        $rsize = isset(Yii::app()->params['avatar_size'][$size]) ? Yii::app()->params['avatar_size'][$size] : 80; 
        $sql ="SELECT avatar_url FROM {{members_info}} WHERE uid=:uid";
        $row = Yii::app()->db_user->createCommand($sql)->bindValue(':uid', $uid)->queryRow();
        //判断是否有该头像
        if(!$row['avatar_url']){
            $op = ceil($uid/2000);
            $row['avatar_url'] = 'avatar/origin/'.$op.'/'.md5(time()).'.jpg';
        }             
        $avatar_url = str_replace('origin', $rsize.'x'.$rsize, $row['avatar_url']);        
        $url_template = Yii::app()->params['imgUrl'].$avatar_url;
        //$url_template = "http://t.16tree.com/data/uploads/avatar/$uid/$size.jpg";
        return $url_template;
    }

    /**
     * 获取微博数
     */
    static public function weibo_count($uid){
       if(!$uid)return "0";
       $sql = "SELECT COUNT(*) cnt FROM {{weibo}} WHERE uid=:uid";
       $cmd = Yii::app()->db_weibo->createCommand($sql);
       $row = $cmd->bindValue(":uid",$uid)->queryRow();
       return $row ? $row['cnt'] : 0;
   }
   /**
    * 用户微博收藏数量
    */
   static public function favorite_count($uid){
       if(!$uid)return "0";
       $sql = "SELECT COUNT(*) cnt FROM {{weibo_favorite}} WHERE uid=:uid";
       $cmd = Yii::app()->db_weibo->createCommand($sql);
       $row = $cmd->bindValue(":uid",$uid)->queryRow();
       return $row ? $row['cnt'] : 0;
   }
   
   /**
    * 用户粉丝数量
    */
   static public function followers_count($uid){
       if(!$uid)return "0";
       $sql = "SELECT COUNT(*) cnt FROM {{weibo_follow}} WHERE fid=:uid";
       $cmd = Yii::app()->db_weibo->createCommand($sql);
       $row = $cmd->bindValue(":uid",$uid)->queryRow();
       return $row ? $row['cnt'] : 0;
   }
   
   /**
    * 用户关注人数量
    */
   static public function followed_count($uid){
       if(!$uid)return "0";
       $sql = "SELECT COUNT(*) cnt FROM {{weibo_follow}} WHERE uid=:uid";
       $cmd = Yii::app()->db_weibo->createCommand($sql);
       $row = $cmd->bindValue(":uid",$uid)->queryRow();
       return $row ? $row['cnt'] : 0;
   }
   
   /**
    * 获取用户email
    */
   static public function email($uid){
       if(!$uid)return "0";
       $sql = "SELECT email FROM {{user}} WHERE uid=:uid ";
       $cmd = Yii::app()->db_weibo->createCommand($sql);
       $cmd->bindValue(':uid',$uid);
       $row = $cmd->queryRow();
       return $row['email'];
                
   }
   
}
