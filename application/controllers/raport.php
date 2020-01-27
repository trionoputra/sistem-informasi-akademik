<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Raport extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("nilaipelajaran_model");
		$this->cekLoginStatus("sswkwmks",true);
    }
	
	public function index()
	{
		$data['title'] = "Raport Siswa";
		$data['layout'] = "raport/index";
		
		$this->load->model("tahunajaran_model");
		$this->load->model("kelas_model");
		$this->load->model("siswa_model");
		$this->load->model("kelassiswa_model");
		$this->load->model("nilaikepribadian_model");
		$this->load->model("nilaiekskul_model");
		$this->load->model("absen_model");
		
		$action = $this->input->get('action');
		
		$kelas = $this->input->get('kelas');
		
		$siswa = $this->input->get('nis');
		$tahun_ajaran = $this->input->get('thn_ajaran');
		$semester = $this->input->get('semester');
		
		list($data['tahun_ajaran'],$totalT) = $this->tahunajaran_model->getAll(null,null,null,"id_thn_ajaran",'desc');
		list($data['kelas'],$totalK) = $this->kelas_model->getAll(null,null,null,"id_kelas",'asc');
		
		$filter = new StdClass();
		
		$data["nis"] = $siswa;
		$data["nama"] = "";
		
		if(!empty($siswa))
		{
			$siswa =  $this->siswa_model->get_by("nis",$siswa,true);
			$data["nama"] = $siswa->nama;
		}
	
		if(empty($semester))
			$semester = 1;
		
		$filter->semester = $semester;
		$filter->nis = $data["nis"];
		$filter->thn_ajaran = $tahun_ajaran;
		$filter->kelas = $kelas;
		
		if($this->getStatus() == 3)
		{
			$sis =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
		
			$data["nis"] = $sis->nis;
			$siswa = $sis->nis;
			$data["nama"] = $sis->nama;
			
			$this->load->model("kelas_model");
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
			$data["tahun_ajaran"] = $tahun_ajaran[0]['thn_ajaran']; 
		} 
		
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
		
		list($data['data'],$total) = $this->nilaipelajaran_model->getAllRekapRaport($filter,0,0,"p.deskripsi","asc");
		
		
		list($data['dataK'],$total) = $this->nilaikepribadian_model->getAll($filter,0,0,"kk.deskripsi","asc");
		list($data['dataE'],$total) = $this->nilaiekskul_model->getAll($filter,0,0,"nm_ekskul","asc");
		$data['dataA'] = $this->absen_model->export($filter,0,0,"s.nama","asc");

		$uasCount = 0;
		foreach($data['data'] as $dt)
		{
			$jn = explode(",",$dt['jenis_nilai']);
			foreach($jn as $j)
			{
				if(strtolower($j) == 'uas')
					$uasCount ++;
			}
		}
		
		if($uasCount == 0 || $uasCount < sizeOf($data['data']))
		{
			$data['data'] = [];
			$data['dataK'] = [];
			$data['dataA'] = [];
		}
		
		if($action)
		{
			$this->export($data,$action,$filter);
		}
		else
			$this->load->view('template',$data);
				
	}
	
	public function export($data,$action,$filter)
	{
		$title = "Raport Siswa";
		$file_name = $title."_".date("Y-m-d");
		$headerTitle = $title;
		
		$this->load->model("siswa_model");
		$siswa =  $this->siswa_model->get_by("nis",$filter->nis,true);
		list($thn_ajaran,$total) = $this->nilaikepribadian_model->getAll($filter,0,0,"s.nama","asc");
		$kelas = $this->kelas_model->get_by("id_kelas",$filter->kelas,true);
			
		$extend = array("NIS" =>$siswa->nis,"Nama" =>$siswa->nama,"Kelas" => $kelas->nm_kelas,"Semester" => $filter->semester,"Tahun Ajaran" => $thn_ajaran[0]['thn_ajaran']);
		if(empty($data['data']))
		{
			$this->session->set_flashdata('admin_save_error', "data tidak tersedia");
		
			redirect("raport/?kelas=".$filter->kelas."&semester=".$filter->semester."&thn_ajaran=".$filter->thn_ajaran."&nis=".$filter->thn_ajaran."");
		}
		else
		{	
			if($action == "excel")
			{
				$this->load->library("excel");
				$this->excel->setActiveSheetIndex(0);
				$this->excel->stream_raport($this->generate_nilai_pelajaran($data['data']),$this->generate_nilai_kepribadian($data['dataK']),$this->generate_nilai_absen($data['dataA']),$this->generate_nilai_ekskul($data['dataE']),$file_name.".xls",$headerTitle,$extend,$data['data'][0]['walikelas']);
			}
			else if ($action == "pdf")
			{
				$this->load->library("pdf");
				$this->pdf->stream_raport($this->generate_nilai_pelajaran($data['data']),$this->generate_nilai_kepribadian($data['dataK']),$this->generate_nilai_absen($data['dataA']),$this->generate_nilai_ekskul($data['dataE']),$file_name,$headerTitle,$extend,$data['data'][0]['walikelas']);
			}
		}
	}
	
	function generate_nilai_pelajaran($data)
	{
		$newdata = array();
	
		foreach($data as $key => $dt)
		{
			$dat = array();
			$dat['NO'] = $key+1;
			$dat['Mata Pelajaran'] = $dt['deskripsi'];
			$dat['KKM'] = $dt['kkm'];
			$dat['Nilai'] = $dt['nilai'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
	
	function generate_nilai_kepribadian($data)
	{
		$newdata = array();
	
		foreach($data as $key => $dt)
		{
			$dat = array();
			$dat['NO'] = $key+1;
			$dat['Aspek Penilaian'] = $dt['nm_kepribadian'];
			$dat['Nilai'] = $dt['nilai'];
			$dat['Cacatan Guru'] = $dt['deskripsi'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
	
	function generate_nilai_ekskul($data)
	{
		$newdata = array();
	
		foreach($data as $key => $dt)
		{
			$dat = array();
			$dat['NO'] = $key+1;
			$dat['Aspek Pengembangan Diri'] = $dt['nm_ekskul'];
			$dat['Nilai'] = $dt['nilai'];
			$newdata[] = $dat;
		}
		
		return $newdata;
	}
	
	function generate_nilai_absen($data)
	{
		
		$data = $data[0];
		$dat = array();
		$dat['NO'] = 1;
		$dat['Alasan Ketidak hadiran'] = 'Ijin';
		$dat['Jumlah'] = $data['Ijin'];
		$newdata[] = $dat;
		
		$dat = array();
		$dat['NO'] = 2;
		$dat['Alasan Ketidak hadiran'] = 'Sakit';
		$dat['Jumlah'] = $data['Sakit'];
		$newdata[] = $dat;
		
		$dat = array();
		$dat['NO'] = 3;
		$dat['Alasan Ketidak hadiran'] = 'Alfa';
		$dat['Jumlah'] = $data['Alfa'];
		$newdata[] = $dat;
		
		
		return $newdata;
	}
}
