<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Absen extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("absen_model");
    }
	
	public function index()
	{
		$this->cekLoginStatus("sswk",true);
		$data['title'] = "Data Absen";
		$data['layout'] = "absen/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->alasan = trim($this->input->get('alasan'));
		$filter->kelas = trim($this->input->get('kelas'));
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
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		
		list($data['data'],$total) = $this->absen_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("absen?");
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
		
		$this->cekLoginStatus("sswk",true);
		$data['title'] = "Form Absen";
		$data['layout'] = "absen/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_absen = "";
		$data['data']->semester = "";
		$data['data']->id_kelas = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->nis = "";
		$data['data']->id_tempati = "";
		$data['data']->keterangan = "";
		$data['data']->alasan = "";
		$data['data']->tgl_absen = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		if($id)
		{
			$dt =  $this->absen_model->get_by("a.id_absen",$id,true);
			if(!empty($dt))
			{
				$data['data'] = $dt;
			}
		}
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		
		$this->cekLoginStatus("sswk",true);
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			$error = array();
			$id = $post['id'];
			
			
			if(!empty($post['alasan']))
				$data['alasan'] = $post['alasan'];
			else
				$error[] = "alasan tidak boleh kosong";
			
			if(!empty($post['keterangan']))
				$data['keterangan'] = $post['keterangan'];
			else
				$error[] = "keterangan tidak boleh kosong";
				
			if(!empty($post['semester']))
				$data['semester'] = $post['semester'];
			else
				$error[] = "semester tidak boleh kosong";
	
			if(!empty($post['id_tempati']))
				$data['id_tempati'] = $post['id_tempati'];
			else
				$error[] = "id kelas siswa tidak boleh kosong";
				
			if(!empty($post['tgl_absen']))
				$data['tgl_absen'] = date("Y-m-d",strtotime($post['tgl_absen']));
			else
				$error[] = "id kelas tidak boleh kosong";
		
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->absen_model->cekAbsen(null,$data['id_tempati'],$data['semester'],$data['tgl_absen']);
				else
					$cek = $this->absen_model->cekAbsen($id,$data['id_tempati'],$data['semester'],$data['tgl_absen']);
				
				if(!empty($cek))
					$error[] = "absen sudah terdaftar";
			
			}
			
			if(empty($error))
			{
			
				$this->load->helper('string');
					
				$absen['id_tempati'] = $data['id_tempati'];
				$absen['semester'] = $data['semester'];
				$absen['tgl_absen'] =  $data['tgl_absen'];
				$absen['alasan'] = $data['alasan'];
				$absen['keterangan'] = $data['keterangan'];
				
				$save = $this->absen_model->save($id,$absen,true);
				
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("absen/manage/".$id);
				else
					redirect("absen");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("absen/manage/".$id);
			}
		}
		else
		  redirect("absen");
	}
	
	public function delete($id = "")
	{
		
		$this->cekLoginStatus("sswk",true);
		if(!empty($id))
		{
			$cek = $this->absen_model->get_by("id_absen",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("absen");
			}
			else
			{
				$this->absen_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("absen");
			}
		}
		else
			redirect("absen");
	}
	
	public function rekap($tipe)
	{
		$data['title'] = "Laporan Absen Siswa";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "3")
		{
			$data['layout'] = "absen/rekap_siswa";
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
			$data["no_absen"] = $tahun_ajaran[0]['no_absen'];
			
			list($data['data'],$total) = $this->absen_model->getAll($filter,0,0,"a.tgl_absen","desc");
			
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "3")
		{
			$data['layout'] = "absen/rekap_guru";
			
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
			
			list($data,$totalK) = $this->absen_model->getAll($filter,0,0,"s.nama","asc");
			
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
		$title = "Laporan Absen Siswa";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
		list($data,$totalK) = $this->absen_model->getAll($filter,0,0,"s.nama","asc");
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		$kelas = $this->kelas_model->get_by("id_kelas",$filter->kelas,true);
			
		if($tipe == "guru")
		{
			$this->load->model("tahunajaran_model");
			$thn_ajaran = $this->tahunajaran_model->get_by("id_thn_ajaran",$filter->thn_ajaran,true);
			$extend = array("Tahun Ajaran" => $thn_ajaran->thn_ajaran,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester);
		}
		else
		{
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			list($thn_ajaran,$total) = $this->absen_model->getAll($filter,0,0,"s.nama","asc");
		
			$extend = array("NIS" =>$siswa->nis,"Nama" =>$siswa->nama,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
			
		}
		
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			if($tipe == "guru")
				redirect("absen/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."&semester=".$filter->semester."");
			else
				redirect("absen/rekap/".$tipe."?kelas=".$filter->kelas."&semester=".$filter->semester."");
		}
		else
		{
			if($action == "excel")
			{
				$this->load->library("excel");
				$this->excel->setActiveSheetIndex(0);
				$this->excel->stream($file_name.'.xls',$this->generate_absen($data),$headerTitle,$extend);
			}
			else if ($action == "pdf")
			{
				$this->load->library("pdf");
				$this->pdf->stream($this->generate_absen($data),$file_name,$headerTitle,$extend);
			}
		}
		
		
	}
	
	function generate_absen($data)
	{
		$newdata = array();
	
		foreach($data as $key => $dt)
		{
			$dat = array();
			$dat['NO'] = $key+1;
			$dat['Tanggal'] = $dt['tgl_absen'];
			$dat['Keterangan'] = $dt['alasan'] = 'i' ? 'Ijin' : $dt['alasan'] = 'a' ? 'Alpa' : 'Sakit';
			$dat['Catatan'] = $dt['keterangan'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
}
