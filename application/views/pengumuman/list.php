<script>
	$(function(){
		 $("select[name='thn_ajaran']").change(function()
		 {
			 $('#filter-form').submit();
		 });
		 
/*		 $("select[name='semester']").change(function()
		 {
			 $('#filter-form').submit();
		 });*/
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
			<form class="form-horizontal" method="get" action="<?php echo site_url("pengumuman/rekap")?>" id="filter-form">
			<div class="panel-heading">
				
			</div>
			<div class="panel-body">
				<div class="form-group ">
					<label for="tahun_ajaran" class="col-sm-2">Tahun Ajaran</label>
					<div class="col-sm-2">
						<select class="form-control input-sm" name="thn_ajaran">
							<?php foreach ($tahun_ajaran as $thn):?>
							<option value="<?php echo $thn['id_thn_ajaran'];?>" <?php echo $this->input->get("thn_ajaran") == $thn['id_thn_ajaran'] ? 'selected' : '';?> ><?php echo $thn['thn_ajaran']?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
			</div>
			</form>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php foreach($data as $dt): ?>
				 <div class="news">
					<div class="page-header">
						<h4><a href="<?php echo site_url("pengumuman/detail")."/".$dt['id_pengumuman']?>"><?php echo $dt['judul'];?></a></h4>
						<div class="date"><?php echo date('d/m/Y h:i:s',strtotime($dt['tgl_input']));?></div>
					</div>
					<div class="body">
						 <?php if(strlen(strip_tags($dt['isi'])) > 800 ): ?>
							<p><?php echo substr(($dt['isi']),0,800);?></p>
							<a href="<?php echo site_url("pengumuman/detail")."/".$dt['id_pengumuman']?>">read mode..</a>
						 <?php else: ?>
							<p><?php echo substr(($dt['isi']),0,800);?></p>
						 <?php endif;?>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
