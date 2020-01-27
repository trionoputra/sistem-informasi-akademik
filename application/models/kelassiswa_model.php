<?php
class Kelassiswa_Model extends CI_Model
{
	var $table  = 'tempati';
	var $key  = 'id_tempati';
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
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
				  
				
			}
			if (!empty($filter->agama))
			{
				if(strtolower($filter->agama) != "all")
					$cond[] = "(s.agama = '" . $this->db->escape_str(strtolower($filter->agama)) . "')"; 
			}
			
			if (!empty($filter->nis))
			{
				if(strtolower($filter->nis) != "all")
					$cond[] = "(t.nis = '" . $this->db->escape_str(strtolower($filter->nis)) . "')"; 
			}
			
			if (!empty($filter->id_kelas))
			{
				if(strtolower($filter->id_kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->id_kelas)) . "')"; 
			}
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			if (!empty($filter->id_thn_ajaran))
			{
				if(strtolower($filter->id_thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->id_thn_ajaran)) . "')"; 
			}
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(t.nis) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nis_nasional) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.agama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.jenis_kelamin) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(s.alamat) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.tahun_keluar) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.id_user) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(s.tahun_masuk) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(t.id_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(tt.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'

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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS t.*,tt.thn_ajaran,k.nm_kelas,s.nis_nasional,s.nama,s.agama,s.jenis_kelamin,g.nama guru,g.alamat
								   FROM ".$this->table." t
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN walikelas w ON t.id_kelas = w.id_kelas && t.id_thn_ajaran = w.id_thn_ajaran
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
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
		$query = $this->db->query("SELECT t.*,tt.thn_ajaran,k.nm_kelas,s.nis_nasional,s.nama,s.agama,s.jenis_kelamin,g.nama guru,g.alamat
								   FROM ".$this->table." t
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN walikelas w ON t.id_kelas = w.id_kelas && t.id_thn_ajaran = w.id_thn_ajaran
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
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
	
	public function cekKelas($id = null,$val,$id_kelas,$thn_ajaran,$field)
	{
		
		if($id)
		{
			$where = "WHERE $field = '".$this->db->escape_str(strtolower($val))."' and id_kelas = '".$this->db->escape_str(strtolower($id_kelas))."' and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE $field = '".$this->db->escape_str(strtolower($val))."' and id_kelas = '".$this->db->escape_str(strtolower($id_kelas))."' and id_thn_ajaran = '".$this->db->escape_str(strtolower($thn_ajaran))."' ";
		}
		
		$query = $this->db->query("SELECT  *
								   FROM ".$this->table." 
								   $where 
								   ");
		
		
		$result = $query->result_array();
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
		$query = $this->db->query("(SELECT  ".$this->key." FROM nilai where ".$this->key." = '".$this->db->escape_str(strtolower($id))."')
								   UNION ALL
								   (SELECT  ".$this->key." FROM nilai_kepribadian  where ".$this->key." = '".$this->db->escape_str(strtolower($id))."')
								   UNION ALL
								   (SELECT  ".$this->key." FROM absen  where ".$this->key." = '".$this->db->escape_str(strtolower($id))."')
								   UNION ALL
								   (SELECT  ".$this->key." FROM nilai_ekskul where ".$this->key." = '".$this->db->escape_str(strtolower($id))."' ) ");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
	
	public function getKelas($userid)
	{
		
		$where = "WHERE s.id_user = '".$this->db->escape_str(strtolower($userid))."'";
		$query = $this->db->query("SELECT  t.*,u.username,s.nama,s.id_user,k.nm_kelas,tt.thn_ajaran,g.nama as walikelas,s.nis,s.nis_nasional as nisn,s.jenis_kelamin,s.tanggal_lahir,s.tempat_lahir,s.agama,s.alamat,s.tahun_masuk FROM ".$this->table." t 
								   LEFT JOIN siswa s on t.nis = s.nis
								   LEFT JOIN user u on s.id_user = u.id_user
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   LEFT JOIN kelas k on t.id_kelas = k.id_kelas
								   LEFT JOIN walikelas w on t.id_thn_ajaran = w.id_thn_ajaran and t.id_kelas = w.id_kelas
								   LEFT JOIN guru g on w.id_guru = g.id_guru
								   $where  ORDER BY  t.id_thn_ajaran desc
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
			
			if (!empty($filter->id_kelas))
			{
				if(strtolower($filter->id_kelas) != "all")
					$cond[] = "(t.id_kelas = '" . $this->db->escape_str(strtolower($filter->id_kelas)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->id_thn_ajaran) != "all")
					$cond[] = "(t.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->id_thn_ajaran)) . "')"; 
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
			
		$query = $this->db->query("SELECT  t.no_absen as 'No Urut',t.nis NIS, s.nis_nasional as 'NISN', s.nama 'Nama Siswa',if(s.jenis_kelamin = '1','L','P') as 'L/P', s.anak_ke as 'Anak Ke', s.agama as Agama, concat(s.tempat_lahir,' ',s.tanggal_lahir) as 'Tempat dan Tgl Lahir', s.alamat as 'Alamat', s.nama_bapak as 'Nama Orang Tua' FROM ".$this->table." t
								   LEFT JOIN siswa s ON t.nis = s.nis
								   LEFT JOIN kelas k ON t.id_kelas = k.id_kelas
								   LEFT JOIN walikelas w ON t.id_kelas = w.id_kelas && t.id_thn_ajaran = w.id_thn_ajaran
								   LEFT JOIN guru g ON w.id_guru = g.id_guru
								   LEFT JOIN tahun_ajaran tt on t.id_thn_ajaran = tt.id_thn_ajaran
								   $where ORDER BY $orderBy $orderType $limitOffset
								   ");
		$result = $query->result_array();
		
		$query->free_result();
		return $result;
	}
}