<?php
class Mcrypter
{
	private  static  function key()
	{
		return pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3"); 
	}
	
	public  static function encrypt($data)
	{
		$cipher  = MCRYPT_RIJNDAEL_128;
		$key = self::key();
		$model = MCRYPT_MODE_CBC;
		$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $model), MCRYPT_RAND); 
		$ciphertext = mcrypt_encrypt($cipher, $key, $data, $model, $iv);
		return base64_encode($iv . $ciphertext);
	}

	public static function decrypt($ciphertext)
	{

		$ciphertext_dec = base64_decode($ciphertext);
		$key = self::key();
		$model = MCRYPT_MODE_CBC;
		$cipher  = MCRYPT_RIJNDAEL_128;

		$iv_size = mcrypt_get_iv_size($cipher, $model);
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);

		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec));
	}
}