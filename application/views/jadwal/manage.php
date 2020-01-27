<script>
var lastkeyword = "";
var tahun_ajaran = "";
$(function(){
	$('button[type="reset"]').click(function(evt) {
	    evt.preventDefault();
	    $(this).closest('form').get(0).reset();
		cekWaktu();
	});
	
	$('#ref-table').on('show.bs.modal', function(e) {
			$(".table-ajax").empty();
			$("#keyword").val("");
			getAjar(1);
	});

	$(".search").click(function(){
		getAjar(1);
	});
	
	$("select[name='id_thn_ajaran']").on('change', function() {
		$("input[name='desc']").val("");
		$("input[name='id_ajar']").val("");
	});
	
	cekWaktu();
});

function getAjar(page)
{
	tahun_ajaran = $("select[name='id_thn_ajaran']").val();
	lastkeyword = $("#keyword").val();
	$.ajax({
		  dataType: "html",
		  url: "<?php echo site_url("api/ajax/getTableAjar");?>",
		  data:{"keyword":lastkeyword,'page':page,"id_thn_ajaran":tahun_ajaran},
		  success:function(d){
			  $(".table-ajax").empty();
			  $(".table-ajax").html(d);
			}
	});	
}

function pilih(id,deskripsi,nama)
{
	$("input[name='id_ajar']").val(id);
	$("input[name='desc']").val(deskripsi + " ("+nama+")");
}

