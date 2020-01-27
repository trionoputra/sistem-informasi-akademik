<?php
class Guru_Model extends CI_Model
{
	var $table  = 'guru';
	var $key  = 'id_guru';
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
					$cond[] = "(g.jenis_kelamin = '" . $this->db->escape_str(strtolower($filter->jenis_kelamin)) . "')"; 
			}
			
			if (!empty($filter->agama))
			{
				if(strtolower($filter->agama) != "all")
					$cond[] = "(g.agama = '" . $this->db->escape_str(strtolower($filter->agama)) . "')"; 
			}
			
			if (!empty($filter->golongan))
			{
				if(strtolower($filter->golongan) != "all")
					$cond[] = "(g.golongan = '" . $this->db->escape_str(strtolower($filter->golongan)) . "')"; 
			}
			
			if (!empty($filter->jabatan))
			{
				if(strtolower($filter->jabatan) != "all")
					$cond[] = "(g.jabatan = '" . $this->db->escape_str(strtolower($filter->jabatan)) . "')"; 
			}
			
			if (!empty($filter->pendidikan))
			{
				if(strtolower($filter->pendidikan) != "all")
					$cond[] = "(g.pendidikan_terakhir = '" . $this->db->escape_str(strtolower($filter->pendidikan)) . "')"; 
			}
			
			if (!empty($filter->userid))
			{
				if(strtolower($filter->userid) != "all")
				{
					if (strtolower($filter->userid) == "emp")
						$cond[] = "(g.id_user = '' or g.id_user is null)";
					else
						$cond[] = "(g.id_user = '" . $this->db->escape_str(strtolower($filter->userid)) . "')";
				}
			}	
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(g.id_guru) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nip) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.jenis_kelamin) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.alamat) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.jabatan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.golongan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.pendidikan_terakhir) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.email) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.telpon) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.id_user) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' )";
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
								   FROM ".$this->table." g 
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
	
	public function cekNip($id,$nip)
	{
		$where = "WHERE nip = '".$this->db->escape_str(strtolower($nip))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
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
	
	public function cekAvalaible($id)
	{
		$query = $this->db->query(" (SELECT  id_guru FROM ajar where id_guru = '".$this->db->escape_str(strtolower($id))."' )
								   UNION ALL
								   (SELECT  id_guru FROM walikelas where id_guru = '".$this->db->escape_str(strtolower($id))."') ");
		$result = $query->row();
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