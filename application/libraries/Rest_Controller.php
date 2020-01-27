<?php
class REST_Controller extends CI_Controller
{
    var $key = '12345678901234567890123456789012';
    var $clientID = 'android';
    public function __construct()
    {
        parent::__construct();
		header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
			
    }

    public function respon($data,$result = true)
    {
		$respon = array();
		if($result)
			$respon = array("message" => "success","result"=> $data);
		else
			$respon = array("message" => "error","result"=> $data);
			
		return json_encode($respon);
    }
	
	public  function encrypt($data){
		 return urlencode(base64_encode(
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_128,
				$this->key,
				$data,
				MCRYPT_MODE_CBC,
				substr(MD5($this->key),0,16)
			)
		));
	}
	
	public function token_auth($data)
    {
		try
		{
			$dec = $this->decrypt($data);
			$client = $dec[0];
			$timestamp = $dec[1];
			
			if($client == $clientID)
				return true;
			else
				return false;
		}
		catch(Exception $e)
		{
			return false;
		}
		
		
		
			
		return json_encode($respon);
    }
	
	public function decrypt($data){
		 $decode = base64_decode($data);
		return rtrim(mcrypt_decrypt(
						MCRYPT_RIJNDAEL_128,
						$this->key,
						$decode,
						MCRYPT_MODE_CBC,
						 substr(MD5($this->key),0,16)
				), "\x00..\x1F");
		
		
	}
	
}