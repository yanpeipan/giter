<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
include('Signfork.class.php');

class AddQuanPingCommand extends CConsoleCommand
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
        
        $sql = "SELECT id,alias,name,is_show,is_show_source,source,`free`,play_count,category FROM {{v_list}} LIMIT $start,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();

        if($rows)
        {
            foreach($rows as $key=>$val)
            {
                $vid    = $val['id'];
                $alias = $val['alias'];
                $name  = $val['name'];
                $free  = $val['free'];
                $is_show = $val['is_show'];
                $is_show_source = $val['is_show_source'];
                $source = $val['source'];
                $play_count = $val['play_count'];
                $category = $val['category'];

                //计算权重
                $weights = intval($play_count);
                //查询搜索次数
                $sql = "SELECT COUNT(id) AS cnt FROM {{search_record}} WHERE v_id=:v_id";
                $cmd = Yii::app()->db->createCommand($sql);
                $row = $cmd->bindValue(':v_id',$vid)->queryRow();
                if($row){
                    $weights +=  intval($row['cnt']);
                }
                
                //处理name多音字
                if(!empty($name))
                {
                    $letters = $this->DealNameDuoYinZi($val);
                    for($i=0;$i<count($letters);$i++){
                        $letter = $letters[$i];
                        if(!empty($letter)){
                            //长度
                            $l = mb_strlen($name);
                            
                            $sql = "INSERT INTO pt_letter SET vid='{$vid}',letter='{$letter}',free='{$free}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}',is_name=0,alias_length='{$l}',weights='{$weights}',category='{$category}';\n";
                            fputs($fp, $sql);
                        }
                    }
                    
                }
                
                //处理alias多音字 
                if(!empty($alias))
                {
                    $letters = $this->DealAliasDuoYinZi($val);
                    for($j=0;$j<count($letters);$j++){
                        $letter = $letters[$j][0];
                        if(!empty($letter)){
                            //长度
                            $l = mb_strlen($letter);
                            $sql = "INSERT INTO pt_letter SET vid='{$vid}',letter='{$letter}',free='{$free}',is_show='{$is_show}',is_show_source='{$is_show_source}',source='{$source}',is_name=0,alias_pinyin=1,alias_length='{$l}',weights='{$weights}',category='{$category}';\n";
                            fputs($fp, $sql);
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
                            $letter = substr($v, 0, 1);
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
    
 
}
?>