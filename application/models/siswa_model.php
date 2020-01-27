<?php
class Siswa_Model extends CI_Model
{
	var $table  = 'siswa';
	var $key  = 'nis';
	function __construct()
    {
        parent::__construct();
    }
	
	function getAll($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->jenis_kelamin))
			{
				if(strtolower($filter->jenis_kelamin) != "all")
					$cond[] = "(s.jenis_kelamin = '" . $this->db->escape_str(strtolower($filter->jenis_kelamin)) . "')"; 
			}
			
			if (!empty($filter->userid))
			{
				if(strtolower($filter->userid) != "all")
				{
					if (strtolower($filter->userid) == "emp")
						$cond[] = "(s.id_user = '' or s.id_user is null)";
					else
						$cond[] = "(s.id_user = '" . $this->db->escape_str(strtolower($filter->userid)) . "')";
				}
			}	
			
			if (!empty($filter->agama))
			{
				if(strtolower($filter->agama) != "all")
					$cond[] = "(s.agama = '" . $this->db->escape_str(strtolower($filter->agama)) . "')"; 
			}
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(s.nis) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nis_nasional) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.jenis_kelamin) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.alamat) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.tahun_keluar) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.id_user) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.tahun_masuk) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' )";
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
	    
		$limitOffset = "LIMIT $offset,$limit";
		if($limit == 0)
			$limitOffset = "";
		
		if(!$orderBy)
			$orderBy = $this->key;
		
		if(!$orderType)
			$orderType = "asc";
		
		
		
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
								   FROM ".$this->table." s 
								   $where ORDER BY $orderBy $orderType $limitOffset
								   ");
		
		$result = $query->result_array();
		
		
		$query->free_result();
		
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;
		
		return array($result,$total);
	}
	
	public function get_by($field, $value = "",$obj = false)
	{
		if(!$field)
			$field = $this->key;
			
		$where = "WHERE $field = '".$this->db->escape_str(strtolower($value))."'";
		$query = $this->db->query("SELECT  *
								   FROM ".$this->table."
								   $where 
								   ");
		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
			
		$query->free_result();
		
		return $result;
	}
	
	public function cekNis($id,$nis)
	{
		$where = "WHERE nis_nasional = '".$this->db->escape_str(strtolower($nis))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		$query = $this->db->query("SELECT  *
								   FROM ".$this->table." 
								   $where 
								   ");
		
		
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	function remove($id)
    {
      if (!is_array($id))
		    $id = array($id);
			
		$this->db->where_in($this->key, $id)->delete($this->table);
    }
	
	function save($id = "",$data = array(), $insert_id = false)
	{
		
		if (!empty($id))
		{
			$this->db->where($this->key, $id);
			$this->db->update($this->table, $data);
		}
		else
		{
			$this->db->insert($this->table, $data);
		}
		
		return $this->db->affected_rows();
	}
	
	public function cekAvalaible($nis)
	{
		$query = $this->db->query(" SELECT  nis FROM tempati where nis = '".$this->db->escape_str(strtolower($nis))."' ");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
}