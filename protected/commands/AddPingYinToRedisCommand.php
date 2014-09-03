<?php
class AddPingYinToRedisCommand extends CConsoleCommand{
    public function actionIndex()
    {
        $filename = '/var/htdocs/puti.tv/www/protected/commands/GBK_Table.txt';
        $fp = fopen($filename, 'r');
        if($fp){
            while(!feof($fp)){
                $str = fgets($fp);
                $key = mb_substr($str, 0, 1, 'utf-8');
                $arr = explode($key, $str);
                $str_val = $arr[1];
                RedisHandler::kv_set($key, $str_val);
                echo $key.'=='.$str_val."\n";
            }
            echo 'ok';
        }
        
        
    }
    
}


?>