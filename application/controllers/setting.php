<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("user_model");
    }
	
	public function index()
	{
		$data['title'] = "Settings";
		$data['layout'] = "setting/index";
		
		$user =  $this->user_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
		
		$data['data'] = new StdClass();
		$data['data']->username = $user->username;
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			$error = array();
			$id = $this->session->userdata('isLogin1');
			if(!empty($post['username']))
				$data['username'] = $post['username'];
			else
				$error[] = "username tidak boleh kosong"; 
			
			if(isset($post['isChange']))
			{
				if(!empty($post['password']))
					$data['password'] = md5($post['password']);
				else
					$error[] = "password tidak boleh kosong"; 
			}
			
			if(empty($error))
			{
				$cekUser = $this->user_model->cekUserName($id,$post['username']);
				
				if(!empty($cekUser))
					$error[] = "username sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->user_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
		
				redirect("setting");
		
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("setting");
			}
		}
		else
		  redirect("setting");
	}
}
