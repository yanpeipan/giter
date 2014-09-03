<?php
class Op{
    static $_instance = null;
    public static function getInstance(){
        if(null == self::$_instance){
            $className = get_called_class();
            self::$_instance = new $className;
        }
        return self::$_instance;
    }
	
	/*
	 * 查询一条记录
	 * $table is table name
	 * $ids 为条件id
	 * $rows  查询字段名
	 */
	public function catRow($table,$ids=array(),$rows='*'){
		$row = '';
		$where = '1'; 
		$params = array();
		if($table==''){
			return false;
		}
		$i = 0;
		if($ids)foreach($ids as $k=>$v){
			$where .=' AND '.$k.'=:'.$k;
			$i++;
			$params += array(
			   ':'.$k =>$v,
			);
		}
		if($where=='1'||$params==array()){
			return false;
		}
		$sql  = "SELECT {$rows} FROM {{{$table}}} WHERE {$where} LIMIT 1";
        $cmd  = Yii::app()->db->createCommand($sql)->bindValues($params);
        $row = $cmd->queryRow();
        return $row;
	}
	
	/*查询多条记录
	 * $table is table name
	 * $wheres is array(),is condition
	 * $rows 查询字段名 default is *
	 * $order 排序
	 * $limit 
	 * $group
	 */
	public function catRows($table,$wheres=array(),$rows='*',$order='',$limit='',$group=''){
		$data = '';
		$where = 1;
		$params = array();
		if($table==''){
			return false;
		}
		$i = 0;
		if($wheres)foreach($wheres as $k=>$r){
			$where.=' AND '.$k.'=:'.$k;
			$i++;
			$params += array(
			   ':'.$k => $r,
			);	
		}
		if($where==''||$params==array()){
			return false;
		}
		if($group!=''){
			$where .= ' GROUP BY '.$group;
		}
		if($order!=''){
			$where .= ' ORDER BY '.$order;
		}
		if($limit!=''){
			$where .= ' LIMIT '.$limit;
		}
		$sql  = "SELECT {$rows} FROM {{{$table}}} WHERE {$where}";
        $cmd  = Yii::app()->db->createCommand($sql)->bindValues($params);
        $data = $cmd->queryAll();
        return $data;
	}
	
	/*查询多条记录   >,>=,<,<=,!=,in,not,like ......Special
	 * $table is table name
	 * $wheres is condition
	 * $params 是参数
	 * $rows 查询字段名 default is *
	 * $order 排序
	 * $limit 
	 * $group
	 */
	public function catSpecial($table,$wheres='',$params=array(),$rows='*',$order='',$limit='',$group=''){
		$data = '';
		$where = 1;
		if($wheres!=''){
			$where.=' AND '.$wheres;
		}
		if($table==''){
			return false;
		}
		if($where==''){
			return false;
		}
		if($group!=''){
			$where .= ' GROUP BY '.$group;
		}
		if($order!=''){
			$where .= ' ORDER BY '.$order;
		}
		if($limit!=''){
			$where .= ' LIMIT '.$limit;
		}
		$sql  = "SELECT {$rows} FROM {{{$table}}} WHERE {$where}";
        $cmd  = Yii::app()->db->createCommand($sql);
		if($params!=array()){
			$cmd->bindValues($params);
		}
        $data = $cmd->queryAll();
        return $data;
	}
	
	/*
	 * 删除方法 
	 * $where is condition;eg array('id'=>$id);
	 * $table id table name;eg 'market_headreco';
	 */
	public function del($wheres=array(),$table){
		if($table==''){
			return false;
		}
		$j = 0;
		$params = array();
		$where = ''; 
		if($wheres)foreach($wheres as $kw=>$v){
			$where .=$kw.'=:'.$kw;
			if($j<count($wheres)-1){
				$where.= ' AND ';
			}
			$j++;
			$params += array(
			   ':'.$kw => $v,
			);
		}
		if($where==''||$params==array()){
			return false;
		}
		$sql = "DELETE FROM {{{$table}}} WHERE {$where}";
        $command = Yii::app()->db->createCommand($sql);
		$command->bindValues($params);
        $result = $command->execute(); 
        return $result;
	}
	
	/*
	 * 修改方法  
	 * $where is condition;eg array('head_id'=>$id);
	 * $table id table name;eg 'market_headreco';
	 * $row is update rows;eg array('head_name'=>$name);
	 */
	public function upd($wheres=array(),$table,$rows=array()){
		if($table==''){
			return false;
		}
		$row = '';
		$params = array();
		$i = 0;
		if($rows)foreach($rows as $k=>$r){
			$row .=$k.'=:'.$k;
			if($i!=(int)count($rows)-1){
				$row.= ',';
			}
			$i++;
			$params += array(
			   ':'.$k => $r,
			);	
		}
        $j = 0;
		$where = ''; 
		if($wheres)foreach($wheres as $kw=>$v){
			$where .=$kw.'=:'.$kw;
			if($j<count($wheres)-1){
				$where.= ' AND ';
			}
			$j++;
			$params += array(
			   ':'.$kw => $v,
			);
		}
		if($where==''||$row==''||$params==array()){
			return false;
		}
		//var_dump($params);
		$sql = "UPDATE {{{$table}}} SET {$row} WHERE {$where}";
		//echo $sql;die;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues($params);
        $result = $command->execute(); 
		return $result;
	}

		
	public function url_exists($url) {
        $head=@get_headers(urldecode($url)); 
        if(is_array($head)) {
           if(strpos($head[0],'HTTP/1.0 200')===0||strpos($head[0],'HTTP/1.1 200')===0){
            	return true; //有文件
        	}else{
            	return false;; //没有文件
        	}
        }
        return false;
    }
	
	//数据库连接
	public function _connect($dsn='mysql:host=192.168.1.20;port=3306;dbname=16tree_spider',$username='root',$password='punica1001'){
		//$connection = new CDbConnection($dsn, $username, $password);
		$db_spider = new CDbConnectionExt($dsn,$username,$password);
		$db_spider->emulatePrepare = true;
		$db_spider->charset = 'utf8';
		$db_spider->active = true;
		$db_spider->tablePrefix = 'sp_';
		$db_spider->slaveConfig = array(
			array('connectionString'=>$dsn,'username'=>$username,'password'=>$password)
		);
		return $db_spider;
		//$connection->active = true;
	}
       
}