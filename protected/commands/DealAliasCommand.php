<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
include('Signfork.class.php');
include_once('py_class.php');

class DealAliasCommand extends CConsoleCommand
{

    private $processNum = 5;

    private function getParams($count)
    {
        $options  = array(
            'page'=>1,
        );
        
        $pageSize = 1000;
        $pages = ceil($count/$pageSize); 
        
        $args = array();
        for($i=1;$i<=$pages;$i++){
            $args[$i] = array_merge($options,array('page'=>$i));
        }
        
        $args = array_chunk($args, ceil(count($args)/$this->processNum));

        return $args;
    }
    
    private $db=null;
    
    public function actionRun()
    {
        $sql = "SELECT COUNT(*) cnt FROM {{new_video}}";
        $cmd = Yii::app()->db->createCommand($sql);
        $row = $cmd->queryRow();
        $count = $row['cnt'];
        $this->db = Yii::app()->db;
        

        //read all
        
        echo "read all data...\n";
        
        $sql = "SELECT id,`name`,is_show,`free`,is_show_source,source,alias FROM {{new_video}} ORDER BY id";
        $cmd = $this->db->createCommand($sql);
        $rows = $cmd->queryAll();
        
        //
        echo "chunk array ...\n";
        $args = array_chunk($rows, ceil(count($rows)/$this->processNum));
        unset($rows);
        
        //$args = $this->getParams($count);
        //echo count($args);exit;
        $Signfork = new Signfork();
        $results = $Signfork->run($this,$args);
        unset($args);
    }    

    public function __fork($cid,$args){
        foreach ($args as $params) {
            //$this->getData($cid,$params);   //别名
            //$this->dealLetter($cid,$params);//字母索引
            //$this->createAliasLetter($cid,$params);//别名字母
            //$this->DealNeedField($pid, $params);//获取需要的字段
            //echo join(",",$params) . "\n";
            $this->CreateDuoYinZi($pid, $params);//多音字
        }
        return "";
    }
    
