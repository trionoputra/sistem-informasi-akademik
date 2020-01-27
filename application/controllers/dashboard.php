<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function index()
	{
		$data['title'] = "Dashboard";
		$this->load->model("nilaipelajaran_model");
		
		if($this->getStatus() != 3)
			$data['layout'] = "dashboard";
		else
		{
			$data['layout'] = "dashboard_siswa";
			
			$this->load->model("siswa_model");
			$siswa =  $this->siswa_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			
			$data["nis"] = $siswa->nis;
			$data["nama"] = $siswa->nama;
			
			$filter = new StdClass();
			$filter->siswa = $data["nis"];
			
			
			$data['data'] = $this->nilaipelajaran_model->getAllNilai($filter,0,0,"t.id_tempati","asc");
		}
			
		$this->load->view('template',$data);
	}
}
