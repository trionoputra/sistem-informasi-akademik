<?php
class Nilaikepribadian_Model extends CI_Model
{
	var $table  = 'nilai_kepribadian';
	var $key  = 'id_nilaikepribadian';
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
			if (!empty($filter->kepribadian))
			{
				if(strtolower($filter->kepribadian) != "all")
					$cond[] = "(kk.id_kepribadian = '" . $this->db->escape_str(strtolower($filter->kepribadian)) . "')"; 
			}
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->nis))
			{
				if(strtolower($filter->nis) != "all")
					$cond[] = "(t.nis = '" . $this->db->escape_str(strtolower($filter->nis)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(n.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "( lower(t.nis) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(kk.deskripsi) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(tt.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' )";
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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS n.*,kk.deskripsi as nm_kepribadian,t.id_kelas,t.id_thn_ajaran,tt.thn_ajaran,t.nis,t.no_absen,k.nm_kelas,s.nama,if(n.nilai >= 80,'A',if( n.nilai < 80 and n.nilai >= 75,'A-',if(n.nilai < 75 and n.nilai >= 71,'B+',if(n.nilai < 71 and n.nilai >= 67,'B',if(n.nilai < 67 and n.nilai >= 63,'B-',if(n.nilai < 63 and n.nilai >= 59,'C+',if(n.nilai < 59 and n.nilai >= 55,'C',if(n.nilai < 55 and n.nilai >= 45,'D','E')))))))) huruf
								   FROM ".$this->table." n
								   LEFT JOIN kepribadian kk on n.id_kepribadian = kk.id_kepribadian
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   $where  ORDER BY $orderBy $orderType $limitOffset
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
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS n.*,kk.deskripsi as nm_kepribadian,t.id_kelas,t.id_thn_ajaran,tt.thn_ajaran,t.nis,t.no_absen,k.nm_kelas,s.nama
								   FROM ".$this->table." n
								   LEFT JOIN kepribadian kk on n.id_kepribadian = kk.id_kepribadian
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
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
	
	public function cekNilai($id = null,$id_kepribadian,$semester,$id_tempati)
	{
		
		if($id)
		{
			$where = "WHERE id_kepribadian = '".$this->db->escape_str(strtolower($id_kepribadian))."' and semester = '".$this->db->escape_str(strtolower($semester))."' and id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE id_kepribadian = '".$this->db->escape_str(strtolower($id_kepribadian))."' and semester = '".$this->db->escape_str(strtolower($semester))."' and id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' ";
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
	
	public function getnilai($filter)
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
					$cond[] = "(n.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
		$query = $this->db->query("SELECT  kk.deskripsi as nama,n.nilai
										  FROM ".$this->table." n
										  LEFT JOIN kepribadian kk on n.id_kepribadian = kk.id_kepribadian
										   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
										   LEFT JOIN siswa s on t.nis = s.nis
										   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
										   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
										   $where
										   GROUP BY n.id_kepribadian ORDER BY  kk.deskripsi desc
								 
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
					$cond[] = "(n.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS t.nis as 'NIS',s.nama as 'Nama',kk.deskripsi as 'Jenis Nilai Kepribadian',n.nilai as 'Nilai Angka',if(n.nilai >= 80,'A',if( n.nilai < 80 and n.nilai >= 75,'A-',if(n.nilai < 75 and n.nilai >= 71,'B+',if(n.nilai < 71 and n.nilai >= 67,'B',if(n.nilai < 67 and n.nilai >= 63,'B-',if(n.nilai < 63 and n.nilai >= 59,'C+',if(n.nilai < 59 and n.nilai >= 55,'C',if(n.nilai < 55 and n.nilai >= 45,'D','E')))))))) 'Nilai Huruf',n.deskripsi as 'Keterangan'
								   FROM ".$this->table." n
								   LEFT JOIN kepribadian kk on n.id_kepribadian = kk.id_kepribadian
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   $where  ORDER BY $orderBy $orderType $limitOffset
								   ");
		$result = $query->result_array();
		
		$query->free_result();
		return $result;
	}
}