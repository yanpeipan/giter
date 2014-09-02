<?php
class PutiApiThird extends BaseApi{
    public function GetTid($params){
        $sql = "SELECT id,tid FROM {{token}} WHERE is_use=0 limit 1";
        $row = Yii::app()->db_puti->createCommand($sql)->queryRow();
        $sql_del = "UPDATE {{token}} SET is_use=1 WHERE id=:id";
        $row_del = Yii::app()->db_puti->createCommand($sql_del)->bindValue(':id',$row['id'])->execute();
        if($row && $row_del){
            $result = array("status"=>"1","tid"=>$row['tid']);
            $arrs = array(
                ':uid'=>$params['uid'],
                ':tid'=>$result['tid'],
                ':os' =>$params['os'],
                ':kernel'=>$params['kernel'],
                ':mac'=>$params['mac'],
                ':model'=>$params['model'],
                ':channel'=>$params['channel'],
                ':rom'=>$params['rom'],
                ':ctime'=>time(),
                ':ip'=>$_SERVER['REMOTE_ADDR'],
                ':lmac'=>$params['lmac'],
                ':gwmac'=>$params['gwmac'],
                ':imei'=>$params['imei'],
                ':did'=>$params['did'],
                ':soft'=>$params['soft'],
                ':soft_version'=>$params['soft_version'],
                ':api_version'=>isset($_POST['version']) ? $_POST['version'] : ''
            );
            $this->setDeviceInfo($arrs);
            return $result;
        }else{
            $result = array("status"=>'0');
            return $result;
        }
    }
    
    private function setDeviceInfo($params){
        $sql = "INSERT INTO {{cnt_equipment}} SET 
        uid=:uid,
        tid=:tid,
        os=:os,
        kernel=:kernel,
        mac=:mac,
        model=:model,
        rom=:rom,
        channel=:channel,
        ctime=:ctime,
        ip=:ip,
        lmac=:lmac,
        gwmac=:gwmac,
        imei=:imei,
        did=:did,
        soft=:soft,
        soft_version=:soft_version,
        api_version=:api_version
        ";
        $cmd = Yii::app()->db_unicom->createCommand($sql);
        $options = array(
            ':uid'=>0,
            ':tid'=>0,
            ':os' =>'',
            ':kernel'=>'',
            ':mac'=>'',
            ':model'=>'',
            ':rom'=>'',
            ':channel'=>'',
            ':ctime'=>time(),
            ':ip'=>'',
            ':lmac'=>'',
            ':gwmac'=>'',
            ':imei'=>'',
            ':did'=>'',
            ':soft'=>'',
            ':soft_version'=>'',
            ':api_version'=>''
        );
        $cmd->bindValues(array_merge($options,$params))->execute();
    }

