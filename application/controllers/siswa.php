<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Siswa extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("siswa_model");
		$this->cekLoginStatus("sswk",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Siswa";
		$data['layout'] = "siswa/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->jenis_kelamin = trim($this->input->get('gender'));
		$filter->agama = trim($this->input->get('agama'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->siswa_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("siswa?");
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
		$data['title'] = "Form Siswa";
		$data['layout'] = "siswa/manage";

		$data['data'] = new StdClass();
		$data['data']->nis = "";
		$data['data']->nis_nasional = "";
		$data['data']->nama = "";
		$data['data']->jenis_kelamin = "";
		$data['data']->tempat_lahir = "";
		$data['data']->tanggal_lahir = "";
		$data['data']->agama = "";
		$data['data']->alamat = "";
		$data['data']->anak_ke = "";
		$data['data']->tahun_masuk = "";
		$data['data']->tahun_keluar = "";
		$data['data']->alasan_keluar = "";
		$data['data']->nama_bapak = "";
		$data['data']->nama_ibu = "";
		$data['data']->pekerjaan_ibu = "";
		$data['data']->pekerjaan_bapak = "";
		$data['data']->pendidikan_bapak = "";
		$data['data']->pendidikan_ibu = "";
		$data['data']->alamat_ibu = "";
		$data['data']->alamat_bapak = "";
		$data['data']->email_ortu = "";
		$data['data']->telp_bapak = "";
		$data['data']->telp_ibu = "";
		$data['data']->nama_wali = "";
		$data['data']->alamat_wali = "";
		$data['data']->telp_wali = "";
		$data['data']->hubungan_wali = "";
		$data['data']->id_user = "";
		
		if($id)
		{
			$dt =  $this->siswa_model->get_by("nis",$id,true);
			
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
			
			if(!empty($post['nis']))
				$data['nis'] = $post['nis'];
			else
				$error[] = "NIS tidak boleh kosong"; 
			
			if(!empty($post['nis_nasional']))
				$data['nis_nasional'] = $post['nis_nasional'];
			else
				$error[] = "NIS nasional tidak boleh kosong"; 
			
			if(!empty($post['nama']))
				$data['nama'] = $post['nama'];
			else
				$error[] = "nama tidak boleh kosong"; 
				
			if(!empty($post['tempat_lahir']))
				$data['tempat_lahir'] = $post['tempat_lahir'];
			else
				$error[] = "tempat lahir tidak boleh kosong";
				
			if(!empty($post['gender']))
				$data['jenis_kelamin'] = $post['gender'];
			else
				$error[] = "jenis kelamin lahir tidak boleh kosong";
				
			if(!empty($post['tanggal_lahir']))
				$data['tanggal_lahir'] = date("Y-m-d",strtotime($post['tanggal_lahir']));
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
			
			if(!empty($post['tahun_masuk']))
			{
				$data['tahun_masuk'] = intval($post['tahun_masuk']);
				if(strlen($data['tahun_masuk']) != 4)
				{
					$error[] = "tahun masuk tidak benar"; 
				}
			}
			else
				$error[] = "tahun masuk tidak boleh kosong"; 
			
			
			
		//	if(!empty($post['tahun_keluar']))
		//	{
				$data['tahun_keluar'] = intval($post['tahun_keluar']);
				if(!empty($data['tahun_keluar']))
				{
					if(strlen($data['tahun_keluar']) != 4)
					{
						$error[] = "tahun keluar tidak benar"; 
					}
				}
				
		//	}
		//	else
		//		$error[] = "tahun keluar tidak boleh kosong";
			
		//	if(!empty($post['alasan_keluar']))
		//		$data['alasan_keluar'] = $post['alasan_keluar'];
		//	else
		//		$error[] = "alasan keluar tidak boleh kosong";
			
		//	if(!empty($post['anak_ke']))
				$data['anak_ke'] = $post['anak_ke'];
		//	else
		//		$error[] = "anak ke tidak boleh kosong";
			
		//	if(!empty($post['nama_bapak']))
				$data['nama_bapak'] = $post['nama_bapak'];
		//	else
		//		$error[] = "nama bapak tidak boleh kosong";
				
		//	if(!empty($post['nama_ibu']))
				$data['nama_ibu'] = $post['nama_ibu'];
		//	else
		//		$error[] = "nama ibu tidak boleh kosong";
				
		//	if(!empty($post['pekerjaan_bapak']))
				$data['pekerjaan_bapak'] = $post['pekerjaan_bapak'];
		//	else
		//		$error[] = "pekerjaan bapak tidak boleh kosong";
			
		//	if(!empty($post['pekerjaan_ibu']))
				$data['pekerjaan_ibu'] = $post['pekerjaan_ibu'];
		//	else
		//		$error[] = "pekerjaan ibu tidak boleh kosong";
				
		//	if(!empty($post['pendidikan_bapak']))
				$data['pendidikan_bapak'] = $post['pendidikan_bapak'];
		//	else
		//		$error[] = "pendidikan bapak tidak boleh kosong";
			
		//	if(!empty($post['pendidikan_ibu']))
				$data['pendidikan_ibu'] = $post['pendidikan_ibu'];
		//	else
		//		$error[] = "pendidikan ibu tidak boleh kosong";
			
		//	if(!empty($post['alamat_bapak']))
				$data['alamat_bapak'] = $post['alamat_bapak'];
		//	else
		//		$error[] = "alamat bapak tidak boleh kosong";
				
		///	if(!empty($post['alamat_ibu']))
				$data['alamat_ibu'] = $post['alamat_ibu'];
		//	else
		//		$error[] = "alamat ibu tidak boleh kosong";
			
		//	if(!empty($post['email_ortu']))
				$data['email_ortu'] = $post['email_ortu'];
		//	else
		//		$error[] = "email orang tua tidak boleh kosong";
			
		//	if(!empty($post['telp_bapak']))
				$data['telp_bapak'] = $post['telp_bapak'];
		//	else
		//		$error[] = "telepon bapak tidak boleh kosong";
				
		//	if(!empty($post['telp_ibu']))
				$data['telp_ibu'] = $post['telp_ibu'];
		//	else
		//		$error[] = "telepon ibu tidak boleh kosong";
				
		//	if(!empty($post['nama_wali']))
				$data['nama_wali'] = $post['nama_wali'];
		//	else
		//		$error[] = "nama wali tidak boleh kosong";
		//	
		//	if(!empty($post['alamat_wali']))
				$data['alamat_wali'] = $post['alamat_wali'];
		//	else
		//		$error[] = "alamat wali tidak boleh kosong";
			
		//	if(!empty($post['telp_wali']))
				$data['telp_wali'] = $post['telp_wali'];
		//	else
		//		$error[] = "telepon wali tidak boleh kosong";
				
		//	if(!empty($post['hubungan_wali']))
				$data['hubungan_wali'] = $post['hubungan_wali'];
		//	else
		//		$error[] = "hubungan wali tidak boleh kosong";
			
			
			if(empty($error))
			{
				if(empty($id))
					$cekNis = $this->siswa_model->get_by("nis",$post['nis']);
				
				if(!empty($cekNis))
					$error[] = "NIS sudah terdaftar"; 
				
				if(!empty($id))
					$cekNisNasional = $this->siswa_model->cekNis($id,$post['nis_nasional']);
				else
					$cekNisNasional = $this->siswa_model->get_by("nis",$post['nis_nasional']);
				
				if(!empty($cekNisNasional))
					$error[] = "NIS nasional sudah terdaftar"; 
					
			}
			
			if(empty($error))
			{
				if(empty($id))
				{
					$data['id_user'] = $this->generate_user_code();
				}
				
				$save = $this->siswa_model->save($id,$data,false);
				if(empty($id))
				{
					$user['id_user'] = $data['id_user'];
					$user['username'] = $data['nis'];
					$user['password'] =  md5(date("Ymd",strtotime($data['tanggal_lahir'])));
					$user['level'] = 3;
					
					$this->load->model("user_model");
					$this->user_model->save("",$user,false);
					
				}
				
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("siswa/manage/".$id);
				else
					redirect("siswa");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("siswa/manage/".$id);
			}
		}
		else
		  redirect("siswa");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->siswa_model->get_by("nis",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "NIS tidak terdaftar");
				redirect("siswa");
			}
			else
			{
				if(!empty($this->siswa_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "siswa sedang digunakan");
					redirect("siswa");
				}
				else
				{
					$this->siswa_model->remove($id);
					
					$this->load->model('user_model');
					
					$this->user_model->remove($cek->id_user);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("siswa");
				}
			}
		}
		else
			redirect("siswa");
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
}
