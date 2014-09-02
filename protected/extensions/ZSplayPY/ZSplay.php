<?php

/////////////////////////////////////////////////////////////////////////
// PHP ZSplay by Rendy
//
// 说明：
// 存在文件中的伸展树，支持插入，查找，不支持删除
/////////////////////////////////////////////////////////////////////////

include_once('../lib/FirePHP/fb.php');

class ZSplay {
   
/////////////////////////////////////////////////////////////////////////
// 常量

    const INVALID_POS = -1;
   
/////////////////////////////////////////////////////////////////////////
// 成员变量   

    private $file = false;
    private $size = 4;
   
/////////////////////////////////////////////////////////////////////////
// 构造、析构函数   
   
    function __construct()
    {
        FB::log("ZSplay func_b __construct()");
       
        $path = "splay.dic";
        $isExist = file_exists($path);
        $mode = ($isExist ? "r+b" : "w+b");
        $this->file = fopen($path, $mode);
       
        if($isExist)
        {
            $this->size = filesize($path);
            //如果是r+方式打开，必须调用如下代码，否则fseek无效
            fseek($this->file, 0, SEEK_END);
        }
        else
            $this->put_root(self::INVALID_POS);
           
        FB::log("ZSplay func_e __construct()");
    }
    function __destruct()
    {
        FB::log("ZSplay func_b __destruct() begin");
       
        if($this->file)
        {
            fflush($this->file);
            fclose($this->file);
        }
           
        FB::log("ZSplay func_e __destruct() end");
    }
   
/////////////////////////////////////////////////////////////////////////
// public函数

    public function find($k)
    {
        FB::log("ZSplay func_b find($k)");
       
        $root = $this->get_root();
        $root = $this->splay($k, $root);
        $this->put_root($root);
        $key = $this->get_key($root);
        if($key == $k)
        {
            FB::log("ZSplay func_e find($k) ".__LINE__);
            return $this->get_data($root);
        }
       
        FB::log("ZSplay func_e find($k) ".__LINE__);
        return 'not find';
    }
   
    public function insert($k, $d)
    {
        FB::log("ZSplay func_b insert($k)");
       
        $n = self::INVALID_POS;
       
        $root = $this->get_root();
        if ($root == self::INVALID_POS)
        {
            $n = $this->new_node($k, $d);
            $this->put_root($n);
            FB::log("ZSplay func_e insert($k) ".__LINE__);
            return true;
        }
       
        $root = $this->splay($k, $root);
        if ($k < $this->get_key($root))
        {
            FB::log("ZSplay if <  ".__LINE__);
           
            $n = $this->new_node($k, $d);
            $this->put_left($n, $this->get_left($root));
            $this->put_right($n, $root);
            $this->put_left($root, self::INVALID_POS);
            $this->put_root($n);
        }
        else if ($k > $this->get_key($root))
        {
            FB::log("ZSplay if > ".__LINE__);
           
            $n = $this->new_node($k, $d);
            $this->put_right($n, $this->get_right($root));
            $this->put_left($n, $root);
            $this->put_right($root, self::INVALID_POS);
            $this->put_root($n);
        }
        else
        {
            FB::log("ZSplay if = ".__LINE__);
           
           
            $this->put_root($root);
        }
   
        FB::log("ZSplay func_e insert($k) ".__LINE__);
        return true;
    }
   
    public function is_empty()
    {
        if($this->get_root() == self::INVALID_POS)
            return true;
           
        return false;
    }
   
/////////////////////////////////////////////////////////////////////////
// private函数
   
    //★★★核心函数★★★
    private function splay($k, $t)
    {
        FB::log("ZSplay func_b splay($k, $t)");
       
        $lh = $rh = $lt = $rt = self::INVALID_POS;
       
        if ($t == self::INVALID_POS)
        {
            FB::log("ZSplay func_e splay($k, $t) ".__LINE__);
            return $t;
        }

        while(true)
        {
            if ($k < $this->get_key($t))
            {
                $tmpl = $this->get_left($t);
                if ($tmpl != self::INVALID_POS && $k < $this->get_key($tmpl))
                {
                    $t = $this->r_rotate($t);
                }
                if ($this->get_left($t) == self::INVALID_POS)
                {
                    break;
                }
   
                //右链接
                if ($rt == self::INVALID_POS)//右树空
                {
                    $rh = $t;
                }
                else            //右树不空
                {
                    $this->put_left($rt, $t);
                }
                $rt = $t;
                $t = $this->get_left($t);
                $this->put_left($rt, self::INVALID_POS);
            }
            else if ($k > $this->get_key($t))
            {
                $tmpr = $this->get_right($t);
                if ($tmpr != self::INVALID_POS && $k > $this->get_key($tmpr))
                {
                    $t = $this->l_rotate($t);
                }
                if ($this->get_right($t) == self::INVALID_POS)
                {
                    break;
                }
   
                //左链接
                if ($lt == self::INVALID_POS)//左树空
                {
                    $lh = $t;
                }
                else            //左树不空
                {
                    $this->put_right($lt, $t);
                }
                $lt = $t;
                $t = $this->get_right($t);
                $this->put_right($lt, self::INVALID_POS);
            }
            else
            {
                break;
            }
        }
   
        if ($lt != self::INVALID_POS)
            $this->put_right($lt, $this->get_left($t));
        if ($rt != self::INVALID_POS)
            $this->put_left($rt, $this->get_right($t));
        if ($lh != self::INVALID_POS)
            $this->put_left($t, $lh);
        if ($rh != self::INVALID_POS)
            $this->put_right($t, $rh);
           
        FB::log("ZSplay func_e splay($k, $t) ".__LINE__);
        return $t;
    }
    //左、右旋转
    private function r_rotate($t)
    {
        FB::log("ZSplay func_b r_rotate($t)");
       
        $y = $this->get_left($t);
        $this->put_left($t, $this->get_right($y));
        $this->put_right($y, $t);
        $t = $y;
   
        FB::log("ZSplay func_e r_rotate($t) ".__LINE__);
        return $t;
    }
    private function l_rotate($t)
    {
        FB::log("ZSplay func_b l_rotate($t)");
       
        $y = $this->get_right($t);
        $this->put_right($t, $this->get_left($y));
        $this->put_left($y, $t);
        $t = $y;
       
        FB::log("ZSplay func_e l_rotate($t) ".__LINE__);
        return $t;
    }
   
