<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nilaikepribadian extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("nilaikepribadian_model");
		
    }
	
	public function index()
	{
		$this->cekLoginStatus("sswk",true);
		$data['title'] = "Data Nilai Kepribadian";
		$data['layout'] = "nilaikepribadian/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->kepribadian = trim($this->input->get('kepribadian'));
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
		$this->load->model("kepribadian_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'asc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['kepribadian'],$totalK) = $this->kepribadian_model->getAll(null,null,null,"id_kepribadian",'asc');
		
		list($data['data'],$total) = $this->nilaikepribadian_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("nilaikepribadian?");
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
		$data['title'] = "Form Nilai Kepribadian";
		$data['layout'] = "nilaikepribadian/manage";

		$data['data'] = new StdClass();
	
		$data['data']->id_nilaikepribadian = "";
		$data['data']->semester = "";
		$data['data']->id_kelas = "";
		$data['data']->id_thn_ajaran = "";
		$data['data']->id_kepribadian = "";
		$data['data']->deskripsi = "";
		$data['data']->id_tempati = "";
		$data['data']->nis = "";
		$data['data']->nilai = "";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		$this->load->model("kepribadian_model");
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		list($data['kepribadian'],$totalK) = $this->kepribadian_model->getAll(null,null,null,"id_kepribadian",'asc');
		
		if($id)
		{
			$dt =  $this->nilaikepribadian_model->get_by("n.id_nilaikepribadian",$id,true);
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
			
			if(!empty($post['id_kepribadian']))
				$data['id_kepribadian'] = $post['id_kepribadian'];
			else
				$error[] = "id kepribadian tidak boleh kosong"; 
				
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
				
			
			
			$data['deskripsi'] = $post['deskripsi'];
			
			if(empty($error))
			{
				if(empty($id))
					$cek = $this->nilaikepribadian_model->cekNilai(null,$data['id_kepribadian'],$data['semester'],$data['id_tempati']);
				else
					$cek = $this->nilaikepribadian_model->cekNilai($id,$data['id_kepribadian'],$data['semester'],$data['id_tempati']);
				
				if(!empty($cek))
					$error[] = "nilai sudah terdaftar";
			}
			
			if(empty($error))
			{
			
				$nilaikepribadian['id_kepribadian'] = $data['id_kepribadian'];
				$nilaikepribadian['id_tempati'] = $data['id_tempati'];
				$nilaikepribadian['semester'] = $data['semester'];
				$nilaikepribadian['nilai'] = $data['nilai'];
				$nilaikepribadian['deskripsi'] = $data['deskripsi'];
				
				$save = $this->nilaikepribadian_model->save($id,$nilaikepribadian,true);
				
				$this->session->set_userdata('selected_tahun_ajaran',$data['id_thn_ajaran']);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("nilaikepribadian/manage/".$id);
				else
					redirect("nilaikepribadian");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("nilaikepribadian/manage/".$id);
			}
		}
		else
		  redirect("nilaikepribadian");
	}
	
	public function delete($id = "")
	{
		$this->cekLoginStatus("sswk",true);
		if(!empty($id))
		{
			$cek = $this->nilaikepribadian_model->get_by("n.id_nilaikepribadian",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "id tidak terdaftar");
				redirect("nilaikepribadian");
			}
			else
			{
				$this->nilaikepribadian_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("nilaikepribadian");
			}
		}
		else
			redirect("nilaikepribadian");
	}
	
	
	public function getnilai($filter)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(n.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
		$query = $this->db->query("SELECT  x.nm_ekskul as nama,n.nilai
										  FROM ".$this->table." n
										   LEFT JOIN ekskul x on n.id_ekskul = x.id_ekskul
										   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
										   LEFT JOIN siswa s on t.nis = s.nis
										   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
										   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
										   $where
										   GROUP BY n.id_ekskul ORDER BY x.nm_ekskul desc
								 
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	public function rekap($tipe)
	{
		$data['title'] = "Laporan Nilai Kepribadian Siswa";
		
		if($tipe == "siswa" && $this->session->userdata('loginstatus') == "3")
		{
			$data['layout'] = "nilaikepribadian/rekap_siswa";
			
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
			
			list($data['data'],$total) = $this->nilaikepribadian_model->getAll($filter,0,0,"s.nama","asc");
			
			if($action)
			{
				$this->export($tipe,$action,$filter);
			}
			else
				$this->load->view('template',$data);
		}	
		else if ($tipe == "guru" && $this->session->userdata('loginstatus') != "3")
		{
			$data['layout'] = "nilaikepribadian/rekap_guru";
			
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
			
			list($data['data'],$total) = $this->nilaikepribadian_model->getAll($filter,0,0,"s.nama","asc");
			
			
				
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
		$title = "Laporan Nilai Kepribadian Siswa";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
	
		$data = $this->nilaikepribadian_model->export($filter,0,0,"s.nama","asc");
		
	
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
			list($thn_ajaran,$total) = $this->nilaikepribadian_model->getAll($filter,0,0,"s.nama","asc");
			
			$extend = array("NIS" =>$siswa->nis,"Nama" =>$siswa->nama,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
			
			$data = $this->generate_siswa_format($data);
		}
		if(empty($data))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
			if($tipe == "guru")
				redirect("nilaikepribadian/rekap/".$tipe."?thn_ajaran=".$filter->thn_ajaran."&kelas=".$filter->kelas."&semester=".$filter->semester."");
			else
				redirect("nilaikepribadian/rekap/".$tipe."?kelas=".$filter->kelas."&semester=".$filter->semester."");
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
			$dat['Jenis Nilai Kepribadian'] = $dt['Jenis Nilai Kepribadian'];
			$dat['Nilai Angka'] = $dt['Nilai Angka'];
			$dat['Nilai Huruf'] = $dt['Nilai Huruf'];
			$dat['Keterangan'] = $dt['Keterangan'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
}
