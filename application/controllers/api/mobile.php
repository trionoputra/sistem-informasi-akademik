<?php
	require APPPATH.'/libraries/Rest_Controller.php';
	class Mobile extends REST_Controller
	{		
		public function authenticate()
		{
		   	$this->load->model('user_model');
			$get = $this->input->get("auth");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$userid = $dec[0];
				$password = $dec[1];
				
				$user = $this->user_model->authenticate_mobile($userid,md5($password));
				if($user)
				{
					$this->load->model('kelassiswa_model');
					
					$data = $this->kelassiswa_model->getKelas($user->id);
					echo $this->respon($data);	
				}
				else
					echo $this->respon("invalid username or password");
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getkelas()
		{
		   	$this->load->model('user_model');
			$get = $this->input->get("data");
			if($get)
			{
				$this->load->model('kelassiswa_model');
				$id = $this->decrypt($get);
				$data = $this->kelassiswa_model->getKelas($id);
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getjadwal()
		{
		   	$this->load->model('jadwal_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$idkelas = $dec[0];
				$idthnajaran = $dec[1];
				$semester = $dec[2];
				
				$filter = new StdClass();
				$filter->kelas = $idkelas ;
				$filter->thn_ajaran = $idthnajaran;
				$filter->semester = $semester;
				
				$data = $this->jadwal_model->getjadwal($filter);
				
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getnilai()
		{
		   	$this->load->model('nilaipelajaran_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$idkelas = $dec[0];
				$idthnajaran = $dec[1];
				$semester = $dec[2];
				
				$filter = new StdClass();
				$filter->kelas = $idkelas ;
				$filter->thn_ajaran = $idthnajaran;
				$filter->semester = $semester;
				
				$data = $this->nilaipelajaran_model->getnilai($filter);
				
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getnilaiekskul()
		{
		   	$this->load->model('nilaiekskul_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$idkelas = $dec[0];
				$idthnajaran = $dec[1];
				$semester = $dec[2];
				
				$filter = new StdClass();
				$filter->kelas = $idkelas ;
				$filter->thn_ajaran = $idthnajaran;
				$filter->semester = $semester;
				
				$data = $this->nilaiekskul_model->getnilai($filter);
				
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getnilaikepribadian()
		{
		   	$this->load->model('nilaikepribadian_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$idkelas = $dec[0];
				$idthnajaran = $dec[1];
				$semester = $dec[2];
				
				$filter = new StdClass();
				$filter->kelas = $idkelas ;
				$filter->thn_ajaran = $idthnajaran;
				$filter->semester = $semester;
				
				$data = $this->nilaikepribadian_model->getnilai($filter);
				
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function getabsen()
		{
		   	$this->load->model('absen_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				$idkelas = $dec[0];
				$idthnajaran = $dec[1];
				$semester = $dec[2];
				
				$filter = new StdClass();
				$filter->kelas = $idkelas ;
				$filter->thn_ajaran = $idthnajaran;
				$filter->semester = $semester;
				
				$data = $this->absen_model->getabsen($filter);
				
				echo $this->respon($data);	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
		
		public function change()
		{
		   	$this->load->model('user_model');
			$get = $this->input->get("data");
			if($get)
			{
				$dec = explode("|",$this->decrypt($get));
				if(sizeof($dec) == 2)
				{
					$id = $dec['0'];
					$dt['username'] = $dec['1'];
					$cek2 = $this->user_model->cekUserName($id,$dt['username']);
						
					if(!empty($cek2))
					{
						echo $this->respon("username sudah terdaftar");
						exit;
					}
					else
					{
						$this->user_model->save($id,$dt,false);
					}
				}
				else if(sizeof($dec) == 4)
				{
					$id = $dec['0'];
					$dt['username'] = $dec['1'];
					$dt['password'] = md5($dec['3']);
					$cek = $this->user_model->cekPassword($id,md5($dec['2']));
					
					if(empty($cek))
					{
						echo $this->respon("password lama salah");
						exit;
					}
					else
					{
						$cek2 = $this->user_model->cekUserName($id,$dt['username']);
						
						if(!empty($cek2))
						{
							echo $this->respon("username sudah terdaftar");
							exit;
						}
						else
						{
							$this->user_model->save($id,$dt,false);
						}
						
					}
				}
				
				echo $this->respon("success");	
				
			}
			else
				echo $this->respon("method not allowed",false);
		}
	}