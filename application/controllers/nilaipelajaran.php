<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nilaipelajaran extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("nilaipelajaran_model");
    }
	
	public function index()
	{
		
		$this->cekLoginStatus("sswkgr",true);
		$data['title'] = "Data Nilai Pelajaran";
		$data['layout'] = "nilaipelajaran/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->pelajaran = trim($this->input->get('pelajaran'));
		$filter->kelas = trim($this->input->get('kelas'));
		$filter->thn_ajaran = trim($this->input->get('thn_ajaran'));
		$filter->semester = trim($this->input->get('semester'));
		$filter->jenis_nilai = trim($this->input->get('jenis_nilai'));
		
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
		$this->load->model("jenisnilai_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['jenis_nilai'],$totalK) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
		
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['pelajaran'],$totalK) = $this->pelajaran_model->getAll(null,null,null,"id_pelajaran",'asc');
		
		if($this->getStatus() == 2)
		{
			
			$this->load->model("guruspesialis_model");
			$this->load->model("guru_model");
			$filter2 = new StdClass();
			
			$guru =  $this->guru_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			$filter2->id_guru = $guru->id_guru;
			$filter->id_guru = $guru->id_guru;
			list($data['pelajaran'],$totalK) = $this->guruspesialis_model->getAll($filter2->id_guru,0,0,"id_pelajaran",'asc');
		}
		
		list($data['data'],$total) = $this->nilaipelajaran_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		if($this->getStatus() == 4)
		{
			$this->load->model("guru_model");
			$this->load->model("walikelas_model");
			
			$filter2 = new StdClass();
			
			$guru =  $this->guru_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			$filter2->id_guru = $guru->id_guru;
			
			list($data['kelas'],$totalK) = $this->walikelas_model->getAll($filter2->id_guru,0,0,"id_kelas",'asc');
		}

		$this->load->library('pagination');
		$config['base_url'] = site_url("nilaipelajaran?");
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
		$data['title'] = "Form Nilai Pelajaran";
		$data['layout'] = "nilaipelajaran/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_nilai = "";
		$data['data']->semester = "";
		$data['data']->id_kelas = "";
		$data['data']->id_jadwal = "";
		$data['data']->id_jenis_nilai = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->nama = "";
		$data['data']->nama_guru = "";
		$data['data']->deskripsi = "";
		$data['data']->id_tempati = "";
		$data['data']->nis = "";
		$data['data']->nilai = "";
/*		$data['data']->keterangan = ""; */
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		$this->load->model("jenisnilai_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['jenis_nilai'],$totalK) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
		
		if($this->getStatus() == 4)
		{
			$this->load->model("guru_model");
			$this->load->model("walikelas_model");
			
			$filter2 = new StdClass();
			
			$guru =  $this->guru_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			$filter2->id_guru = $guru->id_guru;
			
			list($data['kelas'],$totalK) = $this->walikelas_model->getAll($filter2->id_guru,0,0,"id_kelas",'asc');
		}
		
		if($id)
		{
			$dt =  $this->nilaipelajaran_model->get_by("n.id_nilai",$id,true);
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
				$error[] = "tahun ajaran tidak boleh kosong"; 
			
			if(!empty($post['semester']))
				$data['semester'] = $post['semester'];
			else
				$error[] = "semester tidak boleh kosong";
			
			if(!empty($post['id_jadwal']))
				$data['id_jadwal'] = $post['id_jadwal'];
			else
				$error[] = "pelajaran tidak boleh kosong";
				
			if(!empty($post['id_kelas']))
				$data['id_kelas'] = $post['id_kelas'];
			else
				$error[] = "kelas tidak boleh kosong";
				
			if(!empty($post['id_tempati']))
				$data['id_tempati'] = $post['id_tempati'];
			else
				$error[] = "siswa tidak boleh kosong";
				
			if(!empty($post['id_jenis_nilai']))
				$data['id_jenis_nilai'] = $post['id_jenis_nilai'];
			else
				$error[] = "jenis nilai tidak boleh kosong";
				
			if(!empty($post['nilai']))
			{
				$data['nilai'] = $post['nilai'];
				if(!is_numeric($data['nilai']))
					$error[] = "format nilai tidak bener";
				
			}
			else
				$error[] = "nilai tidak boleh kosong";
				
			
			
/*			$data['keterangan'] = $post['keterangan']; */
			
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->nilaipelajaran_model->cekNilai(null,$data['id_jadwal'],$data['id_jenis_nilai'],$data['id_tempati']);
				else
					$cek = $this->nilaipelajaran_model->cekNilai($id,$data['id_jadwal'],$data['id_jenis_nilai'],$data['id_tempati']);
				
				if(!empty($cek))
					$error[] = "nilai sudah terdaftar";
			}
			
			if(empty($error))
			{
			
				$nilaipelajaran['id_jadwal'] = $data['id_jadwal'];
				$nilaipelajaran['id_tempati'] = $data['id_tempati'];
				$nilaipelajaran['id_jenis_nilai'] = $data['id_jenis_nilai'];
				$nilaipelajaran['nilai'] = $data['nilai'];
/*				$nilaipelajaran['keterangan'] = $data['keterangan']; */
				
				$save = $this->nilaipelajaran_model->save($id,$nilaipelajaran,true);
				
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("nilaipelajaran/manage/".$id);
				else
					redirect("nilaipelajaran");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("nilaipelajaran/manage/".$id);
			}
		}
		else
		  redirect("nilaipelajaran");
	}
	
	public function delete($id = "")
	{
		$this->cekLoginStatus("sswkgr",true);
		if(!empty($id))
		{
			$cek = $this->nilaipelajaran_model->get_by("n.id_nilai",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("nilaipelajaran");
			}
			else
			{
				$this->nilaipelajaran_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("nilaipelajaran");
			}
		}
		else
			redirect("nilaipelajaran");
	}
	
	public function rekap($tipe)
	{
		$data['title'] = "Laporan Nilai Pelajaran Siswa";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "3")
		{
			$data['title'] = "Laporan Nilai Pelajaran";
			$data['layout'] = "nilaipelajaran/rekap_siswa";
			
			$this->load->model("kelassiswa_model");
			$this->load->model("siswa_model");
			$this->load->model("jenisnilai_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$semester = $this->input->get('semester');
			
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			list($data['jenis_nilai'],$totalP) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
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
			
			list($data['data'],$total) = $this->nilaipelajaran_model->getAllRekap($filter,0,0,"p.deskripsi","asc");
			
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "3")
		{
			$data['layout'] = "nilaipelajaran/rekap_guru";
			
			$this->load->model("tahunajaran_model");
			$this->load->model("kelas_model");
			$this->load->model("pelajaran_model");
			$this->load->model("jenisnilai_model");
			
			$action = $this->input->get('action');
			$kelas = $this->input->get('kelas');
			$tahun_ajaran = $this->input->get('thn_ajaran');
			$semester = $this->input->get('semester');
			$pelajaran = $this->input->get('pelajaran');
			
			list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
			list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
			list($data['pelajaran'],$totalP) = $this->pelajaran_model->getAll(null,null,null,"id_pelajaran",'asc');
			list($data['jenis_nilai'],$totalP) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
			
			if(!($kelas))
			{
				if(!empty($data['kelas']))
					$kelas = $data['kelas'][0]["id_kelas"];
			}
			
			if(!($tahun_ajaran))
			{
				if(!empty($data['tahun_ajaran']))
					$tahun_ajaran = $data['tahun_ajaran'][0]["id_thn_ajaran"];
			}
			
			if(!($pelajaran))
			{
				if(!empty($data['pelajaran']))
					$pelajaran = $data['pelajaran'][0]["id_pelajaran"];
			}
			
			
			if(!($semester))
				$semester = 1;
			
			$filter = new StdClass();
			$filter->kelas = $kelas;
			$filter->thn_ajaran = $tahun_ajaran;
			$filter->semester = $semester;
			$filter->pelajaran = $pelajaran;
			
			list($data['data'],$total) = $this->nilaipelajaran_model->getAllRekap($filter,0,0,"s.nama","asc");
			
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
		$title = "Laporan  Nilai Pelajaran Siswa";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
		list($data,$total) = $this->nilaipelajaran_model->getAllRekap($filter,0,0,"s.nama","asc");
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		
		$kelas = $this->kelas_model->get_by("id_kelas",$filter->kelas,true);
		
		
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
		}
		
		
		if($tipe == "guru")
		{
			$this->load->model("tahunajaran_model");
			$this->load->model("pelajaran_model");
			
			$pelajaran = $this->pelajaran_model->get_by("id_pelajaran",$filter->pelajaran,true);
			$thn_ajaran = $this->tahunajaran_model->get_by("id_thn_ajaran",$filter->thn_ajaran,true);
			$extend = array("Tahun Ajaran" => $thn_ajaran->thn_ajaran,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"pelajaran" => $pelajaran->deskripsi);
		}
		else
		{
			
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			list($thn_ajaran,$total) = $this->nilaipelajaran_model->getAll($filter,0,0,"s.nama","asc");
			
			$extend = array("NIS" =>$siswa->nis,"Nama" =>$siswa->nama,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
			
		}
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			if($tipe == "guru")
				redirect("nilaipelajaran/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."&semester=".$filter->semester."&pelajaran=".$filter->pelajaran."");
			else
				redirect("nilaipelajaran/rekap/".$tipe."?kelas=".$filter->kelas."&semester=".$filter->semester."");
		}
		else
		{
					
			if($action == "excel")
			{
				$this->load->library("excel");
				$this->excel->setActiveSheetIndex(0);
				
				$this->excel->stream($file_name.'.xls',$this->generate_data_excel($data,$tipe),$headerTitle,$extend);
				
			}
			else if ($action == "pdf")
			{
				$this->load->library("pdf");
				
				$pdfData = $this->generate_data_pdf($data,$tipe);
				$dat = $pdfData[0];
				$extend2 = $pdfData[1];
			
				$this->pdf->stream($dat ,$file_name,$headerTitle,$extend,$extend2,"L");
			}
		}
	}
	
	
	public function generate_data_pdf($data,$tipe)
	{
		$newdata = array();
		$newdataExted = array();
		$this->load->model("jenisnilai_model");
		
		list($jenis_nilai,$totalP) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
		
		$report = array();
		foreach($data as $key => $dt){
			
			if($tipe == "guru")
			{
				$report['1'] = $dt['no_absen'];
				$report['2'] = $dt['nama'];
				$newdataExted[] = "<b>1</b> = No Absen";
				$newdataExted[] = "<b>2</b> = Nama";
				
				$count = 2;
			}
			else if($tipe == "siswa")
			{
				$report['1'] = $key+1;
				$report['2'] = $dt['deskripsi'];
				$report['3'] = $dt['kkm'];
				$newdataExted[] = "<b>1</b> = No";
				$newdataExted[] = "<b>2</b> = Deskripsi";
				$newdataExted[] = "<b>3</b> = KKM";
				$count = 3;
			}
			
			
			$jenis =  explode(",",$dt['jenis_nilai']);
			$n =  explode(",",$dt['nilai']);
			
			$ct =  0;
			$tt =  0;
			$hasTugas = false;
			$hasTugasValue = false;
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'tgs') !== false)
				{
					$count++;
					$nn = "-";
					$hasTugas = true;
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];
							$ct++;
							$tt += $n[$k];
							$hasTugasValue = TRUE;	
						}
					 }
					$report[$count] = $nn;
					$newdataExted[] = "<b>$count</b> = ".$jn['des_jenis_nilai'];
				}
			}
			
			if($hasTugas && $hasTugasValue)
			{
				$count ++;
				$report[$count] = ceil($tt/$ct);
				$newdataExted[] = "<b>$count</b> = Rata - Rata Nilai Tugas";
			}
			else if(($hasTugas && !$hasTugasValue))
			{
				$count ++;
				$report[$count] = "-";
				$newdataExted[] = "<b>$count</b> = Rata - Rata Nilai Tugas";
			}
			
			
			$ch =  0;
			$th =  0;
			$hasUH = false;	
			$hasUHValue = false;				
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'uh') !== false)
				{
					$count++;
					$nn = "-";
					$hasUH = true;		
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];
							$ch++;
							$th += $n[$k];
							$hasUHValue = TRUE;
						}
					 }
					$report[$count] = $nn;
					$newdataExted[] = "<b>$count</b> = ".$jn['des_jenis_nilai'];
				}
			}
			
			if($hasUH && $hasUHValue)
			{
				$count ++;
				$report[$count] = ceil($th/$ch);
				$newdataExted[] = "<b>$count</b> = Rata - Rata Ulangan Harian";
			}
			else if(($hasUH && !$hasUHValue))
			{
				$count ++;
				$report[$count] = "-";
				$newdataExted[] = "<b>$count</b> = Rata - Rata Ulangan Harian";
			}
			
			
			$co =  0;
			$to =  0;
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') === false && strpos(strtolower($jn['des_jenis_nilai']), 'tgs') === false && strpos(strtolower($jn['des_jenis_nilai']), 'uh') === false&& strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') === false)
				{
					$count++;
					$nn = "-";
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];$co++;$to +=$n[$k];
						}
					 }
					$report[$count] = $nn;
					$newdataExted[] = "<b>$count</b> = ".$jn['des_jenis_nilai'];
				}
			}
			
			if($tipe == "guru")
				$max = 2;
			else if($tipe == "siswa")
				$max = 3;
				
			if($count > $max)
			{
				$report[$count+1] = ceil((($tt+$th+$to)/($ct+$ch+$co)));
			}
			else
			{
				$report[$count+1] = "-";
			}
			
			$newdataExted[] = "<b>".($count+1)."</b> = Rata Rata Nilai";
			 
			$newdata[] = $report;
		}
		
		
		
		return array($newdata,$newdataExted);
	}
	
	public function generate_data_excel($data,$tipe)
	{
		$newdata = array();
		
		$this->load->model("jenisnilai_model");
		
		list($jenis_nilai,$totalP) = $this->jenisnilai_model->getAll(null,null,null,"id_jenis_nilai",'asc');
		
		$report = array();
		foreach($data as $key => $dt){
		
			if($tipe == "guru")
			{
				$report['No. Absen'] = $dt['no_absen'];
				$report['Nama'] = $dt['nama'];
			
				$count = 2;
			}
			else if($tipe == "guru")
			{
				$report['No.'] = $key+1;
				$report['Mata Pelajaran'] = $dt['deskripsi'];
				$report['kkm'] = $dt['kkm'];
				
				$count = 3;
			}
			
			
			$jenis =  explode(",",$dt['jenis_nilai']);
			$n =  explode(",",$dt['nilai']);
			
			$ct =  0;
			$tt =  0;
			$hasTugas = false;
			$hasTugasValue = false;
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'tgs') !== false)
				{
					$count++;
					$nn = "-";
					$hasTugas = true;
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];
							$ct++;
							$tt += $n[$k];
							$hasTugasValue = TRUE;	
						}
					 }
					$report[$jn['des_jenis_nilai']] = $nn;
				}
			}
			
			if($hasTugas && $hasTugasValue)
			{
				$count ++;
				$report["Rata - Rata Nilai Tugas"] = ceil($tt/$ct);
			}
			else if(($hasTugas && !$hasTugasValue))
			{
				$count ++;
				$report["Rata - Rata Nilai Tugas"] = "-";
			}
			
			
			$ch =  0;
			$th =  0;
			$hasUH = false;	
			$hasUHValue = false;				
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'uh') !== false)
				{
					$count++;
					$nn = "-";
					$hasUH = true;		
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];
							$ch++;
							$th += $n[$k];
							$hasUHValue = TRUE;
						}
					 }
					$report[$jn['des_jenis_nilai']] = $nn;
				}
			}
			
			if($hasUH && $hasUHValue)
			{
				$count ++;
				$report["Rata - Rata Ulangan Harian"] = ceil($th/$ch);
			}
			else if(($hasUH && !$hasUHValue))
			{
				$count ++;
				$report["Rata - Rata Ulangan Harian"] = "-";
			}
			
			
			$co =  0;
			$to =  0;
			foreach($jenis_nilai as $jn)
			{
				if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') === false && strpos(strtolower($jn['des_jenis_nilai']), 'tgs') === false && strpos(strtolower($jn['des_jenis_nilai']), 'uh') === false&& strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') === false)
				{
					$count++;
					$nn = "-";
					 foreach($jenis as $k => $j)
					 {
						if($j == $jn['des_jenis_nilai'])
						{
							$nn = $n[$k];$co++;$to +=$n[$k];
						}
					 }
					$report[$jn['des_jenis_nilai']] = $nn;
				}
			}
			 
			if($tipe == "guru")
				$max = 2;
			else if($tipe == "siswa")
				$max = 3;
				
			if($count > $max)
			{
				$report["Rata Rata Nilai"] = ceil((($tt+$th+$to)/($ct+$ch+$co)));
			}
			else
			{
				$report["Rata Rata Nilai"] = "-";
			}
			
			$newdataExted[] = "<b>".($count+1)."</b> = Rata Rata Nilai";
			$newdata[] = $report;
		}
		
		return $newdata;
	}
}
