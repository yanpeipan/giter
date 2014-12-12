<?php

class TencentExmail {
	
	public $client_id = 'riverrun';
	public $client_secret = 'e0dc0aa25a7bc53259b5d35b7fda9c9b';
	public $client_host = 'riverrun.cn';
	public $access_token;

	public function __construct($client_id=null, $client_secret=null) {
		if ($client_id) {
			$this->client_id = $client_id;
		}
		if ($client_secret) {
			$this->client_secret = $client_secret;
		}
		$this->token();
	}
	
	public function token() {
		$url = 'https://exmail.qq.com/cgi-bin/token';
		$payload = array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'grant_type' => 'client_credentials',
				);
		$json = $this->post($url, $payload);
		if ($json) {
			$json = CJSON::decode($json);
			if (isset($json['access_token'])) {
				$this->access_token = $json['access_token'];	
			}
		}

	}
	public function completeEmail($email) {
		if (!strpos($email, '@')) {
			$email .= '@' . $this->client_host;
		}
		return $email;
	}

	public function check($email) {
		$url = 'http://openapi.exmail.qq.com:12211/openapi/user/check';
		$email = $this->completeEmail($email);
		$data = array(
			'access_token' => $this->access_token,
			'email' => $email
		);
		$json = CJSON::decode($this->post($url, $data));

		if (isset($json['List'])) {
			foreach($json['List'] as $item) {
				if ($item['Email'] == $email) {
					return $item['Type'];	
				}
			}
		}
		return false;
	}

	public function add($data, $md5 = 1) {
		$url = 'http://openapi.exmail.qq.com:12211/openapi/user/sync';
		if (isset($data['Alias'])) {
			$data['Alias'] = $this->completeEmail($data['Alias']);
			$data['Action'] = 2;
			$data['md5'] = $md5;
			$data['access_token'] = $this->access_token;
			$this->post($url, $data);
			return true;
		}
		return false;
	}
	
	public function update($data, $md5 = 1) {
		$url = 'http://openapi.exmail.qq.com:12211/openapi/user/sync';
		if (isset($data['Alias'])) {

			$data['Alias'] = $this->completeEmail($data['Alias']);
			$data['Action'] = 3;
			$data['md5'] = $md5;
			$data['access_token'] = $this->access_token;
			
			$return = $this->post($url, $data);
			return true;
		}
		return false;
	}
	
	public function delete($email) {
		$url = 'http://openapi.exmail.qq.com:12211/openapi/user/sync';
		$data = array(
			'Alias' => $this->completeEmail($email),
			'Action' => 1,
			'access_token' => $this->access_token,
		);
		$this->post($url, $data);
		return true;
	}
	
	public function post($url, $data) {

		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $url);//设置链接
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
		curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据
		$response = curl_exec($ch);//接收返回信息
		if(curl_errno($ch)){//出错则显示错误信息
			//print curl_error($ch);
			return false;
		}
		curl_close($ch); //关闭curl链接
		return $response;//显示返回信息
	
		
	}
}
