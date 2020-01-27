<?php
class User_Model extends CI_Model
{
	var $table  = 'user';
	var $key  = 'id_user';
	function __construct()
    {
        parent::__construct();
    }
	
	function authenticate($username,$password)
    {
        $this->db->select($this->key ." , username, password,level");
		$this->db->from($this->table);
		$this->db->where('username', $this->db->escape_str($username));
		$this->db->where('password', $this->db->escape_str(MD5($password)));
		$query = $this->db->get();

		if($query->num_rows() == 0)
		{
			return false;
		}
		else
		{
			return $query->row();
		}
    }
	
	function getAll($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(username) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(id_user) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								)";
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
								   FROM ".$this->table." 
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
	
	public function cekUserName($id,$username)
	{
		$where = "WHERE username = '".$this->db->escape_str(strtolower($username))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		$query = $this->db->query("SELECT  *
								   FROM ".$this->table." 
								   $where 
								   ");
		
		
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	public function cekPassword($id,$password)
	{
		$where = "WHERE password = '".$this->db->escape_str(($password))."' and ".$this->key." = '".$this->db->escape_str(($id))."' ";
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
	
	public function get_last()
	{
		$query = $this->db->query("SELECT  * FROM ".$this->table." order by ".$this->key." desc limit 0,1");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
	
	function authenticate_mobile($username,$password)
    {
        $this->db->select($this->key." as id");
		$this->db->from($this->table);
		$this->db->where('username', $this->db->escape_str($username));
		$this->db->where('password', $this->db->escape_str($password));
		$this->db->where('level',"5");
	
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return false;
		}
		else
		{
			return $query->row();
		}
    }
}