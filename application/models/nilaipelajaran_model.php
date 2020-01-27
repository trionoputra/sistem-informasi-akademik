<?php
class Nilaipelajaran_Model extends CI_Model
{
	var $table  = 'nilai';
	var $key  = 'id_nilai';
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
			if (!empty($filter->pelajaran))
			{
				if(strtolower($filter->pelajaran) != "all")
					$cond[] = "(a.id_pelajaran = '" . $this->db->escape_str(strtolower($filter->pelajaran)) . "')"; 
			}
			
			if (!empty($filter->id_guru))
			{
				if(strtolower($filter->id_guru) != "all")
					$cond[] = "(a.id_guru = '" . $this->db->escape_str(strtolower($filter->id_guru)) . "')"; 
			}
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(j.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(j.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->jenis_nilai))
			{
				if(strtolower($filter->jenis_nilai) != "all")
					$cond[] = "(n.id_jenis_nilai = '" . $this->db->escape_str(strtolower($filter->jenis_nilai)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(a.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(g.id_guru) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nip) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							  or lower(s.nis) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							  or lower(s.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(p.deskripsi) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							  or lower(jn.des_jenis_nilai) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(tt.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS n.*,jn.des_jenis_nilai,j.id_ajar,j.id_kelas,j.semester,t.nis,t.no_absen,s.nama,a.id_guru,a.id_pelajaran,a.id_thn_ajaran,
								 g.nama nama_guru,p.deskripsi,k.nm_kelas,tt.thn_ajaran
								   FROM ".$this->table." n
								   LEFT JOIN jenis_nilai jn ON n.id_jenis_nilai = jn.id_jenis_nilai
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
								   LEFT JOIN jadwal j on n.id_jadwal = j.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on a.id_thn_ajaran = tt.id_thn_ajaran
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
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS n.*,jn.des_jenis_nilai,j.id_ajar,j.id_kelas,j.semester,t.nis,t.no_absen,s.nama,a.id_guru,a.id_pelajaran,a.id_thn_ajaran,
								 g.nama,p.deskripsi,k.nm_kelas,tt.thn_ajaran
								   FROM ".$this->table." n
								   LEFT JOIN jenis_nilai jn ON n.id_jenis_nilai = jn.id_jenis_nilai
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
								   LEFT JOIN jadwal j on n.id_jadwal = j.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on a.id_thn_ajaran = tt.id_thn_ajaran
								   $where
								 
								   ");
		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
		
		$query->free_result();
		
		return $result;
	}
	
	public function cekNilai($id = null,$id_jadwal,$id_jenis_nilai,$id_tempati)
	{
		
		if($id)
		{
			$where = "WHERE id_jadwal = '".$this->db->escape_str(strtolower($id_jadwal))."' and id_jenis_nilai = '".$this->db->escape_str(strtolower($id_jenis_nilai))."' and id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE id_jadwal = '".$this->db->escape_str(strtolower($id_jadwal))."' and id_jenis_nilai = '".$this->db->escape_str(strtolower($id_jenis_nilai))."' and id_tempati = '".$this->db->escape_str(strtolower($id_tempati))."' ";
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
					$cond[] = "(j.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(j.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(a.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
		$query = $this->db->query("SELECT  p.deskripsi pelajaran,group_concat(jn.des_jenis_nilai order by n.id_jenis_nilai asc separator '|') jenis_nilai,group_concat(n.nilai separator '|') nilai,group_concat(g.nama separator '|') guru
										  FROM ".$this->table." n
										   LEFT JOIN jenis_nilai jn ON n.id_jenis_nilai = jn.id_jenis_nilai
										   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
										   LEFT JOIN siswa s on t.nis = s.nis
										   LEFT JOIN jadwal j on n.id_jadwal = j.id_jadwal
										   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
										   LEFT JOIN guru g ON a.id_guru = g.id_guru
										   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
										   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
										   LEFT JOIN tahun_ajaran tt on a.id_thn_ajaran = tt.id_thn_ajaran
										   $where
										   GROUP BY a.id_pelajaran ORDER BY p.deskripsi desc
								 
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
	
	function getAllRekap($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->pelajaran))
			{
				if(strtolower($filter->pelajaran) != "all")
					$cond[] = "(a.id_pelajaran = '" . $this->db->escape_str(strtolower($filter->pelajaran)) . "')"; 
			}
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(j.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->nis))
			{
				if(strtolower($filter->nis) != "all")
					$cond[] = "(t.nis = '" . $this->db->escape_str(strtolower($filter->nis)) . "')"; 
			}
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
			}
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(j.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(a.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
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
			
		$query = $this->db->query("SELECT j.semester,t.nis,t.no_absen,s.nama,p.deskripsi,p.kkm,k.nm_kelas,tt.thn_ajaran,ww.nama as walikelas,group_concat(jn.des_jenis_nilai order by n.id_jenis_nilai) as jenis_nilai ,group_concat(n.nilai order by n.id_jenis_nilai) as nilai
								   FROM ".$this->table." n
								   LEFT JOIN jenis_nilai jn ON n.id_jenis_nilai = jn.id_jenis_nilai
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
                                   LEFT JOIN walikelas w on t.id_kelas = w.id_kelas && t.id_thn_ajaran = w.id_thn_ajaran
                                   LEFT JOIN guru ww on w.id_guru = ww.id_guru
								   LEFT JOIN jadwal j on n.id_jadwal = j.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on a.id_thn_ajaran = tt.id_thn_ajaran
								   $where  group by t.nis,a.id_pelajaran ORDER BY $orderBy $orderType $limitOffset
								 
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;
		
		return array($result,$total);
	}
	
	
	function getAllRekapRaport($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->pelajaran))
			{
				if(strtolower($filter->pelajaran) != "all")
					$cond[] = "(a.id_pelajaran = '" . $this->db->escape_str(strtolower($filter->pelajaran)) . "')"; 
			}
			
			if (!empty($filter->kelas))
			{
				if(strtolower($filter->kelas) != "all")
					$cond[] = "(j.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			
			if (!empty($filter->nis))
			{
				if(strtolower($filter->nis) != "all")
					$cond[] = "(t.nis = '" . $this->db->escape_str(strtolower($filter->nis)) . "')"; 
			}
			
			if (!empty($filter->idtempati))
			{
				  $cond[] = "(lower(t.id_tempati) = '" . $this->db->escape_str(strtolower($filter->idtempati)) . "' )";
			}
			
			if (!empty($filter->semester))
			{
				if(strtolower($filter->semester) != "all")
					$cond[] = "(j.semester = '" . $this->db->escape_str(strtolower($filter->semester)) . "')"; 
			}
			
			if (!empty($filter->thn_ajaran))
			{
				if(strtolower($filter->thn_ajaran) != "all")
					$cond[] = "(a.id_thn_ajaran = '" . $this->db->escape_str(strtolower($filter->thn_ajaran)) . "')"; 
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
			
		$query = $this->db->query("SELECT j.semester,t.nis,t.no_absen,s.nama,p.deskripsi,p.kkm,k.nm_kelas,tt.thn_ajaran,ww.nama as walikelas,round(avg(n.nilai)) as nilai,group_concat(jn.des_jenis_nilai) as jenis_nilai
								   FROM ".$this->table." n
								   LEFT JOIN jenis_nilai jn ON n.id_jenis_nilai = jn.id_jenis_nilai
								   LEFT JOIN tempati t on n.id_tempati = t.id_tempati
								   LEFT JOIN siswa s on t.nis = s.nis
                                   LEFT JOIN walikelas w on t.id_kelas = w.id_kelas && t.id_thn_ajaran = w.id_thn_ajaran
                                   LEFT JOIN guru ww on w.id_guru = ww.id_guru
								   LEFT JOIN jadwal j on n.id_jadwal = j.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran tt on a.id_thn_ajaran = tt.id_thn_ajaran
								   $where  group by t.nis,a.id_pelajaran ORDER BY $orderBy $orderType $limitOffset
								 
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;
		
		return array($result,$total);
	}
	
	function getAllNilai($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
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
			
			if (!empty($filter->siswa))
			{
				if(strtolower($filter->siswa) != "all")
					$cond[] = "(t.nis = '" . $this->db->escape_str(strtolower($filter->siswa)) . "')"; 
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
			
		$query = $this->db->query("select round(avg(nil)) as nilai,k.nm_kelas as kelas from 
										( (SELECT round(AVG(nilai)) nil,n.id_tempati,id_thn_ajaran,id_kelas from nilai  n LEFT JOIN tempati t on t.id_tempati = n.id_tempati $where group by id_tempati ORDER BY $orderBy $orderType $limitOffset)
										UNION ALL
									   (SELECT round(AVG(nilai)) nil,n.id_tempati,id_thn_ajaran,id_kelas from nilai_ekskul n LEFT JOIN tempati t on t.id_tempati = n.id_tempati $where group by id_tempati ORDER BY $orderBy $orderType $limitOffset)
									   UNION ALL
									   (SELECT round(AVG(nilai)) nil,n.id_tempati,id_thn_ajaran,id_kelas from nilai_kepribadian n LEFT JOIN tempati t on t.id_tempati = n.id_tempati $where group by id_tempati ORDER BY $orderBy $orderType $limitOffset)
								   ) nn
								   LEFT JOIN tahun_ajaran tt on nn.id_thn_ajaran = tt.id_thn_ajaran
								   LEFT JOIN kelas k ON nn.id_kelas = k.id_kelas
								   group by id_tempati
								   ");
								   
		$result = $query->result_array();
		
		$query->free_result();
		
		return $result;
	}
}