<?php
class Absen_Model extends CI_Model
{
	var $table  = 'absen';
	var $key  = 'id_absen';
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
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(a.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->alasan))
			{
				if(strtolower($filter->alasan) != "all")
					$cond[] = "(a.alasan = '" . $this->db->escape_str(strtolower($filter->alasan)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
			}
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(s.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nis) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(tt.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(t.no_absen) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(a.keterangan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(a.alasan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(a.alasan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' )";
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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS a.*,t.id_thn_ajaran,t.id_kelas,t.nis,t.no_absen,tt.thn_ajaran,k.nm_kelas,s.nis_nasional,s.nama
								   FROM ".$this->table." a
								   LEFT JOIN tempati t ON a.id_tempati = t.id_tempati
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
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
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS a.*,t.id_thn_ajaran,t.id_kelas,t.nis,t.no_absen,tt.thn_ajaran,k.nm_kelas,s.nis_nasional,s.nama
								   FROM ".$this->table." a
								   LEFT JOIN tempati t ON a.id_tempati = t.id_tempati
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   $where 
								   ");
		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
		
		$query->free_result();
		
		return $result;
	}
	
	public function cekAbsen($id = null,$id_tempati,$semester,$tanggal)
	{
		
		if($id)
		{
			$where = "WHERE id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' and tgl_absen = '".$this->db->escape_str(strtolower($tanggal))."' and semester = '".$this->db->escape_str(strtolower($semester))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' and tgl_absen = '".$this->db->escape_str(strtolower($tanggal))."' and semester = '".$this->db->escape_str(strtolower($semester))."' ";
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
	
	public function getabsen($filter)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(a.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
		$query = $this->db->query("SELECT  if(a.alasan = 's','Sakit',if(a.alasan = 'i','Ijin','Alpha')) as nama,count(a.alasan) as nilai
										  FROM ".$this->table." a
										   LEFT JOIN tempati t ON a.id_tempati = t.id_tempati
										   LEFT JOIN siswa s ON t.nis = s.nis
										   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
										   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
										   $where
										   GROUP BY a.alasan ORDER BY FIELD(a.alasan,'i','s','a')
								 
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	function export($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(a.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
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
			
		$query = $this->db->query("SELECT t.no_absen as 'No. Absen', t.nis as 'NIS',s.nama as 'Nama Siswa', sum(if(a.alasan = 'i',1,0)) as Ijin, sum(if(a.alasan = 's',1,0)) as Sakit,sum(if(a.alasan = 'a',1,0)) as Alfa
								    FROM ".$this->table." a
								   LEFT JOIN tempati t ON a.id_tempati = t.id_tempati
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   $where GROUP BY t.nis ORDER BY $orderBy $orderType $limitOffset
								   ");
		$result = $query->result_array();
		
		$query->free_result();
		return $result;
	}
}