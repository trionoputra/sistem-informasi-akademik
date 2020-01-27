<?php
class Admin_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->cekLogin();
    }
	
	private function cekLogin(){
        if(!$this->session->userdata('isLogin1')){
            redirect('login');
        }
    }
	
	public function cekLoginStatus($form = "ss",$isRedirect = false){
		$status = false; 
        if($this->getStatus() === false){
            $status =  false;
        }
		else
		{
		
			if($form == "ss" && $this->getStatus() == "0")
				$status =  true;
			else if ($form == "sswkgr"  && ($this->getStatus() == "0" || $this->getStatus() == "2" || $this->getStatus() == "4"))
				$status =  true;
			else if ($form == "sswk"  && ($this->getStatus() == "0" || $this->getStatus() == "4"))
				$status =  true;
			else if ($form == "ssks"  && ($this->getStatus() == "0" || $this->getStatus() == "1"))
				$status =  true;
			else if ($form == "sswkwmks"  && ($this->getStatus() == "0" || $this->getStatus() == "4" || $this->getStatus() == "3" || $this->getStatus() == "1"))
				$status =  true;
			
		}
		if($status)
		{
			return $status;
		}
		else
		{
			if($isRedirect)
			{
				redirect('dashboard');
			}
			else
				return $status;
		}	
    }
	
	public function getStatus()
	{
		return $this->session->userdata('loginstatus');
	}
}