<?php

class MantisProcess{
    private $db='';
    function __construct(){
        $this->db=Yii::app()->db;
    }
    
    function saveForm($data=array()){
   
        $sql='insert into mantis_bug_text_table set description=:description';
          
        $this->db->createCommand($sql)
              ->bindvalue(':description',$data['description'])           
              ->execute();
        $text_id = $this->db->getLastInsertID();  
        $sql = "SELECT user_id FROM mantis_category_table WHERE id=3";
        $result=$this->db->createCommand($sql)->queryRow();
  
        $handler_id=$result['user_id'];
        $sql="insert into mantis_bug_table set
                          project_id=3,
                          reporter_id=73,
                          handler_id='{$handler_id}',
                          duplicate_id=0,
                          resolution=10,
                          projection=10,
                          date_submitted=:date_submitted,
                          last_updated=:last_updated,
                          bug_text_id={$text_id},
                          sticky=0,
                          category_id=3,
                          reproducibility=70,
                          status=10,
                          severity=50,
                          priority=30,
                          profile_id=10,
                          summary=:summary,
                          view_state=10";
        $result=$this->db->createCommand($sql)       
             ->bindvalue(':date_submitted',time())
             ->bindvalue(':last_updated',time())
             ->bindvalue(':summary',$data['summary'])            
             ->execute();
            
          //0,成功 1,失败   
          if($result>0)        
              echo '{"status":0}';
          else  
              echo '{"status":1}';                                
    }
    

    
    
}
