<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kepribadian extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("kepribadian_model");
		$this->cekLoginStatus("ss",true);
    }
	
	public function index()
	{
		$data['title'] = "Data Kepribadian";
		$data['layout'] = "kepribadian/index";
		
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->kepribadian_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("kepribadian?");
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
		$data['title'] = "Form Kepribadian";
		$data['layout'] = "kepribadian/manage";

		$data['data'] = new StdClass();
		$data['data']->id_kepribadian = "";
		$data['data']->deskripsi = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->kepribadian_model->get_by("id_kepribadian",$id,true);
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
			
			if(!empty($post['id_kepribadian']))
				$data['id_kepribadian'] = $post['id_kepribadian'];
			else
				$error[] = "id kepribadian tidak boleh kosong"; 
			
			if(!empty($post['deskripsi']))
				$data['deskripsi'] = $post['deskripsi'];
			else
				$error[] = "deskripsi tidak boleh kosong"; 
			
			if(empty($error))
			{
				if(empty($id))
					$cekId = $this->kepribadian_model->get_by("id_kepribadian",$post['id_kepribadian']);
				
				if(!empty($cekId))
					$error[] = "ID kepribadian sudah terdaftar";
					
				if(empty($id))
					$cekNm = $this->kepribadian_model->get_by("deskripsi",$post['deskripsi']);
				else
					$cekNm = $this->kepribadian_model->cekNm($id,$post['deskripsi']);
					
				if(!empty($cekNm))
					$error[] = "deskripsi sudah terdaftar";
			}
			
			if(empty($error))
			{
				$save = $this->kepribadian_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("kepribadian/manage/".$id);
				else
					redirect("kepribadian");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("kepribadian/manage/".$id);
			}
		}
		else
		  redirect("kepribadian");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->kepribadian_model->get_by("id_kepribadian",$id);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID kepribadian tidak terdaftar");
				redirect("kepribadian");
			}
			else
			{
				if(!empty($this->kepribadian_model->cekAvalaible($id)))
				{
					$this->session->set_flashdata('admin_save_error', "kepribadian sedang digunakan");
					redirect("kepribadian");
				}
				else
				{
					$this->kepribadian_model->remove($id);
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("kepribadian");
				}
			}
		}
		else
			redirect("kepribadian");
	}
	
	public function generate_code()
	{
		$prefix = "S";
		$code = "01";
		
		$last = $this->kepribadian_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_kepribadian,1,2) + 1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		
		return $prefix.$code;
	}
}
