<?php
class Letter{
    
    /**
     * 检查pt_action表
     */
    public function ChkAction()
    {
        //删除{{v_tv}}表is_del=1的数据
        Letter::DeleteAction();

        $values = array(
            ':table_name'=>'v_list',
            ':is_del'=>'1',
        );
        $sql = "SELECT vid,action FROM {{action}} WHERE table_name=:table_name AND is_del=:is_del";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
        $rows= $cmd->queryAll();

        if($rows)
        {
            for($i=0;$i<count($rows);$i++)
            {
                $vid     = $rows[$i]['vid'];
                $action  = $rows[$i]['action'];
                
                //删除letter表中对应的数据(更新和加入前都要删除letter对应的字段)
                Letter::DeleteLetter($vid);

                //判断操作类型 (1:add 2:update 3:del)
                if($action==1||$action==2)
                {
                    $video = Letter::GetVideoById($vid);
                    if($video)
                    {
                        //把主表的letter信息插入letter表
                        if(!empty($video['letter'])){
                            Letter::InsertIntoLetter($video);
                        }

                        //处理name多音字
                        if(!empty($video['name']))
                        {
                            Letter::DealNameDuoYinZi($video);
                        }

                        //处理alias多音字 
                        if(!empty($video['alias']))
                        {
                            Letter::DealAliasDuoYinZi($video);
                        }
                        
                        //name和别名导入letter表
                        Letter::AddNameToLetter($video);
                    }
                }
                
                //删除action表中对应的的记录
                Letter::DeleteNewVideoAction($vid);
            }
        }
    }
    
