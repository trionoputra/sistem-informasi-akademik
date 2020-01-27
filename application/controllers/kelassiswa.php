<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kelassiswa extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("kelassiswa_model");
		$this->cekLoginStatus("sswk",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Tempati (Kelas Siswa)";
		$data['layout'] = "kelassiswa/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->id_kelas = trim($this->input->get('kelas'));
		$filter->id_thn_ajaran = trim($this->input->get('thn_ajaran'));
		$filter->jenis_kelamin = trim($this->input->get('gender'));
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
		list($data['data'],$total) = $this->kelassiswa_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("kelassiswa?");
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
		$data['title'] = "Form Tempati (Kelas Siswa)";
		$data['layout'] = "kelassiswa/manage";

		$data['data'] = new StdClass();
		$data['data']->id_tempati = "";
		$data['data']->id_kelas = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->no_absen = "";
		$data['data']->nis = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		
		if($id)
		{
			$dt =  $this->kelassiswa_model->get_by("t.id_tempati",$id,true);
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
			
			if(!empty($post['nis']))
				$data['nis'] = $post['nis'];
			else
				$error[] = "nis tidak boleh kosong";
			
			if(!empty($post['no_absen']))
				$data['no_absen'] = $post['no_absen'];
			else
				$error[] = "no absen tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->kelassiswa_model->cekKelas(null,$data['no_absen'],$data['id_kelas'],$data['id_thn_ajaran'],"no_absen");
				else
					$cek = $this->kelassiswa_model->cekKelas($id,$data['no_absen'],$data['id_kelas'],$data['id_thn_ajaran'],"no_absen");
				
				if(!empty($cek))
					$error[] = "No absen sudah terdaftar dikelas dan tahun ajaran tersebut";
					
				if(empty($id))
					$cek2 = $this->kelassiswa_model->cekKelas(null,$data['nis'],$data['id_kelas'],$data['id_thn_ajaran'],"nis");
				else
					$cek2 = $this->kelassiswa_model->cekKelas($id,$data['nis'],$data['id_kelas'],$data['id_thn_ajaran'],"nis");
					
				if(!empty($cek2))
					$error[] = "nis sudah terdaftar dikelas dan tahun ajaran tersebut";
					
			}
			
			if(empty($error))
			{
				
				if(empty($id))
					$cek3 = $this->kelassiswa_model->cekTahunAjaran(null,$data['nis'],$data['id_thn_ajaran'],"nis");
				else
					$cek3 = $this->kelassiswa_model->cekTahunAjaran($id,$data['nis'],$data['id_thn_ajaran'],"nis");
					
				if(!empty($cek3))
					$error[] = "nis sudah terdaftar ditahun ajaran tersebut";
					
			}
			
			if(empty($error))
			{
				$save = $this->kelassiswa_model->save($id,$data,false);
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("kelassiswa/manage/".$id);
				else
					redirect("kelassiswa");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("kelassiswa/manage/".$id);
			}
		}
		else
		  redirect("kelassiswa");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->kelassiswa_model->get_by("t.id_tempati",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID kelas siswa tidak terdaftar");
				redirect("kelassiswa");
			}
			else
			{
				if(!empty($this->kelassiswa_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "kelas siswa sedang digunakan");
					redirect("kelassiswa");
				}
				else
				{
					$this->kelassiswa_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("kelassiswa");
				}
			}
		}
		else
			redirect("kelassiswa");
	}
	
	public function rekap($tipe)
	{
		$data['title'] = "Laporan Siswa Per Kelas";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "5")
		{
			$data['layout'] = "kelassiswa/rekap_siswa";
			$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "5")
		{
			$data['layout'] = "kelassiswa/rekap_guru";
			
			$this->load->model("tahunajaran_model");
			$this->load->model("kelas_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$tahun_ajaran = $this->input->get('thn_ajaran');
			
			list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
			list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
			
			if(!$kelas)
			{
				if(!empty($data['kelas']))
					$kelas = $data['kelas'][0]["id_kelas"];
			}
			
			if(!$tahun_ajaran)
			{
				if(!empty($data['tahun_ajaran']))
					$tahun_ajaran = $data['tahun_ajaran'][0]["id_thn_ajaran"];
			}
			
			
			$filter = new StdClass();

			
		$filter->id_kelas = trim($this->input->get('kelas'));
		$filter->id_thn_ajaran = trim($this->input->get('thn_ajaran'));
		
		$filter->kelas = $filter->id_kelas;
		$filter->thn_ajaran = $filter->id_thn_ajaran; 
			
			list($data['data'],$total) = $this->kelassiswa_model->getAll($filter,0,0,"s.nama","asc");
			
			$this->load->model("walikelas_model");
			
			$data['walikelas'] = "";
			if($data['data'])
			{
				$data['walikelas'] = $data['data'][0]['guru'];
			}
				
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}
		else
			redirect("dashboard");
	}
	
	public function export($tipe,$action,$filter)
	{
		$title = "Laporan Siswa Per Kelas";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
		$data = $this->kelassiswa_model->export($filter,0,0,"s.nama","asc");
		
		list($wl,$t) = $this->kelassiswa_model->getAll($filter,0,0,"s.nama","asc");
		if($wl)
		{
			$walikelas = $wl[0]['guru'];
		}
		
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		$thn_ajaran = $this->tahunajaran_model->get_by("id_thn_ajaran",$filter->thn_ajaran,true);
		$kelas = $this->kelas_model->get_by("id_kelas",$filter->kelas,true);
		
		$extend = array("Tahun Ajaran" => $thn_ajaran->thn_ajaran,"Kelas" => $kelas->nm_kelas,"Wali Kelas" => $walikelas);
		
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			redirect("kelassiswa/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."");
		}
		else
		{
					
			if($action == "excel")
			{
				$this->load->library("excel");
				$this->excel->setActiveSheetIndex(0);
				$this->excel->stream($file_name.'.xls',$data,$headerTitle,$extend);
				
			}
			else if ($action == "pdf")
			{
				$this->load->library("pdf");
				$this->pdf->stream($data,$file_name,$headerTitle,$extend);
			}
		}
		
		
	}
}
