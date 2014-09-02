<?php
class CheckConfigCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        //ini_set("display_errors",1);
        //ini_set("error_reporting",2047);
        echo "begin..\n";
        $filename = "/tmp/sourcecfg";
        $fp = @fopen($filename, 'w+');
        $time_start = date('Y-m-d h:i:s',time());
        @fputs($fp, $time_start."\n");
 
        $check = new Checkcfg;
        $check->DoCheck();
        
        $time = date('Y-m-d h:i:s',time());
        $time_end = "\n".$time;
        @fputs($fp, $time_end."\n");
        @fclose($fp);
        echo "end..\n";
    }
    
    
}

?>