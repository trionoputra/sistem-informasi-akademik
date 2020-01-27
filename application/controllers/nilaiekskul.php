<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nilaiekskul extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("nilaiekskul_model");
    }
	
	public function index()
	{
	
		$this->cekLoginStatus("sswkgr",true);
		$data['title'] = "Data Nilai Ektrakulikuler";
		$data['layout'] = "nilaiekskul/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->ekskul = trim($this->input->get('ekskul'));
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
		$this->load->model("ekskul_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['ekskul'],$totalK) = $this->ekskul_model->getAll(null,null,null,"id_ekskul",'asc');
		
		list($data['data'],$total) = $this->nilaiekskul_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("nilaiekskul?");
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
		$data['title'] = "Form Nilai Ektrakulikuler";
		$data['layout'] = "nilaiekskul/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_nilaiekskul = "";
		$data['data']->semester = "";
		$data['data']->id_kelas = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->id_ekskul = "";
		$data['data']->deskripsi = "";
		$data['data']->id_tempati = "";
		$data['data']->nis = "";
		$data['data']->nilai = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		$this->load->model("ekskul_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['ekskul'],$totalK) = $this->ekskul_model->getAll(null,null,null,"id_ekskul",'asc');
		
		if($id)
		{
			$dt =  $this->nilaiekskul_model->get_by("n.id_nilaiekskul",$id,true);
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
			
			if(!empty($post['id_ekskul']))
				$data['id_ekskul'] = $post['id_ekskul'];
			else
				$error[] = "id ekskul tidak boleh kosong"; 
				
			if(!empty($post['id_thn_ajaran']))
				$data['id_thn_ajaran'] = $post['id_thn_ajaran'];
			else
				$error[] = "tahun ajaran tidak boleh kosong"; 
			
			if(!empty($post['semester']))
				$data['semester'] = $post['semester'];
			else
				$error[] = "semester tidak boleh kosong";
			
				
			if(!empty($post['id_kelas']))
				$data['id_kelas'] = $post['id_kelas'];
			else
				$error[] = "kelas tidak boleh kosong";
				
			if(!empty($post['id_tempati']))
				$data['id_tempati'] = $post['id_tempati'];
			else
				$error[] = "siswa tidak boleh kosong";
				
			if(!empty($post['nilai']))
			{
				$data['nilai'] = $post['nilai'];
				if(!is_numeric($data['nilai']))
					$error[] = "format nilai tidak bener";
				
			}
			else
				$error[] = "nilai tidak boleh kosong";
				
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->nilaiekskul_model->cekNilai(null,$data['id_ekskul'],$data['semester'],$data['id_tempati']);
				else
					$cek = $this->nilaiekskul_model->cekNilai($id,$data['id_ekskul'],$data['semester'],$data['id_tempati']);
				
				if(!empty($cek))
					$error[] = "nilai sudah terdaftar";
			}
			
			if(empty($error))
			{
			
				$nilaiekskul['id_ekskul'] = $data['id_ekskul'];
				$nilaiekskul['id_tempati'] = $data['id_tempati'];
				$nilaiekskul['semester'] = $data['semester'];
				$nilaiekskul['nilai'] = $data['nilai'];
				
				$save = $this->nilaiekskul_model->save($id,$nilaiekskul,true);
				
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("nilaiekskul/manage/".$id);
				else
					redirect("nilaiekskul");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("nilaiekskul/manage/".$id);
			}
		}
		else
		  redirect("nilaiekskul");
	}
	
	public function delete($id = "")
	{
	
		$this->cekLoginStatus("sswkgr",true);
		if(!empty($id))
		{
			$cek = $this->nilaiekskul_model->get_by("n.id_nilaiekskul",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("nilaiekskul");
			}
			else
			{
				$this->nilaiekskul_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("nilaiekskul");
			}
		}
		else
			redirect("nilaiekskul");
	}
	
	public function rekap($tipe)
	{
		
		$data['title'] = "Laporan Nilai Ekstrakulikuler Siswa";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "3")
		{
			$data['layout'] = "nilaiekskul/rekap_siswa";
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
			
			list($data['data'],$total) = $this->nilaiekskul_model->getAll($filter,0,0,"s.nama","asc");
			
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "3")
		{
			$data['layout'] = "nilaiekskul/rekap_guru";
			
			$this->load->model("tahunajaran_model");
			$this->load->model("kelas_model");
			$this->load->model("ekskul_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$tahun_ajaran = $this->input->get('thn_ajaran');
			$semester = $this->input->get('semester');
			$ekskul = $this->input->get('ekskul');
			
			
			list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
			list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
			list($data['ekskul'],$totalK) = $this->ekskul_model->getAll(null,null,null,"id_ekskul",'asc');
			
			
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
			
			if(!$ekskul)
			{
				if(!empty($data['ekskul']))
					$ekskul = $data['ekskul'][0]["id_ekskul"];
			}
			
			if(!$semester)
				$semester = 1;
			
			$filter = new StdClass();
			$filter->kelas = $kelas;
			$filter->thn_ajaran = $tahun_ajaran;
			$filter->semester = $semester;
			$filter->ekskul = $ekskul;
		
			list($data['data'],$total) = $this->nilaiekskul_model->getAll($filter,0,0,"s.nama","asc");
				
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
		$title = "Laporan Nilai Ekstrakulikuler Siswa";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
	
		$data = $this->nilaiekskul_model->export($filter,0,0,"s.nama","asc");
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
	

		$kelas = $this->kelas_model->get_by("id_kelas",$filter->kelas,true);
		
		
		if($tipe == "guru")
		{
			$this->load->model("tahunajaran_model");
			$ekskul = $this->ekskul_model->get_by("id_ekskul",$filter->ekskul,true);
			$this->load->model("ekskul_model");
			$thn_ajaran = $this->tahunajaran_model->get_by("id_thn_ajaran",$filter->thn_ajaran,true);
			$extend = array("Tahun Ajaran" => $thn_ajaran->thn_ajaran,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester);
		}
		else
		{
			
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			list($thn_ajaran,$total) = $this->nilaiekskul_model->getAll($filter,0,0,"s.nama","asc");
			
			$extend = array("NIS" =>$siswa->nis,"Nama" =>$siswa->nama,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
			
			$data = $this->generate_siswa_format($data);
		}
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			if($tipe == "guru")
				redirect("nilaiekskul/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."&semester=".$filter->semester."");
			else
				redirect("nilaiekskul/rekap/".$tipe."?kelas=".$filter->kelas."&semester=".$filter->semester."");
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
			$dat['Kegiatan Ekstrakulikuler'] = $dt['Kegiatan Ekstrakulikuler'];
			$dat['Nilai Angka'] = $dt['Nilai Angka'];
			$dat['Nilai Huruf'] = $dt['Nilai Huruf'];
			
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
}
