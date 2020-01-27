<link rel="stylesheet" href="<?php echo base_url('assets/datepicker/css/datepicker.css')?>">
<script src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.js')?>"></script>
<script>
var lastkeyword = "";
var tahun_ajaran = "";
var kelas = "";

$(function(){
	$('#tgl_absen').datepicker();
	$('button[type="reset"]').click(function(evt) {
	    evt.preventDefault();
	    $(this).closest('form').get(0).reset();
	});
	
	$('#ref-table').on('show.bs.modal', function(e) {
			$(".table-ajax").empty();
			$("#keyword").val("");
			getSiswa(1);
	});

	$(".search").click(function(){
		getSiswa(1);
	});
	
	$("select[name='id_thn_ajaran']").on('change', function() {
		$("input[name='id_tempati']").val("");
		$("input[name='nis']").val("");
	});
	
	$("select[name='id_kelas']").on('change', function() {
		$("input[name='id_tempati']").val("");
		$("input[name='nis']").val("");
	});
	
});

function getSiswa(page)
{
	tahun_ajaran = $("select[name='id_thn_ajaran']").val();
	kelas = $("select[name='id_kelas']").val();
	lastkeyword = $("#keyword").val();
	$.ajax({
		  dataType: "html",
		  url: "<?php echo site_url("api/ajax/getTableKelasSiswa");?>",
		  data:{"keyword":lastkeyword,'page':page,"id_thn_ajaran":tahun_ajaran,"kelas":kelas},
		  success:function(d){
			  $(".table-ajax").empty();
			  $(".table-ajax").html(d);
			}
	});	
}
function pilih(id,nis)
{
	$("input[name='nis']").val(nis);
	$("input[name='id_tempati']").val(id);
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
		<form  method="post" action="<?php echo site_url("absen/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_absen; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("absen")?>" class="btn btn-danger">Cancel</a>
			     			</div>
			  			</div>
			  		</div>
			  </div>
			  <div class="panel-body">
				<div class="form-horizontal" >
					<div class="form-group">
						<label for="id_thn_ajaran" class="col-sm-2 control-label">Tahun Ajaran</label>
						<div class="col-sm-2">
					       <select class="form-control input-sm" name="id_thn_ajaran">
							  <?php foreach ($tahun_ajaran as $thn):?>
							  <option value="<?php echo $thn['id_thn_ajaran'];?>" <?php echo $data->id_thn_ajaran == $thn['id_thn_ajaran'] ? 'selected' : ($this->session->userdata('selected_tahun_ajaran') == $thn['id_thn_ajaran'] && $data->id_thn_ajaran == "" ? 'selected' : '');?> ><?php echo $thn['thn_ajaran']?></option>
							  <?php endforeach;?>
							</select>
					    </div>
					</div>
					<div class="form-group">
					    <label for="id_kelas" class="col-sm-2 control-label">Kelas</label>
					    <div class="col-sm-2">
					       <select class="form-control input-sm" name="id_kelas">
							  <?php foreach ($kelas as $kls):?>
							  <option value="<?php echo $kls['id_kelas'];?>" <?php echo $data->id_kelas == $kls['id_kelas'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
							  <?php endforeach;?>
							</select>
					    </div>
					</div>
					<div class="form-group">
						<label for="semester" class="col-sm-2 control-label">Semester</label>
						<div class="col-sm-1">
							<select class="form-control input-sm" name="semester">
							  <option value="1" <?php echo $data->semester == "1" ? ' selected' : '';?> >1</option>
							   <option value="2" <?php echo $data->semester == "2" ? ' selected' : '';?> >2</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="tgl_absen" class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-3">
							<div class="input-group">
							 <input type="text" class="form-control datepicker" id="tgl_absen" name="tgl_absen"  readonly data-date-format="mm/dd/yyyy" value="<?php echo ($data->tgl_absen != "") ? date("m/d/Y",strtotime($data->tgl_absen)) : date("m/d/Y") ?>">
							  <div class="input-group-addon glyphicon glyphicon-calendar"></div>
							</div>
						</div>
					</div>
					<div class="form-group">
					    <label for="id_tempati" class="col-sm-2 control-label">Siswa</label>
						<input type="hidden" class="form-control" id="id_tempati" name="id_tempati" value="<?php echo $data->id_tempati ; ?>"  />
					    <div class="col-sm-3">
							<input type="text" class="form-control" id="nis" placeholder="pilih siswa" name="nis" value="<?php echo $data->nis; ?>" readonly  />
					    </div>
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#ref-table" href="#"><span class="glyphicon glyphicon-search"></span></a>
					</div>
					<div class="form-group">
						 <label for="alasan" class="col-sm-2 control-label">Alasan</label>
					    <div class="col-sm-2">
					       <select class="form-control input-sm" name="alasan">
							<option value="i" <?php echo $data->alasan == 'i' ? ' selected' : '';?> >Ijin</option>
							<option value="a" <?php echo $data->alasan == 'a' ? ' selected' : '';?> >Alpha</option>
							<option value="s" <?php echo $data->alasan == 's' ? ' selected' : '';?> >Sakit</option>
						</select>
					    </div>
					</div>
					<div class="form-group">
						<label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-4">
						  <textarea class="form-control" rows="3" id="keterangan" name="keterangan" placeholder="input keterangan"><?php echo $data->keterangan; ?></textarea>
						</div>
					</div>
				</div>
			  </div>
			  <div class="panel-footer">
			  
			  </div>
			</div>
		</form>
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

