<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guruspesialis extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("guruspesialis_model");
		$this->cekLoginStatus("sswk",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Ajar (Guru Spesialis)";
		$data['layout'] = "guruspesialis/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->pelajaran = trim($this->input->get('pelajaran'));
		$filter->thn_ajaran = trim($this->input->get('thn_ajaran'));
		$filter->jenis_kelamin = trim($this->input->get('gender'));
		$filter->jabatan = trim($this->input->get('jabatan'));
		$filter->golongan = trim($this->input->get('golongan'));
		$filter->pendidikan_terakhir = trim($this->input->get('pendidikan'));
		$filter->agama = trim($this->input->get('agama'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		$this->load->model("tahunajaran_model");
		$this->load->model("pelajaran_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['pelajaran'],$totalK) = $this->pelajaran_model->getAll(null,null,null,"id_pelajaran",'asc');
		list($data['data'],$total) = $this->guruspesialis_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("guruspesialis?");
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
		$data['title'] = "Form Ajar (Guru Spesialis)";
		$data['layout'] = "guruspesialis/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_pelajaran = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->id_guru = "";
		$data['data']->id_ajar = "";
		
		
		$this->load->model("tahunajaran_model");
		$this->load->model("pelajaran_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['pelajaran'],$totalK) = $this->pelajaran_model->getAll(null,null,null,"id_pelajaran",'asc');
		
		if($id)
		{
			$dt =  $this->guruspesialis_model->get_by("id_ajar",$id,true);
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
			
			if(!empty($post['id_thn_ajaran']))
				$data['id_thn_ajaran'] = $post['id_thn_ajaran'];
			else
				$error[] = "id tahun ajaran tidak boleh kosong"; 
			
			if(!empty($post['id_pelajaran']))
				$data['id_pelajaran'] = $post['id_pelajaran'];
			else
				$error[] = "id pelajaran tidak boleh kosong";
			
			if(!empty($post['id_guru']))
				$data['id_guru'] = $post['id_guru'];
			else
				$error[] = "id guru tidak boleh kosong";
			
		
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->guruspesialis_model->cekPelajaran(null,$data['id_guru'],$data['id_pelajaran'],$data['id_thn_ajaran']);
				else
					$cek = $this->guruspesialis_model->cekPelajaran($id,$data['id_guru'],$data['id_pelajaran'],$data['id_thn_ajaran']);
				
				if(!empty($cek))
					$error[] = "guru sudah terdaftar dipelajaran dan tahun ajaran tersebut";
			
					
			}
			
			if(empty($error))
			{
				$save = $this->guruspesialis_model->save($id,$data,false);
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("guruspesialis/manage/".$id);
				else
					redirect("guruspesialis");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("guruspesialis/manage/".$id);
			}
		}
		else
		  redirect("guruspesialis");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->guruspesialis_model->get_by("id_ajar",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("guruspesialis");
			}
			else
			{
				if(!empty($this->guruspesialis_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "guru spesialis sedang digunakan");
					redirect("guruspesialis");
				}
				else
				{
					$this->guruspesialis_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("guruspesialis");
				}
			}
		}
		else
			redirect("guruspesialis");
	}
}