    public function Deadlink($params){
        @$uid = $params['uid'];
        @$tid = $params['tid'];
        @$vid = $params['vid'];
        @$tv_parent_id = $params['tv_parent_id'];
        @$model = $params['model'];
        @$os = $params['os'];
        @$channel = $params['channel'];
        @$version = $params['tuzi_version'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if(!empty($tid) &&!empty($vid) &&!empty($tv_parent_id) &&!empty($model) &&!empty($os) &&!empty($channel) &&!empty($version) ){
            $sel_sql = "SELECT * FROM {{v_tv}} WHERE id=:vid AND tv_parent_id=:tv_parent_id";
            $result = Yii::app()->db_puti->createCommand($sel_sql)->bindValue(':vid',$vid)->bindValue(':tv_parent_id',$tv_parent_id)->queryRow();
            if($result){
                $select_sql ="SELECT * FROM {{error_report}} WHERE vid=$vid";
                $res = Yii::app()->db_puti->createCommand($select_sql)->queryRow();
                if($res){
                    $count = 1+$res['count'];
                    $edit_sql="UPDATE {{error_report}} SET count='$count' WHERE vid=$vid";
                    $res_edit = Yii::app()->db_puti->createCommand($edit_sql)->execute();
                }else{
                    $edit_sql="INSERT INTO {{error_report}} SET
                                                            vid=:vid,
                                                            tv_parent_id=:tv_parent_id,
                                                            count=1";
                    $res_edit = Yii::app()->db_puti->createCommand($edit_sql)->bindValues(array(
                        ':vid' =>$vid,
                        ':tv_parent_id' =>$tv_parent_id,
                    ))->execute();
                }
                if($res_edit){
                   $sql ="INSERT INTO {{error_list}} SET
                                                uid=:uid,
                                                tid=:tid,
                                                vid=:vid,
                                                model=:model,
                                                ip=:ip,
                                                channel=:channel,
                                                version=:version,
                                                os=:os,
                                                time=:time";
                    $bool = Yii::app()->db_puti->createCommand($sql)->bindValues(array(
                        ':uid' =>$uid,
                        ':tid' =>$tid,
                        ':vid' =>$vid,
                        ':model' =>$model,
                        ':ip' =>$ip,
                        ':channel' =>$channel,
                        ':version' =>$version,
                        ':os' =>$os,
                        ':time' =>time(),
                    ))->execute();
                    if($bool){
                        $json_array = array(
                            'status'=>'1',
                        );
                    }else{
                        $json_array = array(
                            'status'=>'0',
                        );
                    }
                }else{
                    $json_array = array(
                        'status'=>'0',
                    );
                }
            }else{
                $json_array = array(
                    'status'=>'error-2',
                );
            }
            
        }else{
            $json_array = array(
                'status'=>'error-8',
            );
        }
        return $json_array;
    }

    /**
     * 获取资源
     * @param $client
     * @param $cate
     */
    public function GetResource(){
        $client = isset($_REQUEST['client']) ? trim($_REQUEST['client']) : '';
        $cate   = isset($_REQUEST['cate']) ? strtolower(trim($_REQUEST['cate'])) : '';
		$version = isset($_REQUEST['version']) ? trim($_REQUEST['version']) : '';

		if(strtolower($client) == 'android'){
			$sql = "SELECT title,content FROM {{resource}} WHERE for_client=:for_client AND api_version like '%,$version,%'";
			$cmd = Yii::app()->db_puti->createCommand($sql);
			$rows= $cmd->bindValue(':for_client',$client)->queryAll();
			if($rows){
				$json_array = array(
					'status'=>'1',
					'data'=>$rows,
				);
			}else{
				$json_array = array(
					'status'=>'0',
				);
			}
		}else{
	        if(!empty($client) && !empty($cate)){
	            if($cate=='all'){
	                //返回所有数据
	                $sql = "SELECT id,imgUrl AS url,title FROM {{resource}} WHERE for_client=:for_client AND api_version like '%,$version,%' ORDER BY order_id ASC";
	                $cmd = Yii::app()->db_puti->createCommand($sql);
	                $rows = $cmd->bindValue(':for_client', $client)->queryAll();
	            }else{
	                $values = array(
	                    ':cate'=>$cate,
	                    ':for_client'=>$client,
	                );
	                $sql = "SELECT imgUrl AS url,title FROM {{resource}} WHERE cate=:cate AND for_client=:for_client AND api_version like '%,$version,%' ORDER BY order_id ASC";
	                $cmd = Yii::app()->db_puti->createCommand($sql);
	                $rows = $cmd->bindValues($values)->queryAll();
	            }
	
	            if($rows){
	                $json_array = array(
	                    'status'=>'1',
	                    'data'=>$rows,
	                );
	            }else{
	                $json_array = array(
	                    'status'=>'0',
	                    'error_msg'=>'data empty!',
	                );
	            }
	        }else{
	            $json_array = array(
	                'status'=>'0',
	                'error_msg'=>'params lost',
	            );
	        }
		}

        return $json_array;
    }
    
	
    /**
	 * 保存so请求
	 * @param $params
	 */
	public function SoRequestRecord($params){
        $arrs = array(
            ':uid'=>$params['uid'],
            ':tid'=>$params['tid'],
            ':os' =>$params['os'],
            ':kernel'=>$params['kernel'],
            ':mac'=>$params['mac'],
            ':model'=>$params['model'],
            ':channel'=>$params['channel'],
            ':rom'=>$params['rom'],
            ':ctime'=>time(),
            ':ip'=>$_SERVER['REMOTE_ADDR'],
            ':lmac'=>$params['lmac'],
            ':gwmac'=>$params['gwmac'],
            ':imei'=>$params['imei'],
            ':did'=>$params['did'],
            ':soft'=>$params['soft'],
            ':soft_version'=>$params['soft_version'],
            ':api_version'=>isset($_POST['version']) ? $_POST['version'] : ''
        );
		
		 $sql = "INSERT INTO {{so_record}} SET 
		        uid=:uid,
		        tid=:tid,
		        os=:os,
		        kernel=:kernel,
		        mac=:mac,
		        model=:model,
		        rom=:rom,
		        channel=:channel,
		        ctime=:ctime,
		        ip=:ip,
		        lmac=:lmac,
		        gwmac=:gwmac,
		        imei=:imei,
		        did=:did,
		        soft=:soft,
		        soft_version=:soft_version,
		        api_version=:api_version
		        ";
        $cmd = Yii::app()->db_puti->createCommand($sql);
        $cmd->bindValues($arrs);
		if($cmd->execute()){
			$json_array = array(
				'status'=>'1',
			);
		}else{
			$json_array = array(
				'status'=>'0',
			);
		}

		return $json_array;
	}
    
    
    
    
}
