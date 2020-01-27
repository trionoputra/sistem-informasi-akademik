<?php
class Walikelas_Model extends CI_Model
{
	var $table  = 'walikelas';
	var $key  = 'md5(CONCAT(w.id_kelas,w.id_guru,w.id_thn_ajaran))';
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
			
			if (!empty($filter->pendidikan_terakhir))
			{
				if(strtolower($filter->pendidikan_terakhir) != "all")
					$cond[] = "(g.pendidikan_terakhir = '" . $this->db->escape_str(strtolower($filter->pendidikan_terakhir)) . "')"; 
			}
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(w.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->id_thn_ajaran))
			{
				if(strtolower($filter->id_thn_ajaran) != "all")
					$cond[] = "(w.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->id_thn_ajaran)) . "')"; 
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
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(t.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(g.id_user) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' )";
			}
			
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
	    
		$limitOffset = "LIMIT $offset,$limit";
		if($limit == 0)
			$limitOffset = "";
		
		if(!$orderBy)
			$orderBy = "md5(CONCAT(w.id_kelas+w.id_guru+w.id_thn_ajaran))";
		
		if(!$orderType)
			$orderType = "asc";
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS w.*,t.thn_ajaran,k.nm_kelas,g.nip,g.nama,g.agama,g.jenis_kelamin,g.jabatan,g.golongan,g.pendidikan_terakhir,g.email,g.telpon,g.alamat
								   FROM ".$this->table." w
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
								   LEFT JOIN kelas k ON w.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran t on w.id_thn_ajaran = t.id_thn_ajaran
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
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS w.*,t.thn_ajaran,k.nm_kelas,g.nip,g.nama,g.agama,g.jenis_kelamin,g.jabatan,g.golongan,g.pendidikan_terakhir,g.email,g.telpon,g.alamat
								   FROM ".$this->table." w
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
								   LEFT JOIN kelas k ON w.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran t on w.id_thn_ajaran = t.id_thn_ajaran
								   $where 
								   ");
		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
		
		$query->free_result();
		
		return $result;
	}
	
	public function cekTahunAjaran($id = null,$val,$thn_ajaran,$field)
	{
		
		if($id)
		{
			$where = "WHERE $field = '".$this->db->escape_str(strtolower($val))."'  and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE $field = '".$this->db->escape_str(strtolower($val))."'  and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' ";
		}
		
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
			
		$this->db->where_in("md5(CONCAT(id_kelas,id_guru,id_thn_ajaran))", $id)->delete($this->table);
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
	

}