function cekWaktu()
{	
	if($('#csenin').is(':checked'))
		$('#senin').attr("disabled",false);
	else
		$('#senin').attr("disabled",true);
	
	if($('#cselasa').is(':checked'))
			$('#selasa').attr("disabled",false);
		else
			$('#selasa').attr("disabled",true);
	
	if($('#crabu').is(':checked'))
			$('#rabu').attr("disabled",false);
		else
			$('#rabu').attr("disabled",true);
			
	if($('#crabu').is(':checked'))
			$('#rabu').attr("disabled",false);
		else
			$('#rabu').attr("disabled",true);
	
	if($('#ckamis').is(':checked'))
			$('#kamis').attr("disabled",false);
		else
			$('#kamis').attr("disabled",true);
	
	if($('#cjumat').is(':checked'))
			$('#jumat').attr("disabled",false);
		else
			$('#jumat').attr("disabled",true);
	
	if($('#csabtu').is(':checked'))
			$('#sabtu').attr("disabled",false);
		else
			$('#sabtu').attr("disabled",true);
	
	if($('#cminggu').is(':checked'))
			$('#minggu').attr("disabled",false);
		else
			$('#minggu').attr("disabled",true);
	
	$("#csenin").click(function(){
		if($('#csenin').is(':checked'))
			$('#senin').attr("disabled",false);
		else
			$('#senin').attr("disabled",true);
	});
	$("#cselasa").click(function(){
		if($('#cselasa').is(':checked'))
			$('#selasa').attr("disabled",false);
		else
			$('#selasa').attr("disabled",true);
	});
	$("#crabu").click(function(){
		if($('#crabu').is(':checked'))
			$('#rabu').attr("disabled",false);
		else
			$('#rabu').attr("disabled",true);
	});
	$("#ckamis").click(function(){
		if($('#ckamis').is(':checked'))
			$('#kamis').attr("disabled",false);
		else
			$('#kamis').attr("disabled",true);
	});
	$("#cjumat").click(function(){
		if($('#cjumat').is(':checked'))
			$('#jumat').attr("disabled",false);
		else
			$('#jumat').attr("disabled",true);
	});
	$("#csabtu").click(function(){
		if($('#csabtu').is(':checked'))
			$('#sabtu').attr("disabled",false);
		else
			$('#sabtu').attr("disabled",true);
	});
	$("#cminggu").click(function(){
		if($('#cminggu').is(':checked'))
			$('#minggu').attr("disabled",false);
		else
			$('#minggu').attr("disabled",true);
	});
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
		<form  method="post" action="<?php echo site_url("jadwal/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_jadwal; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("jadwal")?>" class="btn btn-danger">Cancel</a>
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
							  <?php foreach ($kelas as $kl):?>
							  <option value="<?php echo $kl['id_kelas'];?>" <?php echo $data->id_kelas == $kl['id_kelas'] ? ' selected' : '';?> ><?php echo $kl['nm_kelas']?></option>
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
					    <label for="id_ajar" class="col-sm-2 control-label">Pelajaran</label>
						<input type="hidden" class="form-control" id="id_ajar" placeholder="pilih pelajaran" name="id_ajar" value="<?php echo $data->id_ajar ; ?>"  />
					    <div class="col-sm-3">
							<input type="text" class="form-control" id="desc" placeholder="pilih pelajaran" name="desc" value="<?php echo $data->nama != "" ? $data->deskripsi." (".$data->nama.")" : ""; ?>" readonly  />
					    </div>
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#ref-table" href="#"><span class="glyphicon glyphicon-search"></span></a>
					</div>
					<div class="form-group">
						<label for="waktu" class="col-sm-2 control-label">Hari & Waktu</label>
						<div class="col-sm-10">
							<table class="table table-striped table-small">
								<thead>
								  <tr>
									<th><input type="checkbox" id="csenin" name="hari[]" value="senin" <?php echo $data->senin != "" ? "checked" : "" ; ?> > Senin</th>
									<th><input type="checkbox" id="cselasa" name="hari[]" value="selasa" <?php echo $data->selasa != "" ? "checked" : "" ; ?>> Selasa</th>
									<th><input type="checkbox" id="crabu" name="hari[]" value="rabu" <?php echo $data->rabu != "" ? "checked" : "" ; ?> > Rabu</th>
									<th><input type="checkbox" id="ckamis" name="hari[]" value="kamis" <?php echo $data->kamis != "" ? "checked" : "" ; ?> > Kamis</th>
									<th><input type="checkbox" id="cjumat" name="hari[]" value="jumat" <?php echo $data->jumat != "" ? "checked" : "" ; ?> > Jumat</th>
									<th><input type="checkbox" id="csabtu" name="hari[]" value="sabtu" <?php echo $data->sabtu != "" ? "checked" : "" ; ?> > Sabtu</th>
									<th><input type="checkbox" id="cminggu" name="hari[]" value="minggu" <?php echo $data->minggu != "" ? "checked" : "" ; ?> > Minggu</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
									<td><div  class="col-sm-10"><input disabled type="text" class="form-control" id="senin"  name="senin" value="<?php echo $data->senin ; ?>"  /></div></td>
									<td><div  class="col-sm-10"><input  disabled type="text" class="form-control" id="selasa"  name="selasa" value="<?php echo $data->selasa ; ?>"  /></div></td>
									<td><div  class="col-sm-10"><input  disabled type="text" class="form-control" id="rabu"  name="rabu" value="<?php echo $data->rabu ; ?>"  /></div></td>
									<td><div  class="col-sm-10"><input  disabled type="text" class="form-control" id="kamis"  name="kamis" value="<?php echo $data->kamis ; ?>"  /></div></td>
									<td><div  class="col-sm-10"><input  disabled type="text" class="form-control" id="jumat"  name="jumat" value="<?php echo $data->jumat ; ?>"  /></div></td>
									<td><div class="col-sm-10"><input  disabled type="text" class="form-control" id="sabtu"  name="sabtu" value="<?php echo $data->sabtu ; ?>"  /></div></td>
									<td><div  class="col-sm-10"><input  disabled type="text" class="form-control" id="minggu"  name="minggu" value="<?php echo $data->minggu ; ?>"  /></div></td>
								  </tr>
								</tbody>
							</table>
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
				<h4 class="modal-title" id="myModalLabel">Pilih Pelajaran</h4>
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

