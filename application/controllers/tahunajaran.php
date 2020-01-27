<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tahunajaran extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("tahunajaran_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Tahun Ajaran";
		$data['layout'] = "tahunajaran/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->tahunajaran_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("tahunajaran?");
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
		$data['title'] = "Form Tahun Ajaran";
		$data['layout'] = "tahunajaran/manage";

		$data['data'] = new StdClass();
		$data['data']->id_thn_ajaran = "";
		$data['data']->thn_ajaran = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->tahunajaran_model->get_by("id_thn_ajaran",$id,true);
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
			
			if(!empty($post['thn_ajaran']))
				$data['thn_ajaran'] = $post['thn_ajaran'];
			else
				$error[] = "tahun ajaran tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->tahunajaran_model->get_by("id_thn_ajaran",$post['id_thn_ajaran']);
				
				if(!empty($cekId))
					$error[] = "ID tahun ajaran sudah terdaftar";
					
				if(empty($id))
					$cekThn = $this->tahunajaran_model->get_by("thn_ajaran",$post['thn_ajaran']);
				else
					$cekThn = $this->tahunajaran_model->cekThn($id,$post['thn_ajaran']);
					
				if(!empty($cekThn))
					$error[] = "tahun ajaran sudah terdaftar";
					
			}
			
			if(empty($error))
			{
				$save = $this->tahunajaran_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("tahunajaran/manage/".$id);
				else
					redirect("tahunajaran");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("tahunajaran/manage/".$id);
			}
		}
		else
		  redirect("tahunajaran");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->tahunajaran_model->get_by("id_thn_ajaran",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID jenis nilai tidak terdaftar");
				redirect("tahunajaran");
			}
			else
			{
				if(!empty($this->tahunajaran_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "jenis nilai sedang digunakan");
					redirect("tahunajaran");
				}
				else
				{
					$this->tahunajaran_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("tahunajaran");
				}
			}
		}
		else
			redirect("tahunajaran");
	}
	
	public function generate_code()
	{
		$prefix = "T";
		$code = "01";
		
		$last = $this->tahunajaran_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_thn_ajaran,1,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
