<script>
	$(function(){
		 $("select[name='thn_ajaran']").change(function()
		 {
			 $('#filter-form').submit();
		 });
		 
		 $("select[name='kelas']").change(function()
		 {
			 $('#filter-form').submit();
		 });
		 
		 $("select[name='semester']").change(function()
		 {
			 $('#filter-form').submit();
		 });
	});
</script>
<div id="body-container">
	<div class="container">
		<div class="page-header form-header">
			<h3><?php echo $title?></h3>
		</div>
		<?php
			 $msg_err = $this->session->flashdata('admin_save_error');
			 $msg_succes = $this->session->flashdata('admin_save_success');
		?>
		<?php if(!empty($msg_err)): ?>
		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Error!</strong> <?php echo $msg_err;?>
		</div>
		<?php endif; ?>
		<?php if(!empty($msg_succes)): ?>
		<div class="alert alert-success">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Succes!</strong> <?php echo $msg_succes;?>
		</div>
		<?php endif; ?>
		<div class="panel panel-default form-master">
			<form class="form-horizontal" method="get" action="<?php echo site_url("nilaiekskul/rekap/siswa")?>" id="filter-form">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-5  pull-right">
						<div class="form-action pull-right">
							<button type="submit" class="btn btn-success" name="action" value="pdf">Export to PDF</button> 
<!--							<button type="submit" class="btn btn-success" name="action" value="excel">Export to Excel</button> -->
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group ">
					<label for="nis" class="col-sm-2">NIS</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis ; ?>" disabled />
					</div>
				</div>
				<div class="form-group ">
					<label for="nama" class="col-sm-2">Nama</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ; ?>" disabled />
					</div>
				</div>
				<div class="form-group">
					<label for="kelas" class="col-sm-2">Kelas</label>
					<div class="col-sm-2">
						<select class="form-control input-sm" name="kelas">
						  <?php foreach ($kelas as $kls):?>
						  <option value="<?php echo $kls['id_kelas']."|".$kls['id_tempati'];?>" <?php echo $this->input->get("kelas") == $kls['id_kelas']."|".$kls['id_tempati'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
						  <?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group ">
					<label for="no_absen" class="col-sm-2">No Absen</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="no_absen" name="no_absen" value="<?php echo $no_absen ; ?>" disabled />
					</div>
				</div>
				
				<div class="form-group">
					<label for="semester" class="col-sm-2">Semester</label>
					<div class="col-sm-2">
						<select class="form-control input-sm" name="semester">
							<option value="1" <?php echo $this->input->get("semester") == '1' ? ' selected' : '';?> >1</option>
							<option value="2" <?php echo $this->input->get("semester") == '2' ? ' selected' : '';?> >2</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tahun_ajaran" class="col-sm-2">Tahun ajaran</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="thn_ajaran" name="thn_ajaran" value="<?php echo $tahun_ajaran; ?>" disabled />
					</div>
				</div>
			</form>
			
			<table class="table table-striped table-small">
			<thead>
			  <tr>
				<th>No</th>
				<th>Kegiatan Ekstrakulikuler</th>
				<th>Nilai Angka</th>
				<th>Nilai Huruf</th>
			  </tr>
			</thead>
			<tbody>
			<?php foreach($data as $key => $dt): ?>
			  <tr>
				<td><?php echo ($key+1)?></td>
				<td><?php echo $dt['nm_ekskul'];?></td>
				<td><?php echo $dt['nilai'];?></td>
				<td><?php echo $dt['huruf'];?></td>
			  </tr>
			<?php endforeach ?>
			</tbody>
		</table>
		</div>
	</div>
	</div>
</div>

