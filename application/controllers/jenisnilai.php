<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jenisnilai extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("jenisnilai_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Jenis Nilai";
		$data['layout'] = "jenisnilai/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->jenisnilai_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("jenisnilai?");
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
		$data['title'] = "Form Jenis Nilai";
		$data['layout'] = "jenisnilai/manage";

		$data['data'] = new StdClass();
		$data['data']->id_jenis_nilai = "";
		$data['data']->des_jenis_nilai = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->jenisnilai_model->get_by("id_jenis_nilai",$id,true);
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
			
			if(!empty($post['id_jenis_nilai']))
				$data['id_jenis_nilai'] = $post['id_jenis_nilai'];
			else
				$error[] = "id jenis nilai tidak boleh kosong"; 
			
			if(!empty($post['des_jenis_nilai']))
				$data['des_jenis_nilai'] = $post['des_jenis_nilai'];
			else
				$error[] = "deskripsi jenis nilai tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->jenisnilai_model->get_by("id_jenis_nilai",$post['id_jenis_nilai']);
				
				if(!empty($cekId))
					$error[] = "ID jenis nilai sudah terdaftar";
					
				if(empty($id))
					$cekJn = $this->jenisnilai_model->get_by("des_jenis_nilai",$post['des_jenis_nilai']);
				else
					$cekJn = $this->jenisnilai_model->cekJn($id,$post['des_jenis_nilai']);
					
				if(!empty($cekJn))
					$error[] = "deskripsi jenis nilai sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->jenisnilai_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("jenisnilai/manage/".$id);
				else
					redirect("jenisnilai");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("jenisnilai/manage/".$id);
			}
		}
		else
		  redirect("jenisnilai");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->jenisnilai_model->get_by("id_jenis_nilai",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID jenis nilai tidak terdaftar");
				redirect("jenisnilai");
			}
			else
			{
				if(!empty($this->jenisnilai_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "jenis nilai sedang digunakan");
					redirect("jenisnilai");
				}
				else
				{
					$this->jenisnilai_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("jenisnilai");
				}
			}
		}
		else
			redirect("jenisnilai");
	}
	
	public function generate_code()
	{
		$prefix = "J";
		$code = "01";
		
		$last = $this->jenisnilai_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_jenis_nilai,1,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
