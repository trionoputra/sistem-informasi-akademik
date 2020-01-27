<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Walikelas extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("walikelas_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Wali Kelas";
		$data['layout'] = "walikelas/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->kelas = trim($this->input->get('kelas'));
		$filter->id_thn_ajaran = trim($this->input->get('thn_ajaran'));
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
		$this->load->model("kelas_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['data'],$total) = $this->walikelas_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("walikelas?");
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
		$data['title'] = "Form Wali Kelas";
		$data['layout'] = "walikelas/manage";

		$data['data'] = new StdClass();
		$data['data']->id_kelas = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->id_guru = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		
		if($id)
		{
			$dt =  $this->walikelas_model->get_by("md5(CONCAT(w.id_kelas,w.id_guru,w.id_thn_ajaran))",$id,true);
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
			
			if(!empty($post['id_kelas']))
				$data['id_kelas'] = $post['id_kelas'];
			else
				$error[] = "id kelas tidak boleh kosong";
			
			if(!empty($post['id_guru']))
				$data['id_guru'] = $post['id_guru'];
			else
				$error[] = "id guru tidak boleh kosong";
			
		
			if(empty($error))
			{
				
				if(empty($id))
					$cek3 = $this->walikelas_model->cekTahunAjaran(null,$data['id_guru'],$data['id_thn_ajaran'],"id_guru");
				else
					$cek3 = $this->walikelas_model->cekTahunAjaran($id,$data['id_guru'],$data['id_thn_ajaran'],"id_guru");
					
				if(!empty($cek3))
					$error[] = "id guru sudah terdaftar ditahun ajaran tersebut";
					
			}
			
			if(empty($error))
			{
				$save = $this->walikelas_model->save($id,$data,false);
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("walikelas/manage/".$id);
				else
					redirect("walikelas");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("walikelas/manage/".$id);
			}
		}
		else
		  redirect("walikelas");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->walikelas_model->get_by("md5(CONCAT(w.id_kelas,w.id_guru,w.id_thn_ajaran))",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("walikelas");
			}
			else
			{
				$this->walikelas_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("walikelas");
			}
		}
		else
			redirect("walikelas");
	}
}
