<?php
class Pelajaran_Model extends CI_Model
{
	var $table  = 'pelajaran';
	var $key  = 'id_pelajaran';
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
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(p.id_pelajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(p.deskripsi) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(p.kkm) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'  )";
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
								   FROM ".$this->table." p 
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
	
	public function cekAvalaible($id)
	{
		$query = $this->db->query(" (SELECT  id_pelajaran FROM ajar where id_pelajaran = '".$this->db->escape_str(strtolower($id))."' )");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
	
	public function cekNm($id,$ref)
	{
		$where = "WHERE deskripsi = '".$this->db->escape_str(strtolower($ref))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		$query = $this->db->query("SELECT  *
								   FROM ".$this->table." 
								   $where 
								   ");
		
		
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_last()
	{
		$query = $this->db->query("SELECT  * FROM ".$this->table." order by ".$this->key." desc limit 0,1");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
}