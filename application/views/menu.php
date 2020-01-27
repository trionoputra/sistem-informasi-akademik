<?php 
	$CI =& get_instance();
?>
<div id="menu-container">
	<nav class="navbar">
		<div class="container">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
	  </div>
	 
	  <div class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
		<ul class="nav navbar-nav">
		  <li><a href="<?php echo site_url('dashboard')?>">Dashboard</a></li>
		  <?php if($CI->getStatus() != 3 ): ?>
		  <li class="dropdown">
			<?php if($CI->getStatus() != 2 && $CI->getStatus() != 1): ?>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Master<span class="caret"></span></a>
			 <?php endif; ?>
			<ul class="dropdown-menu" role="menu">
			  <?php if($CI->cekLoginStatus("sswk")): ?>
			  <li><a href="<?php echo site_url('siswa')?>">Siswa</a></li>
			  <?php endif; ?>
			  <?php if($CI->cekLoginStatus("ss")): ?>
			  <li><a href="<?php echo site_url('guru')?>">Guru</a></li>
			  <?php endif; ?>
			  <?php if($CI->cekLoginStatus("ss")): ?>
			  <li><a href="<?php echo site_url('kelas')?>">Kelas</a></li>
			  <li><a href="<?php echo site_url('pelajaran')?>">Pejalaran</a></li>
			  <li><a href="<?php echo site_url('kepribadian')?>">Kepribadian</a></li>
			  <li><a href="<?php echo site_url('ekskul')?>">Ekstrakulikuler</a></li>
			  <li><a href="<?php echo site_url('jenisnilai')?>">Jenis Nilai</a></li>
			  <li><a href="<?php echo site_url('tahunajaran')?>">Tahun Ajaran</a></li>
			  <li class="divider"></li>
			  <li><a href="<?php echo site_url('user')?>">Users</a></li>
			  <?php endif; ?>
			</ul>
		  </li>
		  <li class="dropdown">
			<?php if($CI->getStatus() != 1): ?>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Transaksi<span class="caret"></span></a>
			<?php endif; ?>
			<ul class="dropdown-menu" role="menu">
				<?php if($CI->cekLoginStatus("sswk")): ?>
				<li><a href="<?php echo site_url('kelassiswa')?>">Tempati (Kelas Siswa)</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("ss")): ?>
				<li><a href="<?php echo site_url('walikelas')?>">Wali Kelas</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("ss")): ?>
				<li><a href="<?php echo site_url('guruspesialis')?>">Ajar (Guru Bidang Studi)</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("ss")): ?>
				<li><a href="<?php echo site_url('jadwal')?>">Jadwal</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("sswk")): ?>
				<li><a href="<?php echo site_url('absen')?>">Absen</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("sswkgr")): ?>
				<li><a href="<?php echo site_url('nilaipelajaran')?>">Nilai Pelajaran Siswa</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("sswk")): ?>
				<li><a href="<?php echo site_url('nilaikepribadian')?>">Nilai Kepribadian Siswa</a></li>
				<?php endif; ?>
				
				<?php if($CI->cekLoginStatus("sswkgr")): ?>
				<li><a href="<?php echo site_url('nilaiekskul')?>">Nilai Ekstrakulikuler</a></li>
				<li><a href="<?php echo site_url('pengumuman')?>">Pengumuman</a></li>
				<?php endif; ?>
			</ul>
		 </li>
		  <li class="dropdown" >
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Laporan<span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
			   <li><a href="<?php echo site_url('kelassiswa/rekap/guru')?>">Laporan Siswa Per Kelas</a></li>
			   <li><a href="<?php echo site_url('nilaikepribadian/rekap/guru')?>">Laporan Nilai Kepribadian Siswa</a></li>
			   <li><a href="<?php echo site_url('nilaipelajaran/rekap/guru')?>">Laporan Nilai Pelajaran Siswa</a></li>
			   <li><a href="<?php echo site_url('nilaiekskul/rekap/guru')?>">Laporan Nilai Ekstrakulikuler Siswa</a></li>
			   <li><a href="<?php echo site_url('absen/rekap/guru')?>">Laporan Absensi Siswa</a></li>
			   <li><a href="<?php echo site_url('jadwal/rekap/guru')?>">Laporan Jadwal</a></li>
			   <li><a href="<?php echo site_url('pengumuman/rekap')?>">Pengumuman</a></li>
			   <li><a href="<?php echo site_url('raport')?>">Raport Siswa</a></li>
			</ul>
		  </li>
		 <?php else: ?>
			<li><a href="<?php echo site_url('raport')?>">Raport</a></li>
			<li><a href="<?php echo site_url('jadwal/rekap/siswa')?>">Jadwal Pelajaran</a></li>
			<li><a href="<?php echo site_url('nilaikepribadian/rekap/siswa')?>">Nilai Kepribadian</a></li>
			<li><a href="<?php echo site_url('nilaiekskul/rekap/siswa')?>">Nilai Ekstrakulikuler</a></li>
			<li><a href="<?php echo site_url('nilaipelajaran/rekap/siswa')?>">Nilai Pelajaran</a></li>
			<li><a href="<?php echo site_url('absen/rekap/siswa')?>">Absensi</a></li>
			<li><a href="<?php echo site_url('pengumuman/rekap')?>">Pengumuman</a></li>
		 <?php endif; ?>
		 <li><a href="<?php echo site_url('setting')?>">Ganti Password</a></li>
		</ul>
	  </div>
	</div>
	</nav>
</div>