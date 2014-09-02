<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
include('Signfork.class.php');

class AddQuanPinCommand extends CConsoleCommand
{

    private function getParams($count)
    {
        $processNum = 1;
        
        $options  = array(
            'page'=>1,
        );
        
        $pageSize = 1000;
        $pages = ceil($count/$pageSize); 
        
        $args = array();
        for($i=1;$i<=$pages;$i++){
            $args[$i] = array_merge($options,array('page'=>$i));
        }
        
        $args = array_chunk($args, ceil(count($args)/$processNum));

        return $args;
    }
    
    public function actionRun()
    {
        $sql = "SELECT COUNT(*) cnt FROM {{v_list}}";
        $cmd = Yii::app()->db->createCommand($sql);
        $row = $cmd->queryRow();
        $count = $row['cnt'];
        
        $args = $this->getParams($count);

        $Signfork = new Signfork();
        $results = $Signfork->run($this,$args);
    }    

    public function __fork($cid,$args){
        echo "pid:$cid .... \n";

        foreach ($args as $params) {
            $this->getData($pid, $params);
        }
        return "";
    }
    
    /**
     * 生成别名文件
     */
    public function getData($pid,$params)
    {
        $filename = '/home/chaodalong/UpLetterDuoYinZi';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $start = ($page-1)*1000;
        
        $sql = "SELECT id,alias,name FROM {{v_list}} LIMIT $start,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();

        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $letter_need = "";
                
                $vid    = $val['id'];
                $alias = $val['alias'];
                $name  = $val['name'];

                //处理name多音字全拼
                if(!empty($name))
                {
                    $letters = $this->DealNameDuoYinZi($val);
                    foreach($letters as $v){
                        $letter = $v;
                        if(!empty($letter)){
                            //长度
                            $letter = $this->remove($letter);
                            if(empty($letter_need)){
                                $letter_need .= $letter;
                            }else{
                                $letter_need .= " ".$letter;
                            }
                        }
                    }
                    //echo $vid.":".$letter_need."\n";
                }

                //处理alias多音字全拼
                if(!empty($alias))
                {
                    $letters = $this->DealAliasDuoYinZi($val);
                    foreach($letters as $val_letters){
                        foreach($val_letters as $v){
                            $letter = $v;
                            if(!empty($letter)){
                                //长度
                                $letter = $this->remove($letter);
                                if(empty($letter_need)){
                                    $letter_need .= $letter;
                                }else{
                                    $letter_need .= " ".$letter;
                                }
                            }
                        }
                    }
                    //echo $vid.":".$letter_need."\n";
                }

                //更新letter_all表
                if(!empty($letter_need)){
                    $sql = "SELECT letter,id FROM {{letter_all}} WHERE vid=:vid";
                    $cmd = Yii::app()->db->createCommand($sql);
                    $cmd->bindValue(':vid', $vid);
                    $row = $cmd->queryRow();
                    if($row){
                        $id = $row['id'];
                        $letter = $row['letter'];
                        $letter_need = empty($letter) ? $letter_need :$letter." ".$letter_need;
                        
                        $values = array(
                            ':id'=>$id,
                            ':letter'=>$letter_need,
                        );

                        $sql = "UPDATE {{letter_all}} SET letter=:letter WHERE id=:id";
                        $cmd = Yii::app()->db->createCommand($sql);
                        $cmd->bindValues($values);
                        if($cmd->execute()){
                            echo $id.":".$letter_need."\n";   
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
     * 获取name的多音字
     * @param 
     */
    private function DealNameDuoYinZi($video)
    {
        $name = $video['name'];
        
        //获取多音字
        $arr = $this->GetDuoYinZi($name);

        return $arr;
    }

    /**
     * 获取别名的多音字
     * @param $video
     */
    private function DealAliasDuoYinZi($video)
    {
        $alias = $video['alias'];

        $arr = array();
        if(!empty($alias))
        {
            $arr_bieming = explode('/', $alias);
            if(!empty($arr_bieming))
            {
                foreach($arr_bieming as $k=>$v)
                {
                    $str = $v;
                    //获取多音字
                    if(!empty($str)){
                        $arr[] = $this->GetDuoYinZi($str);;
                    }
                }
            }
        }
        return $arr;
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
     * 获取多音字
     * @param string $str
     * return array
     */
    private function GetDuoYinZi($str)
    {
        $s   = preg_replace("/ /", '',$str);//处理小键盘下的空格
        $s   = preg_replace("/(|\·|\・|\▪|\:|\s|\~|\`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\-|\+|\=|\{|\}|\[|\]|\||\\|\:|\;|\"|\'|\<|\,|\>|\.|\?|\/)/is","",$s);

        //获取总长度
        $arr_need = array();
        $length = mb_strlen($s);
        $i = 0;
        while($i<$length)
        {
            $result = mb_substr($s, $i, 1,'utf-8');
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
                            if(!in_array($v, $arr_need[$i])){
                                $arr_need[$i][] = strtolower($v);
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
    
    private function remove($str)
    {
        $s   = preg_replace("/ /", '',$str);//处理小键盘下的空格
        $s   = preg_replace("/(|\·|\・|\▪|\:|\s|\~|\`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\-|\+|\=|\{|\}|\[|\]|\||\\|\:|\;|\"|\'|\<|\,|\>|\.|\?|\/)/is","",$s);
        return $s;
    }
    
 
}
?>