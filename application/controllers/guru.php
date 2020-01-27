<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guru extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("guru_model");
		$this->cekLoginStatus("ssks",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Guru";
		$data['layout'] = "guru/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->jenis_kelamin = trim($this->input->get('gender'));
		$filter->agama = trim($this->input->get('agama'));
		$filter->golongan = trim($this->input->get('golongan'));
		$filter->jabatan = trim($this->input->get('jabatan'));
		$filter->pendidikan = trim($this->input->get('pendidikan'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->guru_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("guru?");
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
		
		$data['title'] = "Form Guru";
		$data['layout'] = "guru/manage";

		$data['data'] = new StdClass();
		$data['data']->id_guru = "";
		$data['data']->nip = "";
		$data['data']->nama = "";
		$data['data']->jenis_kelamin = "";
		$data['data']->tempat_lahir = "";
		$data['data']->tgl_lahir = "";
		$data['data']->agama = "";
		$data['data']->alamat = "";
		$data['data']->jabatan = "";
		$data['data']->golongan = "";
		$data['data']->pendidikan_terakhir = "";
		$data['data']->email = "";
		$data['data']->telpon = "";
		$data['data']->tgl_masuk = "";
		$data['data']->id_user = "";
		
		$data['data']->autocode = $this->generate_code();
	
		if($id)
		{
			$dt =  $this->guru_model->get_by("id_guru",$id,true);
			
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
			
			if(!empty($post['id_guru']))
				$data['id_guru'] = $post['id_guru'];
			else
				$error[] = "id guru tidak boleh kosong"; 
			
			if(!empty($post['nip']))
				$data['nip'] = $post['nip'];
			else
				$error[] = "NIP tidak boleh kosong"; 
			
			if(!empty($post['nama']))
				$data['nama'] = $post['nama'];
			else
				$error[] = "nama tidak boleh kosong"; 
			
			if(!empty($post['gender']))
				$data['jenis_kelamin'] = $post['gender'];
			else
				$error[] = "jenis kelamin tidak boleh kosong";
				
			if(!empty($post['tempat_lahir']))
				$data['tempat_lahir'] = $post['tempat_lahir'];
			else
				$error[] = "tempat lahir tidak boleh kosong";
				
			if(!empty($post['tanggal_lahir']))
				$data['tgl_lahir'] = date("Y-m-d",strtotime($post['tanggal_lahir']));
			else
				$error[] = "tempat lahir tidak boleh kosong"; 
				
			if(!empty($post['agama']))
				$data['agama'] = $post['agama'];
			else
				$error[] = "agama tidak boleh kosong"; 
				
			if(!empty($post['alamat']))
				$data['alamat'] = $post['alamat'];
			else
				$error[] = "alamat tidak boleh kosong"; 
			
			
			if(!empty($post['jabatan']))
				$data['jabatan'] = $post['jabatan'];
			else
				$error[] = "jabatan tidak boleh kosong";
			
			if(!empty($post['golongan']))
				$data['golongan'] = $post['golongan'];
			else
				$error[] = "golongan tidak boleh kosong";
			
			if(!empty($post['pendidikan_terakhir']))
				$data['pendidikan_terakhir'] = $post['pendidikan_terakhir'];
			else
				$error[] = "pendidikan terakhir tidak boleh kosong";
				
		//	if(!empty($post['email']))
				$data['email'] = $post['email'];
			//else
			//	$error[] = "email tidak boleh kosong";
				
		//	if(!empty($post['telepon']))
				$data['telpon'] = $post['telepon'];
		//	else
			//	$error[] = "telepon tidak boleh kosong";
				
			if(!empty($post['tanggal_masuk']))
				$data['tgl_masuk'] = date("Y-m-d",strtotime($post['tanggal_masuk']));
			else
				$error[] = "tanggal masuk tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->guru_model->get_by("id_guru",$post['id_guru']);
				
				if(!empty($cekId))
					$error[] = "ID guru sudah terdaftar"; 
				
				if(!empty($id))
					$cekNip = $this->guru_model->cekNip($id,$post['nip']);
				else
					$cekNip = $this->guru_model->get_by("nip",$post['nip']);
					
				if(!empty($cekNip))
					$error[] = "NIP sudah terdaftar"; 
			}
			
			if(empty($error))
			{
				if(empty($id))
				{
					$data['id_user'] = $this->generate_user_code();
				}
				
				$save = $this->guru_model->save($id,$data,false);
				if(empty($id))
				{
					$user['id_user'] = $data['id_user'];
					$user['username'] = $data['nip'];
					$user['password'] =  md5(date("Ymd",strtotime($data['tgl_lahir'])));
					$user['level'] = 2;
					
					$this->load->model("user_model");
					$this->user_model->save("",$user,false);
					
				}
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("guru/manage/".$id);
				else
					redirect("guru");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("guru/manage/".$id);
			}
		}
		else
		  redirect("guru");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->guru_model->get_by("id_guru",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID guru tidak terdaftar");
				redirect("guru");
			}
			else
			{
				if(!empty($this->guru_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "guru sedang digunakan");
					redirect("guru");
				}
				else
				{
					
					$this->guru_model->remove($id);
					
					$this->load->model('user_model');
					$this->user_model->remove($cek->id_user);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("guru");
				}
			}
		}
		else
			redirect("guru");
	}
	
	public function generate_user_code()
	{
		$this->load->model("user_model");
		$prefix = "U";
		$code = "000000001";
		
		$last = $this->user_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_user,1,9) +1;
			$code = str_pad($number, 9, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
	public function generate_code()
	{
		$prefix = "";
		$code = "001";
		
		$last = $this->guru_model->get_last();
		if(!empty($last))
		{
			$number = $last->id_guru + 1;
			$code = str_pad($number, 3, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
