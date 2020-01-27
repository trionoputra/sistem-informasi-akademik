<?php
class Ajax extends Admin_Controller {
	
	public function getTableSiswa()
	{
		$this->load->model("siswa_model");
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->userid = trim($this->input->get('userid'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 10;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		
		list($data['data'],$total) = $this->siswa_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		$content = "<table class='table table-striped table-small'>
						<thead>
						   <tr>
								<th>NIS</th>
								<th>NISN</th>
								<th>Nama</th>
								<th>Jenis Kelamin</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
								<th>Agama</th>
								<th>Alamat</th>
								<th>Action</th>
							  </tr>
						</thead>
						<tbody>";
		
		if(sizeof($data['data']) == 0)
		{
			$content .= "<tr><td><h3>data tidak tersedia</h3></td></tr>";
		}
		else
		{
			foreach($data['data'] as $dt)
			{
				
				$content .= "<tr>
								<td>".$dt['nis']."</td>
								<td>".$dt['nis_nasional']."</td>
								<td>".$dt['nama']."</td>
								<td>".($dt['jenis_kelamin'] == '1' ? 'pria' : 'wanita')."</td>
								<td>".$dt['tempat_lahir']."</td>
								<td>".$dt['tanggal_lahir']."</td>
								<td>".$dt['agama']."</td>
								<td>".$dt['alamat']."</td>";
				
				$content .="<td><button type='button' class='btn btn-success btn-sm' href='#' onClick='pilih(&quot;".$dt['nis']."&quot;)' data-dismiss='modal'>pilih</button></td>";
				
				
				$content  .= "</tr>";
			}
		}
		
		$content .= "</tbody></table>";
		
		$link = "";
		
		if($total > $limit)
		{
			$link .="<ul class='pagination'>";
			for($i=0;$i < $total/$limit;$i++)
			{
				if($page == $i+1)		
					$link .="<li class='active'><a>".($i+1)."</a></li>";
				else
					$link .="<li><a href='#' onclick='getSiswa(".($i+1).")'>".($i+1)."</a></li>";
			}
			
			$link .="</ul>";
		}
		
		echo $content.$link;
		
	}
	
	public function getTableGuru()
	{
		$this->load->model("guru_model");
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->userid = trim($this->input->get('userid'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 10;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->guru_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		$content = "<table class='table table-striped table-small siswa'>
						<thead>
						   <tr>
								<th>ID Guru</th>
								<th>NIP</th>
								<th>Nama</th>
								<th>Jenis Kelamin</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
								<th>Agama</th>
								<th>Golongan</th>
								<th>Jabatan</th>
								<th>Pendidikan Terakhir</th>
								<th>Action</th>
							  </tr>
						</thead>
						<tbody>";
		
		if(sizeof($data['data']) == 0)
		{
			$content .= "<tr><td><h3>data tidak tersedia</h3></td></tr>";
		}
		else
		{
			foreach($data['data'] as $dt)
			{
				
				$content .= "<tr>
								<td>".$dt['id_guru']."</td>
								<td>".$dt['nip']."</td>
								<td>".$dt['nama']."</td>
								<td>".($dt['jenis_kelamin'] == '1' ? 'pria' : 'wanita')."</td>
								<td>".$dt['tempat_lahir']."</td>
								<td>".$dt['tgl_lahir']."</td>
								<td>".$dt['agama']."</td>
								<td>".$dt['golongan']."</td>
								<td>".$dt['jabatan']."</td>
								<td>".$dt['pendidikan_terakhir']."</td>";
				
				$content .="<td><button type='button' class='btn btn-success btn-sm' href='#' onClick='pilih(&quot;".$dt['id_guru']."&quot;)' data-dismiss='modal'>pilih</button></td>";
				
				
				$content  .= "</tr>";
			}
		}
		
		$content .= "</tbody></table>";
		
		$link = "";
		
		if($total > $limit)
		{
			$link .="<ul class='pagination'>";
			for($i=0;$i < $total/$limit;$i++)
			{
				if($page == $i+1)		
					$link .="<li class='active'><a>".($i+1)."</a></li>";
				else
					$link .="<li><a href='#' onclick='getGuru(".($i+1).")'>".($i+1)."</a></li>";
			}
			
			$link .="</ul>";
		}
		
		echo $content.$link;
		
	}
	
	public function getTableAjar()
	{
		$this->load->model("guruspesialis_model");
		$filter = new StdClass();
		
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->thn_ajaran = trim($this->input->get('id_thn_ajaran'));
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 10;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->guruspesialis_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		$content = "<table class='table table-striped table-small'>
						<thead>
						   <tr>
								<th>ID Pelajaran</th>
								<th>Pelajaran</th>
								<th>KKM</th>
								<th>Nama Guru</th>
								<th>Action</th>
							  </tr>
						</thead>
						<tbody>";
		
		if(sizeof($data['data']) == 0)
		{
			$content .= "<tr><td><h3>data tidak tersedia</h3></td></tr>";
		}
		else
		{
			foreach($data['data'] as $dt)
			{
				
				$content .= "<tr>
								<td>".$dt['id_pelajaran']."</td>
								<td>".$dt['deskripsi']."</td>
								<td>".$dt['kkm']."</td>
								<td>".$dt['nama']."</td>";
				
				$content .="<td><button type='button' class='btn btn-success btn-sm' href='#' onClick='pilih(&quot;".$dt['id_ajar']."&quot;,&quot;".$dt['deskripsi']."&quot;,&quot;".$dt['nama']."&quot;)' data-dismiss='modal'>pilih</button></td>";
				
				
				$content  .= "</tr>";
			}
		}
		
		$content .= "</tbody></table>";
		
		$link = "";
		
		if($total > $limit)
		{
			$link .="<ul class='pagination'>";
			for($i=0;$i < $total/$limit;$i++)
			{
				if($page == $i+1)		
					$link .="<li class='active'><a>".($i+1)."</a></li>";
				else
					$link .="<li><a href='#' onclick='getAjar(".($i+1).")'>".($i+1)."</a></li>";
			}
			
			$link .="</ul>";
		}
		
		echo $content.$link;
		
	}
	
	public function getTableKelasSiswa()
	{
		$this->load->model("kelassiswa_model");
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->id_thn_ajaran = trim($this->input->get('id_thn_ajaran'));
		$filter->id_kelas = trim($this->input->get('kelas'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 10;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->kelassiswa_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		$content = "<table class='table table-striped table-small'>
						<thead>
						   <tr>
								<th>NIS</th>
								<th>NIS Nasional</th>
								<th>Nama</th>
								<th>Jenis Kelamin</th>
								<th>Agama</th>
								<th>Action</th>
							  </tr>
						</thead>
						<tbody>";
		
		if(sizeof($data['data']) == 0)
		{
			$content .= "<tr><td><h3>data tidak tersedia</h3></td></tr>";
		}
		else
		{
			foreach($data['data'] as $dt)
			{
				
				$content .= "<tr>
								<td>".$dt['nis']."</td>
								<td>".$dt['nis_nasional']."</td>
								<td>".$dt['nama']."</td>
								<td>".($dt['jenis_kelamin'] == '1' ?  'pria' : 'wanita')."</td>
								<td>".$dt['agama']."</td>";
				
				$content .="<td><button type='button' class='btn btn-success btn-sm' href='#' onClick='pilih(&quot;".$dt['id_tempati']."&quot;,&quot;".$dt['nis']."&quot;)' data-dismiss='modal'>pilih</button></td>";
				
				
				$content  .= "</tr>";
			}
		}
		
		
		$content .= "</tbody></table>";
		
		$link = "";
		
		if($total > $limit)
		{
			$link .="<ul class='pagination'>";
			for($i=0;$i < $total/$limit;$i++)
			{
				if($page == $i+1)		
					$link .="<li class='active'><a>".($i+1)."</a></li>";
				else
					$link .="<li><a href='#' onclick='getSiswa(".($i+1).")'>".($i+1)."</a></li>";
			}
			
			$link .="</ul>";
		}
		
		echo $content.$link;
		
	}
	
	public function getTableJadwal()
	{
		$this->load->model("jadwal_model");
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		$filter->thn_ajaran = trim($this->input->get('id_thn_ajaran'));
		$filter->semester = trim($this->input->get('semester'));
		$filter->kelas = trim($this->input->get('kelas'));
		if($this->getStatus() == 2)
		{
			$this->load->model("guru_model");
			$guru =  $this->guru_model->get_by("id_user",$this->session->userdata('isLogin1'),true);
			
			$filter->id_guru = $guru->id_guru;
			
		}
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 10;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->jadwal_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		$content = "<table class='table table-striped table-small'>
						<thead>
						   <tr>
								<th>Kelas</th>
								<th>Pelajaran</th>
								<th>Guru</th>
								<th>Semester</th>
								<th>Tahun ajaran</th>
								<th>Action</th>
							  </tr>
						</thead>
						<tbody>";
		
		
		if(sizeof($data['data']) == 0)
		{
			$content .= "<tr><td><h3>data tidak tersedia</h3></td></tr>";
		}
		else
		{
			foreach($data['data'] as $dt)
			{
				
				$content .= "<tr>
								<td>".$dt['nm_kelas']."</td>
								<td>".$dt['deskripsi']."</td>
								<td>".$dt['nama']."</td>
								<td>".$dt['semester']."</td>
								<td>".$dt['thn_ajaran']."</td>";
				
				$content .="<td><button type='button' class='btn btn-success btn-sm' href='#' onClick='pilih(&quot;".$dt['id_jadwal']."&quot;,&quot;".$dt['deskripsi']."&quot;,&quot;".$dt['nama']."&quot;)' data-dismiss='modal'>pilih</button></td>";
				
				
				$content  .= "</tr>";
			}
		}
		
		$content .= "</tbody></table>";
		
		$link = "";
		
		if($total > $limit)
		{
			$link .="<ul class='pagination'>";
			for($i=0;$i < $total/$limit;$i++)
			{
				if($page == $i+1)		
					$link .="<li class='active'><a>".($i+1)."</a></li>";
				else
					$link .="<li><a href='#' onclick='getJadwal(".($i+1).")'>".($i+1)."</a></li>";
			}
			
			$link .="</ul>";
		}
		
		echo $content.$link;
		
	}
	
	public function getKelasSiswa($thn)
	{
		$this->load->model("kelassiswa_model");
		$filter = new StdClass();
		$filter->id_thn_ajaran = trim($thn);
		
		list($data['data'],$total) = $this->kelassiswa_model->getAll($filter,null,null,"k.nm_kelas",'asc');
		
		echo json_encode($data['data']);
		
	}
	
}
