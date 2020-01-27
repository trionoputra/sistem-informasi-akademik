
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
				<form class="form-inline" method="get" action="<?php echo site_url("user")?>" >
					 <div class="form-group">
						<input type="text" class="form-control input-sm" id="keyword" placeholder="Keyword" name="keyword" value="<?php echo $this->input->get('keyword');?>">
				  </div>
					 <button type="submit" class="btn btn-primary btn-sm">Search</button>
					 <a href="<?php echo site_url("user/manage")?>" class="btn btn-success btn-sm">Add New</a>
				</form>
			</div>
		<table class="table table-striped table-small">
		<thead>
		  <tr>
			<th>ID User</th>
			<th>Username</th>
			<th>Level</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
		<?php foreach($data as $dt): ?>
		  <tr>
			<td><?php echo $dt['id_user'];?></td>
		    <td><?php echo $dt['username'];?></td>
			<td><?php
					if ($dt['level'] == "0")
						echo "Seketariat Sekolah";
					if ($dt['level'] == "1")
						echo "Kepala Sekolah";
					else if ($dt['level'] == "2")
						echo "Guru Bidang Studi";
					else if ($dt['level'] == "3")
						echo "Wali Murid";
					else if ($dt['level'] == "4")
						echo "Wali Kelas";
					
			?></td>
			<th>
				<a class="btn btn-warning btn-xs" href="<?php echo site_url("user/manage")."/". $dt['id_user']; ?>"><span class="glyphicon glyphicon-edit"></span></a>
				<a class="btn btn-danger btn-xs" data-href="<?php echo site_url("user/delete")."/". $dt['id_user'];?>" data-toggle="modal" data-target="#confirm-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a>
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