    //取值、设值函数
    private function get_root() {
        return $this->get_number(0);
    }
    private function put_root($p) {
        $this->put_number(0, $p);
    }
   
    private function get_left($p) {
        return $this->get_number($p);
    }
    private function get_right($p) {
        return $this->get_number($p+4);
    }
    private function put_left($p, $l) {
        $this->put_number($p, $l);
    }
    private function put_right($p, $r) {
        $this->put_number($p+4, $r);
    }   
    private function get_key($p) {
        $len = $this->get_number($p+8);
        return $this->get_str($p+16, $len);
    }   
    private function put_key($p, $k) {
        $len = strlen($k);
        $this->put_number($p+8, $len);
        $this->put_str($p+16, $k);
    }
    private function get_data($p) {
        $begin = $p+16+$this->get_number($p+8);
        $len = $this->get_number($p+12);
        return $this->get_str($begin, $len);
    }   
    private function put_data($p, $d) {
        $begin = $p+16+$this->get_number($p+8);
        $len = strlen($d);
        $this->put_number($p+12, $len);
        $this->put_str($begin, $d);
    }
   
    //在文件中新增一个记录
    private function new_node($k, $d)
    {
        $r = $this->size;
        $this->put_left($this->size, self::INVALID_POS);
        $this->put_right($this->size, self::INVALID_POS);
        $this->put_key($this->size, $k);
        $this->put_data($this->size, $d);
       
        $this->size += (16+strlen($k)+strlen($d));
        return $r;
    }
   
    private function get_str($pos, $len)
    {
        if(fseek($this->file, $pos) == -1)
            return false;
        $buf = fread($this->file, $len);
        return $buf;
    }
   
    private function put_str($pos, $str)
    {
        if(fseek($this->file, $pos) == -1)
            return false;
        fwrite($this->file, $str, strlen($str));
        return true;
    }
       
    //从数据区中读一个整数
    private function get_number($pos)
    {
        $r = 0;
        settype($pos, "integer");
        if(fseek($this->file, $pos) == -1)
            return false;
           
        $buf = fread($this->file, 4);
        $array = unpack ("L", $buf);
        $r = $array[1];
       
        //FB::trace("get_number r ".$r);
       
        return $r;
    }
    //向数据区中写一个整数
    public function put_number($pos, $n)
    {
        //FB::trace("put_number n ".$n);
       
        settype($n, "integer");
        settype($pos, "integer");
        if(fseek($this->file, $pos) == -1)
            return false;
           
        $buf = pack ("L", $n);
        fwrite($this->file, $buf);
        return true;
    }
   
/////////////////////////////////////////////////////////////////////////
// 显示树的函数
// displayl 所有节点按顺序显示
// displayt 显示每个节点

    public function show($istree = false)
    {
        if($istree)
            $this->displayt($this->get_root());
        else
            $this->displayl($this->get_root());
    }
   
    private function displayl($t)
    {
        if($t<=0 || $t>=$this->size)
        {
            echo "Out of index $t <br>";
            exit(0);
        }
           
        if ($this->get_left($t) != self::INVALID_POS)
            $this->displayl($this->get_left($t));
       
        echo $this->get_key($t)." ";
       
        if ($this->get_right($t) != self::INVALID_POS)
            $this->displayl($this->get_right($t));
    }
   
    private function displayt($t)
    {
        if($t<=0 || $t>=$this->size)
        {
            echo "Out of index $t <br>";
            exit(0);
        }
       
        $l = $r = -1;
        if($this->get_left($t) != -1)
            $l = $this->get_key($this->get_left($t));
           
        if($this->get_right($t) != -1)
            $r = $this->get_key($this->get_right($t));
       
        echo " ".$this->get_key($t)."<br>";
        echo $l." ".$r."<br><br>";
       
        if ($this->get_left($t) != self::INVALID_POS)
            $this->displayt($this->get_left($t));
       
        if ($this->get_right($t) != self::INVALID_POS)
            $this->displayt($this->get_right($t));
    }
}

?>