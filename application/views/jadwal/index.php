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
		
		<div class="form-filter">
				<form class="form-inline" method="get" action="<?php echo site_url("jadwal")?>" >
					<div class="form-group">
						<input type="text" class="form-control input-sm" id="keyword" placeholder="Keyword" name="keyword" value="<?php echo $this->input->get('keyword');?>">
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="pelajaran">
						  <option value="all">Pelajaran</option>
						  <?php foreach ($pelajaran as $kls):?>
						  <option value="<?php echo $kls['id_pelajaran'];?>" <?php echo $this->input->get("pelajaran") == $kls['id_pelajaran'] ? ' selected' : '';?> ><?php echo $kls['deskripsi']?></option>
						  <?php endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="kelas">
						  <option value="all">Kelas</option>
						  <?php foreach ($kelas as $kls):?>
						  <option value="<?php echo $kls['id_kelas'];?>" <?php echo $this->input->get("kelas") == $kls['id_kelas'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
						  <?php endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="semester">
							<option value="all">Semester</option>
							<option value="1" <?php echo $this->input->get("semester") == '1' ? ' selected' : '';?> >1</option>
							<option value="2" <?php echo $this->input->get("semester") == '2' ? ' selected' : '';?> >2</option>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="hari">
							<option value="all">Hari</option>
							<option value="senin" <?php echo $this->input->get("hari") == 'senin' ? ' selected' : '';?> >Senin</option>
							<option value="selasa" <?php echo $this->input->get("hari") == 'selasa' ? ' selected' : '';?> >Selasa</option>
							<option value="rabu" <?php echo $this->input->get("hari") == 'rabu' ? ' selected' : '';?> >Rabu</option>
							<option value="kamis" <?php echo $this->input->get("hari") == 'kamis' ? ' selected' : '';?> >Kamis</option>
							<option value="jumat" <?php echo $this->input->get("hari") == 'jumat' ? ' selected' : '';?> >Jumat</option>
							<option value="sabtu" <?php echo $this->input->get("hari") == 'sabtu' ? ' selected' : '';?> >Sabtu</option>
							<option value="minggu" <?php echo $this->input->get("hari") == 'minggu' ? ' selected' : '';?> >Minggu</option>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="thn_ajaran">
						  <option value="all">Tahun Ajaran</option>
						  <?php foreach ($tahun_ajaran as $thn):?>
						  <option value="<?php echo $thn['id_thn_ajaran'];?>" <?php echo $this->input->get("thn_ajaran") == $thn['id_thn_ajaran'] ? 'selected' : '';?> ><?php echo $thn['thn_ajaran']?></option>
						  <?php endforeach;?>
						</select>
					</div>
					<button type="submit" class="btn btn-primary btn-sm">Search</button>
					<a href="<?php echo site_url("jadwal/manage")?>" class="btn btn-success btn-sm">Add New</a>
				</form>
			</div>
		<table class="table table-striped table-small">
		<thead>
		  <tr>
			<th>Kelas</th>
			<th>Pelajaran</th>
			<th>Guru</th>
			<th>Hari dan Jam</th>
			<th>Semester</th>
			<th>Tahun Ajaran</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
		<?php foreach($data as $dt): ?>
		  <tr>
			<td><?php echo $dt['nm_kelas'];?></td>
		    <td><?php echo $dt['deskripsi'];?></td>
			<td><?php echo $dt['nama'];?></td>
			<td><?php
					$waktu =  explode(',',$dt['waktu']);
				    foreach($waktu as $wk)
					{
						echo "<div style='margin:5px;'><span class='btn btn-default btn-xs'>".ucwords($wk)."<span></div>";
					}
				?>
			</td>
			<td><?php echo $dt['semester'];?></td>
			<td><?php echo $dt['thn_ajaran'];?></td>
			<th>
				<a class="btn btn-warning btn-xs" href="<?php echo site_url("jadwal/manage")."/". $dt['id_jadwal']; ?>"><span class="glyphicon glyphicon-edit"></span></a>
				<a class="btn btn-danger btn-xs" data-href="<?php echo site_url("jadwal/delete")."/". $dt['id_jadwal'];?>" data-toggle="modal" data-target="#confirm-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a>
			</th>
		  </tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<?php echo $this->pagination->create_links();?>
	</div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Konfirmasi</h4>
			</div>
		
			<div class="modal-body">
				<p>Yakin ingin hapus?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a href="#" class="btn btn-danger danger delete">Delete</a>
			</div>
		</div>
	</div>
</div>
<script>
	$('#confirm-delete').on('show.bs.modal', function(e) {
		$(this).find('.delete').attr('href', $(e.relatedTarget).data('href'));
	});
</script>
