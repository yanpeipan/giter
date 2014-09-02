<?php

class EditVideoCommand extends CConsoleCommand{
    public function actionIndex(){
        Yii::import("ext.redis.VideoRedis");
        $redls = new VideoRedis();
        $info = $redls->EditAction();
    }
}