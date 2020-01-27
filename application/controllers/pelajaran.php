<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pelajaran extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("pelajaran_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Pelajaran";
		$data['layout'] = "pelajaran/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->pelajaran_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("pelajaran?");
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
		$data['title'] = "Form Pelajaran";
		$data['layout'] = "pelajaran/manage";

		$data['data'] = new StdClass();
		$data['data']->id_pelajaran = "";
		$data['data']->deskripsi = "";
		$data['data']->kkm = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->pelajaran_model->get_by("id_pelajaran",$id,true);
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
			
			if(!empty($post['id_pelajaran']))
				$data['id_pelajaran'] = $post['id_pelajaran'];
			else
				$error[] = "id pelajaran tidak boleh kosong"; 
			
			if(!empty($post['deskripsi']))
				$data['deskripsi'] = $post['deskripsi'];
			else
				$error[] = "deskripsi tidak boleh kosong"; 
			
			if(!empty($post['kkm']))
				{
					if(is_numeric($post['kkm']))
						$data['kkm'] = $post['kkm'];
					else
						$error[] = "kkm tidak valid";
				}
			else
				$error[] = "kkm tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->pelajaran_model->get_by("id_pelajaran",$post['id_pelajaran']);
				
				if(!empty($cekId))
					$error[] = "ID pelajaran sudah terdaftar";
					
				if(empty($id))
					$cekNm = $this->pelajaran_model->get_by("deskripsi",$post['deskripsi']);
				else
					$cekNm = $this->pelajaran_model->cekNm($id,$post['deskripsi']);
					
				if(!empty($cekNm))
					$error[] = "deskripsi sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->pelajaran_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("pelajaran/manage/".$id);
				else
					redirect("pelajaran");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("pelajaran/manage/".$id);
			}
		}
		else
		  redirect("pelajaran");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->pelajaran_model->get_by("id_pelajaran",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID pelajaran tidak terdaftar");
				redirect("pelajaran");
			}
			else
			{
				if(!empty($this->pelajaran_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "pelajaran sedang digunakan");
					redirect("pelajaran");
				}
				else
				{
					$this->pelajaran_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("pelajaran");
				}
			}
		}
		else
			redirect("pelajaran");
	}
	
	public function generate_code()
	{
		$prefix = "P";
		$code = "01";
		
		$last = $this->pelajaran_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_pelajaran,1,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
