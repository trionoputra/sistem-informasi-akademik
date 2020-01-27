<?php
class Jadwal_Model extends CI_Model
{
	var $table  = 'jadwal';
	var $key  = 'id_jadwal';
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
					$cond[] = "(j.id_kelas = '" . $this->db->escape_str(strtolower($filter->kelas)) . "')"; 
			}
			if (!empty($filter->id_guru))
			{
				if(strtolower($filter->id_guru) != "all")
					$cond[] = "(g.id_guru = '" . $this->db->escape_str(strtolower($filter->id_guru)) . "')"; 
			}
			
			if (!empty($filter->pelajaran))
			{
				if(strtolower($filter->pelajaran) != "all")
					$cond[] = "(a.id_pelajaran = '" . $this->db->escape_str(strtolower($filter->pelajaran)) . "')"; 
			}
			

			
			if (!empty($filter->hari))
			{
				if(strtolower($filter->hari) != "all")
					$cond[] = "(dj.hari = '" . $this->db->escape_str(strtolower($filter->hari)) . "')"; 
			}
			
			if (!empty($filter->guru))
			{
				if(strtolower($filter->guru) != "all")
					$cond[] = "(g.nama like '%" . $this->db->escape_str(strtolower($filter->guru)) . "%')"; 
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
			
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(g.id_guru) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nip) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(g.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%' 
							 or lower(p.deskripsi) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(k.nm_kelas) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(t.thn_ajaran) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
							 or lower(dj.hari) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
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
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS j.*,a.*,GROUP_CONCAT(concat(dj.hari,' - ',dj.jam)) waktu,k.nm_kelas,t.thn_ajaran,p.deskripsi,g.nip,g.nama,g.agama,g.jenis_kelamin,g.jabatan,g.golongan,g.pendidikan_terakhir,g.email,g.telpon
								   FROM ".$this->table." j
								   LEFT JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran t on a.id_thn_ajaran = t.id_thn_ajaran
								   $where GROUP BY j.id_jadwal ORDER BY $orderBy $orderType $limitOffset
								 
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
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS j.*,a.*,GROUP_CONCAT(concat(dj.hari,' - ',dj.jam)) waktu,k.nm_kelas,t.thn_ajaran,p.deskripsi,g.nip,g.nama,g.agama,g.jenis_kelamin,g.jabatan,g.golongan,g.pendidikan_terakhir,g.email,g.telpon
								   FROM ".$this->table." j
								   LEFT JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran t on a.id_thn_ajaran = t.id_thn_ajaran
								   $where GROUP BY j.id_jadwal
								   ");
		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
		
		$query->free_result();
		
		return $result;
	}
	
	public function cekJadwal($id = null,$id_kelas,$semester,$id_ajar)
	{
		
		if($id)
		{
			$where = "WHERE id_ajar = '".$this->db->escape_str(strtolower($id_ajar))."' and id_kelas = '".$this->db->escape_str(strtolower($id_kelas))."' and semester = '".$this->db->escape_str(strtolower($semester))."' and ".$this->key." <> '".$this->db->escape_str(strtolower($id))."' ";
		}
		else
		{
			$where = "WHERE id_ajar = '".$this->db->escape_str(strtolower($id_ajar))."' and id_kelas = '".$this->db->escape_str(strtolower($id_kelas))."' and semester = '".$this->db->escape_str(strtolower($semester))."' ";
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
	
	
	function remove_detail($id)
    {
      if (!is_array($id))
		    $id = array($id);
			
		$this->db->where_in($this->key, $id)->delete("detail_jadwal");
    }
	
	
	function save_detail($data = array())
	{
		$this->db->insert("detail_jadwal", $data);	
		return $this->db->affected_rows();
	}
	
	public function cekAvalaible($id)
	{
		$query = $this->db->query("(SELECT  ".$this->key." FROM nilai where ".$this->key." = '".$this->db->escape_str(strtolower($id))."') ");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}
	
	public function getjadwal($filter)
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
		$query = $this->db->query("SELECT  dj.hari,group_concat(p.deskripsi separator '|') pelajaran,group_concat(dj.jam order by dj.jam asc separator '|') jam,group_concat(g.nama separator '|') guru, g.agama
										   FROM jadwal j
										   LEFT JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
										   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
										   LEFT JOIN guru g ON a.id_guru = g.id_guru
										   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
										   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
										   LEFT JOIN tahun_ajaran t on a.id_thn_ajaran = t.id_thn_ajaran
										   $where
										   GROUP BY dj.hari ORDER BY field(dj.hari,'senin','selasa','rabu','kamis','jumat','sabtu','minggu')
								 
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
			
		$query = $this->db->query("SELECT k.nm_kelas as 'Kelas',p.deskripsi as 'Mata Pelajaran',g.nama as 'Nama Guru',GROUP_CONCAT(concat(dj.hari,' - (',dj.jam,')')) as 'Waktu', g.agama as 'Agama'
								   FROM ".$this->table." j
								   LEFT JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
								   LEFT JOIN ajar a ON j.id_ajar = a.id_ajar
								   LEFT JOIN guru g ON a.id_guru = g.id_guru
								   LEFT JOIN pelajaran p ON a.id_pelajaran = p.id_pelajaran
								   LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
								   LEFT JOIN tahun_ajaran t on a.id_thn_ajaran = t.id_thn_ajaran
								   $where GROUP BY j.id_jadwal ORDER BY $orderBy $orderType $limitOffset
								   ");
		$result = $query->result_array();
		
		$query->free_result();
		return $result;
	}
}