    /**
     * 根据id获取视频信息
     * @param $vid
     */
    private function GetVideoById($vid)
    {
        $sql = "SELECT id,`name`,alias,letter,`free`,is_show,is_show_source,source FROM {{v_list}} WHERE id=:id";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':id', $vid);
        $row = $cmd->queryRow();
        return $row;
    }

    /**
     * 把名字和别名导入letter表
     * @param $video 
     */
    private function AddNameToLetter($video)
    {
        $id   = $video['id'];
        $name = $video['name'];
        $alias= $video['alias'];
        $free = $video['free'];
        $source  = $video['source'];
        $is_show = $video['is_show'];
        $is_show_source = $video['is_show_source'];
        
        $values = array(
            ':vid'=>$id,
            ':letter'=>$name,
            ':free'=>$free,
            ':is_show'=>$is_show,
            ':is_show_source'=>$is_show_source,
            ':source'=>$source,
            ':is_name'=>1,
        );
        
        //电影名字处理
        if(!empty($name))
        {
            $name= preg_replace('/\'/', '', $name);
            $sql = "INSERT INTO {{letter}} SET vid=:vid,letter=:letter,free=:free,is_show=:is_show,is_show_source=:is_show_source,source=:source,is_name=:is_name";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValues($values);
            $cmd->execute();
        }
        
        //别名处理
        $names = explode('/', $alias);
        if(!empty($names))
        {
            for($i=0;$i<count($names);$i++)
            {
                $name = $names[$i];
                if(!empty($name))
                {
                    $name= preg_replace('/\'/', '', $name);
                    $values[':letter'] = $name;
                    $sql = "INSERT INTO {{letter}} SET vid=:vid,letter=:letter,free=:free,is_show=:is_show,is_show_source=:is_show_source,source=:source,is_name=:is_name";
                    $cmd = Yii::app()->db->createCommand($sql);
                    $cmd->bindValues($values);
                    $cmd->execute();
                }
            }
        }
    }
    
    /**
     * 获取name的多音字
     * @param $video
     */
    private function DealNameDuoYinZi($video)
    {
        $vid = $video['id'];
        $name = $video['name'];
        $free = $video['free'];
        $is_show = $video['is_show'];
        $is_show_source = $video['is_show_source'];
        $source = $video['source'];
        
        //获取多音字
        $arr = Letter::GetDuoYinZi($name);

        if(!empty($arr))
        {
            foreach($arr as $key=>$val)
            {
                $letter = $val;
                
                //检查letter是否存在
                $result = Letter::ChkLetterIsExist($vid, $letter);

                if($result)
                {
                    //不处理
                }else{
                    $values = array(
                        ':vid'=>$vid,
                        ':free'=>$free,
                        ':is_show'=>$is_show,
                        ':is_show_source'=>$is_show_source,
                        ':source'=>$source,
                        ':letter'=>$letter,
                    );
                    $sql = "INSERT INTO {{letter}} SET vid=:vid,letter=:letter,free=:free,is_show=:is_show,is_show_source=:is_show_source,source=:source";
                    $cmd = Yii::app()->db->createCommand($sql);
                    $cmd->bindValues($values);
                    $cmd->execute();
                }
            }
        }
    }

    /**
     * 获取别名的多音字
     * @param $video
     */
    private function DealAliasDuoYinZi($video)
    {
        $vid = $video['id'];
        $alias = $video['alias'];
        $free = $video['free'];
        $is_show = $video['is_show'];
        $is_show_source = $video['is_show_source'];
        $source = $video['source'];
        
        if(!empty($alias))
        {
            $arr_bieming = explode('/', $alias);
            if(!empty($arr_bieming))
            {
                foreach($arr_bieming as $k=>$v)
                {
                    $str = $v;
                    //获取多音字
                    $arr = array();
                    if(!empty($str)){
                        $arr = Letter::GetDuoYinZi($str);;
                    }

                    if(!empty($arr))
                    {
                        foreach($arr as $key=>$val)
                        {
                            $letter = $val;
                            
                            //检查letter是否存在
                            $result = Letter::ChkLetterIsExist($vid, $letter);
            
                            if($result)
                            {
                                //不处理
                            }else{
                                $values = array(
                                    ':vid'=>$vid,
                                    ':free'=>$free,
                                    ':is_show'=>$is_show,
                                    ':is_show_source'=>$is_show_source,
                                    ':source'=>$source,
                                    ':letter'=>$letter,
                                );
                                $sql = "INSERT INTO {{letter}} SET vid=:vid,letter=:letter,free=:free,is_show=:is_show,is_show_source=:is_show_source,source=:source";
                                $cmd = Yii::app()->db->createCommand($sql);
                                $cmd->bindValues($values);
                                $cmd->execute();
                            }
                        }
                    }
                }
            }
        }
        
    }


    /**
     * 获取多音字
     * @param string $str
     * return array
     */
    private function GetDuoYinZi($str)
    {
        $s   = preg_replace("/ /", '',$str);//处理小键盘下的空格
        $s   = preg_replace("/(|\・|\▪|\:|\s|\~|\`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\-|\+|\=|\{|\}|\[|\]|\||\\|\:|\;|\"|\'|\<|\,|\>|\.|\?|\/)/is","",$s);
        
        //获取总长度
        $arr_need = array();
        $length = mb_strlen($s);
        $i = 0;
        while($i<$length)
        {
            $result = mb_substr($s, $i, 1,'utf-8');
            //echo $result."\n";
            if(!empty($result))
            {
                if(mb_strlen($result)==3)
                {
                    //获取汉字拼音(包括多音字)(包含日文等)
                    $val = RedisHandler::kv_get($result);
                    if($val)
                    {
                        $arr = explode(',', $val);
                        $arr_need[$i] = array();
                        foreach($arr as $k=>$v)
                        {
                            $letter = substr($v, 0,1);
                            if(!in_array($letter, $arr_need[$i])){
                                $arr_need[$i][] = strtolower($letter);
                            }
                        }
                    }
                }else{
                    //字母
                    $arr_need[$i][] = strtolower($result);
                }
                $i++;
            }else{
                $i = $length;
            }
        }

        if(!empty($arr_need))
        {
            $arr_need = Letter::Arrays2Descartes($arr_need);
        }
        
        return $arr_need;
    }


    /////////////////////////////////////////////////////////////////////////
    //计算多个数组的笛卡尔积
    private function Arrays2Descartes($arr)
    {
        $ret=array();
        $tmp=array();
        
        $tmp = $arr[0];
        for($k=1;$k<count($arr);$k++)
        {
            $pyarr = $arr[$k];
            $n = 0;
            for($i=0;$i<count($tmp);$i++)
            {
                for($j=0;$j<count($pyarr);$j++)
                {
                    $ret[$n] = $tmp[$i].$pyarr[$j];
                    $n++;            
                }
            }
            $tmp = $ret;
        }
        unset($tmp);
        
        return $ret;
    }

    /**
     * 检查letter表信息是否存在
     * @param  $vid
     * @param  $letter
     */
    private function ChkLetterIsExist($vid, $letter)
    {
        $values = array(
            ':vid'=>$vid,
            ':letter'=>$letter,
        );
        $sql = "SELECT id FROM {{letter}} WHERE vid=:vid AND letter=:letter";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
        $row = $cmd->queryRow();
        return $row;
    }


    /**
     * 删除letter表对应的记录
     * @param $vid 
     */
    private function Deleteletter($vid)
    {
        $sql = "DELETE FROM {{letter}} WHERE vid=:vid";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':vid', $vid);
        $cmd->execute();
    }

    /**
     * 把主表的letter信息插入到letter表中
     * @param array $video
     */
    private function InsertIntoLetter($video)
    {
        $vid    = $video['id'];
        $letter = $video['letter'];
        $free   = $video['free'];
        $is_show= $video['is_show'];
        $source = $video['source']; 
        $is_show_source = $video['is_show_source'];
        
        $values = array(
            ':vid'=>$vid,
            ':free'=>$free,
            ':is_show'=>$is_show,
            ':is_show_source'=>$is_show_source,
            ':source'=>$source,
            ':letter'=>$letter,
        );
        
        $sql = "INSERT INTO {{letter}} SET vid=:vid,letter=:letter,free=:free,is_show=:is_show,is_show_source=:is_show_source,source=:source";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
        $cmd->execute();
    }
    
    
    /**
     *删除v_tv表is_del=1的数据
     */
    private function DeleteAction()
    {
        $values = array(
            ':table_name'=>'v_tv',
            ':is_del'=>'1',
        );
        $sql = "DELETE FROM {{action}} WHERE table_name=:table_name AND is_del=:is_del";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
        $cmd->execute();
    }
    
    /**
     * 删除action表中{{v_list}}表的操作
     * @param $vid
     */
    private function DeleteNewVideoAction($vid)
    {
        $values = array(
            ':vid'=>$vid,
            ':table_name'=>'v_list',
            ':is_del'=>'1',
        );
        $sql = "DELETE FROM {{action}} WHERE vid=:vid AND table_name=:table_name AND is_del=:is_del";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
        $cmd->execute();
    }
    
}



?>