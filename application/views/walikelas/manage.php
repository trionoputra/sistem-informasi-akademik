<script>
var lastkeyword = "";
$(function(){
	$('#ref-table').on('show.bs.modal', function(e) {
			$(".table-ajax").empty();
			getGuru(1);
	});
	
	$(".search").click(function(){
		lastkeyword = $("#keyword").val();
		getGuru(1);
	});
	
});

function getGuru(page)
{
	$.ajax({
		  dataType: "html",
		  url: "<?php echo site_url("api/ajax/getTableGuru");?>",
		  data:{"keyword":lastkeyword,'page':page},
		  success:function(d){
			  $(".table-ajax").empty();
			  $(".table-ajax").html(d);
			}
	});	
}

function pilih(id)
{
	$("input[name='id_guru']").val(id);
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
		<form  method="post" action="<?php echo site_url("walikelas/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_thn_ajaran != "" ? md5($data->id_kelas.$data->id_guru.$data->id_thn_ajaran) : ""; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("walikelas")?>" class="btn btn-danger">Cancel</a>
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
						<label for="thn_ajaran" class="col-sm-2 control-label">Kelas</label>
						<div class="col-sm-2">
							<select class="form-control input-sm" name="id_kelas">
							  <?php foreach ($kelas as $kls):?>
							  <option value="<?php echo $kls['id_kelas'];?>" <?php echo $data->id_kelas == $kls['id_kelas'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
							  <?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="form-group">
					    <label for="id_guru" class="col-sm-2 control-label">Guru</label>
					    <div class="col-sm-3">
							<input type="text" class="form-control" id="id_guru" placeholder="pilih id guru" name="id_guru" value="<?php echo $data->id_guru; ?>" readonly  />
					    </div>
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#ref-table" href="#"><span class="glyphicon glyphicon-search"></span></a>
						
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
				<h4 class="modal-title" id="myModalLabel">Pilih Guru</h4>
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

