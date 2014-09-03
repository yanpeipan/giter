<?php
    /////////////////////////////////////////////////////////////////////////
    // PHP ZSplayPY by Rendy
    //
    // 说明：
    // 本类是将中文转化为拼音（全拼或首字母），拼音表存储使用了伸展树
    /////////////////////////////////////////////////////////////////////////

class ZSplayPY {

    /////////////////////////////////////////////////////////////////////////
    //成员变量
    private $splay;
    
    /////////////////////////////////////////////////////////////////////////
    //构造函数    
    function __construct()
    {
        $this->splay = new ZSplay;
        if(!$this->Load())
        {
            echo 'Load pin_yin data failed!';
            exit(0);
        }
    }
    
    /////////////////////////////////////////////////////////////////////////
    //获取一个字的拼音，支持多音
    public function GetPy($char)
    {
        $py = array();
        if($this->IsChinese($char))
        {
            $str = $this->splay->find($char);
            $py = explode(',', $str);        //$str格式为“sang,sang4,sang1”
        }
        else        //如果是字母或数字，直接返回  
        {
            $py[0] = $char;
        }
        return $py;
    }
    //获取整句的拼音
    public function GetPys($str)
    {
        for($i=0;$i<mb_strlen($str, "utf8");$i++)
        {
            $arr[$i] = $this->GetPy(mb_substr($str,$i,1,'utf8'));
        }
        return $this->Arrays2Descartes($arr);
    }
    
    //获取一个字的拼音首字母，支持多音
    public function GetPyF($char)
    {
        $py = array();
        $arr = $this->GetPy($char);
        foreach($arr as $key=>$value)
        {
            $first = mb_substr($value, 0, 1, 'utf8');
            if(!in_array($first, $py))
                array_push($py, $first);
        }
        return $py;
    }    
    //获取整句的拼音首字母
    public function GetPysF($str)
    {
        for($i=0;$i<mb_strlen($str, "utf8");$i++)
        {
            $arr[$i] = $this->GetPyF(mb_substr($str,$i,1,'utf8'));
        }
        return $this->Arrays2Descartes($arr);
    }
    
    //判断字符是否为中文
    private function IsChinese($char)
    {
        if(preg_match("/^[a-z\d]*$/i", $char))  //如果是字母或数字    
        {
            return false;
        }
        return true;
    }
    
    /////////////////////////////////////////////////////////////////////////    
    //从文件读取到内存
    public function Load($pyfile = 'GBK_Table.txt')
    {
        if(!$this->splay->is_empty())
            return true;
            
        if(!$this->Loadf($pyfile))
            return false;
        
        return true;
    }
    
    //将GBK_Table.txt文件中的拼音表读入，并生成splay文件
    private function Loadf($pyfile = 'GBK_Table.txt')
    {
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
                    //echo "key $key val $val <br>";
                    $this->splay->insert($key, $val);
                }
            }
        }
        
        fclose($handle);
        
        return true;
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