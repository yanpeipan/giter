<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
class DealLetterCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $filename = "/tmp/letterlog";
        $fp = @fopen($filename, 'w+');
        $time_start = date('Y-m-d h:i:s',time());
        @fputs($fp, $time_start."\n");
        
        $Letter = new Letter; 
        $Letter->ChkAction();
        
        $time = date('Y-m-d h:i:s',time());
        $time_end = "\n".$time;
        @fputs($fp, $time_end."\n");
        @fclose($fp); 
    }

}























?>