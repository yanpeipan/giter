<?php

class DateCountCommand extends CConsoleCommand{
    public function actionRun(){
        Yii::import("ext.punica.puti.DateCount");
        $count = new DateCount();
        $file = "/tmp/dateCountLog";
        $n = 1;
        
        while(1){
            if(is_file($file)){
                $n = file_get_contents($file);
            }
            $time=date('Y-m-d',time());
            $info = $count->Date($time);
            file_put_contents($file, intval($n)+1);
            sleep(60);
        }
        
    }
}