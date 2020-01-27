<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ekskul extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("ekskul_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Ekstrakulikuler";
		$data['layout'] = "ekskul/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->ekskul_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("ekskul?");
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
		$data['title'] = "Form Ekstrakulikuler";
		$data['layout'] = "ekskul/manage";

		$data['data'] = new StdClass();
		$data['data']->id_ekskul = "";
		$data['data']->nm_ekskul = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->ekskul_model->get_by("id_ekskul",$id,true);
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
			
			if(!empty($post['id_ekskul']))
				$data['id_ekskul'] = $post['id_ekskul'];
			else
				$error[] = "id ekskul tidak boleh kosong"; 
			
			if(!empty($post['nm_ekskul']))
				$data['nm_ekskul'] = $post['nm_ekskul'];
			else
				$error[] = "nama ekskul tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->ekskul_model->get_by("id_ekskul",$post['id_ekskul']);
				
				if(!empty($cekId))
					$error[] = "ID ekskul sudah terdaftar";
				
				if(empty($id))
					$cekNm = $this->ekskul_model->get_by("nm_ekskul",$post['nm_ekskul']);
				else
					$cekNm = $this->ekskul_model->cekNm($id,$post['nm_ekskul']);
					
				if(!empty($cekNm))
					$error[] = "nama ekskul sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->ekskul_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("ekskul/manage/".$id);
				else
					redirect("ekskul");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("ekskul/manage/".$id);
			}
		}
		else
		  redirect("ekskul");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->ekskul_model->get_by("id_ekskul",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID ekskul tidak terdaftar");
				redirect("ekskul");
			}
			else
			{
				if(!empty($this->ekskul_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "ekskul sedang digunakan");
					redirect("ekskul");
				}
				else
				{
					$this->ekskul_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("ekskul");
				}
			}
		}
		else
			redirect("ekskul");
	}
	
	public function generate_code()
	{
		$prefix = "X";
		$code = "01";
		
		$last = $this->ekskul_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_ekskul,1,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
