<?php

/**
 * @file
 * Sample OAuth2 Library PDO DB Implementation.
 */

/**
 * OAuth2 Library PDO DB Implementation.
 */
class PDOOAuth2 extends OAuth2 {

  private $db;
  

  /**
   * Overrides OAuth2::__construct().
   */
  public function __construct() {
    parent::__construct();
	
    try {
      $this->db = Yii::app()->db_dev;
    } catch (PDOException $e) {
      print('Connection failed: ' . $e->getMessage());
      Yii::app()->end();
    }
  }

  public function init(){
      
  }

  /**
   * Release DB connection during destruct.
   */
  function __destruct() {
    $this->db = NULL; // Release db connection
  }

  /**
   * Handle PDO exceptional cases.
   */
  private function handleException($e) {
    echo "Database error: " . $e->getMessage();
    Yii::app()->end();
  }

  /**
   * Implements OAuth2::checkClientCredentials().
   *
   * Do NOT use this in production! This sample code stores the secret
   * in plaintext!
   */
  protected function checkClientCredentials($client_id, $client_secret = NULL) {
    try {
      $sql = "SELECT client_secret FROM {{app}} WHERE client_id = :client_id LIMIT 1";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $result = $stmt->queryRow();

      if ($client_secret === NULL)
          return $result !== FALSE;

      return $result["client_secret"] == $client_secret;
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::getRedirectUri().
   */
  protected function getRedirectUri($client_id) {
    try {
      $sql = "SELECT redirect_uri FROM {{app}} WHERE client_id = :client_id LIMIT 1";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $result = $stmt->queryRow();

      if ($result === FALSE)
          return FALSE;

      return isset($result["redirect_uri"]) && $result["redirect_uri"] ? $result["redirect_uri"] : NULL;
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Implements OAuth2::getAccessToken().
   */
  protected function getAccessToken($oauth_token) {
  		$result = RedisHandler::kv_get($oauth_token);
		if($result)
		{
			return $result;
		}else{
		    try {
		      $sql = "SELECT client_id, expires, scope,uid FROM {{token}} WHERE oauth_token = :oauth_token LIMIT 1";
		      $stmt = $this->db->createCommand($sql);
		      $stmt->bindParam(":oauth_token", $oauth_token, PDO::PARAM_STR);
		      $result = $stmt->queryRow();
		      RedisHandler::kv_set_expire($oauth_token,$result,Yii::app()->params['cache']['expire']);
		      return $result !== FALSE ? $result : NULL;
		    } catch (PDOException $e) {
		      $this->handleException($e);
		    }
		}
  }

  /**
   * Implements OAuth2::setAccessToken().
   */
  protected function setAccessToken($oauth_token, $client_id, $expires, $scope = NULL) {
		//缓存中保存一份
		$values = array(
			'oauth_token'=>$oauth_token,
			'client_id'=>$client_id,
			'expire'=>$expires,
			'scope'=>$scope,
			'uid'=>Yii::app()->session->get("uid")
		);
		RedisHandler::kv_set_expire($oauth_token, $values, Yii::app()->params['cache']['expire']);
		
		//数据库中也保存一份
	    try
	    {
	      $sql = "INSERT INTO {{token}} (oauth_token, client_id, expires, scope,uid) VALUES (:oauth_token, :client_id, :expires, :scope,:uid)";
	      $stmt = $this->db->createCommand($sql);
	      $stmt->bindParam(":oauth_token", $oauth_token, PDO::PARAM_STR);
	      $stmt->bindParam(":client_id", $client_id, PDO::PARAM_STR);
	      $stmt->bindParam(":expires", $expires, PDO::PARAM_INT);
	      $stmt->bindParam(":scope", $scope, PDO::PARAM_STR);
	      $stmt->bindParam(":uid",Yii::app()->session->get("uid"),PDO::PARAM_INT);
	      $stmt->execute();
	    } catch (PDOException $e) {
	      $this->handleException($e);
	    }
  }

  /**
   * Overrides OAuth2::getSupportedGrantTypes().
   */
  protected function getSupportedGrantTypes() {
    return array(
      OAUTH2_GRANT_TYPE_AUTH_CODE,
      OAUTH2_GRANT_TYPE_USER_CREDENTIALS,
      OAUTH2_GRANT_TYPE_REFRESH_TOKEN,
    );
  }

  /**
   * Overrides OAuth2::getAuthCode().
   */
  protected function getAuthCode($code) {
    try {
      $sql = "SELECT code, client_id, redirect_uri, expires, scope FROM {{authorize_code}} WHERE code = :code";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":code", $code, PDO::PARAM_STR);
      $result=$stmt->queryRow();
      
      return $result !== FALSE ? $result : NULL;
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }

  /**
   * Overrides OAuth2::setAuthCode().
   */
  protected function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL) {
    try {
      $sql = "INSERT INTO {{authorize_code}} (code, client_id, redirect_uri, expires, scope) VALUES (:code, :client_id, :redirect_uri, :expires, :scope)";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":code", $code, PDO::PARAM_STR);
      $stmt->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $stmt->bindParam(":redirect_uri", $redirect_uri, PDO::PARAM_STR);
      $stmt->bindParam(":expires", $expires, PDO::PARAM_INT);
      $stmt->bindParam(":scope", $scope, PDO::PARAM_STR);

      $stmt->execute();
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }
  
  protected function checkUserCredentials($client_id, $username, $password){
	  $sql = "SELECT m.uid,password,salt FROM {{members}} as m inner join {{members_info}} as i  on i.uid=m.uid WHERE m.email='$username'  or i.mobile='$username' LIMIT 1";
      $cmd = Yii::app()->db_user->createCommand($sql);
      $row = $cmd->queryRow();
      if($row){
          $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
          if($row['password'] == md5($passwordmd5.$row['salt'])){
              Yii::app()->session->add("uid",$row['uid']);
              return $data['scope'] = 'basic';
          }
      }else{
          $sql = "SELECT uid,password,salt FROM {{members}} WHERE username=:username  LIMIT 1";
          $cmd = Yii::app()->db_user->createCommand($sql);
          $cmd->bindValue(":username",$username);
          $row = $cmd->queryRow();
          if($row){
              $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
              if($row['password'] == md5($passwordmd5.$row['salt'])){
                  Yii::app()->session->add("uid",$row['uid']);
                  return $data['scope'] = 'basic';
              }
          }
      }
      return false;
  }
  
  protected function getRefreshToken($refresh_token){
      try {
          $sql = "SELECT refresh_token token,client_id, expires, scope,uid FROM {{refresh_token}} WHERE refresh_token = :refresh_token  LIMIT 1";
          $stmt = $this->db->createCommand($sql);
          $stmt->bindParam(":refresh_token", $refresh_token, PDO::PARAM_STR);
          $result = $stmt->queryRow();
          if($result !== FALSE){
              Yii::app()->session->add("uid",$result['uid']);
              return $result;
          }else{
              return NULL;
          }
        } catch (PDOException $e) {
          $this->handleException($e);
        }
  }
  
  protected function setRefreshToken($refresh_token, $client_id, $expires, $scope = NULL) {
    try {
      $sql = "INSERT INTO {{refresh_token}} (refresh_token, client_id, expires, scope,uid) VALUES (:refresh_token, :client_id, :expires, :scope,:uid)";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":refresh_token", $refresh_token, PDO::PARAM_STR);
      $stmt->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $stmt->bindParam(":expires", $expires, PDO::PARAM_INT);
      $stmt->bindParam(":scope", $scope, PDO::PARAM_STR);
      $stmt->bindParam(":uid",Yii::app()->session->get("uid"),PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }
  
  protected function unsetRefreshToken($refresh_token) {
    try {
      $sql = "UPDATE {{refresh_token}} SET expires=0 WHERE refresh_token=:refresh_token  LIMIT 1";
      $stmt = $this->db->createCommand($sql);
      $stmt->bindParam(":refresh_token", $refresh_token, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      $this->handleException($e);
    }
  }
  
}
