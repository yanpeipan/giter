<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
include('Signfork.class.php');
include_once('py_class.php');

class AddLetterToOneFieldCommand extends CConsoleCommand
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
        $filename = '/home/chaodalong/UpLetterBieMing';
        $fp = fopen($filename, 'a+');
        
        extract($params);
        $start = ($page-1)*1000;
        
        $sql = "SELECT id FROM {{v_list}} LIMIT $start,1000";
        $cmd = Yii::app()->db->createCommand($sql);
        $rows= $cmd->queryAll();
        
        if($rows){
            foreach($rows as $val){
                $id = $val['id'];
                $values = array(
                    ':vid'=>$id,
                    ':is_name'=>0,
                );
                $sql = "SELECT letter FROM {{letter}} WHERE vid=:vid AND is_name=:is_name";
                $cmd = Yii::app()->db->createCommand($sql);
                $letters = $cmd->bindValues($values)->queryAll();
                if($letters){
                    $letter = '';
                    foreach($letters as $v){
                        if(empty($letter)){
                            $letter .= $v['letter'];
                        }else{
                            $letter .= " ".$v['letter'];
                        }
                    }
                    if(!empty($letter)){
                        $values = array(
                            ':vid'=>$id,
                            ':letter'=>$letter,
                        );
                        $sql = "INSERT INTO {{letter_all}} SET letter=:letter,vid=:vid";
                        $cmd = Yii::app()->db->createCommand($sql);
                        if($cmd->bindValues($values)->execute()){
                            echo $id.":".$letter."\n";
                        }
                    }
                }
            }
        }
   
    }
}
?>