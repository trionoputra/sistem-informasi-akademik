<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jadwal extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("jadwal_model");
    }
	
	public function index()
	{
		$this->cekLoginStatus("sswkgr",true);
		$data['title'] = "Data Jadwal Pelajaran";
		$data['layout'] = "jadwal/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->pelajaran = trim($this->input->get('pelajaran'));
		$filter->kelas = trim($this->input->get('kelas'));
		$filter->hari = trim($this->input->get('hari'));
		$filter->thn_ajaran = trim($this->input->get('thn_ajaran'));
		$filter->semester = trim($this->input->get('semester'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		$this->load->model("pelajaran_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['pelajaran'],$totalK) = $this->pelajaran_model->getAll(null,null,null,"id_pelajaran",'asc');
		
		list($data['data'],$total) = $this->jadwal_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("jadwal?");
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
		$data['title'] = "Form Jadwal Pelajaran";
		$data['layout'] = "jadwal/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_jadwal = "";
		$data['data']->semester = "";
		$data['data']->id_kelas = "";
		$data['data']->id_ajar = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->nama = "";
		$data['data']->deskripsi = "";
		$data['data']->senin = "";
		$data['data']->selasa = "";
		$data['data']->rabu = "";
		$data['data']->kamis = "";
		$data['data']->jumat = "";
		$data['data']->sabtu = "";
		$data['data']->minggu = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		
		if($id)
		{
			$dt =  $this->jadwal_model->get_by("j.id_jadwal",$id,true);
			
			$dt->senin = "";
			$dt->selasa = "";
			$dt->rabu = "";
			$dt->kamis = "";
			$dt->jumat = "";
			$dt->sabtu = "";
			$dt->minggu = "";
			$waktu = explode(",",$dt->waktu);
			foreach($waktu as $wk)
			{
				$w = explode(" - ",$wk);
				$hari = $w[0];
				$jam = $w[1];
				if($hari == "senin")
					$dt->senin = $jam;
				if($hari == "selasa")
					$dt->selasa = $jam;
				if($hari == "rabu")
					$dt->rabu = $jam;
				if($hari == "kamis")
					$dt->kamis = $jam;
				if($hari == "jumat")
					$dt->jumat = $jam;
				if($hari == "sabtu")
					$dt->sabtu = $jam;
				if($hari == "minggu")
					$dt->minggu = $jam;
			}
		
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
			
			if(!empty($post['id_thn_ajaran']))
				$data['id_thn_ajaran'] = $post['id_thn_ajaran'];
			else
				$error[] = "id tahun ajaran tidak boleh kosong"; 
			
			if(!empty($post['semester']))
				$data['semester'] = $post['semester'];
			else
				$error[] = "semester tidak boleh kosong";
			
			if(!empty($post['id_ajar']))
				$data['id_ajar'] = $post['id_ajar'];
			else
				$error[] = "pelajaran tidak boleh kosong";
				
			if(!empty($post['id_kelas']))
				$data['id_kelas'] = $post['id_kelas'];
			else
				$error[] = "id kelas tidak boleh kosong";
			
			if(!isset($post['hari']))
				$error[] = "pilih minimal satu hari";
			else
			{
				$data['hari'] = $post['hari'];
				foreach($data['hari'] as $hr)
				{
					if(!empty($post[$hr]))
					{
						$data[$hr] = $post[$hr];
						$matches = preg_match("/^-?([0-7]?[0-9]{1,2}|8([0-2][0-9]|3[0-8]))(:[0-5][0-9]){1,2}$/D", $data[$hr]);
						if($matches <= 0)
						{
							$error[] = "format jam tidak valid";
							break;
						}
					}
					else
					{
						$error[] = "pilih waktu dihari yang terpilih";
						break;
					}
				}
			}
		
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->jadwal_model->cekJadwal(null,$data['id_kelas'],$data['semester'],$data['id_ajar']);
				else
					$cek = $this->jadwal_model->cekJadwal($id,$data['id_kelas'],$data['semester'],$data['id_ajar']);
				
				if(!empty($cek))
					$error[] = "jadwal sudah terdaftar";
			
			}
			
			if(empty($error))
			{
			
				$this->load->helper('string');
					
				$jadwal['id_ajar'] = $data['id_ajar'];
				$jadwal['semester'] = $data['semester'];
				$jadwal['id_kelas'] = $data['id_kelas'];
				
				if(empty($id))
					$jadwal['id_jadwal'] = random_string('unique');
				
				$save = $this->jadwal_model->save($id,$jadwal,true);
				
				if(!empty($id))
					$this->jadwal_model->remove_detail($id);
					
				foreach($data['hari'] as $hr)
				{
					$data[$hr] = $post[$hr];
					if(empty($id))
						$detail['id_jadwal'] = $jadwal['id_jadwal'];
					else
						$detail['id_jadwal'] = $id;
					$detail['hari'] = $hr;
					$detail['jam'] = $data[$hr];
					$this->jadwal_model->save_detail($detail);
				}
				
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("jadwal/manage/".$id);
				else
					redirect("jadwal");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("jadwal/manage/".$id);
			}
		}
		else
		  redirect("jadwal");
	}
	
	public function delete($id = "")
	{
		$this->cekLoginStatus("sswkgr",true);
		if(!empty($id))
		{
			$cek = $this->jadwal_model->get_by("j.id_jadwal",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("jadwal");
			}
			else
			{
				if(!empty($this->jadwal_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "jadwal sedang digunakan");
					redirect("jadwal");
				}
				else
				{
					$this->jadwal_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("jadwal");
				}
			}
		}
		else
			redirect("jadwal");
	}
	
	
	public function rekap($tipe)
	{
		$data['title'] = "Laporan Jadwal";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "3")
		{
			$data['layout'] = "jadwal/rekap_siswa";
			$this->load->model("kelassiswa_model");
			$this->load->model("siswa_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$semester = $this->input->get('semester');
			
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			
			$data["nis"] = $siswa->nis;
			$data["nama"] = $siswa->nama;
			$data['kelas'] = $this->kelassiswa_model->get_by("t.nis",$data["nis"]);
			
			$filter = new StdClass();
			$ex = explode("|",$kelas);
			
			$kelas = $ex[0];
			if(isset($ex[1]))
			{
				$idtempati = $ex[1];
				$filter->idtempati = $idtempati;
				
			}
			else
			{
				$filter->idtempati = $data['kelas'][0]['id_tempati'];
			}
			$filter->kelas = $kelas;
			$filter->semester = $semester;
			$filter->nis = $data["nis"];
			
			list($tahun_ajaran,$t) =  $this->kelassiswa_model->getAll($filter,0,0,"s.nama","asc");
			
			if(!$kelas)
			{
				if(!empty($data['kelas']))
					$kelas = $data['kelas'][0]["id_kelas"];
			}
			if(!isset($semester))
				$semester = 1;
				
			$data["tahun_ajaran"] = $tahun_ajaran[0]['thn_ajaran'];
			
			$filter->thn_ajaran = $tahun_ajaran[0]['id_thn_ajaran'];
			list($data['data'],$total) = $this->jadwal_model->getAll($filter,0,0,"p.deskripsi","asc");
			
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "3")
		{
			$data['layout'] = "jadwal/rekap_guru";
			
			$this->load->model("tahunajaran_model");
			$this->load->model("kelas_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$tahun_ajaran = $this->input->get('thn_ajaran');
			$semester = $this->input->get('semester');
			
			list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
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
			
			if(!isset($semester))
				$semester = 1;
			
			$filter = new StdClass();
			$filter->kelas = $kelas;
			$filter->thn_ajaran = $tahun_ajaran;
			$filter->semester = $semester;
			
			list($data['data'],$t) = $this->jadwal_model->getAll($filter,0,0,"k.id_kelas","asc");
		
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
		$title = "Laporan Jadwal";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
		$data = $this->jadwal_model->export($filter,0,0,"k.id_kelas","asc");
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		if($tipe == "guru")
		{
			$this->load->model("tahunajaran_model");
			$thn_ajaran = $this->tahunajaran_model->get_by("id_thn_ajaran",$filter->thn_ajaran,true);
			$extend = array("Tahun Ajaran" => $thn_ajaran->thn_ajaran,"Semester" => $filter->semester);
		}
		else
		{
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			list($thn_ajaran,$total) = $this->jadwal_model->getAll($filter,0,0,"p.deskripsi","asc");
			
			$kelas = $this->kelassiswa_model->get_by("t.nis",$siswa->nis,true);
			$extend = array("Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
			
			$data = $this->generate_siswa_format($data);
		}
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			if($tipe == "guru")
				redirect("jadwal/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."&semester=".$filter->semester."");
			else
				redirect("jadwal/rekap/".$tipe."?kelas=".$filter->kelas."&semester=".$filter->semester."");
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
	
	public function generate_siswa_format($data)
	{
		$newdata = array();
		
		foreach($data as $key => $dt)
		{
			$dat = array();
			$dat['No'] = $key+1;
			$dat['Mata Pelajaran'] = $dt['Mata Pelajaran'];
			$dat['Nama Guru'] = $dt['Nama Guru'];
			$dat['Waktu'] = $dt['Waktu'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}

}
