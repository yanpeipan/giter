<?php
/**
 * 天气类
 */
class Weather{ 
    public function main($params){
		$city_name = $params['name'];
        $city_weather_id = $this->getWeatherIdByName($city_name);

		$redis_key = 'weather_'.$city_weather_id;	 
		$data = RedisHandler::kv_get($redis_key);
		if(!$data){
	        $data = $this->getWeather($city_weather_id);
			if($data['status'] == 1){
				RedisHandler::kv_set_expire($redis_key, $data, 1800);
			}
		}
		
		return $data;
    }
    private function getWeather($id){
        $url = "http://m.weather.com.cn/data/{$id}.html";
        $result = Yii::app()->curl->run($url);
		if($result){
			$status = $result['status'];
			if($status == 1){
				$data = json_decode($result['data'],true);
				$weather = $data['weatherinfo'];
				
				$data = array(
					'city'=>$weather['city'],
					'temp'=>$weather['temp1'],
					'weather'=>$weather['weather1'],
					'wind'=>$weather['wind1'],
				);
				$json_array = array(
					'status'=>'1',
					'data'=>$data,
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
		return $json_array;
		
		/*
        $sql = "SELECT weather FROM {{weather}} WHERE weather_id=:weather_id";
        $row = Yii::app()->db_weather->createCommand($sql)->bindValue(":weather_id",$id)->queryRow();
        return json_decode($row['weather']);
		 * 
		 */
    }
    private function getWeatherIdByName($name){
    	$redis_key = 'city_'.$name;
		$row = RedisHandler::kv_get($redis_key);
		if(!$row){
	        $sql = "SELECT weather_id FROM {{china}} WHERE name=:name AND name_type='station'";
	        $cmd = Yii::app()->db_weather->createCommand($sql);
	        $row = $cmd->bindValue(":name",$name)->queryRow();
			if($row){
				RedisHandler::kv_set_expire($redis_key, $row, 3600*720);
			}
		}
		
        return $row ? $row['weather_id'] : false;
    }
}


?>