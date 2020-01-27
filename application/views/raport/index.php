<?php 
	$CI =& get_instance();
?>
<script>
	var lastkeyword = "";
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
		 
		 $('#ref-table').on('show.bs.modal', function(e) {
			$(".table-ajax").empty();
			getSiswa(1);
		});
		
		$(".search").click(function(){
			lastkeyword = $("#keyword").val();
			getSiswa(1);
		});
		
	});

function getSiswa(page)
{
	$.ajax({
		  dataType: "html",
		  url: "<?php echo site_url("api/ajax/getTableSiswa");?>",
		  data:{"keyword":lastkeyword,'page':page},
		  success:function(d){
			  $(".table-ajax").empty();
			  $(".table-ajax").html(d);
			}
	});	
}

function pilih(id)
{
	$("input[name='nis']").val(id);
	$("form[name='filter']").submit();
}

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
			<form class="form-horizontal" method="get" action="<?php echo site_url("raport")?>" id="filter-form" name="filter">
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
						<input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis ; ?>" readonly />
					</div>
				<?php if($CI->getStatus() != 3): ?>
 				<a class="btn btn-default" data-toggle="modal" data-target="#ref-table" href="#"><span class="glyphicon glyphicon-search"></span></a> 
				<?php endif; ?>
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
						  <?php if($CI->getStatus() != 3): ?>  
						  <option value="<?php echo $kls['id_kelas'];?>" <?php echo $this->input->get("kelas") == $kls['id_kelas'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
						  <?php else: ?>
						  <option value="<?php echo $kls['id_kelas']."|".$kls['id_tempati'];?>" <?php echo $this->input->get("kelas") == $kls['id_kelas']."|".$kls['id_tempati'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>   
						  <?php endif; ?> 
						  <?php endforeach;?>
						</select>
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
						 <?php if(!is_array($tahun_ajaran)) :?> 
						<input type="text" class="form-control" id="thn_ajaran" name="thn_ajaran" value="<?php echo $tahun_ajaran; ?>" disabled /> 
						<?php else: ?>  
						<select class="form-control input-sm" name="thn_ajaran">
						  <?php foreach ($tahun_ajaran as $kls):?>
						  <option value="<?php echo $kls['id_thn_ajaran'];?>" <?php echo $this->input->get("thn_ajaran") == $kls['id_thn_ajaran'] ? ' selected' : '';?> ><?php echo $kls['thn_ajaran']?></option>
						  <?php endforeach;?>
						</select>
						<?php endif;?> 
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-8">
					<table class="table table-striped table-small">
					<thead>
					  <tr>
						<th>No</th>
						<th>Mata Pelajaran</th>
						<th>KKM</th>
						<th>Nilai</th>
					  </tr>
					</thead>
					<tbody>
					<?php foreach($data as $key => $dt): ?>	
						<tr>
							<td><?php echo ($key+1) ?></td>
							<td><?php echo $dt['deskripsi'];?></td>
							<td><?php echo $dt['kkm'];?></td>
							
							<td><span <?php echo $dt['nilai'] < $dt['kkm'] ? "style='color:red;'" : ''?> ><?php echo $dt['nilai'];?></span></td>
						</tr>
					<?php endforeach;?>
					</tbody>
					</table>
					<div>*)  KKM=Kriteria Ketuntasan Minimal</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-top:40px;">Nilai Kepribadian</div>
					<table class="table table-striped table-small">
					<thead>
					  <tr>
						<th>No</th>
						<th>Kepribadian</th>
						<th>Nilai</th>
						<th>Keterangan</th>
					  </tr>
					</thead>
					<tbody>
					<?php foreach($dataK as $key => $dt): ?>
					<tr>
					<td><?php echo ($key+1) ?></td>
					<td><?php echo $dt['nm_kepribadian'];?></td>
					<td><?php echo $dt['nilai'];?></td>
					<td><?php echo $dt['deskripsi'];?></td>
					</tr>
					<?php endforeach;?>
					</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-top:40px;">Pengembangan Diri</div>
					<table class="table table-striped table-small">
					<thead>
					  <tr>
						<th>No</th>
						<th>Aspek Pengenbangan Diri</th>
						<th>Nilai</th>
					  </tr>
					</thead>
					<tbody>
					<?php foreach($dataE as $key => $dt): ?>
					<tr>
					<td><?php echo ($key+1) ?></td>
					<td><?php echo $dt['nm_ekskul'];?></td>
					<td><?php echo $dt['nilai'];?></td>
					</tr>
					<?php endforeach;?>
					</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-top:40px;">Ketidak Hadiran</div>
					<table class="table table-striped table-small">
					<thead>
					  <tr>
						<th>No</th>
						<th>Alasan Ketidakhadiran</th>
						<th>Jumlah</th>
					  </tr>
					</thead>
					<tbody>
					<?php if(!empty($dataA)): ?>
					<tr>
						<td>1</td>
						<td>Ijin</td>
						<td><?php echo $dataA[0]['Ijin']?></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Sakit</td>
						<td><?php echo $dataA[0]['Sakit']?></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Alfa</td>
						<td><?php echo $dataA[0]['Alfa']?></td>
					</tr>
					<?php endif;?>
					</tbody>
					</table>
				</div>
			</div>
			
			
		</div>
	</div>
	</div>
</div>
<div class="modal fade" id="ref-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ajax-modal" style="margin-top:100px;">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Pilih Siswa</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-filter-modal">
						<form class="form-inline" method="get" action="#" >
							<div class="form-group">
								<input type="text" class="form-control input-sm" id="keyword" placeholder="Keyword" name="keyword" value="">
							</div>
							<button type="button"  class="btn btn-primary btn-sm search">Search</button>
						</form>
					</div>
				</div>
				
					<div class="table-ajax">
					</div>
				
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger danger delete" data-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>

