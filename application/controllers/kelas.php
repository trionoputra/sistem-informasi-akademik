<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kelas extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("kelas_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Kelas";
		$data['layout'] = "kelas/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->kelas_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("kelas?");
		$config['total_rows'] = $total;
		$config['per_page'] = $limit;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers']  = TRUE;
		$config['page_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		$this->load->view('template',$data);
	}
	
	public function manage($id = "")
	{
		$data['title'] = "Form Kelas";
		$data['layout'] = "kelas/manage";

		$data['data'] = new StdClass();
		$data['data']->id_kelas = "";
		$data['data']->nm_kelas = "";
		$data['data']->kapasitas = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->kelas_model->get_by("id_kelas",$id,true);
			if(!empty($dt))
			{
				$data['data'] = $dt;
			}
		}
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			$error = array();
			$id = $post['id'];
			
			if(!empty($post['id_kelas']))
				$data['id_kelas'] = $post['id_kelas'];
			else
				$error[] = "id kelas tidak boleh kosong"; 
			
			if(!empty($post['nm_kelas']))
				$data['nm_kelas'] = $post['nm_kelas'];
			else
				$error[] = "nama kelas tidak boleh kosong"; 
			
			if(!empty($post['kapasitas']))
				{
					if(is_numeric($post['kapasitas']))
						$data['kapasitas'] = $post['kapasitas'];
					else
						$error[] = "kapasitas tidak valid";
				}
			else
				$error[] = "kapasitas tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->kelas_model->get_by("id_kelas",$post['id_kelas']);
				
				if(!empty($cekId))
					$error[] = "ID kelas sudah terdaftar";
				
				if(empty($id))
					$cekNm = $this->kelas_model->get_by("nm_kelas",$post['nm_kelas']);
				else
					$cekNm = $this->kelas_model->cekNm($id,$post['nm_kelas']);
					
				if(!empty($cekNm))
					$error[] = "nama kelas sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->kelas_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("kelas/manage/".$id);
				else
					redirect("kelas");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("kelas/manage/".$id);
			}
		}
		else
		  redirect("kelas");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->kelas_model->get_by("id_kelas",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID kelas tidak terdaftar");
				redirect("kelas");
			}
			else
			{
				if(!empty($this->kelas_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "kelas sedang digunakan");
					redirect("kelas");
				}
				else
				{
					$this->kelas_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("kelas");
				}
			}
		}
		else
			redirect("kelas");
	}
	
	public function generate_code()
	{
		$prefix = "KL";
		$code = "01";
		
		$last = $this->kelas_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_kelas,2,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