    /**
     * 生成别名文件
     */
    public function getData($pid,$params)
    {
        $filename = '/home/chaodalong/alias1';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $star = ($page-1)*1000;
        
        $sql = "SELECT alias,soku_url FROM {{soku_info}} LIMIT $star,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();
        
        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $pattern = "/(\'|\")/";
                $alias = preg_replace($pattern, '', $val['alias']);
                $soku_url = $val['soku_url'];
                $sql_update = "UPDATE pt_new_video SET alias='{$alias}' WHERE soku_url='{$soku_url}';\n";
                fputs($fp, $sql_update);
            }
            echo "page:$page----ok-------\n";
        }else{
             echo "page:$page---have not video\n";
        }        
    }
    
    /**
     * 读取new_video表letter插入letter表
     */
    private function dealLetter($pid,$params)
    {
        $filename = '/home/chaodalong/letterupdate';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $star = ($page-1)*1000;
        
        $sql = "SELECT id,letter,`free`,is_show,is_show_source,source FROM {{new_video}} LIMIT $star,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();
        
        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $letter = $val['letter'];
                $vid = $val['id'];
                $free = $val['free'];
                $is_show = $val['is_show'];
                $is_show_source = $val['is_show_source'];
                $source = $val['source'];
                
                if(!empty($letter))
                {
                    $sql = "INSERT INTO pt_letter SET letter='{$letter}',vid='{$vid}',`free`='{$free}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}';\n";
                    fputs($fp, $sql);
                }
            }
            echo "page:$page----ok-------\n";
        }else{
             echo "page:$page---have not video\n";
        }     
    } 
    
    
    /**
     * 生成别名的letter索引
     */
    private function createAliasLetter($pid,$params)
    {
        $filename = '/home/chaodalong/aliasletter';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $star = ($page-1)*1000;
        
        $sql = "SELECT id,alias,is_show,is_show_source,`free`,source FROM {{new_video}} LIMIT $star,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();
        
        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $alias = $val['alias'];
                $vid = $val['id'];
                $free = $val['free'];
                $is_show = $val['is_show'];
                $is_show_source = $val['is_show_source'];
                $source = $val['source'];
                
                if(!empty($alias))
                {
                    $arr_bieming = explode('/', $alias);
                    if(!empty($arr_bieming))
                    {
                        foreach($arr_bieming as $k=>$v)
                        {
                            $py = new py_class;
                            $letter = $py->str2py($v);
                            if(!empty($letter)){
                                $sql = "INSERT INTO pt_letter SET letter='{$letter}',vid='{$vid}',`free`='{$free}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}';\n";
                                fputs($fp, $sql);
                            }
                        }
                    }
                }
            }
            echo "page:$page----ok-------\n";
        }else{
             echo "page:$page---have not video\n";
        } 
    }
    
    /**
     * 把需要的字段数据放入到letter表中
     */
    private function DealNeedField($pid,$params)
    {
        $filename = '/home/chaodalong/source';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $star = ($page-1)*1000;
        
        $sql = "SELECT id,source FROM {{new_video}} LIMIT $star,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();
        
        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $vid = $val['id'];
                $source = $val['source'];

                $sql = "UPDATE pt_letter SET source='{$source} 'WHERE vid='{$vid}';\n";
                fputs($fp, $sql);
            }
            echo "page:$page----ok-------\n";
        }else{
             echo "page:$page---have not video\n";
        } 
    }
    
    private function GetRows(){
        
    }
    
    /**
     * 生成多音字
     */
    private function CreateDuoYinZi($pid,$rows)
    {
        $filename = '/home/chaodalong/nameduoyinzi';
        $fp = fopen($filename, 'a+');
       
        if($rows)
        {

            $vid = $rows['id'];
            $str = $rows['name'];
            $is_show = $rows['is_show'];
            $is_show_source = $rows['is_show_source'];
            $free = $rows['free'];
            $source = $rows['source'];
            $alias = $rows['alias'];
            
            /*
            if(!empty($alias))
            {
                $arr_bieming = explode('/', $alias);
                if(!empty($arr_bieming))
                {
                    foreach($arr_bieming as $k=>$v)
                    {
                        $str = $v;
                        //获取多音字
                        $need_arr = array();
                        if(!empty($str)){
                            $need_arr = $this->DealDuoYinZi($str);
                        }
            
            
                        //写入到文件里
                        if(!empty($need_arr))
                        {
                            if(is_array($need_arr))
                            {
                                for($m=0;$m<count($need_arr);$m++)
                                {
                                    //转换成小写
                                    $letter = strtolower($need_arr[$m]);
                                    //$result = $this->chkletter($vid, $letter);
                                    if($result)
                                    {
                                        //不处理
                                    }else{
                                        $sql = "INSERT INTO pt_letter SET vid='{$vid}',letter='{$letter}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}',`free`='{$free}';\n";
                                        fputs($fp, $sql);
                                    }
                                }
                            }
                            
                        }
                        
                        echo $rows['id']."----ok----- \n";
                    }
                }
            }*/
            
            
            //获取多音字
            $need_arr = array();
            if(!empty($str)){
                $need_arr = $this->DealDuoYinZi($str);
            }


            //写入到文件里
            if(!empty($need_arr))
            {
                if(is_array($need_arr))
                {
                    for($m=0;$m<count($need_arr);$m++)
                    {
                        //转换成小写
                        $letter = strtolower($need_arr[$m]);
                        $result = $this->chkletter($vid, $letter);
                        if($result)
                        {
                            //不处理
                        }else{
                            $sql = "INSERT INTO pt_letter SET vid='{$vid}',letter='{$letter}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}',`free`='{$free}';\n";
                            fputs($fp, $sql);
                        }
                    }
                    exit;
                }
                
            }
            
            echo $rows['id']."----ok----- \n";
        }
        
    }


    /**
     * 检查letter表中是否有此条记录
     * @param $vid $letter
     */
    private function chkletter($vid, $letter)
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
     * 处理多音字
     * @param $str
     */
    private function DealDuoYinZi($str)
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
                                $arr_need[$i][] = $letter;
                            }
                        }
                    }
                }else{
                    //字母
                    $arr_need[$i][] = $result;
                }
                $i++;
            }else{
                $i = $length;
            }
        }

        if(!empty($arr_need))
        {
            $arr_need = $this->Arrays2Descartes($arr_need);
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
    
    
    public function actionLoadPinyin(){
        $this->Loadf();
    }
    
    /**
     * 把字典写入到redis中
     */
    private function Loadf()
    {
        $pyfile = Yii::getPathOfAlias('ext').'/ZSplayPY/GBK_Table.txt';
        $handle = fopen($pyfile, 'rt');
        if(!$handle)
            return false;
            
        while(!feof($handle))
        {    
            $tmp = fgets($handle);
            if($tmp !== '')
            {
                $key = mb_substr($tmp, 0, 1, "utf8");
                $val = mb_substr($tmp, 1, mb_strlen($tmp,"utf8")-2, "utf8");
                if(empty($key))
                    continue;
                else
                {
                    //写入redis
                    if(RedisHandler::kv_set($key,$val,0)){
                        echo "----$key------ok---.\n";
                    }else{
                        echo "----$key------faild---.\n";
                    }
                }
            }
        }
        
        fclose($handle);
        
        return true;
    }
    
}























?>