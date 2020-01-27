<?php
class Guruspesialis_Model extends CI_Model
{
	var $table  = 'ajar';
	var $key  = 'id_ajar';
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
			if (!empty($filter->id_guru))
			{
				if(strtolower($filter->id_guru) != "all")
					$cond[] = "(g.id_guru = '" . $this->db->escape_str(strtolower($filter->id_guru)) . "')"; 
			}
			
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
			
			if (!empty($filter->pelajaran))
			{
				if(strtolower($filter->pelajaran) != "all")
					$cond[] = "(w.id_pelajaran = '" . $this->db->escape_str(strtolower($filter->pelajaran)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(w.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
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
							 or lower(p.deskripsi) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
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
			$orderBy = $this->key;
		
		if(!$orderType)
			$orderType = "asc";
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS w.*,t.thn_ajaran,p.deskripsi,g.nip,g.nama,g.agama,g.jenis_kelamin,g.jabatan,g.golongan,g.pendidikan_terakhir,g.email,g.telpon,p.kkm
								   FROM ".$this->table." w
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON w.id_pelajaran = p.id_pelajaran
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
	
	public function cekPelajaran($id = null,$id_guru,$id_pelajaran,$thn_ajaran)
	{
		
		if($id)
		{
			$where = "WHERE id_pelajaran = '".$this->db->escape_str(strtolower($id_pelajaran))."' and id_guru = '".$this->db->escape_str(strtolower($id_guru))."' and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE id_pelajaran = '".$this->db->escape_str(strtolower($id_pelajaran))."' and id_guru = '".$this->db->escape_str(strtolower($id_guru))."' and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' ";
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
		$query = $this->db->query("(SELECT  ".$this->key." FROM jadwal where ".$this->key." = '".$this->db->escape_str(strtolower($id))."') ");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
}