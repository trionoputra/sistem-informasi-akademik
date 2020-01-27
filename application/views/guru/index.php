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
				<form class="form-inline" method="get" action="<?php echo site_url("guru")?>" >
					<div class="form-group">
						<input type="text" class="form-control input-sm" id="keyword" placeholder="Keyword" name="keyword" value="<?php echo $this->input->get('keyword');?>">
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="gender">
						  <option value="all">Jenis Kelamin</option>
						  <option value="1" <?php echo $this->input->get("gender") == "1" ? ' selected' : '';?>>Pria</option>
						  <option value="2" <?php echo $this->input->get("gender") == "2" ? ' selected' : '';?>>Wanita</option>
						</select>
					</div>
					  <div class="form-group">
						<select class="form-control input-sm" name="agama">
						  <option value="all">Agama</option>
						  <option value="islam" <?php echo $this->input->get("agama") == "islam" ? ' selected' : '';?>>Islam</option>
						  <option value="katolik" <?php echo $this->input->get("agama") == "katolik" ? ' selected' : '';?>>Katolik</option>
						  <option value="protestan" <?php echo $this->input->get("agama") == "protestan" ? ' selected' : '';?>>Protestan</option>
						  <option value="hindu" <?php echo $this->input->get("agama") == "hindu" ? ' selected' : '';?>>Hindu</option>
						  <option value="buddha" <?php echo $this->input->get("agama") == "buddha" ? ' selected' : '';?>>Buddha</option>
						  <option value="konghucu" <?php echo $this->input->get("agama") == "konghucu" ? ' selected' : '';?>>Konghucu</option>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" name="jabatan">
						  <option value="all">jabatan</option>
						  <option value="Guru Kelas" <?php echo $this->input->get("jabatan") == "Guru Kelas" ? 'selected' : '';?> >Guru Kelas</option>
						  <option value="Guru Bidang Studi" <?php echo $this->input->get("jabatan") == "Guru Bidang Studi" ? 'selected' : '';?> >Guru Bidang Studi</option>
						  <option value="TU" <?php echo $this->input->get("jabatan") == "TU" ? 'selected' : '';?> >TU</option>
						  <option value="Operasional" <?php echo $this->input->get("jabatan") == "Operasional" ? 'selected' : '';?> >Operasional</option>
						</select>
					</div>
					<button type="submit" class="btn btn-primary btn-sm">Search</button>
					<a href="<?php echo site_url("guru/manage")?>" class="btn btn-success btn-sm">Add New</a>
				</form>
			</div>
		<table class="table table-striped table-small siswa">
		<thead>
		  <tr>
			<th>ID Guru</th>
			<th>NIP</th>
			<th>Nama</th>
			<th>Jenis Kelamin</th>
			<th>Tempat Lahir</th>
			<th>Tanggal Lahir</th>
			<th>Agama</th>
			<th>Alamat</th>
			<th>Jabatan</th>
			<th>Golongan</th>
			<th>Email</th>
			<th>Telepon</th>
			<th>Pendidikan Terakhir</th>
			<th>Tanggal Masuk</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
		<?php foreach($data as $dt): ?>
		  <tr>
			<td><?php echo $dt['id_guru'];?></td>
		    <td><?php echo $dt['nip'];?></td>
			<td><?php echo $dt['nama'];?></td>
			<td><?php echo $dt['jenis_kelamin'] == '1' ? 'pria' : 'wanita';?></td>
			<td><?php echo $dt['tempat_lahir'];?></td>
			<td><?php echo date('d/m/Y',strtotime($dt['tgl_lahir']));?></td>
			<td><?php echo $dt['agama'];?></td>
			<td><?php echo $dt['alamat'];?></td>
			<td><?php echo $dt['jabatan'];?></td>
			<td><?php echo $dt['golongan'];?></td>
			<td><?php echo $dt['email'];?></td>
			<td><?php echo $dt['telpon'];?></td>
			<td><?php echo $dt['pendidikan_terakhir'];?></td>
			<td><?php echo date('d/m/Y',strtotime($dt['tgl_masuk']));?></td>
			<th>
				<a class="btn btn-warning btn-xs" href="<?php echo site_url("guru/manage")."/". $dt['id_guru']; ?>"><span class="glyphicon glyphicon-edit"></span></a>
				<a class="btn btn-danger btn-xs" data-href="<?php echo site_url("guru/delete")."/". $dt['id_guru'];?>" data-toggle="modal" data-target="#confirm-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a>
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
