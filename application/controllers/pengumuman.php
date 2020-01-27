<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pengumuman extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("pengumuman_model");
    }
	
	public function index()
	{
		$this->cekLoginStatus("sswkgr",true);
		$data['title'] = "Data Pengumuman";
		$data['layout'] = "pengumuman/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->pengumuman_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("pengumuman?");
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
		$this->cekLoginStatus("sswkgr",true);
		$data['title'] = "Form Pengumuman";
		$data['layout'] = "pengumuman/manage";

		$data['data'] = new StdClass();
		$data['data']->id_pengumuman = "";
		$data['data']->judul = "";
		$data['data']->isi = "";
		$data['data']->tgl_input = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->autocode = $this->generate_code();
		
		$this->load->model("tahunajaran_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		
		if($id)
		{
			$dt =  $this->pengumuman_model->get_by("id_pengumuman",$id,true);
			if(!empty($dt))
			{
				$data['data'] = $dt;
			}
		}
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		$this->cekLoginStatus("sswkgr",true);
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			
			$error = array();
			$id = $post['id'];
			
			if(!empty($post['id_pengumuman']))
				$data['id_pengumuman'] = $post['id_pengumuman'];
			else
				$error[] = "id pengumuman tidak boleh kosong"; 
			
			if(!empty($post['isi']))
				$data['isi'] = $post['isi'];
			else
				$error[] = "isi tidak boleh kosong"; 
			
			if(!empty($post['judul']))
				$data['judul'] = $post['judul'];
			else
				$error[] = "judul tidak boleh kosong"; 
				
			if(!empty($post['id_thn_ajaran']))
				$data['id_thn_ajaran'] = $post['id_thn_ajaran'];
			else
				$error[] = "tahun ajaran tidak boleh kosong"; 
			
			$data['id_user'] = $this->session->userdata('isLogin1');
			
			$data['tgl_input'] = date("Y-m-d h:i:s",strtotime($post['tgl_input']." ".date("h:i:s")));
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->pengumuman_model->get_by("id_pengumuman",$post['id_pengumuman']);
				
				if(!empty($cekId))
					$error[] = "ID pengumuman sudah terdaftar";
				
			}
			
			if(empty($error))
			{
				$save = $this->pengumuman_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("pengumuman/manage/".$id);
				else
					redirect("pengumuman");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("pengumuman/manage/".$id);
			}
		}
		else
		  redirect("pengumuman");
	}
	
	public function delete($id = "")
	{
		$this->cekLoginStatus("sswkgr",true);
		if(!empty($id))
		{
			$cek = $this->pengumuman_model->get_by("id_pengumuman",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID pengumuman tidak terdaftar");
				redirect("pengumuman");
			}
			else
			{
				$this->pengumuman_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("pengumuman");
			}
		}
		else
			redirect("pengumuman");
	}
	
	public function rekap()
	{
		$data['title'] = "Pengumuman";
		$data['layout'] = "pengumuman/list";
		
		$this->load->model("tahunajaran_model");
		$tahun_ajaran = $this->input->get('thn_ajaran');
		$semester = $this->input->get('semester');
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		
		if(!$tahun_ajaran)
		{
			if(!empty($data['tahun_ajaran']))
				$tahun_ajaran = $data['tahun_ajaran'][0]["id_thn_ajaran"];
		}
		
		if(!isset($semester))
			$semester = 1;
		
		$filter = new StdClass();
		$filter->thn_ajaran = $tahun_ajaran;
		$filter->semester = $semester;
		
		list($data['data'],$total) = $this->pengumuman_model->getAll($filter,0,0,"p.tgl_input","desc");

		$this->load->view('template',$data);
	}
	
	public function detail($id = "")
	{
		$data['title'] = "Detail Pengumuman";
		$data['layout'] = "pengumuman/detail";
		if($id)
		{
			$dt =  $this->pengumuman_model->get_by("id_pengumuman",$id,true);
			if(!empty($dt))
			{
				$data['data'] = $dt;
				$this->load->view('template',$data);
			}
			else
				redirect("pengumuman/rekap");
		}
		else
			redirect("pengumuman/rekap");
		
		
	}
	
	public function generate_code()
	{
		$prefix = "M";
		$code = "0001";
		
		$last = $this->pengumuman_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_pengumuman,1,4) + 1;
			$code = str_pad($number, 4, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
